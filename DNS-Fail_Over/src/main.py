"""
=========================================================
DNS Failover
Version : 4.0
=========================================================
"""

import sys

from config import Config
from logger import Logger
from checker import Checker
from cloudflare import Cloudflare
from decision import Decision


class DNSFailover:

    def __init__(self):

        Logger.info("")
        Logger.info("========================================")
        Logger.info("DNS FAILOVER STARTING")
        Logger.info("========================================")

        #
        # Load Config
        #

        self.config = Config().load()

        #
        # Checker
        #

        self.checker = Checker(

            self.config

        )

        #
        # Cloudflare
        #

        self.cloudflare = Cloudflare()

        #
        # Decision
        #

        self.decision = Decision(

            self.config

        )

        #
        # Domains
        #

        self.domains = self.config.get_domains()

        #
        # Runtime
        #

        self.result = None

        self.decision_result = None

    # =====================================================
    # Show Configuration
    # =====================================================

    def show_config(self):

        Logger.info("")

        Logger.info("Configuration")

        Logger.info("----------------------------------------")

        Logger.info(

            f"Timeout           : {self.config.get_setting('timeout')}"

        )

        Logger.info(

            f"Retry             : {self.config.get_setting('retry')}"

        )

        Logger.info(

            f"Retry Delay       : {self.config.get_setting('retry_delay')}"

        )

        Logger.info(

            f"Fail Threshold    : {self.config.get_setting('fail_threshold')}"

        )

        Logger.info(

            f"Restore Threshold : {self.config.get_setting('restore_threshold')}"

        )

        Logger.info(

            f"Restore Primary   : {self.config.get_setting('restore_primary')}"

        )

        Logger.info("")

    # =====================================================
    # Check WAN
    # =====================================================

    def check_wan(self):

        Logger.info(

            "Checking WAN"

        )

        Logger.info(

            "----------------------------------------"

        )

        #
        # Checker
        #

        self.result = self.checker.run()

        #
        # Decision Analyze
        # Chỉ gọi 1 lần duy nhất
        #

        self.decision_result = self.decision.analyze(

            self.result

        )

        #
        # No WAN
        #

        if self.result is None:

            Logger.warning(

                "No WAN Available"

            )

            return False

        #
        # Selected WAN
        #

        Logger.success(

            f"Selected WAN : {self.result['wan']}"

        )

        Logger.success(

            f"IP           : {self.result['ip']}"

        )

        Logger.success(

            f"Method       : {self.result['method']}"

        )

        Logger.success(

            f"Latency      : {self.result['latency']} ms"

        )

        return True

    # =====================================================
    # Cloudflare
    # =====================================================
    
    def sync_cloudflare(self):
    
        if self.result is None:
    
            return {
    
                "success": False,
    
                "message": "No WAN"
    
            }
    
        Logger.info("")
        Logger.info("Cloudflare")
        Logger.info("----------------------------------------")
    
        try:
    
            return self.cloudflare.sync(
    
                self.domains,
    
                self.result["ip"]
    
            )
    
        except Exception as ex:
    
            Logger.error(str(ex))
    
            return {
    
                "success": False,
    
                "message": str(ex),
    
                "changed": False,
    
                "updated": 0,
    
                "failed": 1,
    
                "details": []
    
            }

    # =====================================================
    # Execute Decision
    # =====================================================
    
    def execute_decision(
    
        self,
    
        cloudflare_result
    
    ):
    
        Logger.info("")
    
        Logger.info("Decision")
    
        Logger.info("----------------------------------------")
    
        self.decision.execute(
        
            result=self.decision_result,
        
            domains=self.domains,
        
            cloudflare_success=cloudflare_result["success"],
        
            error_message=cloudflare_result.get(
        
                "message",
        
                ""
        
            )
        
        )

    # =====================================================
    # Run
    # =====================================================
    
    def run(self):
    
        try:
    
            # ---------------------------------------------
            # Configuration
            # ---------------------------------------------
    
            self.show_config()
    
            # ---------------------------------------------
            # Check WAN
            # ---------------------------------------------
    
            self.check_wan()
    
            # ---------------------------------------------
            # Default Cloudflare Result
            # ---------------------------------------------
    
            cloudflare_result = {
    
                "success": False,
    
                "changed": False,
    
                "updated": 0,
    
                "skipped": 0,
    
                "failed": 0,
    
                "details": [],
    
                "message": ""
    
            }
    
            # ---------------------------------------------
            # Both WAN Down
            # ---------------------------------------------
    
            if self.result is None:
    
                cloudflare_result["message"] = "All WAN Down"
    
                self.execute_decision(
    
                    cloudflare_result
    
                )
    
                Logger.error("")
    
                Logger.error(
    
                    "DNS Failover Aborted"
    
                )
    
                return 1
    
            # ---------------------------------------------
            # Cloudflare
            # ---------------------------------------------
    
            cloudflare_result = self.sync_cloudflare()

            if cloudflare_result:
            
                self.decision_result.need_dns_update = (
            
                    cloudflare_result["updated"] > 0
            
                )            
    
            # ---------------------------------------------
            # Decision
            # ---------------------------------------------
    
            self.execute_decision(
    
                cloudflare_result
    
            )
    
            # ---------------------------------------------
            # Cloudflare Failed
            # ---------------------------------------------
    
            if not cloudflare_result.get(
    
                "success",
    
                False
    
            ):
    
                Logger.error("")
    
                Logger.error(
    
                    "Cloudflare Synchronization Failed"
    
                )
    
                return 1
    
            # ---------------------------------------------
            # Finish
            # ---------------------------------------------
    
            Logger.info("")
    
            Logger.success(
    
                "DNS Failover Finished Successfully"
    
            )
    
            return 0
    
        except KeyboardInterrupt:
    
            Logger.error(
    
                "Interrupted by User"
    
            )
    
            return 1
    
        except Exception as ex:
    
            Logger.error(
    
                str(ex)
    
            )
    
            return 1


# =========================================================
# Main
# =========================================================

def main():

    app = DNSFailover()

    code = app.run()

    Logger.info("")

    Logger.info(

        "========================================"

    )

    Logger.info(

        "APPLICATION EXIT"

    )

    Logger.info(

        "========================================"

    )

    Logger.info(

        f"Exit Code : {code}"

    )

    Logger.info("")

    sys.exit(code)


# =========================================================
# Entry
# =========================================================

if __name__ == "__main__":

    main()
