FROM python:3.11-slim

# FFmpeg + comprehensive Unicode/font stack for multilingual subtitle rendering.
# Noto + Fontconfig provide broad Unicode coverage with automatic fallback
# across Latin, CJK, Arabic, Indic, Thai, Khmer, Lao, Myanmar, Hebrew,
# Greek, Cyrillic and emoji without hard-coding a language-specific font.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ffmpeg \
        curl \
        git \
        build-essential \
        nodejs \
        fontconfig \
        fonts-noto-core \
        fonts-noto-extra \
        fonts-noto-cjk \
        fonts-noto-mono \
        fonts-noto-color-emoji \
    && fc-cache -f -v \
    && rm -rf /var/lib/apt/lists/*

ENV LANG=C.UTF-8 \
    LC_ALL=C.UTF-8 \
    PYTHONUTF8=1

WORKDIR /app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY . .

# Fail the image build early if the AI/audio stack or multilingual font
# fallback is incomplete.
RUN python -c "import torch, torchaudio, torchcodec, faster_whisper, demucs, edge_tts; print('torch=', torch.__version__); print('torchaudio=', torchaudio.__version__); print('torchcodec=OK'); print('AI runtime imports OK')" \
    && yt-dlp --version \
    && node --version \
    && fc-match ':lang=en' \
    && fc-match ':lang=vi' \
    && fc-match ':lang=zh' \
    && fc-match ':lang=ja' \
    && fc-match ':lang=ko' \
    && fc-match ':lang=ar' \
    && fc-match ':lang=hi' \
    && fc-match ':lang=bn' \
    && fc-match ':lang=th' \
    && fc-match ':lang=he' \
    && fc-match ':lang=el' \
    && fc-match ':lang=ru'

EXPOSE 8000
CMD ["uvicorn", "worker.main:app", "--host", "0.0.0.0", "--port", "8000"]
