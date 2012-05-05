{strip}
{if $items}
<ul{if $ulClass} class="{$ulClass}"{/if}>
	{foreach from=$items item=item}
		<li><a href="{$item->link|make_link}">{$item->title}</a></li>
		{if $item|property_exists:'subItems' and $item->subItems and $deep gt 1}
			{assign var=deep value=$deep-1}
			{include file='Base.part.itemLinkTree.tpl' items=$item->subItems deep=$deep}
			{assign var=deep value=$deep+1}
		{/if}
	{/foreach}
</ul>
{/if}
{/strip}

