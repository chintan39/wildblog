<?php

class BaseFilesTest extends AbstractTest {
	
	var $description = "This test case checks file permissions.";

	public function run() {
		$result = array();
		$res = new stdClass();
		$res->result = false;
		$res->text = tg('File') . ' "' . "some" . ' ' . tg('has wrong attributes') . '. ' . 
			tg('Current attributes') . ': ' . '0644' . ', ' .
			tg('Correct attributes') . ': ' . '0755' . '.';
		$result[] = $res;

		$res = new stdClass();
		$res->result = true;
		$res->text = tg('File') . ' "' . "some" . ' ' . tg('has wrong attributes') . '. ' . 
			tg('Current attributes') . ': ' . '0644' . ', ' .
			tg('Correct attributes') . ': ' . '0755' . '.';
		$result[] = $res;

		$res = new stdClass();
		$res->result = false;
		$res->text = tg('File') . ' "' . "some" . ' ' . tg('has wrong attributes') . '. ' . 
			tg('Current attributes') . ': ' . '0644' . ', ' .
			tg('Correct attributes') . ': ' . '0755' . '.';
		$result[] = $res;

		return $result;
	}

}

?>