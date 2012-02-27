{strip}
{if $relatedPosts->data.items}
	<div class="related">
	<h3>{tg}related posts{/tg}</h3>
	<!-- webdiffer-no-log-begin -->
	{foreach from=$relatedPosts->data.items item=postItem}
		<a href="{$postItem->link}"><img src="{$iconsPath}16/blog_post.png" alt="" />{$postItem->title} ({$postItem->published|date_format:"%e"}.{$postItem->published|date_format:"%m"}.{$postItem->published|date_format:"%Y"})</a>
	{/foreach}
	<!-- webdiffer-no-log-end -->
	</div><!-- box -->
{/if}
{/strip}

