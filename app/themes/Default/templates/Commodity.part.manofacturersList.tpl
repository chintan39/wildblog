{if $manofacturersList->data.items}
<h2>{tg}Manofacturers list header{/tg}</h2>
{strip}
<ul class="sidemenu">
	{foreach from=$manofacturersList->data.items item=item}
		<li><a href="{$item->link_detail}"><img src="{$item->image}" alt="{$item->title}" title="{$item->title}" class="no-border" /></a></li>
	{/foreach}
</ul>
{/strip}
{/if}

