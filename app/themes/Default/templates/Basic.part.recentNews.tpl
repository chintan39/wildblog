{strip}
{if $recentNews->data.items}
	<h2>{tg}recent news{/tg}</h2>
	{foreach from=$recentNews->data.items item=news}
		<a href="{$news->link}">{$news->title} <span class="date">{$news->published|date_format:"%m"|month_format:"%nam"}|{$news->published|date_format:"%e"}</span></a>
	{/foreach}
{/if}
{/strip}

