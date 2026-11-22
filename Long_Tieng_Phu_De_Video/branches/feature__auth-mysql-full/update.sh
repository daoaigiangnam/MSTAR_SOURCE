#!/bin/bash

set -e

PROJECT_DIR="/www/wwwroot/VideoDubing-AI"
REPO="https://github.com/daoaigiangnam/VideoDubing-AI.git"

echo "======================================"
echo " VideoDubing-AI DEPLOY / UPDATE"
echo "======================================"

if [ ! -d "$PROJECT_DIR/.git" ]; then
    echo "[1/4] Project chưa tồn tại - clone GitHub..."

    mkdir -p "$(dirname "$PROJECT_DIR")"

    git clone "$REPO" "$PROJECT_DIR"
else
    echo "[1/4] Project đã tồn tại - git pull..."

    cd "$PROJECT_DIR"
    git pull origin main
fi

cd "$PROJECT_DIR"

echo
echo "[2/4] Build API + Worker..."
docker compose build --no-cache api worker

echo
echo "[3/4] Start API + Worker..."
docker compose up -d api worker

echo
echo "[4/4] Container status..."
docker compose ps

echo
echo "======================================"
echo " DEPLOY / UPDATE COMPLETED"
echo "======================================"