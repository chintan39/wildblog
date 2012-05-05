{include file='part.header'}
<h1>{$title|default:'View':tg}</h1>
{foreach from=$changableColumns item=column}
	<h2>{$column->getLabel()}</h2>
	{assign var=itemName value=$column->getName()}
	<div>{$item->$itemName}</div>
	<div class="clear"></div>
{/foreach}
{include file='part.footer'}

