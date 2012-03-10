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


class NewsletterMessagesContactsModel extends AbstractDefaultModel {
	
	var $package = 'Newsletter';
	var $icon = '', $table = 'messages_contacts';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('message')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('contact')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('sent_time')
			->setLabel('Sent time')
			->setDescription('when the item was sent')
			->setRestrictions(Restriction::R_TIMESTAMP)
			->setType(Form::FORM_INPUT_DATETIME)
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('email_log')
			->setLabel('E-mail log')
			->setDescription('item in e-mail log with details obout sending')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('NewsletterMessagesModel', 'message', 'id');
        $this->addCustomRelation('NewsletterContactsModel', 'contact', 'id');
    }


} 

?>
