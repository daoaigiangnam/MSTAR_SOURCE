
"""
=========================================================
Telegram Notification
Version : 2.0
=========================================================
"""

import os
import time
import requests

from logger import Logger


class Telegram:

    def __init__(self):

        self.enable = True

        self.token = os.getenv("TG_BOT_TOKEN", "").strip()

        self.chat_id = os.getenv("TG_CHAT_ID", "").strip()

        self.timeout = 20

        self.retry = 3

        self.retry_delay = 2

        if self.token == "" or self.chat_id == "":

            self.enable = False

            Logger.warning(
                "Telegram Disabled (Secret Missing)"
            )

        else:

            Logger.success(
                "Telegram Client Initialized"
            )

    # =====================================================
    # Send Message
    # =====================================================

    def send(
        self,
        message: str,
        parse_mode: str = "HTML"
    ) -> bool:

        if not self.enable:

            return False

        url = (
            f"https://api.telegram.org/bot"
            f"{self.token}/sendMessage"
        )

        payload = {

            "chat_id": self.chat_id,

            "text": message,

            "parse_mode": parse_mode,

            "disable_web_page_preview": True

        }

        for attempt in range(1, self.retry + 1):

            Logger.info(

                f"Telegram Send ({attempt}/{self.retry})"

            )

            try:

                response = requests.post(

                    url,

                    json=payload,

                    timeout=self.timeout

                )

                response.raise_for_status()

                result = response.json()

                if result.get("ok", False):

                    Logger.success(

                        "Telegram Message Sent"

                    )

                    return True

                Logger.warning(

                    str(result)

                )

            except Exception as ex:

                Logger.warning(

                    str(ex)

                )

            if attempt < self.retry:

                time.sleep(

                    self.retry_delay

                )

        Logger.error(

            "Telegram Send Failed"

        )

        return False

    # =====================================================
    # DNS Failover
    # =====================================================

    def send_failover(
        self,
        old_wan: str,
        new_wan: str,
        old_ip: str,
        new_ip: str,
        domains: list
    ):

        msg = (
            "🔄 <b>DNS FAILOVER</b>\n\n"
            f"<b>Primary</b>\n{old_wan}\n\n"
            f"<b>Backup</b>\n{new_wan}\n\n"
            "<b>DNS</b>\n"
            f"{old_ip}\n⬇\n{new_ip}\n\n"
            "<b>Domains</b>\n"
        )

        for domain in domains:

            msg += f"• {domain}\n"

        return self.send(msg)

    # =====================================================
    # Restore Primary
    # =====================================================

    def send_restore(
        self,
        primary: str,
        backup: str,
        old_ip: str,
        new_ip: str,
        domains: list
    ):

        msg = (
            "🟢 <b>PRIMARY RESTORED</b>\n\n"
            f"<b>Primary</b>\n{primary}\n\n"
            f"<b>Backup</b>\n{backup}\n\n"
            "<b>DNS</b>\n"
            f"{old_ip}\n⬇\n{new_ip}\n\n"
            "<b>Domains</b>\n"
        )

        for domain in domains:

            msg += f"• {domain}\n"

        return self.send(msg)

    # =====================================================
    # DNS Updated
    # =====================================================

    def send_dns_update(
        self,
        domain: str,
        old_ip: str,
        new_ip: str
    ):

        msg = (
            "🌐 <b>DNS UPDATED</b>\n\n"
            f"<b>Domain</b>\n{domain}\n\n"
            "<b>Old IP</b>\n"
            f"{old_ip}\n\n"
            "<b>New IP</b>\n"
            f"{new_ip}"
        )

        return self.send(msg)

    # =====================================================
    # Both WAN Down
    # =====================================================

    def send_both_down(
        self,
        wans: list
    ):

        msg = (
            "🚨 <b>CRITICAL</b>\n\n"
            "<b>All WAN Connections Are DOWN</b>\n\n"
        )

        for wan in wans:

            msg += (
                f"❌ {wan['name']}"
                f" ({wan['ip']})\n"
            )

        msg += (
            "\n<b>Action</b>\n"
            "Cloudflare DNS was NOT updated."
        )

        return self.send(msg)

    # =====================================================
    # Cloudflare Error
    # =====================================================

    def send_cloudflare_error(
        self,
        message: str
    ):

        msg = (
            "❌ <b>CLOUDFLARE ERROR</b>\n\n"
            f"{message}"
        )

        return self.send(msg)

    # =====================================================
    # Warning
    # =====================================================

    def send_warning(
        self,
        message: str
    ):

        msg = (
            "⚠️ <b>WARNING</b>\n\n"
            f"{message}"
        )

        return self.send(msg)

    # =====================================================
    # Information
    # =====================================================

    def send_info(
        self,
        message: str
    ):

        msg = (
            "ℹ️ <b>INFO</b>\n\n"
            f"{message}"
        )

        return self.send(msg)

    # =====================================================
    # Error
    # =====================================================

    def send_error(
        self,
        message: str
    ):

        msg = (
            "🔥 <b>ERROR</b>\n\n"
            f"{message}"
        )

        return self.send(msg)



  
