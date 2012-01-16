{require file='part.header'}

<h1>{$title}</h1>
{assign var=found value=0}
{foreach from=$results item=controllerCollection}
	{if  $controllerCollection and  $controllerCollection->data.items}
	<h2>{$controllerCollection->getIdentifier()|tg}</h2>
	{assign var=found value=1}
	{foreach from=$controllerCollection->data.items item=item}
		<strong><a href="{$item->link}">{$item->title|default:"no title"|tg}</a></strong><br />
		{$item->preview}
		<br /><br />
	{/foreach}
	{generate_paging collection=$controllerCollection}
	{/if}
{/foreach}

{if not $found}
{tg}Nothing was found.{/tg}
{/if}

{require file='part.footer'}

