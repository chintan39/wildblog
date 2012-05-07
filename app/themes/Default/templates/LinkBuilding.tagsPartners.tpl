{include file='Base.part.header.tpl'}

<h1>{tg}Partners sorted by tags{/tg}</h1>

{if $tagsPartnersMenu->data.items}
{foreach from=$tagsPartnersMenu->data.items item=tag}
	<h2>{$tag->title}</h2>
	{if $tag->partners}
		{foreach from=$tag->partners item=partner}
			<a href="{$partner->link}" rel="external">{$partner->title}</a>{if $partner->description} - {$partner->description}{/if}<br />
		{/foreach}
	{/if}
{/foreach}
{else}
{tg}Nothing was found.{/tg}
{/if}

{include  file='Base.part.addNewItem.tpl' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer.tpl'}

