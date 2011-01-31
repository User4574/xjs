<?php

$ls = scandir("json");

$db = sqlite_open("xkcd.sqlite") or die ("Failed to open DB");

$latest = 0;

function insert($obj) {
	global $db, $latest;
	foreach ($obj as $key => $value) $obj[$key] = sqlite_escape_string($value);
	sqlite_query($db,
		"insert into xkcd values (\"" .
		$obj["img"] .
		"\", \"" .
		$obj["title"] .
		"\", \"" .
		$obj["month"] .
		"\", \"" .
		$obj["num"] .
		"\", \"" .
		$obj["link"] .
		"\", \"" .
		$obj["year"] .
		"\", \"" .
		$obj["news"] .
		"\", \"" .
		$obj["safe_title"] .
		"\", \"" .
		$obj["transcript"] .
		"\", \"" .
		$obj["alt"] .
		"\", \"" .
		$obj["day"] .
		"\")"
	);
	if ($obj["num"] > $latest) {
		sqlite_query($db,
			"update latest set latest=". $obj["num"]
		);
	}
}

foreach ($ls as $file) {
	if ($file == "." || $file == "..") continue;
	echo "$file ";
	$json = file_get_contents("json/" . $file);
	$obj = json_decode($json, true);
	insert($obj);
}

sqlite_close($db);

?>
