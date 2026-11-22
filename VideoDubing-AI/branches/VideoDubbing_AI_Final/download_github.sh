#!/bin/bash

set -e

REPO="git@github.com:daoaigiangnam/VideoDubing-AI.git"
PROJECT="VideoDubing-AI"

echo "=========================================="
echo " GitHub Auto Login & Download"
echo "=========================================="

if ! command -v git >/dev/null 2>&1; then
    echo "ERROR: Git chưa được cài đặt."
    exit 1
fi

if ! command -v ssh >/dev/null 2>&1; then
    echo "ERROR: SSH chưa được cài đặt."
    exit 1
fi

if [ -f "/www/wwwroot/VideoDubing-AI/id_videodubbing" ]; then
    SSH_KEY="/www/wwwroot/VideoDubing-AI/id_videodubbing"
else
    echo "ERROR: Không tìm thấy SSH Key."
    exit 1
fi

eval "$(ssh-agent -s)" >/dev/null
ssh-add "$SSH_KEY" 2>/dev/null || {
    echo "ERROR: Không thể add SSH Key."
    exit 1
}

SSH_TEST=$(ssh -T git@github.com 2>&1 || true)
echo "$SSH_TEST"

if echo "$SSH_TEST" | grep -qi "successfully authenticated" || echo "$SSH_TEST" | grep -qi "Hi "; then
    echo "[OK] GitHub SSH authentication thành công."
else
    echo "ERROR: GitHub SSH authentication thất bại."
    exit 1
fi

if [ -d "$PROJECT/.git" ]; then
    cd "$PROJECT"
    git pull origin main
else
    git clone "$REPO" "$PROJECT"
fi

echo "Repository downloaded/updated successfully."
