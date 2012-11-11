{require file='part.header'}

	<h1>{$event->title}</h1>
	<div class="date"><strong>{tg}Date: {/tg}</strong>{$event->date_from|date_format:'%e. %mnamelong %Y'}</div>
	<div class="participants"><strong>{tg}Number of participants{/tg}: </strong>{$event->participantsCount}</div>
	<div class="clear"></div>
	<div class="description">{$event->description}</div>

	{require file='part.cleanForm' theme=Common form=$registrationForm formId=registrationForm}
	
	{require package=Base file='part.editItem' itemPackage=Attendance itemController=Events itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$event}

{require file='part.footer'}

