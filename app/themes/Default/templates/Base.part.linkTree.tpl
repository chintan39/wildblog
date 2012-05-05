{strip}
{if $items}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$items item=item}
		<li><a href="{$item->link}" class="{$item->activity}">{$item->title}</a></li>
		{if $item->subLinks and ($item->activity ne 'passive') and ($item->activity ne 'sup_passive') and $deep gt 1}
			{assign var=deep value=$deep-1}
			{include file='part.linkTree' items=$item->subLinks deep=$deep}
			{assign var=deep value=$deep+1}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

