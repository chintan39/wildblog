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


class NewsletterMessagesModel extends AbstractPagesModel {
	
	var $package = 'Newsletter';
	var $icon = 'blog_post', $table = 'messages';
	static public $defaultFromEmail = null;
	static public $defaultReplyToEmail = null;
	static public $defaultEmail = null;
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdEmail()
    		->setName('from')
			->setLabel('From')
			->setDescription('e-mail of sender, if empty the following is used: ' . $this->getDefualtFromEmail())
    		);
    	
    	$this->addMetaData(AtributesFactory::stdEmail()
    		->setName('reply_to')
			->setLabel('Reply to')
			->setDescription('e-mail to reply, if empty the following is used: ' . $this->getDefualtReplytoEmail())
    		);
    	
		$this->addMetaData(AtributesFactory::create('contactGroups')
			->setLabel('Contact groups')
			->setType(Form::FORM_CUSTOM)
			->setOptionsMethod('listSelect')
			->setRenderObject($this)
			->setIsVisible(ModelMetaItem::NEVER)
			);
		
		$this->addMetaData(AtributesFactory::create('messageContactsConnection')
			->setLabel('Contacts')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('NewsletterContactsModel', 'NewsletterMessagesContactsModel', 'message', 'contact', 'messageContactsConnection'); // define a many:many relation
    }


    protected function getDefualtEmail() {
    	if (self::$defaultEmail === null) {
    		$defaultEmails = preg_split('/[,;]/', Config::Get('DEFAULT_EMAIL'));
			self::$defaultEmail = $defaultEmails[0];
    	}
		return self::$defaultEmail;
	}
	
	
    protected function getDefualtFromEmail() {
    	if (self::$defaultFromEmail === null) {
    		self::$defaultFromEmail = $this->getDefualtEmail();
    	}
    	return self::$defaultFromEmail;
    }   
    
    
    protected function getDefualtReplytoEmail() {
    	if (self::$defaultReplyToEmail === null) {
    		self::$defaultReplyToEmail = $this->getDefualtEmail();
    	}
    	return self::$defaultReplyToEmail;
    }   

    
    public function getFrom() {
    	return ($this->from ? $this->from : $this->getDefualtFromEmail());
    }

    
    public function getReplyto() {
    	return ($this->reply_to ? $this->reply_to : $this->getDefualtReplytoEmail());
    }

    public function getFormHTMLEditable($formField) {
    	$meta = $formField->getMeta();
		$fieldName = $meta->getName();
		if ($fieldName == 'contactGroups') {
			if (Config::Get('NEWSLETTER_ALLOW_CHECK_ADDRESSES')) {
				$output = '';
				$groups = NewsletterGroupsModel::Search('NewsletterGroupsModel', array(), array(), array(), array('id', 'title'));
				
				$output .= '<div class="clear"></div>' . "\n";
		
				$output .= '<input type="checkbox" name="' . $fieldName . '_all" id="' . $fieldName . '_all" value="-1" onchange="newsletterSelectMails(this)" class="checkbox" style="margin-left: 170px;" />' . "\n";
				$output .= '<label for="' . $fieldName . '_all" style="text-align: left;">' . tg('Select all') . '</label>' . "\n";
				$output .= '<div class="clear"></div>' . "\n";
				
				$groupContactMap = array();
				
				if ($groups) {
					foreach ($groups as $group) {
						$contacts = $group->Find('NewsletterContactsModel', array(), array(), array(), array('id'));
						$groupContactMap[$group->id] = array();
						if ($contacts) {
							foreach ($contacts as $contact) {
								$groupContactMap[$group->id][$contact->id] = 1;
							}
						}
						$output .= '<input type="checkbox" name="' . $fieldName . '_' . $group->id . '" id="' . $fieldName . '_' . $group->id . '" value="' . $group->id . '" onchange="newsletterSelectMails(this)" class="checkbox" style="margin-left: 170px;" />' . "\n";
						$output .= '<label for="' . $fieldName . '_' . $group->id . '" style="text-align: left;width: 400px;">' . $group->title . '</label>' . "\n";
						$output .= '<div class="clear"></div>' . "\n";
					}
				}
				
				$output .= '<script type="text/javascript">' . "\n";
				$output .= '  var newsletterGroupContact=' . json_encode($groupContactMap) . ";\n";
				$output .= <<<EOF
		  function newsletterSelectMails(el) {
			$$('select#form_messageContactsConnection option').each(function(s) {
			  if (el.value == -1) {
				s.selected = el.checked;
			  } else if (newsletterGroupContact[el.value]) {
				if (newsletterGroupContact[el.value][s.value]) {
				  s.selected = el.checked;
				}
			  }
			});
		  }
EOF;
				$output .= '</script>';
				$output .= '<div class="clear"></div>';
			}
		}
		return $output;
	}

} 

?>