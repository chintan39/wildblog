{if $shortContact}
{$shortContact->text}
{include  file='Basic.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$shortContact}
{/if}

