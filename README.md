# Telegram V2ray Extractor

This is a PHP script that extracts V2Ray configs from various Telegram public channels and saves them to different files (commonly called *Subscriptions*) based on their protocol type (VMess, VLESS, Trojan).

**Note: This project is intended for educational/persoanl purposes only. Any other use, including commercial or non-educational use, is not accepted!**

## Usage
You can use this project in a self-hosted server or shared hosting with minimum requirements, *out of Iran* that supports PHP (Access to Telegram is not available on many hosting providers within Iran) and has access to `Cron Jobs` to define scheduled extraction.

**Notes:**
* Default parameters are defined in `config.php`.
* Channels (with supported protocols) are defined as an array in `channels\channels.php`.
* Croned job log file (`log.txt` as default) logs start/end execution time, total extracted configs (with & without duplicates) and the type of interface between web server and PHP (such as: `cli`, `apache`, `litespeed` and etc.) for test cases. You can enable/disable it from `config.php`.
* For scheduled execution, after extract project in a custom directory in your host, define custom scheduled task (as: once per hour, or every 2 or 3 hours) in your `Cron Jobs` from your hosting panel as:
```shell
php -q /path/to/collector.php
```
* It is recommended to change the `collector.php` file to a random name.

## NODE Sources
This project currently utilizes Channels as the source of v2ray nodes.

Channels utilized in this Project (alphabetically):

| # | Channel | # | Channel | # | Channel | # | Channel |
|---|---|---|---|---|---|---|---|
| 1 | [ARv2ray](https://t.me/ARv2ray) | 2 | [Awlix_ir](https://t.me/Awlix_ir) | 3 | [azadi_az_inja_migzare](https://t.me/azadi_az_inja_migzare) | 4 | [BestV2rang](https://t.me/BestV2rang)  |
| 5 | [bright_vpn](https://t.me/bright_vpn) | 6 | [Capital_NET](https://t.me/Capital_NET) | 7 | [CloudCityy](https://t.me/CloudCityy) | 8 | [ConfigsHUB](https://t.me/ConfigsHUB)  |
| 9 | [Cov2ray](https://t.me/Cov2ray) | 10 | [customv2ray](https://t.me/customv2ray) | 11 | [CUSTOMVPNSERVER](https://t.me/CUSTOMVPNSERVER) | 12 | [DigiV2ray](https://t.me/DigiV2ray)  |
| 13 | [DirectVPN](https://t.me/DirectVPN) | 14 | [FalconPolV2rayNG](https://t.me/FalconPolV2rayNG) | 15 | [flyv2ray](https://t.me/flyv2ray) | 16 | [fnet00](https://t.me/fnet00)  |
| 17 | [FreakConfig](https://t.me/FreakConfig) | 18 | [free1_vpn](https://t.me/free1_vpn) | 19 | [free4allVPN](https://t.me/free4allVPN) | 20 | [free_v2rayyy](https://t.me/free_v2rayyy)  |
| 21 | [frev2ray](https://t.me/frev2ray) | 22 | [God_CONFIG](https://t.me/God_CONFIG) | 23 | [hashmakvpn](https://t.me/hashmakvpn) | 24 | [Helix_Servers](https://t.me/Helix_Servers)  |
| 25 | [Hope_Net](https://t.me/Hope_Net) | 26 | [INIT1984](https://t.me/INIT1984) | 27 | [iP_CF](https://t.me/iP_CF) | 28 | [ipV2Ray](https://t.me/ipV2Ray)  |
| 29 | [IRN_VPN](https://t.me/IRN_VPN) | 30 | [iSegaro](https://t.me/iSegaro) | 31 | [L_AGVPN13](https://t.me/L_AGVPN13) | 32 | [lightning6](https://t.me/lightning6)  |
| 33 | [Lockey_vpn](https://t.me/Lockey_vpn) | 34 | [LoRd_uL4mo](https://t.me/LoRd_uL4mo) | 35 | [lrnbymaa](https://t.me/lrnbymaa) | 36 | [MehradLearn](https://t.me/MehradLearn)  |
| 37 | [melov2ray](https://t.me/melov2ray) | 38 | [MsV2ray](https://t.me/MsV2ray) | 39 | [MTConfig](https://t.me/MTConfig) | 40 | [NIM_VPN_ir](https://t.me/NIM_VPN_ir)  |
| 41 | [oneclickvpnkeys](https://t.me/oneclickvpnkeys) | 42 | [Outline_Vpn](https://t.me/Outline_Vpn) | 43 | [Outlinev2rayNG](https://t.me/Outlinev2rayNG) | 44 | [OutlineVpnOfficial](https://t.me/OutlineVpnOfficial)  |
| 45 | [PAINB0Y](https://t.me/PAINB0Y) | 46 | [Parsashonam](https://t.me/Parsashonam) | 47 | [polproxy](https://t.me/polproxy) | 48 | [PrivateVPNs](https://t.me/PrivateVPNs)  |
| 49 | [Proxy_PJ](https://t.me/Proxy_PJ) | 50 | [proxystore11](https://t.me/proxystore11) | 51 | [proxyymeliii](https://t.me/proxyymeliii) | 52 | [prrofile_purple](https://t.me/prrofile_purple)  |
| 53 | [rayvps](https://t.me/rayvps) | 54 | [reality_daily](https://t.me/reality_daily) | 55 | [Royalping_ir](https://t.me/Royalping_ir) | 56 | [rxv2ray](https://t.me/rxv2ray)  |
| 57 | [SafeNet_Server](https://t.me/SafeNet_Server) | 58 | [serveriran11](https://t.me/serveriran11) | 59 | [ServerNett](https://t.me/ServerNett) | 60 | [ShadowProxy66](https://t.me/ShadowProxy66)  |
| 61 | [ShadowSocks_s](https://t.me/ShadowSocks_s) | 62 | [shh_proxy](https://t.me/shh_proxy) | 63 | [shopingv2ray](https://t.me/shopingv2ray) | 64 | [UnlimitedDev](https://t.me/UnlimitedDev)  |
| 65 | [v2_team](https://t.me/v2_team) | 66 | [v2Line](https://t.me/v2Line) | 67 | [V2parsin](https://t.me/V2parsin) | 68 | [V2pedia](https://t.me/V2pedia)  |
| 69 | [v2ray1_ng](https://t.me/v2ray1_ng) | 70 | [v2ray_outlineir](https://t.me/v2ray_outlineir) | 71 | [v2ray_swhil](https://t.me/v2ray_swhil) | 72 | [v2ray_vpn_ir](https://t.me/v2ray_vpn_ir)  |
| 73 | [V2rayCollectorDonate](https://t.me/V2rayCollectorDonate) | 74 | [v2rayng_config_amin](https://t.me/v2rayng_config_amin) | 75 | [V2rayng_Fast](https://t.me/V2rayng_Fast) | 76 | [v2rayNG_Matsuri](https://t.me/v2rayNG_Matsuri)  |
| 77 | [v2rayng_vpnrog](https://t.me/v2rayng_vpnrog) | 78 | [V2rayNGmat](https://t.me/V2rayNGmat) | 79 | [V2rayNGn](https://t.me/V2rayNGn) | 80 | [V2rayngninja](https://t.me/V2rayngninja)  |
| 81 | [v2rayngvpn](https://t.me/v2rayngvpn) | 82 | [V2RayTz](https://t.me/V2RayTz) | 83 | [vless_vmess](https://t.me/vless_vmess) | 84 | [VlessConfig](https://t.me/VlessConfig)  |
| 85 | [vmess_vless_v2rayng](https://t.me/vmess_vless_v2rayng) | 86 | [vmessorg](https://t.me/vmessorg) | 87 | [VmessProtocol](https://t.me/VmessProtocol) | 88 | [VPNCLOP](https://t.me/VPNCLOP)  |
| 89 | [VPNCUSTOMIZE](https://t.me/VPNCUSTOMIZE) | 90 | [VpnFreeSec](https://t.me/VpnFreeSec) | 91 | [vpnmasi](https://t.me/vpnmasi) | 92 | [VpnProSec](https://t.me/VpnProSec)  |
| 93 | [WebShecan](https://t.me/WebShecan) | 94 | [XsV2ray](https://t.me/XsV2ray) | 95 | [yaney_01](https://t.me/yaney_01) | 96 | [zen_cloud](https://t.me/zen_cloud)  |

_Updated @ 2023-06-09_

## License
This project is licensed under the MIT License - see the LICENSE file for details.
