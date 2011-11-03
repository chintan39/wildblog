{assign var=deep value=3}
{strip}
{if $articlesTree->links}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$articlesTree->links item=item}
		<li class="{$item->activity}"><a href="{$item->link|make_link}">{$item->title}</a></li>
		{if $item|property_exists:'subLinks' and $item->subLinks and $deep gt 1 and ($item->activity eq 'active' or $item->activity eq 'sup_active')}
			{require file='part.itemLinkTree' items=$item->subLinks deep=$deep-1}
		{/if}
		{if $item->action.item eq 58}
		{require file='part.categoriesMenu' package=GlobalReading ulClass='sidemenu'}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

