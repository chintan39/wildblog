{if $references->data.items}
<h2>{tg}References list header{/tg}</h2>
{strip}
{foreach from=$references->data.items item=item}
	<p class="quote">&quot;{$item->text}&quot;</p>
	<p class="align-right">- {$item->firstname} {$item->surname}, {$item->city}</p>
{/foreach}
{/strip}
<p><a href="{linkto package=Commodity controller=References action=actionReferencesList}"><img src="{$iconsPath}16/references.png" alt="+" class="no-border" /> {tg}more refernces{/tg}</a></p>
<p><a href="{linkto package=Commodity controller=References action=actionReferenceAdd}"><img src="{$iconsPath}16/add.png" alt="+" class="no-border" /> {tg}add refernce{/tg}</a></p>
{/if}

