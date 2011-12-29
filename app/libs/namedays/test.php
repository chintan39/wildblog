<?php

require_once('NameDays.class.php');
date_default_timezone_set('Europe/Prague');
$res = true;

try {
	NameDays::getName();
	echo "Init #1 test FAIL.\n";
	$res = false;
} catch (Exception $e) {
	echo "Init days #1 test OK.\n";
}

NameDays::init('cs');

if (!(strcmp('Jan', NameDays::getName(6, 24)))) {
	echo "Name #1 test OK.\n";
} else {
	echo "Name #1 test FAIL.\n";
	$res = false;
}

if (strcmp('Jan', NameDays::getName(7, 24))) {
	echo "Name #2 test OK.\n";
} else {
	echo "Name #2 test FAIL.\n";
	$res = false;
}

if (!strcmp('Svátek práce', NameDays::getBankHoliday(5, 1))) {
	echo "Bank holiday #1 test OK.\n";
} else {
	echo "Bank holiday #1 test FAIL.\n";
	$res = false;
}

if ('' === NameDays::getBankHoliday(6, 24)) {
	echo "Bank holiday #2 test OK.\n";
} else {
	echo "Bank holiday #2 test FAIL.\n";
	$res = false;
}
	
if (NameDays::getToleranceFlowerDays(-5) === array()) {
	echo "Flower days #1 test OK.\n";
} else {
	echo "Flower days #1 test FAIL.\n";
	$res = false;
}

if (array('Mezinárodní den žen') === NameDays::getToleranceFlowerDays(0, null, 3, 8)) {
	echo "Flower days #2 test OK.\n";
} else {
	echo "Flower days #2 test FAIL.\n";
	$res = false;
}

echo "=================================\n";
echo "Test result: " . ($res ? 'OK':'FAIL') . "\n";

?>
