{strip}
{if $items}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$items item=item}
		<li><a href="{$item->link|make_link}">{$item->title}</a></li>
		{if $item|property_exists:'subItems' and $item->subItems and $deep gt 1}
			{require file='part.itemLinkTree' items=$item->subItems deep=$deep-1}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

