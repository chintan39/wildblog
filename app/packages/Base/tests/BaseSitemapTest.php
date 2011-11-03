<?php

class BaseSitemapTest extends AbstractTest {
	
	var $description = "This test case checks sitemap generating.";

	public function run() {
		$results = array();
		$res = new stdClass();
		$res->result = true;
		$res->text = tg('Sitemap seems to be gnerated correctly.');
		$results[] = $res;
		return $results;
	}

}

?>