{include file='part.header'}

<h1>{$title}</h1>
	
{if $references->data.items}
{foreach from=$references->data.items item=item}
<p class="quote">&quot;{$item->text}&quot;</p>
<p class="align-right">
{section name=stars start=0 loop=$item->rating}<img class="star" src="{$iconsPath}16/favorite.png" alt="*" />{/section}
- {$item->firstname} {$item->surname}, {$item->city}
</p>
{/foreach}
{else}
	<p>{tg}No references found.{/tg}</p>
{/if}

{generate_paging collection=$references}

<p class="addReference"><a href="{linkto package=References controller=References action=actionReferenceAdd}"><img src="{$iconsPath}32/add.png" alt="+" class="no-border" /> {tg}add refernce{/tg}</a></p>
			
{include file='part.footer'}
