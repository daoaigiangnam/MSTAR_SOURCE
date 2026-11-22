FROM python:3.11-slim

RUN apt-get update \
    && apt-get install -y --no-install-recommends ffmpeg curl git build-essential nodejs unzip ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Modern yt-dlp requires an external JavaScript runtime for full YouTube
# extraction. Install Deno system-wide so YouTube JS challenges are solved
# consistently inside the worker container.
ENV DENO_INSTALL=/usr/local
RUN curl -fsSL https://deno.land/install.sh | sh \
    && deno --version

WORKDIR /app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY . .

# Fail the image build early if the media/AI runtime is incomplete.
RUN python -c "import torch, torchaudio, torchcodec, faster_whisper, demucs, edge_tts; print('torch=', torch.__version__); print('torchaudio=', torchaudio.__version__); print('torchcodec=OK'); print('AI runtime imports OK')" \
    && yt-dlp --version \
    && deno --version \
    && node --version \
    && ffmpeg -version | head -1

EXPOSE 8000
CMD ["uvicorn", "worker.main:app", "--host", "0.0.0.0", "--port", "8000"]
