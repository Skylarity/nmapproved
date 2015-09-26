<?php

require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/mysql/encrypted-config.php");

/**
 * This file reads in a csv file, and puts it in the database.
 * This is purely for testing.
 *
 * @author Skyler Rexroad
 */

$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

$row = 1;
if(($handle = fopen(dirname(dirname(__DIR__)) . "/data/vendor-list.csv", "r")) !== FALSE) {
	fgetcsv($handle, 0, ",");
	while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		echo "<p> $num fields in line $row: <br /></p>" . PHP_EOL;
		for($c = 0; $c < $num; $c++) {
			var_dump($data[$c]);
		}

		$name = $data[0];
		$location = $data[2] . " " . $data[3] . " " . $data[4] . " " . $data[5] . " " . $data[6];
		$phone = "";
		if(strlen($data[7]) > 0) {
			$phone = $data[7];
		} else {
			$phone = $data[8];
		}
		$website = "";
		$email = $data[9];

		$business = new Business(null, $name, $location, $phone, $website, $email, 1);
		$business->insert($pdo);
		$subcategory = new Subcategory(null, 1, $data[10]);
		$subcategory->insert($pdo);

		$row++;
	}
	fclose($handle);
}