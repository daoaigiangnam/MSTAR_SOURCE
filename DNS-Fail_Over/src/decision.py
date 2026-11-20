"""
=========================================================
Decision Engine
Version : 4.1
=========================================================
"""

from enum import Enum
from datetime import datetime

from logger import Logger
from state import State
from telegram import Telegram


class DecisionEvent(Enum):
    FIRST_RUN = "FIRST_RUN"
    NO_CHANGE = "NO_CHANGE"
    FAILOVER = "FAILOVER"
    RESTORE = "RESTORE"
    BOTH_DOWN = "BOTH_DOWN"
    DNS_UPDATED = "DNS_UPDATED"
    DNS_MISMATCH = "DNS_MISMATCH"
    CLOUDFLARE_FAILED = "CLOUDFLARE_FAILED"


class DecisionResult:
    def __init__(self):
        self.event = DecisionEvent.NO_CHANGE
        self.old_wan = None
        self.new_wan = None
        self.old_ip = None
        self.new_ip = None
        self.old_status = None
        self.new_status = None
        self.need_dns_update = False
        self.need_notify = False
        self.need_save_state = False
        self.need_ = False
        self.need_restore = False
        self.cloudflare_success = True
        self.cloudflare_message = ""
        self.message = ""
        self.time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")


class Decision:
    def __init__(self, config):
        self.config = config
        self.state = State()
        self.telegram = Telegram()

        self.restore_primary = config.get_setting("restore_primary", True)
        self.fail_threshold = config.get_setting("fail_threshold", 2)
        self.restore_threshold = config.get_setting("restore_threshold", 2)

        self.fail_count = 0
        self.restore_count = 0

        Logger.info("")
        Logger.info("Decision Engine Initialized")
        Logger.info(f"Restore Primary : {self.restore_primary}")
        Logger.info(f"Fail Threshold  : {self.fail_threshold}")
        Logger.info(f"Restore Threshold : {self.restore_threshold}")

    @staticmethod
    def event_status(event: DecisionEvent) -> str:
        return {
            DecisionEvent.FIRST_RUN: "NORMAL",
            DecisionEvent.NO_CHANGE: "NORMAL",
            DecisionEvent.FAILOVER: "FAILOVER",
            DecisionEvent.RESTORE: "RESTORE",
            DecisionEvent.BOTH_DOWN: "BOTH_DOWN",
            DecisionEvent.DNS_UPDATED: "DNS_UPDATED",
            DecisionEvent.DNS_MISMATCH: "DNS_MISMATCH",
            DecisionEvent.CLOUDFLARE_FAILED: "CLOUDFLARE_FAILED"
        }.get(event, event.value)

    def finalize_status(self, result: DecisionResult):
        result.old_status = self.state.get_status()
        result.new_status = self.event_status(result.event)

        # Telegram is driven only by a real persisted status transition.
        # NORMAL is the steady state and is intentionally silent.
        result.need_notify = (
            result.old_status != result.new_status
            and result.new_status != "NORMAL"
        )

        Logger.info(
            f"State Transition : {result.old_status} -> {result.new_status}"
        )
        Logger.info(f"Telegram State Change : {result.need_notify}")

    def analyze(self, current) -> DecisionResult:
        result = DecisionResult()

        result.old_wan = self.state.get_current_wan()
        result.old_ip = self.state.get_current_ip()

        Logger.info("")
        Logger.info("Decision Engine")
        Logger.info("----------------------------------------")
        Logger.info(f"Old WAN : {result.old_wan}")
        Logger.info(f"Old IP  : {result.old_ip}")
        Logger.info(f"Old Status : {self.state.get_status()}")

        if current is None:
            result.event = DecisionEvent.BOTH_DOWN
            result.message = "All WAN Down"
            result.need_dns_update = False
            self.finalize_status(result)
            return result

        result.new_wan = current["wan"]
        result.new_ip = current["ip"]

        Logger.info(f"New WAN : {result.new_wan}")
        Logger.info(f"New IP  : {result.new_ip}")

        if result.old_wan is None:
            result.event = DecisionEvent.FIRST_RUN
            result.message = "First Run"
            result.need_dns_update = True
            self.finalize_status(result)
            return result

        if result.old_wan == result.new_wan and result.old_ip == result.new_ip:
            result.event = DecisionEvent.NO_CHANGE
            result.message = "No Change"
            result.need_dns_update = True
            self.finalize_status(result)
            return result

        if result.old_wan == "VNPT" and result.new_wan != "VNPT":
            result.event = DecisionEvent.FAILOVER
            result.message = "Primary WAN Failed"
            result.need_dns_update = True
            self.finalize_status(result)
            return result

        if (
            self.restore_primary
            and result.old_wan != "VNPT"
            and result.new_wan == "VNPT"
        ):
            result.event = DecisionEvent.RESTORE
            result.message = "Primary WAN Restored"
            result.need_restore = True
            result.need_dns_update = True
            self.finalize_status(result)
            return result

        result.event = DecisionEvent.DNS_UPDATED
        result.message = "WAN Changed"
        result.need_dns_update = True
        self.finalize_status(result)
        return result

    def notify(self, result: DecisionResult, domains: list):
        if not result.need_notify:
            Logger.info("Telegram : Skip (No Status Change)")
            return False

        Logger.info("")
        Logger.info("Telegram Notification")
        Logger.info("----------------------------------------")

        try:
            sent = False

            if result.event == DecisionEvent.FAILOVER:
                sent = self.telegram.send_failover(
                    result.old_wan, result.new_wan,
                    result.old_ip, result.new_ip,
                    domains
                )

            elif result.event == DecisionEvent.RESTORE:
                sent = self.telegram.send_restore(
                    result.new_wan, result.old_wan,
                    result.old_ip, result.new_ip,
                    domains
                )

            elif result.event == DecisionEvent.BOTH_DOWN:
                sent = self.telegram.send_both_down(self.config.get_wans())

            elif result.event == DecisionEvent.DNS_UPDATED:
                sent_count = 0
                for domain in domains:
                    domain_name = (
                        domain.get("name", "")
                        if isinstance(domain, dict)
                        else str(domain)
                    )
                    if self.telegram.send_dns_update(
                        domain_name, result.old_ip, result.new_ip
                    ):
                        sent_count += 1
                sent = sent_count > 0

            elif result.event == DecisionEvent.CLOUDFLARE_FAILED:
                sent = self.telegram.send_cloudflare_error(result.message)

            # FIRST_RUN and NO_CHANGE deliberately do not notify.
            if sent:
                self.state.mark_notification_sent(result.new_status)
                Logger.success(
                    f"Telegram State Notification Sent : {result.new_status}"
                )
                return True

            Logger.warning("Telegram Notification Not Sent")
            return False

        except Exception as ex:
            Logger.error(f"Telegram Error : {ex}")
            return False

    def save_state(self, result: DecisionResult):
        if not result.need_save_state:
            return

        Logger.info("")
        Logger.info("Save State")
        Logger.info("----------------------------------------")

        if result.new_wan is not None:
            self.state.update_wan(result.new_wan, result.new_ip)

        if result.cloudflare_success and result.new_ip is not None:
            self.state.update_dns(result.new_ip)

        self.state.set_status(result.new_status)

        if self.state.save():
            Logger.success("State Updated")
        else:
            Logger.error("Save state failed")

    def rollback(self, result: DecisionResult):
        Logger.warning("Rollback State")
        self.state.update_wan(result.old_wan, result.old_ip)
        self.state.update_dns(result.old_ip)
        self.state.set_status(result.old_status)
        self.state.save()

    def finish_success(self, result: DecisionResult, domains: list):
        self.summary(result)
        self.notify(result, domains)
        Logger.success("Decision Completed")

    def finish_failed(self, result: DecisionResult, error: str):
        result.event = DecisionEvent.CLOUDFLARE_FAILED
        result.message = error
        self.finalize_status(result)
        self.notify(result, [])
        Logger.error(error)

    def execute(
        self,
        result: DecisionResult,
        domains: list,
        cloudflare_success: bool = True,
        error_message: str = ""
    ) -> DecisionResult:

        Logger.info("")
        Logger.info("Decision Execute")
        Logger.info("----------------------------------------")

        result.cloudflare_success = cloudflare_success
        result.cloudflare_message = error_message

        if not cloudflare_success:
            Logger.error("Cloudflare Synchronization Failed")
            result.event = DecisionEvent.CLOUDFLARE_FAILED
            result.message = error_message or "Cloudflare Synchronization Failed"
            self.finalize_status(result)
            self.state.set_status(result.new_status)
            self.state.save()
            self.notify(result, domains)
            self.summary(result)
            return result

        if result.event == DecisionEvent.BOTH_DOWN:
            Logger.warning("All WAN Down")
            self.state.set_status(result.new_status)
            self.state.save()
            self.notify(result, domains)
            self.summary(result)
            return result

        result.need_save_state = True
        self.save_state(result)
        self.notify(result, domains)
        self.summary(result)

        Logger.success("Decision Completed")
        return result

    def summary(self, result: DecisionResult):
        Logger.info("")
        Logger.info("Decision Summary")
        Logger.info("----------------------------------------")
        Logger.info(f"Event           : {result.event.value}")
        Logger.info(f"Old Status      : {result.old_status}")
        Logger.info(f"New Status      : {result.new_status}")
        Logger.info(f"Old WAN         : {result.old_wan}")
        Logger.info(f"New WAN         : {result.new_wan}")
        Logger.info(f"Old IP          : {result.old_ip}")
        Logger.info(f"New IP          : {result.new_ip}")
        Logger.info(f"Need DNS Update : {result.need_dns_update}")
        Logger.info(f"Need Notify     : {result.need_notify}")
        Logger.info(f"Need Save State : {result.need_save_state}")
        Logger.info(f"Message         : {result.message}")
        Logger.info("")

    def dump(self):
        self.state.dump()

    def current_state(self):
        return {
            "wan": self.state.get_current_wan(),
            "ip": self.state.get_current_ip(),
            "dns": self.state.get_dns_ip(),
            "status": self.state.get_status(),
            "last_change": self.state.get("last_change"),
            "last_notify": self.state.get("last_notify")
        }

    def reset(self):
        self.state.reset()

    def version(self):
        return "4.1"
