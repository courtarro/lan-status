LAN Status
==========

Simple set of PHP scripts to show LAN status and perform wake-on-LAN (WOL) requests.

Prerequisites:
- modern version of PHP
- Linux (for current ping command)

To use, copy `lan.json.example` to `lan.json` and edit its contents. Each host must have a `MAC` address in order to support wake-on-lan, and a `Host` in order to support ping.

Optionally password-protect the script using external access controls (htaccess, etc.)
