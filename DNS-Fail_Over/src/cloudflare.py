"""
=========================================================
DNS Failover
Cloudflare API
Version : 2.0
Part    : 1/3
=========================================================
"""

import os
import time
import requests

from logger import Logger


class Cloudflare:

    BASE_URL = "https://api.cloudflare.com/client/v4"

    # =====================================================
    # Update Record
    # =====================================================
    
    def update_record(
    
        self,
    
        domain,
    
        new_ip,
    
        record_type="A"
    
    ):
    
        record = self.get_record(
    
            domain,
    
            record_type
    
        )
    
        if record is None:
    
            raise RuntimeError(
    
                f"DNS Record Not Found : {domain}"
    
            )
    
        endpoint = (
    
            f"/zones/{self.zone_id}"
    
            f"/dns_records/{record['id']}"
    
        )
    
        payload = {
    
            "type": record["type"],
    
            "name": record["name"],
    
            "content": new_ip,
    
            "ttl": record["ttl"],
    
            "proxied": record["proxied"]
    
        }
    
        Logger.info(
    
            f"Updating {domain}"
    
        )
    
        self.put(
    
            endpoint,
    
            payload
    
        )
    
        Logger.success(
    
            f"{domain} Updated"
    
        )
    
        return True
    
    # =====================================================
    # Verify Update
    # =====================================================
    
    def verify_update(
    
        self,
    
        domain,
    
        expected_ip,
    
        record_type="A"
    
    ):
    
        record = self.get_record(
    
            domain,
    
            record_type
    
        )
    
        if record is None:
    
            return False
    
        current_ip = record["content"]
    
        if current_ip == expected_ip:
    
            Logger.success(
    
                f"{domain} Verify Success"
    
            )
    
            return True
    
        Logger.error(
    
            f"{domain} Verify Failed"
    
        )
    
        Logger.error(
    
            f"Current : {current_ip}"
    
        )
    
        Logger.error(
    
            f"Expect  : {expected_ip}"
    
        )
    
        return False    

    # =====================================================
    # Update If Needed
    # =====================================================
    
    def update_if_needed(
    
        self,
    
        domain,
    
        new_ip,
    
        record_type="A"
    
    ):
    
        record = self.get_record(
    
            domain,
    
            record_type
    
        )
    
        if record is None:
    
            raise RuntimeError(
    
                f"DNS Record Not Found : {domain}"
    
            )
    
        old_ip = record["content"]
    
        #
        # Already Correct
        #
    
        if old_ip == new_ip:
    
            Logger.info(
    
                f"{domain} Already {new_ip}"
    
            )
    
            return {
    
                "updated": False,
    
                "old_ip": old_ip,
    
                "new_ip": new_ip
    
            }
    
        #
        # Update DNS
        #
    
        Logger.info(
    
            f"{domain} : {old_ip} -> {new_ip}"
    
        )
    
        self.update_record(
    
            domain,
    
            new_ip,
    
            record_type
    
        )
    
        #
        # Verify
        #
    
        if not self.verify_update(
    
            domain,
    
            new_ip,
    
            record_type
    
        ):
    
            raise RuntimeError(
    
                f"Verify Failed : {domain}"
    
            )
    
        return {
    
            "updated": True,
    
            "old_ip": old_ip,
    
            "new_ip": new_ip
    
        }    

    # =====================================================
    # Sync DNS
    # =====================================================
    
    def sync(
    
        self,
    
        domains,
    
        target_ip
    
    ):
    
        Logger.info("")
        Logger.info("========================================")
        Logger.info("Cloudflare DNS Synchronization")
        Logger.info("========================================")
    
        total = len(domains)
    
        updated = 0
    
        skipped = 0
    
        failed = 0
    
        changed = False
    
        details = []
    
        for item in domains:
    
            # -----------------------------------------
            # Domain
            # -----------------------------------------
    
            if isinstance(item, dict):
    
                domain = item.get("name")
    
                record_type = item.get("type", "A")
    
            else:
    
                domain = item
    
                record_type = "A"
    
            Logger.info("")
            Logger.info("----------------------------------------")
            Logger.info(f"Domain : {domain}")
    
            try:
    
                info = self.update_if_needed(
    
                    domain,
    
                    target_ip,
    
                    record_type
    
                )
    
                if info["updated"]:
    
                    updated += 1
    
                    changed = True
    
                    Logger.success(
    
                        f"{domain} : {info['old_ip']} -> {info['new_ip']}"
    
                    )
    
                    details.append({
    
                        "domain": domain,
    
                        "status": "UPDATED",
    
                        "old_ip": info["old_ip"],
    
                        "new_ip": info["new_ip"]
    
                    })
    
                else:
    
                    skipped += 1
    
                    Logger.info(
    
                        f"{domain} : Skip"
    
                    )
    
                    details.append({
    
                        "domain": domain,
    
                        "status": "SKIPPED",
    
                        "old_ip": info["old_ip"],
    
                        "new_ip": info["new_ip"]
    
                    })
    
            except Exception as ex:
    
                Logger.error(
    
                    str(ex)
    
                )
    
                failed += 1
    
                details.append({
    
                    "domain": domain,
    
                    "status": "FAILED",
    
                    "message": str(ex)
    
                })
    
        Logger.info("")
        Logger.info("========================================")
        Logger.info("Cloudflare Synchronization Finished")
        Logger.info("========================================")
    
        Logger.success(f"Total   : {total}")
        Logger.success(f"Updated : {updated}")
        Logger.success(f"Skipped : {skipped}")
        Logger.success(f"Failed  : {failed}")
    
        return {
    
            "success": failed == 0,
    
            "changed": changed,
    
            "total": total,
    
            "updated": updated,
    
            "skipped": skipped,
    
            "failed": failed,
    
            "target_ip": target_ip,
    
            "details": details
    
        }    

    # =====================================================
    # Constructor
    # =====================================================

    def __init__(self):

        self.token = os.getenv("CF_TOKEN")

        self.zone_id = os.getenv("CF_ZONE_ID")

        if not self.token:

            raise RuntimeError(
                "Missing GitHub Secret : CF_TOKEN"
            )

        if not self.zone_id:

            raise RuntimeError(
                "Missing GitHub Secret : CF_ZONE_ID"
            )

        self.headers = {

            "Authorization": f"Bearer {self.token}",

            "Content-Type": "application/json"

        }

        Logger.success(
            "Cloudflare Client Initialized"
        )

        self.test()

    # =====================================================
    # Internal Request
    # =====================================================

    def _request(

        self,

        method,

        endpoint,

        payload=None,

        retry=3

    ):

        url = f"{self.BASE_URL}{endpoint}"

        for attempt in range(1, retry + 1):

            try:

                Logger.info(

                    f"{method.upper()} {url} ({attempt}/{retry})"

                )

                response = requests.request(

                    method=method,

                    url=url,

                    headers=self.headers,

                    json=payload,

                    timeout=30

                )

                response.raise_for_status()

                result = response.json()

                if not result.get("success", False):

                    raise RuntimeError(

                        str(result.get("errors", []))

                    )

                return result

            except Exception as ex:

                Logger.warning(str(ex))

                if attempt < retry:

                    Logger.info(

                        "Retry after 2s"

                    )

                    time.sleep(2)

        raise RuntimeError(

            f"Cloudflare API Failed : {endpoint}"

        )

    # =====================================================
    # HTTP GET
    # =====================================================

    def get(

        self,

        endpoint

    ):

        return self._request(

            "GET",

            endpoint

        )

    # =====================================================
    # HTTP POST
    # =====================================================

    def post(

        self,

        endpoint,

        payload

    ):

        return self._request(

            "POST",

            endpoint,

            payload

        )

    # =====================================================
    # HTTP PUT
    # =====================================================

    def put(

        self,

        endpoint,

        payload

    ):

        return self._request(

            "PUT",

            endpoint,

            payload

        )

    # =====================================================
    # HTTP PATCH
    # =====================================================

    def patch(

        self,

        endpoint,

        payload

    ):

        return self._request(

            "PATCH",

            endpoint,

            payload

        )

    # =====================================================
    # HTTP DELETE
    # =====================================================

    def delete(

        self,

        endpoint

    ):

        return self._request(

            "DELETE",

            endpoint

        )

    # =====================================================
    # Test API
    # =====================================================

    def test(self):

        Logger.info(

            "Testing Cloudflare API..."

        )

        endpoint = f"/zones/{self.zone_id}"

        result = self.get(endpoint)

        zone = result["result"]

        Logger.success(

            f"Zone : {zone['name']}"

        )

        Logger.success(

            f"Status : {zone['status']}"

        )

        return True

    # =====================================================
    # Zone
    # =====================================================

    def get_zone(self):

        endpoint = f"/zones/{self.zone_id}"

        result = self.get(

            endpoint

        )

        return result["result"]

    # =====================================================
    # DNS Records
    # =====================================================

    def get_records(

        self,

        record_type=None

    ):

        endpoint = (

            f"/zones/{self.zone_id}/dns_records"

        )

        if record_type:

            endpoint += f"?type={record_type}"

        result = self.get(

            endpoint

        )

        return result["result"]

    # =====================================================
    # Get Record
    # =====================================================
    
    def get_record(
    
        self,
    
        domain,
    
        record_type="A"
    
    ):
    
        endpoint = (
    
            f"/zones/{self.zone_id}/dns_records"
    
            f"?type={record_type}"
    
            f"&name={domain}"
    
        )
    
        result = self.get(
    
            endpoint
    
        )
    
        records = result.get(
    
            "result",
    
            []
    
        )
    
        if not records:
    
            Logger.warning(
    
                f"DNS Record Not Found : {domain}"
    
            )
    
            return None
    
        record = records[0]
    
        return {
    
            "id": record["id"],
    
            "zone_id": self.zone_id,
    
            "name": record["name"],
    
            "type": record["type"],
    
            "content": record["content"],
    
            "ttl": record["ttl"],
    
            "proxied": record["proxied"],
    
            "comment": record.get(
    
                "comment",
    
                ""
    
            )
    
        }




