{if $adminMenuTop}
<div class="topmenu">
{foreach from=$adminMenuTop item=link}
	<a href="{$link->link}" class="{$link->activity}" title="{$link->title}">
		<span class="label">{$link->label}</span>
		{if $link->image}<span class="image"><img src="{$iconsPath}32/{$link->image}.png" alt="" /></span>{/if}
	</a>
{/foreach}
<div class="clear"></div>
</div>
{/if}

