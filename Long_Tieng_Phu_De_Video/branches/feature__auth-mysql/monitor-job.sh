#!/usr/bin/env bash

set -u

# ============================================================
# VideoDubing-AI Job Monitor
# ============================================================
#
# FLAG 0 = DOWNLOAD (one or more URLs)
# FLAG 1 = DUBBING  (one URL, optional target language)
#                      without target language = DOWNLOAD ONLY
# FLAG 2 = SUBTITLE (one URL, optional target language)
#
# Examples:
#   ./monitor-job.sh "URL1" "URL2" "URL3" 0
#   ./monitor-job.sh "URL" vi 1
#   ./monitor-job.sh "URL" 1
#   ./monitor-job.sh "URL" vi 2
#   ./monitor-job.sh "URL" 2
#
# FLAG 1 without language intentionally keeps the source video unchanged:
# no transcription, translation, TTS, or rendering is requested.
# ============================================================

API_BASE="${API_BASE:-http://127.0.0.1:3000}"
CREATE_ENDPOINT="${API_BASE}/api/v1/download"
JOB_ENDPOINT="${API_BASE}/api/v1/jobs"
POLL_INTERVAL="${POLL_INTERVAL:-3}"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

show_cursor() { printf '\033[?25h'; }
hide_cursor() { printf '\033[?25l'; }
clear_screen() { printf '\033[2J\033[H'; }

trap 'show_cursor' EXIT
trap 'show_cursor; exit 130' INT
trap 'show_cursor; exit 143' TERM

usage() {
    cat <<'EOF'

VideoDubing-AI Job Monitor

DOWNLOAD - multiple URLs:
  ./monitor-job.sh "URL1" "URL2" "URL3" 0

DUBBING - one URL:
  ./monitor-job.sh "URL" vi 1
  ./monitor-job.sh "URL" 1
  # FLAG 1 without language = download only, no dubbing

SUBTITLE - one URL:
  ./monitor-job.sh "URL" vi 2
  ./monitor-job.sh "URL" 2

When FLAG 1 is used without target language, the monitor sends FLAG 0 to the API
so the original video is downloaded unchanged. No dubbing pipeline is executed.

When language is omitted for FLAG 2, target_language is omitted from the API payload.

Environment:
  API_BASE=http://127.0.0.1:3000
  POLL_INTERVAL=3

EOF
    exit 1
}

check_dependencies() {
    command -v curl >/dev/null 2>&1 || { echo -e "${RED}ERROR: curl not found${NC}"; exit 1; }
    command -v python3 >/dev/null 2>&1 || { echo -e "${RED}ERROR: python3 not found${NC}"; exit 1; }
}

normalize_url() {
    python3 - "$1" <<'PY'
import re
import sys

url = sys.argv[1].strip()
m = re.fullmatch(r'\[([^\]]+)\]\(([^)]+)\)', url)
if m:
    url = m.group(2).strip()

url = url.replace(r'\&', '&')
url = url.replace(r'\_', '_')
url = url.replace(r'\?', '?')
url = url.replace(r'\=', '=')

if len(url) >= 2 and ((url[0] == '"' and url[-1] == '"') or (url[0] == "'" and url[-1] == "'")):
    url = url[1:-1].strip()

print(url)
PY
}

valid_url() {
    case "$1" in
        http://*|https://*) return 0 ;;
        *) return 1 ;;
    esac
}

check_api() {
    local response
    response=$(curl -fsS --connect-timeout 5 --max-time 15 "${API_BASE}/health" 2>/dev/null) || {
        echo -e "${RED}ERROR: API unavailable: ${API_BASE}${NC}"
        exit 1
    }

    python3 - "$response" <<'PY'
import json
import sys
try:
    data = json.loads(sys.argv[1])
except Exception:
    raise SystemExit(1)
if not data.get("success"):
    raise SystemExit(1)
PY

    if [[ $? -ne 0 ]]; then
        echo -e "${RED}ERROR: API health check failed${NC}"
        echo "$response"
        exit 1
    fi
}

build_download_payload() {
    python3 - "${URLS[@]}" <<'PY'
import json
import sys
print(json.dumps({"flag": 0, "urls": sys.argv[1:]}, ensure_ascii=False))
PY
}

build_single_download_payload() {
    python3 - "$1" <<'PY'
import json
import sys
print(json.dumps({"flag": 0, "urls": [sys.argv[1]]}, ensure_ascii=False))
PY
}

build_single_payload() {
    python3 - "$1" "$2" "$3" <<'PY'
import json
import sys

url = sys.argv[1]
language = sys.argv[2]
flag = int(sys.argv[3])

payload = {
    "flag": flag,
    "urls": [url],
}

if language:
    payload["target_language"] = language

print(json.dumps(payload, ensure_ascii=False))
PY
}

create_jobs() {
    local payload="$1"
    local response

    echo "REQUEST PAYLOAD:"
    echo
    echo "$payload" | python3 -m json.tool
    echo

    response=$(curl -fsS \
        -X POST \
        -H 'Content-Type: application/json' \
        --data "$payload" \
        "$CREATE_ENDPOINT") || {
        echo -e "${RED}ERROR: API request failed${NC}"
        exit 1
    }

    echo "API RESPONSE:"
    echo
    echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"
    echo

    mapfile -t JOB_LINES < <(python3 - "$response" <<'PY'
import json
import sys

try:
    response = json.loads(sys.argv[1])
except Exception as exc:
    print(f"ERROR: invalid JSON: {exc}", file=sys.stderr)
    raise SystemExit(1)

if response.get("success") is False:
    print(response.get("message") or response.get("error") or "unknown API error", file=sys.stderr)
    raise SystemExit(2)

data = response.get("data")

if isinstance(data, dict) and isinstance(data.get("jobs"), list):
    jobs = data["jobs"]
elif isinstance(data, dict) and data.get("id"):
    jobs = [data]
elif isinstance(data, list):
    jobs = data
else:
    jobs = []

if not jobs:
    print("ERROR: API did not return any JOB ID", file=sys.stderr)
    raise SystemExit(3)

for job in jobs:
    if not isinstance(job, dict) or not job.get("id"):
        continue
    print(json.dumps({
        "id": str(job["id"]),
        "url": str(job.get("url") or ""),
        "status": str(job.get("status") or "accepted")
    }, ensure_ascii=False))
PY
    )

    if [[ ${#JOB_LINES[@]} -eq 0 ]]; then
        echo -e "${RED}ERROR: API did not return any JOB ID${NC}"
        exit 1
    fi

    JOB_IDS=()
    JOB_URLS=()

    local line id url status
    for line in "${JOB_LINES[@]}"; do
        id=$(python3 - "$line" <<'PY'
import json,sys
print(json.loads(sys.argv[1])["id"])
PY
)
        url=$(python3 - "$line" <<'PY'
import json,sys
print(json.loads(sys.argv[1]).get("url", ""))
PY
)
        status=$(python3 - "$line" <<'PY'
import json,sys
print(json.loads(sys.argv[1]).get("status", "accepted"))
PY
)

        JOB_IDS+=("$id")
        JOB_URLS+=("$url")

        echo -e "${GREEN}✓ JOB ID:${NC} $id"
        echo "URL: $url"
        echo "STATUS: $status"
    done
}

get_job() {
    curl -fsS --connect-timeout 5 --max-time 15 \
        "${JOB_ENDPOINT}/$1" 2>/dev/null || echo '{}'
}

parse_job() {
    python3 - "$1" <<'PY'
import json
import sys
try:
    response = json.loads(sys.argv[1])
    data = response.get("data") or {}
except Exception:
    data = {}
status = str(data.get("status") or "unknown")
progress = data.get("progress")
if progress is None:
    progress = 0
error = str(data.get("error") or "")
output = str(data.get("output_url") or data.get("filepath") or "")
for value in (status, progress, error, output):
    print(str(value).replace("\n", " ").replace("\r", " "))
PY
}

status_failed() {
    case "$1" in
        failed|error|cancelled|canceled) return 0 ;;
        *) return 1 ;;
    esac
}

progress_bar() {
    local value="$1"
    local width="${2:-30}"
    local pct filled empty

    pct=$(python3 - "$value" <<'PY'
import sys
try:
    p=float(sys.argv[1])
except Exception:
    p=0
p=max(0,min(100,p))
print(f"{p:.1f}")
PY
)

    filled=$(python3 - "$pct" "$width" <<'PY'
import sys
p=float(sys.argv[1]); w=int(sys.argv[2])
print(max(0,min(w,int(round(p*w/100)))))
PY
)
    empty=$((width-filled))

    printf '['
    (( filled > 0 )) && printf '%*s' "$filled" '' | tr ' ' '#'
    (( empty > 0 )) && printf '%*s' "$empty" '' | tr ' ' '.'
    printf '] %s%%' "$pct"
}

short_url() {
    local url="$1"
    local max="${2:-78}"
    if [[ ${#url} -le $max ]]; then
        printf '%s' "$url"
    else
        printf '%s...' "${url:0:$((max-3))}"
    fi
}

status_display() {
    case "$1" in
        completed) printf '%b✓ completed%b' "$GREEN" "$NC" ;;
        failed|error|cancelled|canceled) printf '%b✗ %s%b' "$RED" "$1" "$NC" ;;
        downloading|extracting|separating|transcribing|translating|tts|rendering|subtitle_rendering|subtitle_preparing)
            printf '%b● %s%b' "$CYAN" "$1" "$NC" ;;
        *) printf '%b○ %s%b' "$YELLOW" "$1" "$NC" ;;
    esac
}

print_job() {
    local n="$1" id="$2" url="$3" status="$4" progress="$5" error="$6" output="$7"

    echo "┌──────────────────────────────────────────────────────────────────────────────"
    printf '│ JOB %02d\n' "$n"
    echo "├──────────────────────────────────────────────────────────────────────────────"
    printf '│ URL      : %s\n' "$(short_url "$url")"
    printf '│ JOB ID   : %s\n' "$id"
    printf '│ STATUS   : %b\n' "$(status_display "$status")"
    printf '│ PROGRESS : %s\n' "$(progress_bar "$progress")"

    if [[ "$status" == "completed" ]]; then
        printf '│ RESULT   : %b✓ COMPLETED%b\n' "$GREEN" "$NC"
        [[ -n "$output" ]] && printf '│ OUTPUT   : %s\n' "$(short_url "$output")"
    elif status_failed "$status"; then
        printf '│ RESULT   : %b✗ FAILED%b\n' "$RED" "$NC"
        [[ -n "$error" ]] && printf '│ ERROR    : %s\n' "$(short_url "$error")"
    else
        printf '│ RESULT   : %b● RUNNING%b\n' "$YELLOW" "$NC"
    fi

    echo "└──────────────────────────────────────────────────────────────────────────────"
}

monitor_jobs() {
    local total=${#JOB_IDS[@]}
    local i response parsed status progress error output
    local completed failed running

    declare -a JOB_STATUS JOB_PROGRESS JOB_ERROR JOB_OUTPUT

    for ((i=0; i<total; i++)); do
        JOB_STATUS[$i]="accepted"
        JOB_PROGRESS[$i]="0"
        JOB_ERROR[$i]=""
        JOB_OUTPUT[$i]=""
    done

    hide_cursor

    while true; do
        completed=0
        failed=0
        running=0

        for ((i=0; i<total; i++)); do
            response=$(get_job "${JOB_IDS[$i]}")
            parsed=$(parse_job "$response")

            status=$(printf '%s\n' "$parsed" | sed -n '1p')
            progress=$(printf '%s\n' "$parsed" | sed -n '2p')
            error=$(printf '%s\n' "$parsed" | sed -n '3p')
            output=$(printf '%s\n' "$parsed" | sed -n '4p')

            JOB_STATUS[$i]="$status"
            JOB_PROGRESS[$i]="$progress"
            JOB_ERROR[$i]="$error"
            JOB_OUTPUT[$i]="$output"

            if [[ "$status" == "completed" ]]; then
                ((completed++))
            elif status_failed "$status"; then
                ((failed++))
            else
                ((running++))
            fi
        done

        clear_screen

        echo "╔══════════════════════════════════════════════════════════════════════════════╗"
        echo "║                         VideoDubing-AI MONITOR                              ║"
        echo "╠══════════════════════════════════════════════════════════════════════════════╣"
        printf '║ JOBS: %-3s   RUNNING: %-3s   COMPLETED: %-3s   FAILED: %-3s   POLL: %-2ss       ║\n' \
            "$total" "$running" "$completed" "$failed" "$POLL_INTERVAL"
        echo "╚══════════════════════════════════════════════════════════════════════════════╝"
        echo

        for ((i=0; i<total; i++)); do
            print_job \
                "$((i+1))" \
                "${JOB_IDS[$i]}" \
                "${JOB_URLS[$i]}" \
                "${JOB_STATUS[$i]}" \
                "${JOB_PROGRESS[$i]}" \
                "${JOB_ERROR[$i]}" \
                "${JOB_OUTPUT[$i]}"
            echo
        done

        if (( completed + failed == total )); then
            break
        fi

        sleep "$POLL_INTERVAL"
    done

    show_cursor
    echo "============================================================"
    echo "FINAL SUMMARY"
    echo "============================================================"
    echo
    echo "TOTAL     : $total"
    printf 'COMPLETED : %b%s%b\n' "$GREEN" "$completed" "$NC"
    printf 'FAILED    : %b%s%b\n' "$RED" "$failed" "$NC"

    (( failed == 0 ))
}

# ============================================================
# MAIN
# ============================================================

check_dependencies
[[ $# -ge 2 ]] || usage

FLAG="${!#}"
case "$FLAG" in
    0|1|2) ;;
    *) echo -e "${RED}ERROR: flag must be 0, 1 or 2${NC}"; usage ;;
esac

ARGS=("${@:1:$(($#-1))}")

# ============================================================
# FLAG 0 - DOWNLOAD MULTIPLE URLS
# ============================================================
if [[ "$FLAG" == "0" ]]; then
    URLS=()

    for raw_url in "${ARGS[@]}"; do
        url=$(normalize_url "$raw_url")
        if ! valid_url "$url"; then
            echo -e "${RED}ERROR: Invalid URL: $url${NC}"
            exit 1
        fi
        URLS+=("$url")
    done

    clear_screen
    echo "============================================================"
    echo " DOWNLOAD BATCH"
    echo "============================================================"
    echo
    echo "URLs: ${#URLS[@]}"
    for ((i=0; i<${#URLS[@]}; i++)); do
        echo "[$((i+1))] ${URLS[$i]}"
    done
    echo

    echo "[$(date '+%H:%M:%S')] Checking API..."
    check_api
    echo -e "${GREEN}API: OK${NC}"
    echo
    echo "[$(date '+%H:%M:%S')] Creating ${#URLS[@]} download job(s)..."

    payload=$(build_download_payload)
    create_jobs "$payload"
    monitor_jobs
    exit $?
fi

# ============================================================
# FLAG 1/2 - SINGLE DUBBING/SUBTITLE JOB
# ============================================================

if [[ ${#ARGS[@]} -lt 1 || ${#ARGS[@]} -gt 2 ]]; then
    echo -e "${RED}ERROR: flag $FLAG requires URL [language]${NC}"
    echo "./monitor-job.sh \"URL\" $FLAG"
    echo "./monitor-job.sh \"URL\" vi $FLAG"
    exit 1
fi

URL=$(normalize_url "${ARGS[0]}")
LANGUAGE=""
[[ ${#ARGS[@]} -eq 2 ]] && LANGUAGE="${ARGS[1]}"

if ! valid_url "$URL"; then
    echo -e "${RED}ERROR: Invalid URL: $URL${NC}"
    exit 1
fi

# FLAG 1 without target language means source == target.
# Per current product rule, this must be a pure download:
# do not send flag=1 to the API and do not invoke any dubbing stage.
if [[ "$FLAG" == "1" && -z "$LANGUAGE" ]]; then
    clear_screen
    echo "============================================================"
    echo " DUBBING"
    echo "============================================================"
    echo
    echo "URL      : $URL"
    echo "LANGUAGE : SOURCE = TARGET"
    echo "FLAG     : 1"
    echo "MODE     : DOWNLOAD ONLY (NO DUBBING)"
    echo
    echo "[$(date '+%H:%M:%S')] Checking API..."
    check_api
    echo -e "${GREEN}API: OK${NC}"
    echo
    echo "Creating download-only job..."
    echo

    payload=$(build_single_download_payload "$URL")
    create_jobs "$payload"
    monitor_jobs
    exit $?
fi

clear_screen
if [[ "$FLAG" == "1" ]]; then
    echo "============================================================"
    echo " DUBBING"
    echo "============================================================"
else
    echo "============================================================"
    echo " SUBTITLE"
    echo "============================================================"
fi

echo
echo "URL      : $URL"
if [[ -n "$LANGUAGE" ]]; then
    echo "LANGUAGE : $LANGUAGE"
else
    echo "LANGUAGE : AUTO-DETECT"
fi
echo "FLAG     : $FLAG"
echo
echo "[$(date '+%H:%M:%S')] Checking API..."
check_api
echo -e "${GREEN}API: OK${NC}"
echo

if [[ "$FLAG" == "1" ]]; then
    echo "Creating dubbing job..."
else
    echo "Creating subtitle job..."
fi

echo
payload=$(build_single_payload "$URL" "$LANGUAGE" "$FLAG")
create_jobs "$payload"
monitor_jobs
exit $?