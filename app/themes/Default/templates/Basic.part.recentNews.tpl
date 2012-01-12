<div class="recentNews">
{strip}
{if $recentNews->data.items}
	<h2>{tg}Recent news{/tg}</h2>
	{foreach from=$recentNews->data.items item=news}
		<div class="news">
		<a href="{$news->link}">{$news->title} <span class="date">{$news->published|date_format:"%relative"}</span></a>
		<div class="desc">{$news->text|strip_tags|truncate:200}</div>
		</div>
	{/foreach}
{/if}
{/strip}

{require package=Base file='part.addNewItem' itemPackage=Basic itemController=News itemAction=actionNew}
</div>
