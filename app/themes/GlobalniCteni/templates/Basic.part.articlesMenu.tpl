{assign var=deep value=3}
{strip}
{if $articlesTree->links}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$articlesTree->links item=item}
		<li class="{$item->activity}"><a href="{$item->link|make_link}">{$item->title}</a></li>
		{if $item|property_exists:'subLinks' and $item->subLinks and $deep gt 1 and ($item->activity eq 'active' or $item->activity eq 'sup_active')}
			{include file='Base.part.itemLinkTree.tpl' items=$item->subLinks deep=$deep-1}
		{/if}
		{if $item->action.item eq 58}
		{include file='GlobalReading.part.categoriesMenu.tpl' package=GlobalReading ulClass='sidemenu'}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

