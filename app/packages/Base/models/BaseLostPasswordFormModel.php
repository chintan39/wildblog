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
 
class BaseLostPasswordFormModel extends AbstractVirtualModel {
	
	var $package = 'Base';
	var $user;

    protected function attributesDefinition() {
    	$this->addMetaData(AbstractAttributesModel::stdLoginEmail());
	}
	
	/**
	 * Save data to some object, can be overwritten, but not has to be.
	 */
	public function Save() {
		
		// generate new token
		$token = sha1(microtime());
		
		// erase another tokens to the same user
		while ($user = BaseLostPasswordModel::Search('BaseLostPasswordModel', array('user = ?'), array($this->user->id))) {
			$user[0]->DeleteYourself();
		}
		
		// save token
		$lostPassword = new BaseLostPasswordModel();
		$lostPassword->token = $token;
		$lostPassword->user = $this->user->id;
		$lostPassword->Save();

		
		// compose email
		$link = Request::getLinkItem($this->package, 'LostPassword', 'actionLostPasswordChange', $lostPassword);
		Environment::$smarty->assign('tokenLink', $link);
		$mailBody = Environment::$smarty->fetch('file:/' . Themes::getTemplatePath($this->package, Themes::getThemeFromBranch(Themes::BACK_END), 'lostPasswordEmail' . '.html'));
		$mailAltBody = Environment::$smarty->fetch('file:/' . Themes::getTemplatePath($this->package, Themes::getThemeFromBranch(Themes::BACK_END), 'lostPasswordEmail'));
		$mail = new Email(); // defaults to using php 'mail()'
		$mail->AddAddressMore($this->user->email);
		$mail->Subject = t('Yout forgotten password');
		$mail->AltBody = $mailAltBody; 
		$mail->MsgHTML($mailBody);

		// sending the email
		$sent = $mail->Send();

	}
	
	
	/**
	 * Checking of the values sent by form, this overwrittes standard.
	 */
	protected function checkAllFieldsValue(&$newData, &$preddefinedData) {
		if ($user = Environment::getPackage('Base')->getController('Users')->getItemFilter(array('email = ?'), array($newData['email']))) {
			$this->user = $user;
		} else {
			$this->addMessageSimple('errors', t('A username does not exist.'));
		}
	}

    
}


?>
