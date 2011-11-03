{foreach from=$adminMenuLeft item=link}
	{if $link->activity eq "active" or $link->activity eq "sup_active"}
		<div class="active">
		<a href="{$link->link}" class="top" title="{$link->label}: {$link->title}">
			<span class="large">{$link->label}</span>
			<span class="small">{$link->title|nl2br}</span>
			{if $link->image}<span class="image"><img src="{$iconsPath}64/{$link->image}.png" alt="" /></span>{/if}
		</a>
		{foreach from=$link->subLinks item=subLink}
			<a href="{$subLink->link}" class="sub {$subLink->activity}" title="{$subLink->label}: {$subLink->title}">
				<span class="text">{$subLink->label}</span>
				{if $subLink->image}<span class="image"><img src="{$iconsPath}32/{$subLink->image}.png" alt="" /></span>{/if}
			</a>
		{/foreach}
		<span class="bottom"></span>
		</div>
	{/if}
{/foreach}

{foreach from=$adminMenuLeft item=link}
	{if $link->activity neq "active" and $link->activity neq "sup_active"}
		<a href="{$link->link}" class="{$link->activity}" title="{$link->label}: {$link->title}">
			<span class="large">{$link->label}</span>
			<span class="small">{$link->title|nl2br}</span>
			{if $link->image}<span class="image"><img src="{$iconsPath}64/{$link->image}.png" alt="" /></span>{/if}
		</a>
	{/if}
{/foreach}

