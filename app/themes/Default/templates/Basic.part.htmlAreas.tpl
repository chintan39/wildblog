{if $mainAreas->data.items}
{foreach from=$mainAreas->data.items item=item}
{$item->text}
{/foreach}
{/if}

{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=HtmlAreas itemAction=actionNew itemActionSimple=actionSimpleNew}

