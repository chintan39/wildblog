{strip}
{if $recentPosts->data.items}
	<h2>{tg}recent posts{/tg}</h2>
	{foreach from=$recentPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} {$postItem->published|date_format:"%m/%e"}</a>
	{/foreach}
{/if}
{/strip}

