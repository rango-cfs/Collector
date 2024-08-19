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


$allVlessConfigs = $allVMessConfigs = $allSSConfigs = $allTrojanConfigs = $allH2Configs = $alltuicConfigs = [];

$telegramChannelURLs = [
    "https://t.me/s/ip_cf",
    "https://t.me/s/DailyV2RY",
    "https://t.me/s/nufilter",
    "https://t.me/s/v2ray_configs_pool",
    "https://t.me/s/Hinminer",
    "https://t.me/s/FreakConfig",
    "https://t.me/s/Tunder_vpn",
    "https://t.me/s/EliV2ray",
    "https://t.me/s/proxystore11",
    "https://t.me/s/v2rayng_org",
    "https://t.me/s/v2rayngvpn",
    "https://t.me/s/v2rayNG_VPNN",
	"https://t.me/s/FreeVlessVpn",
	"https://t.me/s/vmess_vless_v2rayng",
	"https://t.me/s/PrivateVPNs",
	"https://t.me/s/freeland8",
	"https://t.me/s/Outline_Vpn",
	"https://t.me/s/V2rayNG3",
	"https://t.me/s/ShadowsocksM",
	"https://t.me/s/VmessProtocol",
	"https://t.me/s/V2RAY_VMESS_free",
	"https://t.me/s/FreeV2rays",
	"https://t.me/s/DigiV2ray",
	"https://t.me/s/v2rayNG_VPN",
	"https://t.me/s/freev2rayssr",
	"https://t.me/s/vpnmasi",
	"https://t.me/s/VPNCUSTOMIZE",
	"https://t.me/s/ultrasurf_12",
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


$fileContents = [
    'IP_CF_(vless)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allVlessConfigs) . PHP_EOL . $signature,
    'IP_CF_(vmess)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allVMessConfigs) . PHP_EOL . $signature,
    'IP_CF_(ss)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allSSConfigs) . PHP_EOL . $signature,
    'IP_CF_(trojan)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allTrojanConfigs) . PHP_EOL . $signature,
    'IP_CF_(hysteria)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $allH2Configs) . PHP_EOL . $signature,
    'IP_CF_(tuic)' => $trojanConfig . PHP_EOL . implode(PHP_EOL, $alltuicConfigs) . PHP_EOL . $signature,
    'IP_CF_(mix)' => $trojanConfig . PHP_EOL .
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
