{include file='Base.part.header.tpl'}

<h1>{$title|default:'View':tg}</h1>

<div class="viewItemValues">
{foreach from=$changableColumns item=column}
	<h2>{$column->getLabel()}</h2>
	{assign var=itemName value=$column->getName()}
	<div class="value">{$item->$itemName|default:'&nbsp;'}</div>
	<div class="clear"></div>
{/foreach}
</div> <!-- viewItemValues -->

<div class="viewItemChanges" id="viewItemChanges">
<h2>{tg}Changes{/tg}</h2>
{foreach from=$changes item=change}
<p>
{tg}Field{/tg} <strong>{$change->field}</strong> {tg} was changed by {/tg} <strong>user#{$change->user}</strong> (ip {$change->ip}) {$change->inserted|date_format2:'%relative'}
<a href="#" onclick="$('viewItemChanges{$change->id}').toggle(); return false;">{tg}show/hide{/tg}</a>
</p>
<pre class="box" id="viewItemChanges{$change->id}" style="display: none;">{$change->data|htmlspecialchars}</pre>
{/foreach}
</div> <!-- viewItemChanges -->

{*<p><a href="#" onclick="$('viewItemChanges').style.height='auto';return false;">{tg}Show all changes{/tg}</a></p>*}
<p><a href="#" onclick="$('viewItemChanges').style.height='auto';$$('#viewItemChanges pre').each( function(item) { item.show() });return false;">{tg}Show all changes{/tg}</a></p>

{if $editLink}<a href="{$editLink}" class="editItem" title="{tg}Edit item{/tg}"></a>{/if}

{include file='Base.part.footer.tpl'}

