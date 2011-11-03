{strip}
{if $relatedPosts and $relatedPosts->data.items}
	<h2>{tg}related posts{/tg}</h2>
	{foreach from=$relatedPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} {$postItem->published|date_format:"%m/%e"}</a>
	{/foreach}
{/if}
{/strip}

