{include file='Base.part.header.tpl'}
<h1>View</h1>
{foreach from=$changableColumns item=column}
	<h2>{$column.label}</h2>
	{assign var=itemName value=$column.name}
	<div>{$item->$itemName}</div>
	<div class="clear"></div>
{/foreach}
{include file='Base.part.footer.tpl'}

