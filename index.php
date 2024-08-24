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

function gregorianToJalali($gy, $gm, $gd) {
    $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
    if ($gy > 1600) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return array($jy, $jm, $jd);
}

function getTehranTime() {
    // Set the timezone to Tehran
    date_default_timezone_set('Asia/Tehran');

    // Get the current date and time in Tehran
    $date = new DateTime();

    // Get the day of the week in English
    $dayOfWeek = $date->format('D');

    // Get the day of the month
    $day = $date->format('d');

    // Get the month and year
    $month = (int)$date->format('m');
    $year = (int)$date->format('Y');

    // Convert Gregorian date to Jalali date
    list($jy, $jm, $jd) = gregorianToJalali($year, $month, $day);

    // Map Persian month names to their short forms
    $monthNames = [
        1 => 'FAR',
        2 => 'ORD',
        3 => 'KHORDAD',
        4 => 'TIR',
        5 => 'MORDAD',
        6 => 'SHAHRIVAR',
        7 => 'MEHR',
        8 => 'ABAN',
        9 => 'AZAR',
        10 => 'DEY',
        11 => 'BAHMAN',
        12 => 'ESFAND'
    ];
    $shortMonth = $monthNames[$jm];

    // Get the time in 24-hour format
    $time = $date->format('H:i');

    // Construct the final formatted string
    $formattedString = sprintf('%s-%02d-%s-%04d 🕑 %s', $dayOfWeek, $jd, $shortMonth, $jy, $time);

    return $formattedString;
}

function generateTrojanConfig() {
    $currentDateTime = getTehranTime();
    return "trojan://bcacaab-baca-baca-dbac-accaabbcbacb@127.0.0.1:1080?security=tls&type=tcp#LATEST-UPDATE - {$currentDateTime}";
}

function Signature() {
    return "trojan://bcacaab-baca-baca-dbac-accaabbcbacb@127.0.0.1:8080?security=tls&type=tcp#Made by:github.com/Rango_CF";
}


$allVlessConfigs = $allVMessConfigs = $allSSConfigs = $allTrojanConfigs = $allH2Configs = $alltuicConfigs = [];

$telegramChannelURLs = [
    "https://t.me/s/ip_cf",
    "https://t.me/s/Hinminer",
    "https://t.me/s/Freeland8",
    "https://t.me/s/ArV2ray",
    "https://t.me/s/nufilter",
    "https://t.me/s/Tunder_vpn",
    "https://t.me/s/V2pedia",
    "https://t.me/s/lightni",

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

function changeNameInVmessLink($vmessLink) {
    $jsonPart = base64_decode(substr($vmessLink, strpos($vmessLink, '://') + 3));
    $data = json_decode($jsonPart, true);

    if ($data !== null && isset($data['ps'])) {
        $newName = implode(' | ', array_slice(explode(' | ', $data['ps']), 1, 2)) . '⚜️Telegram:@IP_CF';
        $data['ps'] = $newName;
        $newJsonPart = base64_encode(json_encode($data));

        return substr_replace($vmessLink, $newJsonPart, strpos($vmessLink, '://') + 3);
    }

    return $vmessLink;
}




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
