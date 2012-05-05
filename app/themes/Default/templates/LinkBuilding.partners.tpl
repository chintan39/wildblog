{include file='part.header'}

<h1>{$title}</h1>

{if $partners->data.items}
{foreach from=$partners->data.items item=item}
<a href="{$item->link}" rel="external">{$item->title}</a>{if $item->description} - {$item->description}{/if}<br />
{/foreach}
{else}
{tg}Nothing was found.{/tg}
{/if}

{include package=Base file='part.addNewItem' itemPackage=LinkBuilding itemController=Partners itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='part.footer'}

