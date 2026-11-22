from worker.config import default_tts_voice


def test_default_tts_voice_follows_target_language():
    assert default_tts_voice("vi") == "vi-VN-HoaiMyNeural"
    assert default_tts_voice("vi-VN") == "vi-VN-HoaiMyNeural"
    assert default_tts_voice("en") == "en-US-AriaNeural"
