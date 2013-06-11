{if $partnersMenu->data.items}
<div class="page-1-col-2 right-40 border-top">
<h3 class="top-1 cursive">{tg}Partners{/tg}</h3>
	<div class="box-1 wrapper">
		<div class="extra-wrap">
    {foreach from=$partnersMenu->data.items item=partner name=partners}
		<a href="{$partner->link}" class="color-2" rel="external"{if $partner and $partner->description} title="{$partner->description}"{/if}>{$partner->title}</a>
		<br />
    {/foreach}
			{include  file='Base.part.addNewItem.tpl' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew itemActionSimple=actionSimpleNew}
		</div>
	</div>
</div>
{/if}


