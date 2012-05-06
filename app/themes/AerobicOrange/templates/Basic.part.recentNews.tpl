{strip}
{if $recentNews->data.items}
	<h2>{tg}recent news{/tg}</h2>
	{foreach from=$recentNews->data.items item=item}
		<div class="event">
		<h3><a href="{$item->link}">{$item->title}</a></h3>
		<p>{$item->description}</p>
		<p><a href="{$item->link}" class="arrow">{tg}More info{/tg}</a></p>
		<p class="date">{$item->published|date_format2:"%e"}. {$item->published|date_format2:"%m"|month_format:"%m"}. {$item->published|date_format2:"%Y"}</p>
		</div>
	{/foreach}
{/if}
{/strip}

