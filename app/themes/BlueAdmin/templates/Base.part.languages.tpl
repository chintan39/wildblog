{if $languages}
  {foreach from=$languages item=lang}
	  <a href="{$lang->link}"><img src="{$iconsPath}24/lang_{$lang->url}{if not $lang->actual}_bw{/if}.png" alt="{$lang->title}" title="{$lang->title}" /></a>
  {/foreach}
{/if}

