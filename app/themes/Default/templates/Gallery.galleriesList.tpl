{include file='part.header'}

<h1>{$title}</h1>
	
{if $galleriesList->data.items}
{foreach from=$galleriesList->data.items item=gallery}
<h3><a href="{$gallery->link}">{$gallery->title}</a></h3>
<p><a href="{$gallery->link}">{if $gallery->titleimage}<img src="{$gallery->titleimage|thumbnail:150:150:'c'}" />{/if}</a>
{$gallery->description|strip_tags|truncate}</p>  
<div class="clear"></div>
{/foreach}
{else}
	<p>{tg}No galleries found.{/tg}</p>
{/if}

{include package=Base file='part.addNewItem' itemPackage=Gallery itemController=Galleries itemAction=actionNew itemActionSimple=actionSimpleNew}

{generate_paging collection=$galleriesList}

{include file='part.footer'}

