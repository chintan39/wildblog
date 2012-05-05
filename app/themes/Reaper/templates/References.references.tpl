{include file='Base.part.header'}

<h1>{$title}</h1>
	
{if $references->data.items}
{foreach from=$references->data.items item=item}
<p class="quote">&quot;{$item->text}&quot;</p>
<p class="align-right">- {$item->firstname} {$item->surname}, {$item->city}</p>
{/foreach}
{else}
	<p>{tg}No references found.{/tg}</p>
{/if}

{generate_paging collection=$references}
			
{include file='Base.part.footer'}
