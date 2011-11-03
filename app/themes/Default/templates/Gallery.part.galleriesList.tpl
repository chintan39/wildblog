{if $latestGalleriesList->data.items}
{foreach from=$latestGalleriesList->data.items item=gallery}
<div>
<a href="{$gallery->link}">
  {if $gallery->titleimage}
    <img src="{$gallery->titleimage|thumbnail:150:150:'c'}" title="{$gallery->title}" alt="{$gallery->title}" />
  {else}
    {$gallery->title}
  {/if}
</a>
</div>  
{/foreach}
<div class="clear"></div>
{/if}

