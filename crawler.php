<?php

function findHoChiMinh($elements) {
    foreach ($elements as $city) {
        if (strtolower($city['name']) == 'hồ chí minh') {
            return $city;
        }
    }

    return null;
}

function findSjc1l10l($elements) {
    foreach ($elements as $item) {
        if (strtolower($item['type']) == 'vàng sjc 1l - 10l') {
            return $item;
        }
    }

    return null;
}

$queryString = http_build_query(['t' => time()]);
$url = "http://www.sjc.com.vn/xml/tygiavang.xml?{$queryString}";

$options = [
    CURLOPT_RETURNTRANSFER => true,   // return web page
    CURLOPT_HEADER         => false,  // don't return headers
    CURLOPT_FOLLOWLOCATION => true,   // follow redirects
    CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
    CURLOPT_ENCODING       => "",     // handle compressed
    CURLOPT_USERAGENT      => "test", // name of client
    CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
    CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
    CURLOPT_TIMEOUT        => 120,    // time-out on response
];

$ch = curl_init($url);
curl_setopt_array($ch, $options);

$xml = simplexml_load_string(curl_exec($ch));

$updatedTime = DateTime::createFromFormat('h:i:s a d/m/Y', $xml->ratelist['updated']);
$hoChiMinh = findHoChiMinh($xml->ratelist->children());
$price = findSjc1l10l($hoChiMinh);
echo "Updated time: {$updatedTime->format('d/m/Y h:i:s A')}\n";
echo "Price:\nBUY - {$price['buy']}\nSELL - {$price['sell']}";

curl_close($ch);