{if $formId}<a name="{$formId}"></a>{/if}

<form action="{$form.action|htmlentities}{if $formId}#{$formId}{/if}" method="{$form.method}" class="cleanform{if $formClass} {$formClass}{/if}" enctype="multipart/form-data">
{if $form.tabs and $form.displayForm}
<div id="{$form.tabContainerId}" class="black_tabs">
	<ul class="tabnav">
	{foreach from=$form.tabs item=tab}
	{if $tab.inTab}
	<li><a href="{$thisUrl}#{$tab.id}" id="tab_{$tab.id}" class="tab">{$tab.label|tg}<b></b></a></li>
	{/if}
	{/foreach}
	</ul>
	<div class="clear"></div>
{/if}

{if $form.label or $form.description}<div class="header">
	{if $form.label}<h2>{$form.label}</h2>{/if}
	{if $form.description}<p class="description">{$form.description}</p>{/if}
</div>{/if}

{if $form.messagesFromBus}
<div class="confirm">
  {foreach from=$form.messagesFromBus item=message name=mess}
	  {$message->text}
	  {if !$smarty.foreach.mess.last}<br />{/if}
  {/foreach}
</div>
{/if}

{if $form.messages.errors || $form.messages.warnings}
<div class="error">
{foreach from=$form.messages item=errorType}
	{foreach from=$errorType item=fieldErrors name=errors}
		{foreach from=$fieldErrors item=item name=errorsField}
			{$item}{if not $smarty.foreach.errors.last or not $smarty.foreach.errorsField.last}<br />{/if}
		{/foreach}
	{/foreach}
{/foreach}
</div>
{/if}

{if $form.displayForm}

{if $form.tabs}
	{foreach from=$form.tabs item=tab}
	{if $tab.inTab}
	<div id="panel_{$tab.id}" class="panel">
		{foreach from=$tab.fields item=field}{strip}
			{form_field field=$field}
		{/strip}{foreachelse}
		{tg}No form item found.{/tg}
		{/foreach}
	</div>
	{/if}
	{/foreach}
{else}
	{foreach from=$form.fields item=field}
		{form_field field=$field}
	{foreachelse}
	{tg}No form item found.{/tg}
	{/foreach}
{/if}
<div class="clear"></div>

{if $form.tabs}
</div><!-- tabs container -->
	{if $form.tabsInitJS}
	<script type="text/javascript">
	{$form.tabsInitJS}
	</script>
	{/if}
	{foreach from=$form.tabs item=tab}
	{if not $tab.inTab}
		{foreach from=$tab.fields item=field}{strip}
			{form_field field=$field}
		{/strip}{foreachelse}
		{tg}No form item found.{/tg}
		{/foreach}
	{/if}
	{/foreach}
{/if}
<div class="clear"></div>

{if $form.formHasCompulsoryFields}
<p>{tg}* These fields have to be filled.{/tg}</p>
{/if}

<div class="float-right">
{foreach from=$form.buttons item=button}
	{form_button button=$button}
{foreachelse}
{tg}No buttons found.{/tg}
{/foreach}
<div class="clear"></div>
</div>
<div class="clear"></div>

{/if}

</form>


