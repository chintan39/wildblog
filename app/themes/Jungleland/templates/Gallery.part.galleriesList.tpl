{if $latestGalleriesList and $latestGalleriesList->data.items}
<h3>{tg}Recent Photos{/tg}</h3>
<p class="thumbs">
{foreach from=$latestGalleriesList->data.items item=gallery}
<a href="{$gallery->link}">
  {if $gallery->titleimage}
    <img src="{$gallery->titleimage|thumbnail:64:64:'c'}" width="64" height="64" title="{$gallery->title}" alt="{$gallery->title}" />
  {else}
    {$gallery->title}
  {/if}
</a>
{/foreach}
<span class="clear"></span>
</p>
{/if}

