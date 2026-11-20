
from datetime import datetime


class Logger:

    @staticmethod
    def _write(level: str, message: str):

        now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        print(f"[{now}] [{level}] {message}")

    @staticmethod
    def info(message: str):

        Logger._write("INFO", message)

    @staticmethod
    def warning(message: str):

        Logger._write("WARNING", message)

    @staticmethod
    def error(message: str):

        Logger._write("ERROR", message)

    @staticmethod
    def success(message: str):

        Logger._write("SUCCESS", message)
