{require file='part.header'}

<h1>{$title}</h1>
	
{if $galleriesList->data.items}
{foreach from=$galleriesList->data.items item=gallery}
<div class="galleryItem">
<h3><a href="{$gallery->link}">{$gallery->title}</a></h3>
<p><a href="{$gallery->link}">{if $gallery->titleimage}<img src="{$gallery->titleimage|thumbnail:140:140:'c'}" alt="" />{/if}</a>
{$gallery->description|strip_tags|truncate}</p>  
<div class="clear"></div>
</div>
{/foreach}
<div class="clear"></div>
{else}
	<p>{tg}No galleries found.{/tg}</p>
{/if}

{generate_paging collection=$galleriesList}

{require file='part.footer' useReferences=1}

