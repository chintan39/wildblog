{strip}
{if $relatedPosts and $relatedPosts->data.items}
	<div class="box">
	<div class="dark headline">{tg}related posts{/tg}</div><!-- dark -->
	<div class="light">
	<div class="news">
	<!-- webdiffer-no-log-begin -->
	{foreach from=$relatedPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} <span class="date">{$postItem->published|date_format2:"%m"|month_format:"%nam"}|{$postItem->published|date_format2:"%e"}</span></a>
	{/foreach}
	<!-- webdiffer-no-log-end -->
	</div><!-- news -->
	</div><!-- light -->
	</div><!-- box -->
{/if}
{/strip}

