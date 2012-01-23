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


class BaseDatabaseController extends AbstractDefaultController {
	
	public $order = 9;				// order of the controller (0-10)

	/**
	 * Action Listing - table lists
	 * @param mixed $args arguments to this action
	 */
	public function actionListing($args) {
		$items = new ItemCollection($this->getMainListIdentifier(), $this);
		$items->setLimit(0);
		$items->loadCollection();
		$items->addButtons(array(ItemCollection::BUTTON_EDIT => 'actionEdit'));
		$this->assign($items->getIdentifier(), $items);
	}
	

	/**
	 * Action edit - this will show the construct SQL for the model
	 * @param mixed $args name of the module
	 */ 
	public function actionEdit($item) {
		$modelName = $item->id;
		$text = BaseDatabaseModel::getConstructTable($modelName);
		$this->assign("title", "Database Construct SQL for model $modelName");
		$this->assign("text", '<pre>' . $text . '</pre>');
	}
	
	
	/**
	 * Action Construct - shows the construct for the all database
	 * @param mixed $args Arguments to the action
	 */
	public function actionConstruct($item) {
		
		$constructSQL = BaseDatabaseModel::getConstructSQL();
		$this->assign("title", "Database Construct SQL for database");
		$this->assign("text", $constructSQL);
	}
	

	/**
	 * Action Init - display of the construct of the database
	 */
	public function actionDbInit() {
		$constructSQL = BaseDatabaseModel::getConstructDbInit();
		$this->assign("title", "Database Construct SQL for database");
		$this->assign("text", $constructSQL);
	}

	
	/**
	 * Action Install DB - display of the construct of the database
	 */
	public function actionDbInstall() {
		
		$constructSQL = BaseDatabaseModel::getConstructDBInstall();
		
		$errors = array();
		$result = BaseDatabaseModel::doMultipleQueries($constructSQL, $errors);
		
		$this->assign("errors", $errors);
		$this->assign("result", $result);
		if (!$result) {
		} else {
			// set PROJECT_STATUS to true
			$file = DIR_PROJECT_PATH_CONFIG . 'config.php';
			$content = file_get_contents($file);
			$content = str_replace('Config::Set(\'PROJECT_STATUS\', PROJECT_NOT_INSTALLED)', 'Config::Set(\'PROJECT_STATUS\', PROJECT_READY)', $content);
			file_put_contents($file, $content);
			file_put_contents(VERSION_FILE, APP_VERSION);
			$this->assign('email', $defaultEmail);
			$this->assign('password', $randomPassword);
		}
		
		$this->assign("title", "Database Construct SQL for database");
		$this->assign("text", $constructSQL);
	}

	
	/**
	 * Action Check - display of the changes of the database (simple display)
	 * @param mixed $modelName name of the model
	 */
	public function actionDbCheckSimple() {
		$this->assign('action', '<p><a href="?doChangesInDB=1">Do changes in DB.</a></p>');
		$checkSQL = BaseDatabaseModel::getCheckDbSQL($this->getMainListIdentifier(), $this);
		if (isset($_GET['doChangesInDB']) && $_GET['doChangesInDB'] == 1) {
			$errors = array();
			if (BaseDatabaseModel::doMultipleQueries($checkSQL, $errors)) {
				file_put_contents(VERSION_FILE, APP_VERSION);
				Request::redirect(Request::getSameLink(array('doChangesInDB' => '0')));
				exit;
			}
		}
		$this->assign("title", "Database Check SQL for database");
		$this->assign("text", $checkSQL);
	}
	
	
	/**
	 * Action Check - display of the changes of the database
	 * @param mixed $modelName name of the model
	 */
	public function actionDbCheck() {

		$checkSQL = BaseDatabaseModel::getCheckDbSQL();
		$this->assign("title", "Database Check SQL for database");
		$this->assign("text", $checkSQL);
	}

	
	/**
	 * Action Check - display of the changes of the database
	 * @param mixed $modelName name of the model
	 */
	public function actionDbTestCopy() {

		$this->assign("title", "Copy Production DB to Test DB");
		if (dbConnection::getInstance()->tablePrefix() != dbConnection::getInstance('TestDatabase')->tablePrefix()
			|| dbConnection::getInstance()->connectionHash() != dbConnection::getInstance('TestDatabase')->connectionHash()) {
			if (isset($_POST['submit'])) {
				$action = isset($_POST['action']) ? $_POST['action'] : '';
				switch ($action) {
					case 'check': 
						MessageBus::sendMessage(tg("Test data check not implemented yet."), MessageBus::MESSAGE_TYPE_WARN);
						break;
					case 'clean': 
						$clean = BaseDatabaseModel::cleanTestTables();
						MessageBus::sendMessage(tg("Production data has been cleaned."));
						break;
					case 'copy': 
						if (BaseDatabaseModel::copyTestTables($inserted)) {
							MessageBus::sendMessage($inserted . ' ' . tg("rows of Production data have been copied to Test database. All succeeded."), MessageBus::MESSAGE_TYPE_INFO);
						} else {
							MessageBus::sendMessage($inserted . ' ' . tg("rows of Production data have been copied to Test database. Some proceeded with errors."), MessageBus::MESSAGE_TYPE_WARN);
						}
						break;
					case 'create': 
						if (BaseDatabaseModel::createTestTables($errors)) {
							MessageBus::sendMessage(tg("Test data structure created."), MessageBus::MESSAGE_TYPE_INFO);
						} else {
							MessageBus::sendMessage(tg("Test data structure create proceeded with errors."), MessageBus::MESSAGE_TYPE_WARN);
						}
						break;
					default: 
						MessageBus::sendMessage(tg("No action selected."), MessageBus::MESSAGE_TYPE_WARN);
						break;
				}
				
			} 
			$output = '';
			$output .= "<form action=\"\" method=\"post\" class=\"cleanform\"><fieldset>\n";
			$output .= "<h2>Select action to be performed</h2>\n";
			$output .= "<div><input type=\"radio\" name=\"action\" id=\"action_check\" value=\"check\" class=\"radio\" />\n";
			$output .= "<label for=\"action_check\">Check DB</label></div>\n";
			$output .= "<div class=\"clear\"></div>\n";
			$output .= "<div><input type=\"radio\" name=\"action\" id=\"action_create\" value=\"create\" class=\"radio\" />\n";
			$output .= "<label for=\"action_create\">Create DB tables</label></div>\n";
			$output .= "<div class=\"clear\"></div>\n";
			$output .= "<div><input type=\"radio\" name=\"action\" id=\"action_clean\" value=\"clean\" class=\"radio\" />\n";
			$output .= "<label for=\"action_clean\">Clean data</label></div>\n";
			$output .= "<div class=\"clear\"></div>\n";
			$output .= "<div><input type=\"radio\" name=\"action\" id=\"action_copy\" value=\"copy\" class=\"radio\" />\n";
			$output .= "<label for=\"action_copy\">Copy data</label></div>\n";
			$output .= "<div class=\"clear\"></div>\n";
			$output .= "<p>There are " . BaseDatabaseModel::countTestTables() . " rows in Production database.</p>\n";
			$output .= "<p>There are " . BaseDatabaseModel::countTestTables('TestDatabase') . " rows in Test database.</p>\n";
			$output .= "<p>Production and Test databases seems not to be identical.</p>\n";
			$output .= "<p>You can copy data from Production DB to Test DB.</p>\n";
			$output .= "<p>This can take long time, depending on database size.</p>\n";
			$output .= "<p>Database structure has to be prepared.</p>\n";
			$output .= "<input type=\"submit\" name=\"submit\" value=\"Process\" class=\"button submit\" />\n";
			$output .= "<fieldset></form>\n";
			$this->assign("text", $output);
		} else {
			MessageBus::sendMessage(tg("Production and Test databases seems to be identical. Copying data from Production DB to Test DB is not possible."), MessageBus::MESSAGE_TYPE_WARN);
		}
	}

	
	/**
	 * Links to admin Menu Left
	 * @return array Links
	 */
	public function getLinksAdminMenuLeft() {
		$listLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionListing"), 
			'label' => $this->name, 
			'title' => tg('database config'), 
			'image' => $this->getIcon(), 
			'action' => array(
				"package" => $this->package, 
				"controller" => $this->name, 
				"action" => "actionListing")));
		$listLink->addSuperiorActiveActions($this->package, $this->name, "actionEdit");
		$listLink->setOrder($this->order);
		
		$constructLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionConstruct"), 
			'label' => $this->name . ' ' . tg('construct'), 
			'title' => tg('database construct'), 
			'image' => $this->getIcon()));
		
		$testCopyLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionDbTestCopy"), 
			'label' => $this->name . ' ' . tg('test DB copy'), 
			'title' => tg('database test copy'), 
			'image' => $this->getIcon()));
		
		$checkLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionDbCheck"), 
			'label' => $this->name . ' ' . tg('check'), 
			'title' => tg('database check'), 
			'image' => $this->getIcon()));
		$checkLink->setOrder($this->order);
		
		return array($listLink, $testCopyLink, $constructLink, $checkLink);
	}

}

?>