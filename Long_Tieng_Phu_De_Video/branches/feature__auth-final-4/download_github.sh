#!/bin/bash

set -e

REPO="git@github.com:daoaigiangnam/VideoDubing-AI.git"
PROJECT="VideoDubing-AI"

echo "=========================================="
echo " GitHub Auto Login & Download"
echo "=========================================="

# 1. Check Git
if ! command -v git >/dev/null 2>&1; then
    echo "ERROR: Git chưa được cài đặt."
    exit 1
fi

echo "[OK] Git: $(git --version)"

# 2. Check SSH
if ! command -v ssh >/dev/null 2>&1; then
    echo "ERROR: SSH chưa được cài đặt."
    exit 1
fi

# 3. Find SSH key
if [ -f "/www/wwwroot/VideoDubing-AI/id_videodubbing" ]; then
    SSH_KEY="/www/wwwroot/VideoDubing-AI/id_videodubbing"
elif [ -f "/www/wwwroot/VideoDubing-AI/id_videodubbing" ]; then
    SSH_KEY="/www/wwwroot/VideoDubing-AI/id_videodubbing"
else
    echo "ERROR: Không tìm thấy SSH Key."
    echo
    echo "Kiểm tra:"
    echo "  ls -la /www/wwwroot/VideoDubing-AI"
    exit 1
fi

echo "[OK] SSH Key: $SSH_KEY"

# 4. Start ssh-agent
eval "$(ssh-agent -s)" >/dev/null

# 5. Add SSH key
ssh-add "$SSH_KEY" 2>/dev/null || {
    echo "ERROR: Không thể add SSH Key."
    exit 1
}

echo "[OK] SSH Key đã được nạp."

# 6. Test GitHub
echo
echo "Đang kiểm tra kết nối GitHub..."

SSH_TEST=$(ssh -T git@github.com 2>&1 || true)

echo "$SSH_TEST"

if echo "$SSH_TEST" | grep -qi "successfully authenticated"; then
    echo "[OK] GitHub SSH authentication thành công."
elif echo "$SSH_TEST" | grep -qi "Hi "; then
    echo "[OK] GitHub SSH authentication thành công."
else
    echo
    echo "ERROR: GitHub SSH authentication thất bại."
    exit 1
fi

# 7. Download / Update repository
echo
echo "=========================================="
echo " Download Repository"
echo "=========================================="

if [ -d "$PROJECT/.git" ]; then

    echo "[INFO] Repository đã tồn tại."
    echo "[INFO] Đang cập nhật source..."

    cd "$PROJECT"

    git fetch origin
    git pull --ff-only

else

    echo "[INFO] Repository chưa tồn tại."
    echo "[INFO] Đang clone..."

    git clone "$REPO" "$PROJECT"

    cd "$PROJECT"
fi

# 8. Check status
echo
echo "=========================================="
echo " Repository Status"
echo "=========================================="

git status

echo
echo "Branch:"
git branch --show-current

echo
echo "Latest Commit:"
git log -1 --oneline

echo
echo "=========================================="
echo " DOWNLOAD SUCCESS"
echo "=========================================="

echo
echo "Source:"
pwd

echo
echo "Files:"
ls -la