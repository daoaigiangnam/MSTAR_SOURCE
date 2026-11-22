FROM python:3.11-slim

RUN apt-get update \
    && apt-get install -y --no-install-recommends ffmpeg curl git build-essential nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY . .

# Fail the image build early if the audio stack is incomplete.  Demucs with
# modern torchaudio requires TorchCodec for saving separated audio.
RUN python -c "import torch, torchaudio, torchcodec, faster_whisper, demucs, edge_tts; print('torch=', torch.__version__); print('torchaudio=', torchaudio.__version__); print('torchcodec=OK'); print('AI runtime imports OK')" \
    && yt-dlp --version \
    && node --version

EXPOSE 8000
CMD ["uvicorn", "worker.main:app", "--host", "0.0.0.0", "--port", "8000"]
