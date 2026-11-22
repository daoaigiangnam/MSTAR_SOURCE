from types import SimpleNamespace

from worker.transcriber import _split_word_timed_segment


def test_word_timestamps_split_sentences_and_ignore_segment_silence():
    segment = SimpleNamespace(
        start=10.0,
        end=275.0,
        text="Hello there. How are you?",
        words=[
            SimpleNamespace(word="Hello ", start=10.0, end=10.4),
            SimpleNamespace(word="there. ", start=10.5, end=10.9),
            SimpleNamespace(word="How ", start=11.2, end=11.5),
            SimpleNamespace(word="are ", start=11.5, end=11.8),
            SimpleNamespace(word="you?", start=11.8, end=12.1),
        ],
    )

    result = _split_word_timed_segment(segment)

    assert len(result) == 2
    assert result[0]["start"] == 10.0
    assert result[0]["end"] == 10.9
    assert result[0]["text"] == "Hello there."
    assert result[1]["start"] == 11.2
    assert result[1]["end"] == 12.1
    assert result[1]["text"] == "How are you?"
