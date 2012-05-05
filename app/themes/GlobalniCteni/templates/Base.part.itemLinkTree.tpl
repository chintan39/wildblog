{strip}
{if $items}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$items item=item}
		<li class="{$item->activity}"><a href="{$item->link|make_link}">{$item->title}</a></li>
		{if $item|property_exists:'subLinks' and $item->subLinks and $deep gt 1 and ($item->activity eq 'active' or $item->activity eq 'sup_active')}
			{include file='Base.part.itemLinkTree' items=$item->subLinks deep=$deep-1}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

