{include file='Base.part.header'}

{if $title and not $notitle}<h1>{$title}</h1>{/if}

{if $news->data.items}
{foreach from=$news->data.items item=item}
	<div class="news">
	<h2><a href="{$item->link}">{$item->title}</a></h2>
	<div class="date">{$item->published|date_format:"%relative"}</div>
	{$item->preview}
	<div class="clear"></div>
	</div>
{/foreach}
{else}
	<p>{tg}No news found.{/tg}</p>
{/if}

{generate_paging collection=$news}

{include package=Base file='part.addNewItem' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer'}

