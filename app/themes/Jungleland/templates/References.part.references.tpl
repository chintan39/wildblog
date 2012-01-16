{if $references->data.items}
<h2>{tg}References list header{/tg}</h2>
{strip}
{foreach from=$references->data.items item=item}
	<p class="quote">&quot;{$item->text}&quot;</p>
	<p class="align-right">- {$item->firstname} {$item->surname}, {$item->city}</p>
{/foreach}
{/strip}
<p class="float-left sub-references"><a href="{linkto package=References controller=References action=actionReferencesList}" class="moreReferences"><img src="{$iconsPath}16/references.png" alt="+" class="no-border" /> {tg}more refernces{/tg}</a></p>
<p class="float-right sub-references"><a href="{linkto package=References controller=References action=actionReferenceAdd}" class="addReference"><img src="{$iconsPath}16/add.png" alt="+" class="no-border" /> {tg}add refernce{/tg}</a></p>
<div class="clear"></div>
{/if}

