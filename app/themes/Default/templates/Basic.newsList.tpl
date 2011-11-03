{require file='part.header'}

{if $title and not $notitle}<h1>{$title}</h1>{/if}

{if $news->data.items}
{foreach from=$news->data.items item=item}
	<div class="article">
	<h1><a href="{$item->link}">{$item->title}</a></h1>
	<div class="date">{$item->published|date_format:"%m/%e"}</div>
	{$item->preview}
	<div class="clear"></div>
	</div>
{/foreach}
{else}
	<p>{tg}No news found.{/tg}</p>
{/if}

{generate_paging collection=$news}

{require package=Base file='part.addNewItem' itemPackage=Basic itemController=News itemAction=actionNew}

{require file='part.footer'}

