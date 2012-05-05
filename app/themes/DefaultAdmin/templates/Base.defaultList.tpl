{include file='Base.part.header'}
<div id="{$main->containerId}">
<h1>{$title|default:'List':tg}</h1>
{generate_table collection=$main}
{if $csvLink}<a href="{$csvLink}" class="csvLink">{tg}Show in CSV{/tg}</a>{/if}
</div>
{include file='Base.part.footer'}

