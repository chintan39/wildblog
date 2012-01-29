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

/**
 * 
 */
 
class BaseLostPasswordChangeFormModel extends AbstractVirtualModel {
	
	var $package = 'Base';
	var $token = null;

    protected function attributesDefinition() {
    	$this->addMetaData(AtributesFactory::stdAccountpassword());
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
