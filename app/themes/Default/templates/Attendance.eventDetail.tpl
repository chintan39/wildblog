{include file='Base.part.header.tpl'}

	<h1>{$event->title}</h1>
	<div class="date"><strong>{tg}Date: {/tg}</strong>{$event->date_from|date_format2:'%e. %mnamelong %Y'}</div>
	<div class="participants"><strong>{tg}Number of participants{/tg}: </strong>{$event->participantsCount}</div>
	<div class="clear"></div>
	<div class="description">{$event->description}</div>

	{include file='Base.part.cleanForm.tpl' theme=Common form=$registrationForm formId=registrationForm}
	
	{include file='Base.part.editItem.tpl' itemPackage=Attendance itemController=Events itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$event}

{include file='Base.part.footer.tpl'}

