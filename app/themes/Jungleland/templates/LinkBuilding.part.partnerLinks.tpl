{if $partnersMenu->data.items}
<h3>{tg}Interesting links{/tg}</h3>
<ul>
    {foreach from=$partnersMenu->data.items item=partner}
	<li><a href="{$partner->link}"{if $partner and $partner->description} title="{$partner->description}"{/if} rel="external">{$partner->title}</a><span>{$partner->link}</span></li>
    {/foreach}
	<li><strong><a href="{linkto package=LinkBuilding controller=Partners action=actionPartners}">{tg}more partners{/tg}</a><span>{tg}Click here to view more partners{/tg}</span></strong></li>
</ul>
{/if}

{require package=Base file='part.addNewItem' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew itemActionSimple=actionSimpleNew}

