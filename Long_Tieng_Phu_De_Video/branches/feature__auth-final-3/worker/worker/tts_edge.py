"""Concrete TTS provider using Edge TTS."""

import asyncio
from pathlib import Path

import edge_tts


async def _synthesize(text: str, output_file: str, voice: str, rate: str = "+0%"):
    communicate = edge_tts.Communicate(text, voice, rate=rate)
    await communicate.save(output_file)


def synthesize(text: str, output_file: str, voice: str = "en-US-AriaNeural", rate: str = "+0%") -> dict:
    if not text.strip():
        raise ValueError("text is required")
    path = Path(output_file)
    path.parent.mkdir(parents=True, exist_ok=True)
    asyncio.run(_synthesize(text, str(path), voice, rate))
    if not path.is_file() or path.stat().st_size == 0:
        raise RuntimeError("TTS provider produced no audio")
    return {"status": "completed", "audio_path": str(path), "voice": voice, "rate": rate}
