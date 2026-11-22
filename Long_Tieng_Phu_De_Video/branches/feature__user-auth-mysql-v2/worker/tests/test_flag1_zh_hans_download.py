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


def test_download_other_language_pair_is_unchanged():
    request = JobRequest(
        job_id="test-vi",
        task="download",
        payload={
            "source_language": "zh",
            "target_language": "vi",
        },
    )

    assert _effective_task(request) == "download"


def test_zh_hant_is_not_changed():
    request = JobRequest(
        job_id="test-zh-hant",
        task="download",
        payload={
            "source_language": "zh",
            "target_language": "zh-Hant",
        },
    )

    assert _effective_task(request) == "download"
