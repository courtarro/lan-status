<?php

// Code by Ethan Trewhitt
//
// favicon.ico courtesy http://www.visualpharm.com/

$hostFile = "lan.json";
$hosts = json_decode(file_get_contents($hostFile));

?>
<html>
<head>
<title>LAN Status</title>
<link rel="stylesheet" href="lan.css" />
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script>
<script src="jquery.metadata.js"></script>
<script src="jquery.tablesorter.min.js"></script>
<script>
function ping(href, id) {
	// Ping a specific host using the full ping.php href handed to us
	$("#" + id).children(".pingresult").text("Pinging...");
	$.ajax({url: href, dataType: "text"}).done(function(result) {
		$("#" + id).children(".pingresult").text(result);
		$(".tablesorter").trigger("update");
	} );
}

$(document).ready(function() {
	// Init tablesorter
	$(".tablesorter").tablesorter();

	// Set up JS link events
	$(".wakelink").click(function(event) {
		var href = $(this).attr("href");
		var id = $(this).closest("tr").attr("id");
		$.ajax({url: href, dataType: "text"}).done(function(result) {
			$("<div>" + result + "</div>").appendTo("#status").delay(2000).fadeOut().queue(function () {
				$(this).remove();
			} );
		} );
		event.preventDefault();
	} );
	$(".pinglink").click(function(event) {
		var href = $(this).attr("href");
		var id = $(this).closest("tr").attr("id");
		ping(href, id);
		event.preventDefault();
	} );

	// Automatically ping all the hosts
	$(".pinghost").each(function(event) {
		var host = $(this).text();

		if (host == "(none)")
			return;

		var href = "ping.php?hostname=" + host;
		var id = $(this).closest("tr").attr("id");
		ping(href, id);
	} );
} );
</script>
</head>
<body>
<h1>LAN Status</h1>
<table id="hostlist" class="tablesorter {sortlist: [[0,0]]}">
<thead>
<tr>
	<th>Nickname</th>
	<th>MAC Address</th>
	<th>Host</th>
	<th>Ping Status</th>
	<th class="{sorter: false}">Actions</th>
</tr>
</thead>
<tbody>
<?php

foreach ($hosts as $key => $hostData) {
	$nickname = $key;
	$mac = @$hostData->MAC;
	$host = @$hostData->Host;

	echo "<tr id=\"{$nickname}\">\n";
	echo "\t<td>" . $nickname . "</td>\n";
	echo "\t<td class=\"mac\">" . ($mac ? $mac : "(none)") . "</td>\n";
	echo "\t<td class=\"pinghost\">" . ($host ? $host : "(none)") . "</td>\n";
	echo "\t<td class=\"pingresult\">N/A</td>\n";
	echo "\t<td>\n";
	if ($host) {
		echo "\t\t<a href=\"ping.php?hostname={$host}\" class=\"pinglink\">Ping</a>\n";
	} else {
		echo "\t\t<span class=\"disabled\" title=\"Hostname required to support ping\">Ping</span>\n";
	}
	if ($mac) {
		echo "\t\t<a href=\"wol.php?nickname={$nickname}\" class=\"wakelink\">Wake</a>\n";
	} else {
		echo "\t\t<span class=\"disabled\" title=\"MAC address required to support wake-on-LAN\">Wake</span>\n";
	}
	echo "\t</td>\n";
	echo "</tr>\n";
}

?>
</tbody>
</table>

<div id="status"></div>

</body>
</html>
