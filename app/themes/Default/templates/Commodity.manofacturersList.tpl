{require file='part.header'}

<h1>{$title}</h1>
	
{if $manofacturersList->data.items}
{foreach from=$manofacturersList->data.items item=manofacturer}
<h3><a href="{$manofacturer->link_detail}">{$manofacturer->title}</a></h3>
<p><a href="{$manofacturer->link_detail}">{if $manofacturer->image}<img src="{$manofacturer->image" alt="sekacka" class="float-left" />{/if}</a>
{$manofacturer->text}</p>  
<div class="clear"></div>
{/foreach}
{else}
	<p>{tg}No manofacturers found.{/tg}</p>
{/if}

{generate_paging collection=$manofacturer}
			
{require file='part.footer'}

