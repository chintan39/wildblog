{if $partnersMenu->data.items}<h3>partners</h3>
    {foreach from=$partnersMenu->data.items item=partner name=partners}
	  <a href="{$partner->link}" rel="external"{if $partner and $partner->description} title="{$partner->description}"{/if}>{$partner->title}</a>
	  {if not $smarty.foreach.partners.last} | {/if}
    {/foreach}
	<br />
	<strong><a href="{linkto package=LinkBuilding controller=Partners action=actionPartners}">{tg}more partners{/tg}</a></strong>
{/if}

{require package=Base file='part.addNewItem' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew}

