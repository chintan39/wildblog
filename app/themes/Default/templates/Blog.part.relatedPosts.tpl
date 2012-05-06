{strip}
{if $relatedPosts and $relatedPosts->data.items}
	<h2>{tg}related posts{/tg}</h2>
	<!-- webdiffer-no-log-begin -->
	{foreach from=$relatedPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} {$postItem->published|date_format2:"%m/%e"}</a>
	{/foreach}
	<!-- webdiffer-no-log-end -->
{/if}
{/strip}

