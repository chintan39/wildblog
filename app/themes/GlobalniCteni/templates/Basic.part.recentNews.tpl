{strip}
{if $recentNews->data.items}
	<h2>{tg}News{/tg}</h2>
	{foreach from=$recentNews->data.items item=news}
		<h3>{if $news->text}<a href="{$news->link}">{/if}{$news->title}{if $news->text}</a>{/if}</h3> 
		<div class="date">{$news->published|date_format:"%e. %m. %Y"}</div>
		<p>{$news->description}</p>
	{/foreach}
{/if}
{/strip}

