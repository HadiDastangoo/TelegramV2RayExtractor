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

Channels utilized in this Project:

| Channels | Channels | Channels | Channels |
| -------- | -------- | -------- | -------- |
| [V2rayNGn](https://t.me/V2rayNGn) | [free4allVPN](https://t.me/free4allVPN) | [PrivateVPNs](https://t.me/PrivateVPNs) | [V2rayng_Fast](https://t.me/V2rayng_Fast) |
| [DirectVPN](https://t.me/DirectVPN) | [ProxyFn](https://t.me/ProxyFn) | [v2ray_outlineir](https://t.me/v2ray_outlineir) | [v2ray_swhil](https://t.me/v2ray_swhil) |
| [NetAccount](https://t.me/NetAccount) | [oneclickvpnkeys](https://t.me/oneclickvpnkeys) | [daorzadannet](https://t.me/daorzadannet) | [LoRd_uL4mo](https://t.me/LoRd_uL4mo) |
| [Outline_Vpn](https://t.me/Outline_Vpn) | [vpn_xw](https://t.me/vpn_xw) | [prrofile_purple](https://t.me/prrofile_purple) | [proxyymeliii](https://t.me/proxyymeliii) |
| [ShadowSocks_s](https://t.me/ShadowSocks_s) | [azadi_az_inja_migzare](https://t.me/azadi_az_inja_migzare) | [WomanLifeFreedomVPN](https://t.me/WomanLifeFreedomVPN) | [MsV2ray](https://t.me/MsV2ray) |
| [internet4iran](https://t.me/internet4iran) | [LegenderY_Servers](https://t.me/LegenderY_Servers) | [vpnfail_v2ray](https://t.me/vpnfail_v2ray) | [free_v2rayy](https://t.me/free_v2rayy) |
| [UnlimitedDev](https://t.me/UnlimitedDev) | [vmessorg](https://t.me/vmessorg) | [v2rayNG_Matsuri](https://t.me/v2rayNG_Matsuri) | [v2ray1_ng](https://t.me/v2ray1_ng) |
| [v2rayngvpn](https://t.me/v2rayngvpn) | [vpn_ioss](https://t.me/vpn_ioss) | [v2freevpn](https://t.me/v2freevpn) | [vless_vmess](https://t.me/vless_vmess) |
| [customv2ray](https://t.me/customv2ray) | [FalconPolV2rayNG](https://t.me/FalconPolV2rayNG) | [Jeyksatan](https://t.me/Jeyksatan) | [MTConfig](https://t.me/MTConfig) |
| [hassan_saboorii](https://t.me/hassan_saboorii) | [v2rayvmess](https://t.me/v2rayvmess) | [v2rayNGNeT](https://t.me/v2rayNGNeT) | [lagvpn13](https://t.me/lagvpn13) |
| [server01012](https://t.me/server01012) | [ShadowProxy66](https://t.me/ShadowProxy66) | [ipV2Ray](https://t.me/ipV2Ray) | [v2rayNG_VPNN](https://t.me/v2rayNG_VPNN) |
| [kiava](https://t.me/kiava) | [Helix_Servers](https://t.me/Helix_Servers) | [PAINB0Y](https://t.me/PAINB0Y) | [vmess_vless_v2rayng](https://t.me/vmess_vless_v2rayng) |
| [VpnProSec](https://t.me/VpnProSec) | [VlessConfig](https://t.me/VlessConfig) | [NIM_VPN_ir](https://t.me/NIM_VPN_ir) | [FreeIranT](https://t.me/FreeIranT) |
| [hashmakvpn](https://t.me/hashmakvpn) | [X_Her0](https://t.me/X_Her0) | [Napsternetvirani](https://t.me/Napsternetvirani) | [Cov2ray](https://t.me/Cov2ray) |
| [iran_ray](https://t.me/iran_ray) | [INIT1984](https://t.me/INIT1984) | [EXOGAMERS](https://t.me/EXOGAMERS) | [V2RayTz](https://t.me/V2RayTz) |
| [ServerNett](https://t.me/ServerNett) | [Pinkpotatocloud](https://t.me/Pinkpotatocloud) | [CloudCityy](https://t.me/CloudCityy) | [VmessProtocol](https://t.me/VmessProtocol) |
| [DarkVPNpro](https://t.me/DarkVPNpro) | [Qv2raychannel](https://t.me/Qv2raychannel) | [xrayzxn](https://t.me/xrayzxn) | [MehradLearn](https://t.me/MehradLearn) |
| [shopingv2ray](https://t.me/shopingv2ray) | [xrayproxy](https://t.me/xrayproxy) | [Proxy_PJ](https://t.me/Proxy_PJ) | [SafeNet_Server](https://t.me/SafeNet_Server) |
| [ovpn2](https://t.me/ovpn2) | [OutlineVpnOfficial](https://t.me/OutlineVpnOfficial) | [proxy_kafee](https://t.me/proxy_kafee) | [TheHotVPN](https://t.me/TheHotVPN) |
| [lrnbymaa](https://t.me/lrnbymaa) | [v2ray_vpn_ir](https://t.me/v2ray_vpn_ir) | [ConfigsHUB](https://t.me/ConfigsHUB) | [freeconfigv2](https://t.me/freeconfigv2) |
| [vpn_tehran](https://t.me/vpn_tehran) | [v2_team](https://t.me/v2_team) | [AlienVPN402](https://t.me/AlienVPN402) | [V2rayngninja](https://t.me/V2rayngninja) |
| [iSegaro](https://t.me/iSegaro) | [bright_vpn](https://t.me/bright_vpn) | [talentvpn](https://t.me/talentvpn) | [proxystore11](https://t.me/proxystore11) |
| [yaney_01](https://t.me/yaney_01) | [rayvps](https://t.me/rayvps) | [free1_vpn](https://t.me/free1_vpn) | [Parsashonam](https://t.me/Parsashonam) |

## License
This project is licensed under the MIT License - see the LICENSE file for details.
