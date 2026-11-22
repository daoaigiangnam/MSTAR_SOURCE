#!/bin/bash

set -u

CLEANUP_ENABLED="${CLEANUP_ENABLED:-false}"
CLEANUP_TTL_HOURS="${CLEANUP_TTL_HOURS:-2}"
CLEANUP_INTERVAL_MINUTES="${CLEANUP_INTERVAL_MINUTES:-5}"
JOBS_DIR="${JOBS_DIR:-/app/storage/downloads}"
DATABASE_PATH="${DATABASE_PATH:-/app/api-data/videodubing.db}"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*"
}

if [ "$CLEANUP_ENABLED" != "true" ]; then
    log "Cleanup disabled (CLEANUP_ENABLED=$CLEANUP_ENABLED)"
    while true; do
        sleep 3600
done
fi

case "$CLEANUP_TTL_HOURS" in
    ''|*[!0-9]*)
        log "Invalid CLEANUP_TTL_HOURS=$CLEANUP_TTL_HOURS"
        exit 1
        ;;
esac

case "$CLEANUP_INTERVAL_MINUTES" in
    ''|*[!0-9]*)
        log "Invalid CLEANUP_INTERVAL_MINUTES=$CLEANUP_INTERVAL_MINUTES"
        exit 1
        ;;
esac

TTL_SECONDS=$((CLEANUP_TTL_HOURS * 3600))
INTERVAL_SECONDS=$((CLEANUP_INTERVAL_MINUTES * 60))

if [ "$TTL_SECONDS" -le 0 ]; then
    log "CLEANUP_TTL_HOURS must be > 0"
    exit 1
fi

if [ "$INTERVAL_SECONDS" -le 0 ]; then
    log "CLEANUP_INTERVAL_MINUTES must be > 0"
    exit 1
fi

cleanup_once() {
    local now cutoff cutoff_iso row job_id status finished_at job_dir

    if [ ! -f "$DATABASE_PATH" ]; then
        log "Database not found: $DATABASE_PATH"
        return 0
    fi

    now=$(date +%s)
    cutoff=$((now - TTL_SECONDS))
    cutoff_iso=$(date -u -d "@$cutoff" '+%Y-%m-%dT%H:%M:%S.000Z')

    log "Scanning completed/failed jobs older than ${CLEANUP_TTL_HOURS}h"

    while IFS='|' read -r job_id status finished_at; do
        [ -n "$job_id" ] || continue

        job_dir="$JOBS_DIR/$job_id"

        # Only process terminal jobs returned by the database query.
        # Orphan folders are intentionally ignored for safety.
        if [ -d "$job_dir" ]; then
            log "Removing expired job: id=$job_id status=$status finished_at=$finished_at"
            if rm -rf -- "$job_dir"; then
                log "Removed folder: $job_dir"
            else
                log "ERROR failed to remove folder: $job_dir"
                continue
            fi
        else
            log "Folder already absent: $job_dir"
        fi

        # Remove the database record only after the job is eligible for cleanup.
        if sqlite3 "$DATABASE_PATH" "PRAGMA busy_timeout=5000; DELETE FROM jobs WHERE id = '$(printf '%s' "$job_id" | sed "s/'/''/g")';"; then
            log "Removed DB record: $job_id"
        else
            log "ERROR failed to remove DB record: $job_id"
        fi
    done < <(
        sqlite3 -separator '|' "$DATABASE_PATH" \
            "PRAGMA busy_timeout=5000; SELECT id, status, finished_at FROM jobs WHERE status IN ('completed','failed','cancelled') AND finished_at IS NOT NULL AND finished_at <= '$cutoff_iso' ORDER BY finished_at ASC;"
    )
}

log "Cleanup service started"
log "enabled=$CLEANUP_ENABLED ttl_hours=$CLEANUP_TTL_HOURS interval_minutes=$CLEANUP_INTERVAL_MINUTES jobs_dir=$JOBS_DIR database=$DATABASE_PATH"

while true; do
    cleanup_once
    sleep "$INTERVAL_SECONDS"
done
