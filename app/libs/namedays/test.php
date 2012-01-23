<?php
/*
Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
