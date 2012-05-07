{include file='Base.part.header.tpl'}

{if $title and not $notitle}<h1>{$title}</h1>{/if}

{if $news and $news->data.items}
{foreach from=$news->data.items item=item}
	<div class="article">
	<h1><a href="{$item->link}"{if $item->color} style="color: {$item->color};"{/if}>{$item->title}</a></h1>
	<div class="date">{$item->published|date_format2:"%e"}. {$item->published|date_format2:"%m"|month_format:"%m"}. {$item->published|date_format2:"%Y"}</div>
	{$item->preview}
	<div class="clear"></div>
	</div>
{/foreach}
{else}
	<p>{tg}No news found.{/tg}</p>
{/if}

{generate_paging collection=$news}

{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer.tpl'}

