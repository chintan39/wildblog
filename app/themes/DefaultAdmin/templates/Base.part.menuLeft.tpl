<!-- addScriptaculouse -->
{if $adminMenuLeft}
<div id="leftmenu">
{assign var=newId value=1}
{foreach from=$adminMenuLeft item=link}
	{if $link->activity eq "active" or $link->activity eq "sup_active"}
	<h2><span class="head">{$link->label}</span></h2>
	<div id="submenu_{$newId}">
	{foreach from=$link->subLinks item=subLink}
		<a href="{$subLink->link}" class="sub {$subLink->activity}" title="{$subLink->label}: {$subLink->title}">
			<span class="text">{$subLink->label}</span>
			{if $subLink->image}<span class="image"><img src="{$iconsPath}32/{$subLink->image}.png" alt="" /></span>{/if}
		</a>
	{/foreach}
	</div>
	<span class="bottom"></span>
	{/if}
	{assign var=newId value=$newId+1}
{/foreach}
	
{foreach from=$adminMenuLeft item=link}
	{if $link->activity neq "active" and $link->activity neq "sup_active"}
	<h2><a href="#" title="{tg}unwrap{/tg}" class="wrapping" onclick="Effect.toggle('submenu_{$newId}','blind'); if (this.className.include('unwrapped')) {ldelim}this.removeClassName('unwrapped');this.title = '{tg}wrap{/tg}';{rdelim} else {ldelim}this.addClassName('unwrapped');this.title = '{tg}wrap{/tg}';{rdelim} return false;"><span class="head">{$link->label}</span></a></h2>
	<div id="submenu_{$newId}" style="display:none;">
	{foreach from=$link->subLinks item=subLink}
		<a href="{$subLink->link}" class="sub {$subLink->activity}" title="{$subLink->label}: {$subLink->title}">
			<span class="text">{$subLink->label}</span>
			{if $subLink->image}<span class="image"><img src="{$iconsPath}32/{$subLink->image}.png" alt="" /></span>{/if}
		</a>
	{/foreach}
	</div>
	<span class="bottom"></span>
	{/if}
	{assign var=newId value=$newId+1}
{/foreach}
</div>
{/if}

