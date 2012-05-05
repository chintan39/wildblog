{include file='part.header'}

<h1>{$title}</h1>
	
{if $gallery->images->data.items}
{foreach from=$gallery->images->data.items item=image}
<a href="{$image->image}" rel="lightbox[images]" title="{$image->description|default:$image->title|strip_tags|truncate}">{if $image->image}<img src="{$image->image|thumbnail:150:100:'c'}" title="{$image->description|default:$image->title|strip_tags|truncate}" alt="{$image->title}" />{/if}</a>
{/foreach}
<div class="clear"></div>
{else}
	<p>{tg}No images found.{/tg}</p>
{/if}

{generate_paging collection=$gallery->images}

{include file='part.footer'}

