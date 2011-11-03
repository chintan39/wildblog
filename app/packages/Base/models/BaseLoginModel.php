<?php

class BaseLoginModel extends AbstractVirtualModel {

	var $package = 'Base';
	var $user = false;
	
    protected function attributesDefinition() {
    	$this->addMetaData(AbstractAttributesModel::stdId());
    	$this->addMetaData(AbstractAttributesModel::stdLoginEmail());
    	$this->addMetaData(AbstractAttributesModel::stdLoginPassword());
	}
	
	/**
	 * Save data to some object, can be overwritten, but not has to be.
	 */
	public function Save() {
		Permission::setUser($this->user->id);
	}
	
	
	/**
	 * Checking of the values sent by form, this overwrittes standard.
	 */
	protected function checkAllFieldsValue(&$newData, &$preddefinedData) {
		$usersMatch = Environment::getPackage('Base')->getController('Users')->tryLogin($newData['email'], $newData['password']);
		if (count($usersMatch)) {
			$this->user = $usersMatch[0];
		}
		if (!$this->user) {
			$this->addMessageSimple('errors', tg('Password of username is not correct.'));
		}
	}
}

?>
