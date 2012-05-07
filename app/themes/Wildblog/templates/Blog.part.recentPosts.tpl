{strip}
{if $recentPosts->data.items}
	<div class="box">
	<div class="dark headline">{tg}recent posts{/tg}</div><!-- dark -->
	<div class="light">
	<div class="news">
	{foreach from=$recentPosts->data.items item=postItem}
		<a href="{$postItem->link}">{$postItem->title} <span class="date">{$postItem->published|date_format2:"%m"|month_format:"%nam"}|{$postItem->published|date_format2:"%e"}</span></a>
	{/foreach}
	</div><!-- news -->
	</div><!-- light -->
	</div><!-- box -->
{/if}
{/strip}

