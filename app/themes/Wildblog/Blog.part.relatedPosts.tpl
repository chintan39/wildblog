{strip}
{if $relatedPosts and $relatedPosts->data.items}
	<div class="box">
	<div class="dark headline">{tg}related posts{/tg}</div><!-- dark -->
	<div class="light">
	<div class="news">
	{foreach from=$relatedPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} <span class="date">{$postItem->published|date_format:"%m"|month_format:"%nam"}|{$postItem->published|date_format:"%e"}</span></a>
	{/foreach}
	</div><!-- news -->
	</div><!-- light -->
	</div><!-- box -->
{/if}
{/strip}

