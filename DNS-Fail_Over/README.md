# DNS Failover

Automatic Cloudflare DNS Failover using GitHub Actions.

---

# Features

- Automatic WAN Detection
- Multiple WAN Support
- Multiple Domain Support
- Cloudflare API
- GitHub Actions
- Retry
- Retry Delay
- Fail Threshold
- Restore Threshold
- Automatic Restore
- Cross Platform
- No Server Required
- No VPS Required
- No Cron Required

---

# Architecture

```
GitHub Actions
        │
        ▼
 Load Config
        │
        ▼
 Check WAN
        │
        ▼
 Select Active WAN
        │
        ▼
 Update Cloudflare DNS
        │
        ▼
 Verify
        │
        ▼
 Finish
```

---

# Project Structure

```
dns-failover/

│

├── .github/

│   └── workflows/

│       └── failover.yml

│

├── config/

│   ├── config.json

│   └── domains.json

│

├── src/

│   ├── config.py

│   ├── logger.py

│   ├── checker.py

│   ├── cloudflare.py

│   └── main.py

│

├── requirements.txt

├── LICENSE

└── README.md
```

---

# Requirements

- Python 3.11+
- GitHub Account
- Cloudflare Account
- API Token
- Zone ID

---

# GitHub Secrets

Repository

Settings

↓

Secrets and Variables

↓

Actions

Create

```
CF_TOKEN
```

Create

```
CF_ZONE_ID
```

---

# Config

config/config.json

```json
{
    "settings":{

        "timeout":5,

        "retry":3,

        "retry_delay":2,

        "fail_threshold":2,

        "restore_threshold":2,

        "restore_primary":true,

        "github_log":true,

        "history_log":true,

        "check_methods":[

            {

                "type":"ping",

                "enable":true

            }

        ]

    },

    "wan":[

        {

            "name":"VNPT",

            "ip":"14.161.10.44",

            "priority":1

        },

        {

            "name":"VIETTEL",

            "ip":"27.74.254.52",

            "priority":2

        }

    ]
}
```

---

# Domains

config/domains.json

```json
[
    {

        "name":"review360.id.vn",

        "type":"A"

    },

    {

        "name":"hoangkim.review360.id.vn",

        "type":"A"

    }
]
```

---

# Install

```bash
git clone https://github.com/USERNAME/dns-failover.git

cd dns-failover
```

Install packages

```bash
pip install -r requirements.txt
```

---

# Run

```bash
python src/main.py
```

Windows

```cmd
python src/main.py
```

Linux

```bash
python3 src/main.py
```

---

# GitHub Actions

Workflow

```
.github/workflows/failover.yml
```

Run Schedule

```
Every 5 Minutes
```

Manual Run

```
Actions

↓

DNS Failover

↓

Run Workflow
```

---

# How It Works

```
Start

↓

Load Config

↓

Load Domains

↓

Check Primary WAN

↓

Alive ?

│

├── Yes

│      │

│      ▼

│  Select Primary

│

└── No

       │

       ▼

Check Backup WAN

       │

Alive ?

│

├── Yes

│      │

│      ▼

│ Select Backup

│

└── No

       │

       ▼

Exit
```

---

# DNS Update Flow

```
Selected WAN

↓

Current WAN IP

↓

Cloudflare

↓

Read Current DNS

↓

Same ?

│

├── Yes

│      │

│      ▼

│ Skip

│

└── No

       │

       ▼

Update DNS

       │

       ▼

Verify

       │

       ▼

Done
```

---

# Add New WAN

config/config.json

```json
{

    "name":"FPT",

    "ip":"113.xxx.xxx.xxx",

    "priority":3

}
```

No source code modification required.

---

# Add New Domain

config/domains.json

```json
{

    "name":"api.review360.id.vn",

    "type":"A"

}
```

No source code modification required.

---

# Enable TCP Check

config.json

```json
{

    "type":"tcp",

    "port":443,

    "enable":true

}
```

Disable Ping

```json
{

    "type":"ping",

    "enable":false

}
```

---

# Enable HTTPS Check

```json
{

    "type":"https",

    "url":"/",

    "enable":true

}
```

---

# Retry

```
retry = 3
```

Result

```
FAIL

↓

FAIL

↓

PASS
```

WAN is still considered ONLINE.

---

# Fail Threshold

```
fail_threshold = 2
```

Result

```
Cycle 1

FAIL

↓

Cycle 2

FAIL

↓

Failover
```

---

# Restore

```
restore_primary=true
```

Primary WAN

↓

Online

↓

Restore DNS

Automatically

---

# Logging

GitHub Actions

```
[INFO]

Checking VNPT

Checking VIETTEL

Updating review360.id.vn

Done
```

---

# Exit Code

```
0

Success
```

```
1

Failed
```

---

# Supported Record Types

- A

Future Version

- AAAA
- CNAME
- TXT
- MX

---

# Supported Platforms

- Windows
- Linux
- Ubuntu
- Debian
- macOS

---

# Supported Python

- Python 3.11
- Python 3.12
- Python 3.13

- ---

# Troubleshooting

## Missing CF_TOKEN

```
Missing GitHub Secret : CF_TOKEN
```

Solution

Create GitHub Secret

```
CF_TOKEN
```

---

## Missing CF_ZONE_ID

```
Missing GitHub Secret : CF_ZONE_ID
```

Solution

Create GitHub Secret

```
CF_ZONE_ID
```

---

## DNS Record Not Found

```
DNS Record Not Found
```

Check

- Domain Name
- Record Type
- Zone ID

---

## Cloudflare Authentication Failed

Check

- API Token
- Zone Permission
- Token Permission

Required Permission

```
Zone

↓

DNS

↓

Edit
```

---

## Ping Failed

Possible Cause

- Firewall
- ICMP Disabled
- WAN Offline

Solution

Enable TCP Check

```json
{
    "type":"tcp",
    "port":443,
    "enable":true
}
```

---

## TCP Failed

Possible Cause

- Port Closed
- Firewall
- ISP

Solution

Try

```
80

443

22
```

---

## HTTPS Failed

Possible Cause

- SSL Certificate
- HTTPS Disabled

Solution

Enable

```
HTTP

or

TCP
```

---

# FAQ

### Does this require a VPS?

No.

---

### Does this require a server?

No.

---

### Does this require Cron?

No.

---

### Does this require InControl2?

No.

---

### Does this require Peplink API?

No.

---

### Does this require Record ID?

No.

---

### Does this require Database?

No.

---

### Does this require Docker?

No.

---

### Can I add unlimited domains?

Yes.

Only edit

```
config/domains.json
```

---

### Can I add more WAN?

Yes.

Only edit

```
config/config.json
```

---

### Can I change detection method?

Yes.

Supported

- Ping
- TCP
- HTTPS

---

### Can I disable Ping?

Yes.

```json
{
    "type":"ping",
    "enable":false
}
```

---

# Roadmap

## Version 1.1

- Telegram Notification
- Email Notification
- Discord Notification
- Slack Notification
- Dry Run Mode
- Test Mode

---

## Version 1.2

- Multi Zone
- IPv6
- Cloudflare Cache
- AAAA Record
- CNAME Record

---

## Version 2.0

- Web Dashboard
- REST API
- Docker Image
- Multi Provider DNS
- AWS Route53
- Azure DNS
- Google Cloud DNS

---

# Changelog

## Version 1.0

Initial Release

Modules

- Config
- Logger
- Checker
- Cloudflare
- Main
- GitHub Actions

---

# License

MIT

---

# Author

DNS Failover

GitHub Actions + Cloudflare

---

# Release

Version

```
1.0
```

Status

```
Production Ready
```

---

# End

Thank you for using DNS Failover.
