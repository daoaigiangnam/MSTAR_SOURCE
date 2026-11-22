import os
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT))

from worker.main import app  # noqa: E402


def test_health():
    # Lightweight contract test without starting a server.
    routes = {route.path for route in app.routes}
    assert "/health" in routes
    assert "/jobs" in routes


def test_worker_api_url_default():
    assert os.getenv("API_URL", "http://api:3000").startswith("http")
