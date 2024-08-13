<?php
#include 'channels.php';
date_default_timezone_set('Asia/Tehran');

function fetchContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept-Language: en-US,fa;q=0.9',
        'Cookie: messagesDesktopMode=0;'
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception('Error fetching the content: ' . curl_error($ch));
    }
    curl_close($ch);
    return $response;
}

function extractConfigurations($content) {
    $vlessPattern = '/vless:\/\/[^<>\'"]+/';
    $vmessPattern = '/vmess:\/\/[^<>\'"]+/';
    $ssPattern = '/ss:\/\/[^<>\'"]+/';
    $trojanPattern = '/trojan:\/\/[^<>\'"]+/';
    $H2Pattern = '/hy2:\/\/[^<>\'"]+/';
    $tuicPattern = '/tuic:\/\/[^<>\'"]+/';

    return [
        implode(PHP_EOL, preg_match_all($vlessPattern, $content, $vlessMatches) ? $vlessMatches[0] : []),
        implode(PHP_EOL, preg_match_all($vmessPattern, $content, $vmessMatches) ? $vmessMatches[0] : []),
        implode(PHP_EOL, preg_match_all($ssPattern, $content, $ssMatches) ? $ssMatches[0] : []),
        implode(PHP_EOL, preg_match_all($trojanPattern, $content, $trojanMatches) ? $trojanMatches[0] : []),
        implode(PHP_EOL, preg_match_all($H2Pattern, $content, $H2Matches) ? $H2Matches[0] : []),
        implode(PHP_EOL, preg_match_all($tuicPattern, $content, $tuicMatches) ? $tuicMatches[0] : []),
    ];
}

function generateTrojanConfig() {
    $currentDateTime = date('D-d-M-Y H:i');
    return "trojan://bcacaab-baca-baca-dbac-accaabbcbacb@127.0.0.1:1080?security=tls&type=tcp#LATEST-UPDATE - {$currentDateTime}";
}

function Signature() {
    return "trojan://bcacaab-baca-baca-dbac-accaabbcbacb@127.0.0.1:8080?security=tls&type=tcp#Made by:github.com/Rango_CF";
}

function generateHiddifyTags($name) {
    $profileTitle = base64_encode("IP_CF ".$name);
    return "#profile-title: base64:{$profileTitle}\n#profile-update-interval: 1\n#subscription-userinfo: upload=5368709120; download=545097156608; total=955630223360; expire=1762677732\n#support-url: https://IP_CF\n#profile-web-page-url: https://IP_CF.t.me
";
}

$allVlessConfigs = $allVMessConfigs = $allSSConfigs = $allTrojanConfigs = $allH2Configs = $alltuicConfigs = [];

$telegramChannelURLs = [
    "https://t.me/s/networknim",
    "https://t.me/s/beiten",
    "https://t.me/s/ip_cf",
    "https://t.me/s/MsV2ray",
    "https://t.me/s/foxrayiran",
    "https://t.me/s/DailyV2RY",
    "https://t.me/s/yaney_01",
    "https://t.me/s/FreakConfig",
    "https://t.me/s/EliV2ray",
    "https://t.me/s/ServerNett",
    "https://t.me/s/proxystore11",
    "https://t.me/s/v2rayng_fa2",
    "https://t.me/s/v2rayng_org",
    "https://t.me/s/V2rayNGvpni",
    "https://t.me/s/custom_14",
    "https://t.me/s/v2rayNG_VPNN",
    "https://t.me/s/v2ray_outlineir",
    "https://t.me/s/v2_vmess",
	"https://t.me/s/FreeVlessVpn",
	"https://t.me/s/vmess_vless_v2rayng",
	"https://t.me/s/PrivateVPNs",
	"https://t.me/s/freeland8",
	"https://t.me/s/vmessiran",
	"https://t.me/s/Outline_Vpn",
	"https://t.me/s/vmessq",
	"https://t.me/s/WeePeeN",
	"https://t.me/s/V2rayNG3",
	"https://t.me/s/ShadowsocksM",
	"https://t.me/s/shadowsocksshop",
	"https://t.me/s/v2rayan",
	"https://t.me/s/ShadowSocks_s",
	"https://t.me/s/VmessProtocol",
	"https://t.me/s/napsternetv_config",
	"https://t.me/s/Easy_Free_VPN",
	"https://t.me/s/V2Ray_FreedomIran",
	"https://t.me/s/V2RAY_VMESS_free",
	"https://t.me/s/v2ray_for_free",
	"https://t.me/s/V2rayN_Free",
	"https://t.me/s/free4allVPN",
	"https://t.me/s/vpn_ocean",
	"https://t.me/s/configV2rayForFree",
	"https://t.me/s/FreeV2rays",
	"https://t.me/s/DigiV2ray",
	"https://t.me/s/v2rayNG_VPN",
	"https://t.me/s/freev2rayssr",
	"https://t.me/s/v2rayn_server",
	"https://t.me/s/Shadowlinkserverr",
	"https://t.me/s/iranvpnet",
	"https://t.me/s/vmess_iran",
	"https://t.me/s/mahsaamoon1",
	"https://t.me/s/V2RAY_NEW",
	"https://t.me/s/v2RayChannel",
	"https://t.me/s/configV2rayNG",
	"https://t.me/s/config_v2ray",
	"https://t.me/s/vpn_proxy_custom",
	"https://t.me/s/vpnmasi",
	"https://t.me/s/v2ray_custom",
	"https://t.me/s/VPNCUSTOMIZE",
	"https://t.me/s/HTTPCustomLand",
	"https://t.me/s/vpn_proxy_custom",
	"https://t.me/s/ViPVpn_v2ray",
	"https://t.me/s/FreeNet1500",
	"https://t.me/s/v2ray_ar",
	"https://t.me/s/beta_v2ray",
	"https://t.me/s/vip_vpn_2022",
	"https://t.me/s/FOX_VPN66",
	"https://t.me/s/VorTexIRN",
	"https://t.me/s/YtTe3la",
	"https://t.me/s/V2RayOxygen",
	"https://t.me/s/Network_442",
	"https://t.me/s/VPN_443",
	"https://t.me/s/v2rayng_v",
	"https://t.me/s/ultrasurf_12",
	"https://t.me/s/iSeqaro",
	"https://t.me/s/frev2rayng",
	"https://t.me/s/frev2ray",
	"https://t.me/s/FreakConfig",
	"https://t.me/s/Awlix_ir",
	"https://t.me/s/v2rayngvpn",
	"https://t.me/s/God_CONFIG",
	"https://t.me/s/Configforvpn01",
	"https://t.me/s/inikotesla",
	"https://t.me/s/forwardv2ray",
    "https://t.me/s/TUICity",
    "https://t.me/s/ParsRoute",
];

foreach ($telegramChannelURLs as $channelURL) {
    $channelContent = fetchContent($channelURL);

    if ($channelContent !== false) {
        [
            $allVlessConfigs[],
            $allVMessConfigs[],
            $allSSConfigs[],
            $allTrojanConfigs[],
            $allH2Configs[],
            $alltuicConfigs[],
        ] = extractConfigurations($channelContent);
    }
}

$trojanConfig = generateTrojanConfig();
$signature = Signature();
$generateHiddifyTags = generateHiddifyTags();

$fileContents = [
    'vless' => $generateHiddifyTags('vless') . $trojanConfig . PHP_EOL . implode(PHP_EOL, $allVlessConfigs) . PHP_EOL . $signature,
    'vmess' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allVMessConfigs) . PHP_EOL . $signature,
    'ss' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allSSConfigs) . PHP_EOL . $signature,
    'trojan' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allTrojanConfigs) . PHP_EOL . $signature,
    'hysteria' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allH2Configs) . PHP_EOL . $signature,
    'tuic' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $alltuicConfigs) . PHP_EOL . $signature,
    'mix' => $trojanConfig . PHP_EOL .
        implode(PHP_EOL, $allVlessConfigs) . PHP_EOL .
        implode(PHP_EOL, $allVMessConfigs) . PHP_EOL .
        implode(PHP_EOL, $allSSConfigs) . PHP_EOL .
        implode(PHP_EOL, $allTrojanConfigs) . PHP_EOL .
        implode(PHP_EOL, $allH2Configs) . PHP_EOL .
        implode(PHP_EOL, $alltuicConfigs) . PHP_EOL .
        $signature,
];

foreach ($fileContents as $key => $content) {
    file_put_contents("sub/{$key}", $content);
    file_put_contents("sub/{$key}base64", base64_encode($content));
}
?>
