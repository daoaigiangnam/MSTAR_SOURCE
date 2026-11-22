from worker.subtitles import split_text, write_srt


def test_split_text():
    parts = split_text("Hello world. This is a test!")
    assert parts == ["Hello world.", "This is a test!"]


def test_write_srt(tmp_path):
    path = write_srt([
        {"start": 0, "end": 1.25, "text": "Hello"},
    ], str(tmp_path / "out.srt"))
    content = (tmp_path / "out.srt").read_text(encoding="utf-8")
    assert path.endswith("out.srt")
    assert "00:00:00,000 --> 00:00:01,250" in content
