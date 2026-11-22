from worker.timing import available_slot, build_segments, fit_duration


def test_build_segments_preserves_timeline():
    result = build_segments([{"start": 1.2, "end": 3.7, "text": "Hello"}], ["Xin chào"])
    assert result[0].duration == 2.5
    assert result[0].target_text == "Xin chào"


def test_build_segments_clamps_overlapping_segments():
    result = build_segments([
        {"start": 1.0, "end": 3.0, "text": "one"},
        {"start": 2.8, "end": 4.0, "text": "two"},
    ])
    assert result[1].start >= result[0].end


def test_available_slot_uses_gap_before_next_segment():
    result = build_segments([
        {"start": 1.0, "end": 2.0, "text": "one"},
        {"start": 3.0, "end": 4.0, "text": "two"},
    ])
    assert available_slot(result[0], result[1]) <= 2.0


def test_fit_duration_has_bounds():
    assert fit_duration("hello", 2.0) >= 0.25
    assert fit_duration("a" * 1000, 2.0) <= 2.0
