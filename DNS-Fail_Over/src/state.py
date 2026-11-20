"""
=========================================================
State Manager
Version : 3.0
=========================================================
"""

import json

from pathlib import Path

from datetime import datetime

from logger import Logger

import os


class State:

    def __init__(self):



        self.root = Path(__file__).resolve().parent.parent

        self.data_dir = self.root / "data"

        self.state_file = self.data_dir / "state.json"

        Logger.info(f"ROOT      : {self.root}")
        Logger.info(f"DATA DIR  : {self.data_dir}")
        Logger.info(f"STATE FILE: {self.state_file}")        

        self.default = {

            "current_wan": None,

            "current_ip": None,

            "last_dns_ip": None,

            "status": "INIT",

            "last_change": None,

            "last_notify": None

        }

        self.data = {}

        self.load()

    # =====================================================
    # Load
    # =====================================================
    
    def load(self):
    
        os.makedirs(
            self.data_dir,
            exist_ok=True
        )
        
        Logger.info(f"Create Data Dir : {self.data_dir}")
    
        #
        # State file not exists
        #
    
        if not self.state_file.exists():
    
            self.data = self.default.copy()
    
            self.save()
    
            Logger.info(
    
                "Create state.json"
    
            )
    
            return
    
        #
        # Load State
        #
    
        try:
    
            with open(
    
                self.state_file,
    
                "r",
    
                encoding="utf-8"
    
            ) as f:
    
                self.data = json.load(f)
    
            #
            # Auto fill missing keys
            #
    
            changed = False
    
            for key, value in self.default.items():
    
                if key not in self.data:
    
                    self.data[key] = value
    
                    changed = True
    
            if changed:
    
                self.save()
    
                Logger.warning(
    
                    "state.json upgraded"
    
                )
    
            Logger.success(
    
                "State Loaded"
    
            )
    
        except Exception as ex:
    
            Logger.error(
    
                f"State Load Error : {ex}"
    
            )
    
            Logger.warning(
    
                "state.json invalid"
    
            )
    
            self.data = self.default.copy()
    
            self.save()
        
    # =====================================================
    # Save
    # =====================================================
    
    def save(self):
    
        try:
    
            self.data["last_change"] = (
                datetime.now().strftime(
                    "%Y-%m-%d %H:%M:%S"
                )
            )
    
            #
            # Create data directory
            #
    
            self.data_dir.mkdir(
                parents=True,
                exist_ok=True
            )
    
            with open(
                self.state_file,
                "w",
                encoding="utf-8"
            ) as f:
    
                json.dump(
                    self.data,
                    f,
                    indent=4,
                    ensure_ascii=False
                )
    
            Logger.info("State Saved")
    
            # -------------------------------
            # Debug
            # -------------------------------
    
            import os
    
            Logger.info(
                f"State File : {self.state_file}"
            )
    
            Logger.info(
                f"Exists     : {self.state_file.exists()}"
            )
    
            Logger.info(
                f"Data Dir   : {os.listdir(self.data_dir)}"
            )
    
            return True
    
        except Exception as ex:
    
            Logger.error(
                f"Save Error : {ex}"
            )
    
            return False

    # =====================================================
    # Get
    # =====================================================

    def get(

        self,

        key,

        default=None

    ):

        return self.data.get(

            key,

            default

        )

    # =====================================================
    # Set
    # =====================================================

    def set(

        self,

        key,

        value

    ):

        self.data[key] = value

    # =====================================================
    # Update Multiple
    # =====================================================

    def update(

        self,

        **kwargs

    ):

        for key, value in kwargs.items():

            self.data[key] = value

    # =====================================================
    # Exists
    # =====================================================

    def exists(self):

        return self.state_file.exists()

    # =====================================================
    # Reset
    # =====================================================

    def reset(self):

        self.data = self.default.copy()

        self.save()

        Logger.warning(

            "State Reset"

        )

    # =====================================================
    # Current WAN
    # =====================================================

    def get_current_wan(self):

        return self.get(

            "current_wan"

        )

    def set_current_wan(

        self,

        wan: str

    ):

        self.set(

            "current_wan",

            wan

        )

    # =====================================================
    # Current IP
    # =====================================================

    def get_current_ip(self):

        return self.get(

            "current_ip"

        )

    def set_current_ip(

        self,

        ip: str

    ):

        self.set(

            "current_ip",

            ip

        )

    # =====================================================
    # DNS IP
    # =====================================================

    def get_dns_ip(self):

        return self.get(

            "last_dns_ip"

        )

    def set_dns_ip(

        self,

        ip: str

    ):

        self.set(

            "last_dns_ip",

            ip

        )

    # =====================================================
    # Status
    # =====================================================

    def get_status(self):

        return self.get(

            "status",

            "INIT"

        )

    def set_status(

        self,

        status: str

    ):

        self.set(

            "status",

            status

        )

    # =====================================================
    # Compare
    # =====================================================

    def is_same_wan(

        self,

        wan: str

    ):

        return (

            self.get_current_wan()

            ==

            wan

        )

    def is_same_ip(

        self,

        ip: str

    ):

        return (

            self.get_current_ip()

            ==

            ip

        )

    def is_same_dns(

        self,

        ip: str

    ):

        return (

            self.get_dns_ip()

            ==

            ip

        )

    # =====================================================
    # Update WAN
    # =====================================================

    def update_wan(

        self,

        wan: str,

        ip: str

    ):

        self.set_current_wan(

            wan

        )

        self.set_current_ip(

            ip

        )

    # =====================================================
    # Update DNS
    # =====================================================

    def update_dns(

        self,

        ip: str

    ):

        self.set_dns_ip(

            ip

        )

    # =====================================================
    # Mark Failover
    # =====================================================

    def mark_failover(self):

        self.set_status(

            "FAILOVER"

        )

        self.set(

            "last_notify",

            datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            )

        )

    # =====================================================
    # Mark Restore
    # =====================================================

    def mark_restore(self):

        self.set_status(

            "RESTORE"

        )

        self.set(

            "last_notify",

            datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            )

        )

    # =====================================================
    # Mark Both Down
    # =====================================================

    def mark_both_down(self):

        self.set_status(

            "BOTH_DOWN"

        )

        self.set(

            "last_notify",

            datetime.now().strftime(

                "%Y-%m-%d %H:%M:%S"

            )

        )

    # =====================================================
    # Dump
    # =====================================================

    def dump(self):

        Logger.info("")

        Logger.info(

            "State"

        )

        Logger.info(

            "----------------------------------------"

        )

        for key, value in self.data.items():

            Logger.info(

                f"{key:<20}: {value}"

            )

        Logger.info("")
