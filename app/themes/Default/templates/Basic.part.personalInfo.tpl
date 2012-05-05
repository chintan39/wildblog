{if $personalInfo}
{$personalInfo->text}
{include  file='Basic.part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$personalInfo}
{/if}

