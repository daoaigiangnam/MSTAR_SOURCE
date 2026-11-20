import json
from pathlib import Path


class Config:

    def __init__(self):

        self.root = Path(__file__).resolve().parent.parent

        self.config_file = self.root / "config" / "config.json"
        self.domain_file = self.root / "config" / "domains.json"

        self.settings = {}
        self.wans = []
        self.domains = []

    def load(self):

        self.settings = {}
        self.wans = []
        self.domains = []

        # config.json
        if self.config_file.exists():

            with open(self.config_file, "r", encoding="utf-8") as f:

                cfg = json.load(f)

            self.settings = cfg.get("settings", {})
            self.wans = cfg.get("wan", [])

        # domains.json
        if self.domain_file.exists():

            with open(self.domain_file, "r", encoding="utf-8") as f:

                self.domains = json.load(f)

        return self

    def get_setting(self, name, default=None):

        return self.settings.get(name, default)

    def get_check_methods(self):

        methods = self.settings.get("check_methods", [])

        return [m for m in methods if m.get("enable", False)]

    def get_wans(self):

        return sorted(
            self.wans,
            key=lambda x: x.get("priority", 999)
        )

    def get_domains(self):

        return self.domains
