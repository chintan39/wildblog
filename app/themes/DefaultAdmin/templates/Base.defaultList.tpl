{require file='part.header'}
<h1>{$title|default:'List':tg}</h1>
{generate_table collection=$main}
{if $csvLink}<a href="{$csvLink}" class="csvLink">{tg}Show in CSV{/tg}</a>{/if}
{*include file='part.defaultTable'*}
{require file='part.footer'}

