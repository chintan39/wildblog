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
{tg}Field{/tg} {$change->field} {tg} was changed{/tg} {$change->inserted|date_format2:'%relative'}
<a href="#" onclick="$('viewItemChanges{$change->id}').toggle(); return false;">{tg}show/hide{/tg}</a>
</p>
<pre class="box" id="viewItemChanges{$change->id}" style="display: none;">{$change->data|htmlspecialchars}</pre>
{/foreach}
</div> <!-- viewItemChanges -->

<p><a href="#" onclick="$('viewItemChanges').style.height='auto';return false;">{tg}Show all changes{/tg}</a></p>

<a href="{$editLink}" class="editItem" title="{tg}Edit item{/tg}"></a>

{include file='Base.part.footer.tpl'}

