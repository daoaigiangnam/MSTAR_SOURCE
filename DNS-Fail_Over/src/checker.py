
"""
=========================================================
DNS Failover
WAN Checker
Version : 2.0
=========================================================
"""

import platform
import re
import socket
import subprocess
import time
from datetime import datetime

import requests

from config import Config
from logger import Logger


class Checker:

    def __init__(self, config: Config):

        self.config = config

        self.system = platform.system().lower()

        self.timeout = config.get_setting("timeout", 5)

        self.retry = config.get_setting("retry", 3)

        self.retry_delay = config.get_setting("retry_delay", 2)

        self.methods = config.get_check_methods()

    # =====================================================
    # Check One WAN
    # =====================================================

    def check(self, wan: dict):

        Logger.info(
            f"Checking {wan['name']} ({wan['ip']})"
        )

        for method in self.methods:

            check_type = method.get("type", "").lower()

            if check_type == "ping":

                result = self._ping(wan)

            elif check_type == "tcp":

                result = self._tcp(
                    wan,
                    method.get("port", 80)
                )

            elif check_type == "http":

                result = self._http(
                    wan,
                    False,
                    method.get("url", "/")
                )

            elif check_type == "https":

                result = self._http(
                    wan,
                    True,
                    method.get("url", "/")
                )

            else:

                Logger.warning(
                    f"Unknown check method : {check_type}"
                )

                continue

            if result["success"]:

                return result

        return {

            "success": False,

            "wan": wan["name"],

            "ip": wan["ip"],

            "method": None,

            "latency": None,

            "retry": self.retry,

            "time": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),

            "message": "All methods failed"

        }

    # =====================================================
    # Extract Ping Time
    # =====================================================

    def _extract_ping_time(self, output: str):

        output = output.lower()

        patterns = [

            r"time[=<]\s*([\d\.]+)\s*ms",

            r"tempo[=<]\s*([\d\.]+)\s*ms",

            r"temps[=<]\s*([\d\.]+)\s*ms"

        ]

        for pattern in patterns:

            match = re.search(pattern, output)

            if match:

                try:

                    return float(match.group(1))

                except Exception:

                    pass

        return None

    # =====================================================
    # Ping
    # =====================================================

    def _ping(self, wan: dict):

        ip = wan["ip"]

        for attempt in range(1, self.retry + 1):

            Logger.info(

                f"PING {ip} ({attempt}/{self.retry})"

            )

            start = time.perf_counter()

            try:

                if self.system == "windows":

                    cmd = [

                        "ping",

                        "-n", "1",

                        "-w", str(self.timeout * 1000),

                        ip

                    ]

                else:

                    cmd = [

                        "ping",

                        "-c", "1",

                        "-W", str(self.timeout),

                        ip

                    ]

                process = subprocess.run(

                    cmd,

                    capture_output=True,

                    text=True,

                    timeout=self.timeout + 2

                )

                output = (

                    process.stdout +

                    process.stderr

                ).lower()

                elapsed = round(

                    (time.perf_counter() - start) * 1000,

                    2

                )

                latency = self._extract_ping_time(output)

                if latency is None:

                    latency = elapsed

                if process.returncode == 0:

                    Logger.success(

                        f"{ip} Alive ({latency} ms)"

                    )

                    return {

                        "success": True,

                        "wan": wan["name"],

                        "ip": ip,

                        "method": "ping",

                        "latency": latency,

                        "retry": attempt,

                        "time": datetime.now().strftime(

                            "%Y-%m-%d %H:%M:%S"

                        ),

                        "message": "Reply OK"

                    }

                Logger.warning(

                    f"Ping Failed ({attempt})"

                )

            except subprocess.TimeoutExpired:

                Logger.warning(

                    "Ping Timeout"

                )

            except Exception as ex:

                Logger.error(str(ex))

            if attempt < self.retry:

                Logger.info(

                    f"Retry after {self.retry_delay}s"

                )

                time.sleep(self.retry_delay)

        return {

            "success": False,

            "wan": wan["name"],

            "ip": ip,

            "method": "ping",

            "latency": None,

            "retry": self.retry,

            "time": datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            ),

            "message": "Ping Failed"

        }

    # =====================================================
    # TCP Check
    # =====================================================

    def _tcp(self, wan: dict, port: int):

        ip = wan["ip"]

        for attempt in range(1, self.retry + 1):

            Logger.info(

                f"TCP {ip}:{port} ({attempt}/{self.retry})"

            )

            start = time.perf_counter()

            try:

                sock = socket.create_connection(

                    (ip, port),

                    timeout=self.timeout

                )

                sock.close()

                latency = round(

                    (time.perf_counter() - start) * 1000,

                    2

                )

                Logger.success(

                    f"{ip}:{port} Alive ({latency} ms)"

                )

                return {

                    "success": True,

                    "wan": wan["name"],

                    "ip": ip,

                    "method": "tcp",

                    "latency": latency,

                    "retry": attempt,

                    "time": datetime.now().strftime(

                        "%Y-%m-%d %H:%M:%S"

                    ),

                    "message": f"TCP {port} OK"

                }

            except Exception:

                Logger.warning(

                    f"TCP {port} Failed"

                )

            if attempt < self.retry:

                Logger.info(

                    f"Retry after {self.retry_delay}s"

                )

                time.sleep(self.retry_delay)

        return {

            "success": False,

            "wan": wan["name"],

            "ip": ip,

            "method": "tcp",

            "latency": None,

            "retry": self.retry,

            "time": datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            ),

            "message": f"TCP {port} Failed"

        }

    # =====================================================
    # HTTP / HTTPS Check
    # =====================================================

    def _http(

        self,

        wan: dict,

        https: bool,

        url: str

    ):

        protocol = "https" if https else "http"

        target = f"{protocol}://{wan['ip']}{url}"

        for attempt in range(1, self.retry + 1):

            Logger.info(

                f"{protocol.upper()} {target} ({attempt}/{self.retry})"

            )

            start = time.perf_counter()

            try:

                response = requests.get(

                    target,

                    timeout=self.timeout,

                    verify=False,

                    allow_redirects=True

                )

                latency = round(

                    (time.perf_counter() - start) * 1000,

                    2

                )

                if response.status_code < 500:

                    Logger.success(

                        f"{target} [{response.status_code}] ({latency} ms)"

                    )

                    return {

                        "success": True,

                        "wan": wan["name"],

                        "ip": wan["ip"],

                        "method": protocol,

                        "latency": latency,

                        "retry": attempt,

                        "time": datetime.now().strftime(

                            "%Y-%m-%d %H:%M:%S"

                        ),

                        "message": f"HTTP {response.status_code}"

                    }

                Logger.warning(

                    f"HTTP Status {response.status_code}"

                )

            except Exception as ex:

                Logger.warning(str(ex))

            if attempt < self.retry:

                Logger.info(

                    f"Retry after {self.retry_delay}s"

                )

                time.sleep(self.retry_delay)

        return {

            "success": False,

            "wan": wan["name"],

            "ip": wan["ip"],

            "method": protocol,

            "latency": None,

            "retry": self.retry,

            "time": datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            ),

            "message": f"{protocol.upper()} Failed"

        }

    # =====================================================
    # Run Checker
    # =====================================================

    def run(self):

        Logger.info("")

        Logger.info("========================================")

        Logger.info("WAN CHECK START")

        Logger.info("========================================")

        wans = self.config.get_wans()

        if len(wans) == 0:

            Logger.error("No WAN Configured")

            return None

        for wan in wans:

            result = self.check(wan)

            if result["success"]:

                return result

            Logger.warning(

                f"{wan['name']} Unavailable"

            )

        Logger.error("")

        Logger.error("========================================")

        Logger.error("NO WAN AVAILABLE")

        Logger.error("========================================")

        return {

            "success": False,

            "wan": None,

            "ip": None,

            "method": None,

            "latency": None,

            "retry": self.retry,

            "time": datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            ),

            "message": "No WAN Available"

        }

    # =====================================================
    # Get Version
    # =====================================================

    @staticmethod
    def version():

        return "2.0"

    # =====================================================
    # Get Platform
    # =====================================================

    def get_platform(self):

        return self.system

    # =====================================================
    # Get Timeout
    # =====================================================

    def get_timeout(self):

        return self.timeout

    # =====================================================
    # Get Retry
    # =====================================================

    def get_retry(self):

        return self.retry

    # =====================================================
    # Get Retry Delay
    # =====================================================

    def get_retry_delay(self):

        return self.retry_delay

    # =====================================================
    # Get Check Methods
    # =====================================================

    def get_methods(self):

        return self.methods

    # =====================================================
    # Self Test
    # =====================================================

    def self_test(self):

        Logger.info("")

        Logger.info("========================================")

        Logger.info("CHECKER SELF TEST")

        Logger.info("========================================")

        Logger.info(

            f"Platform     : {self.system}"

        )

        Logger.info(

            f"Timeout      : {self.timeout}"

        )

        Logger.info(

            f"Retry        : {self.retry}"

        )

        Logger.info(

            f"Retry Delay  : {self.retry_delay}"

        )

        Logger.info(

            f"Methods      : {len(self.methods)}"

        )

        Logger.info("")

        return True

    # =====================================================
    # Health Check
    # =====================================================

    def health(self):

        return {

            "platform": self.system,

            "timeout": self.timeout,

            "retry": self.retry,

            "retry_delay": self.retry_delay,

            "methods": self.methods

        }

    # =====================================================
    # String
    # =====================================================

    def __str__(self):

        return (

            f"Checker("

            f"platform={self.system}, "

            f"timeout={self.timeout}, "

            f"retry={self.retry}, "

            f"retry_delay={self.retry_delay}"

            f")"

        )

    # =====================================================
    # Representation
    # =====================================================

    def __repr__(self):

        return self.__str__()

  
