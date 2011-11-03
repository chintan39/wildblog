{if $latestGalleriesList and $latestGalleriesList->data.items}
<h3>{tg}Recent Photos{/tg}</h3>
<div class="photos">
{foreach from=$latestGalleriesList->data.items item=gallery}
<a href="{$gallery->link}">
  {if $gallery->titleimage}
    <img src="{$gallery->titleimage|thumbnail:80:80:'c'}" title="{$gallery->title}" alt="{$gallery->title}" />
  {else}
    {$gallery->title}
  {/if}
</a>
{/foreach}
<div class="clear"></div>
</div>  
{/if}

