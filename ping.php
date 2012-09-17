<?php

// This script is Linux-specific

$g_hostname = @$_GET['hostname'];

if (!$g_hostname) {
	echo "Error: missing hostname";
	exit;
}

// If it's a bad hostname, it might also be bad input to a command line. This is an important security check
if (!preg_match("/^(?=.{1,255}$)[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?(?:\.[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?)*\.?$/i", $g_hostname)) {
	echo "Error: bad hostname";
	exit;
}

exec("ping -n -c1 -q {$g_hostname}", $output, $error);

if ($error) {
	echo "Failure";
} else {
	echo "Success";
}
