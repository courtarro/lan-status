<?php

$hostFile = "lan.json";
$hosts = json_decode(file_get_contents($hostFile));

?>
<html>
<head>
<title>LAN Status</title>
<link rel="stylesheet" href="lan.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script>
<script src="/jquery.metadata.js"></script>
<script src="/jquery.tablesorter.min.js"></script>
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
			alert("Wake-on-LAN magic packet sent");
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
	echo "\t<td>" . $mac . "</td>\n";
	echo "\t<td class=\"pinghost\">" . $host . "</td>\n";
	echo "\t<td class=\"pingresult\">Unknown</td>\n";
	echo "\t<td>\n";
	echo "\t\t<a href=\"ping.php?hostname={$host}\" class=\"pinglink\">Ping</a>\n";
	echo "\t\t<a href=\"wol.php?nickname={$nickname}\" class=\"wakelink\">Wake</a>\n";
	echo "\t</td>\n";
	echo "</tr>\n";
}

?>
</tbody>
</table>
</body>
</html>