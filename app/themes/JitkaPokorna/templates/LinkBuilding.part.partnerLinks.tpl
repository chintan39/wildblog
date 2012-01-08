{if $partnersMenu->data.items}<h2>{tg}Partners{/tg}</h2>
<ul>
    {foreach from=$partnersMenu->data.items item=partner name=partners}
	  <li><a href="{$partner->link}" rel="external"{if $partner->description} title="{$partner->description}"{/if}>{$partner->title}</a></li>
    {/foreach}
      {*<li><a href="{linkto package=LinkBuilding controller=Partners action=actionPartners}"><i>{tg}more partners{/tg}</i></a></li>*}
</ul>
{/if}

{require package=Base file='part.addNewItem' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew}

