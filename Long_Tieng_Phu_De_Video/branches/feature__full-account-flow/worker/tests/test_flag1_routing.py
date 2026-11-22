from worker.main import JobRequest, _effective_task


def test_flag1_zh_to_zh_hans_is_download_only():
    request = JobRequest(
        job_id="test-zh-hans",
        task="download",
        payload={
            "source_language": "zh",
            "target_language": "zh-Hans",
        },
    )
    assert _effective_task(request) == "download_only"


def test_flag1_zh_hans_monitor_case_without_source_is_download_only():
    request = JobRequest(
        job_id="test-zh-hans-monitor",
        task="download",
        payload={
            "target_language": "zh-Hans",
        },
    )
    assert _effective_task(request) == "download_only"


def test_flag1_chinese_to_vietnamese_still_uses_dubbing():
    request = JobRequest(
        job_id="test-zh-vi",
        task="download",
        payload={
            "source_language": "zh",
            "target_language": "vi",
        },
    )
    assert _effective_task(request) == "download"


def test_flag1_zh_hant_is_not_changed_to_download_only():
    request = JobRequest(
        job_id="test-zh-hant",
        task="download",
        payload={
            "source_language": "zh",
            "target_language": "zh-Hant",
        },
    )
    assert _effective_task(request) == "download"
