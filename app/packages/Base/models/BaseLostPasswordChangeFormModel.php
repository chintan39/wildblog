<?php
/**
 * 
 */
 
class BaseLostPasswordChangeFormModel extends AbstractVirtualModel {
	
	var $package = 'Base';
	var $token = null;

    protected function attributesDefinition() {
    	$this->addMetaData(AbstractAttributesModel::stdAccountpassword());
	}
	
	/**
	 * Save data to some object, can be overwritten, but not has to be.
	 */
	public function Save() {
		$token = BaseLostPasswordModel::Search('BaseLostPasswordModel', array('token = ?'), array($this->token));
		if ($token) {
			$user = new BaseUsersModel($token[0]->user);
			if ($user) {
				$user->password = $this->password;
				$user->Save();
				$token[0]->DeleteYourself();
				Request::redirect(Request::getLinkSimple($this->package, 'LostPassword', 'actionLostPasswordChangeDone'));
			}
		}
	}

    
	public function setToken($token) {
		$this->token = $token;
	}
}


?>
