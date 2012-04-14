{if $mainAreas->data.items}
{foreach from=$mainAreas->data.items item=item}
{$item->text}
{/foreach}
{/if}

{require package=Base file='part.addNewItem' itemPackage=Basic itemController=HtmlAreas itemAction=actionNew itemActionSimple=actionSimpleNew}

