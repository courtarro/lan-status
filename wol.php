<?php

$hostFile = "lan.json";
$hosts = json_decode(file_get_contents($hostFile));
$wolHost = "255.255.255.255";
$wolPort = 9;

$g_nickname = @$_GET['nickname'];

if (!$g_nickname) {
	echo "Error: missing host nickname";
	exit;
}

if (!property_exists($hosts, $g_nickname)) {
	echo "Error: invalid host nickname";
	exit;
}

$host = $hosts->$g_nickname;
$mac = $host->MAC;

// Generate the packet
// WOL packets contain 0xFF repeated 6 times, followed by 16 repeated copies of the MAC address of the desired host
$packet = "";
for ($i = 0; $i < 6; $i++)
	$packet .= chr(0xFF);
for ($j = 0; $j < 16; $j++) {
	for ($k = 0; $k < 6; $k++) {
		$str = substr($mac, $k * 3, 2);
		$dec = hexdec($str);
		$packet .= chr($dec);
	}
}

// Set up the UDP socket and send the WOL packet
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);			// SO_BROADCAST allows us to send to 255.255.255.255
socket_sendto($socket, $packet, strlen($packet), 0, $wolHost, $wolPort);
socket_close($socket);

echo "Packet sent";
