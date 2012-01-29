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


class NewsletterContactsRegisterModel extends NewsletterContactsModel {

	var $useInInitDatabase = false;
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::stdYesNoRadio()
			->setName('unsubscribe')
			->setLabel('Unsubscribe')
			->setDescription('Choose Yes if you would like to unsubscribe'));
		
		$this->getMetaData('token')->setIsEditable(ModelMetaItem::ALWAYS);
    	
    }

    
	public function Save($forceSaving=false) 
	{
		$unsubscribe = $this->changedValues['unsubscribe'];
		unset($this->changedValues['unsubscribe']);
		if ($unsubscribe == 1) {
			$tmp = NewsletterContactsModel::Search('NewsletterContactsModel', array('email = ?'), array($this->email));
			if ($tmp && count($tmp)) {
				$tmp = $tmp[0];
				$id = $tmp->id;
				$tmp->DeleteYourself();
				return $id;
			}
			return false;
		} else {
			parent::Save($forceSaving);
			
			$mail = new Email(); // defaults to using php "mail()"
	
			$mail->AddAddress($this->email);
			
			$messageText = tg('You have been successfully registered to newsletter on the web #$webname#.<br />If you would like to unsubscribe from newsletter, visit #$unsubscribeurl#. Your personal code is: #$personalcode#', 
				array(
					'webname' => Config::Get('PROJECT_TITLE'),
					'unsubscribeurl' => Request::getLinkSimple($this->package, 'Contacts', 'actionRegister', 
						array(
							'_pred_' => array(
								'token' => $this->token,
								'unsubscribe' => 1,
								'email' => $this->email,
								))),
					'personalcode' => $this->token,
					));
			
			$mailAltBody = strip_tags(str_replace('<br />', "\n", $messageText));
			
			// fill basic fields
			$mail->Subject = tg('Newsletter registration');
			$mail->AltBody = $mailAltBody; 
			$mail->MsgHTML($messageText);
	
			// sending the email
			$sent = $mail->Send();
			
		}
	}
	
	
	protected function checkFieldValue(&$meta, &$newData) {
		if (isset($newData['unsubscribe']) && $newData['unsubscribe'] == 1) {
			if ($meta->getName() == 'email') {
				$meta->removeRestrictions(Restriction::R_UNIQUE);
			} else if ($meta->getName() == 'token') {
				$meta->setIsEditable(ModelMetaItem::ALWAYS);
				$meta->setAdjustMethod(null);
				$meta->addRestrictions(Restriction::R_NOT_EMPTY);
				if ($newData['token'] && $newData['email']) {
					if (!NewsletterContactsModel::SearchCount('NewsletterContactsModel', array('email = ? AND token = ?'), array($newData['email'], $newData['token']))) {
						$this->addMessage("errors", $meta->getName(), tg("E-mail or token is not filled correctly."));
					}
				}
			}
		} else {
			if ($meta->getName() == 'agreement' && !$newData['agreement']) {
				$this->addMessage("errors", $meta->getName(), tg("You have to agree with the data processing agreement."));
			}
		}
		parent::checkFieldValue($meta, $newData);
	}

	
	/**
	 * Checks field's uniqueness (for example email in registration).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldUnique(&$value, &$meta) {
		if ($this->fieldIsNotUnique($value, $meta)) {
			if ($meta->getName() == 'email') {
				$this->addMessage("errors", $meta->getName(), tg("Your e-mail is already registered."));
			} else {
				$this->addMessageField("errors", $meta, "must be unique"); 
			}
		}
	}
} 

?>