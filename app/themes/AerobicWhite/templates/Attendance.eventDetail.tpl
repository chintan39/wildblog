{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$event->title}</h1>
				<div class="date"><strong>{tg}Date: {/tg}</strong>{$event->date_from|date_format2:'%e. %mnamelong %Y'}</div>
				<div class="participants"><strong>{tg}Number of participants{/tg}: </strong>{$event->participantsCount}</div>
				<div class="capacity"><strong>{tg}Capacity{/tg}:</strong> {$event->capacity}</div>
				<div class="clear"></div>
				<div class="description">{$event->description}</div>
				{if $event->capacity gt $event->participantsCount}
				{include file='Base.part.cleanForm.tpl' theme=Common form=$registrationForm formId=registrationForm}
				{else}
				{tg}There are no free places anymore{/tg}
				{/if}
                </div>
                {include file='Base.part.editItem.tpl' itemPackage=Attendance itemController=Events itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$event}
        </div>
        <div class="grid_5">
        	{include file='Basic.part.recentNews.tpl'}
			<!-- LinkBuilding list-->
        	{include file='LinkBuilding.part.partnerLinks.tpl'}
			<!-- LinkBuilding end -->
        </div>     
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}

