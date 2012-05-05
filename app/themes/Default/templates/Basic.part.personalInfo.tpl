{if $personalInfo}
{$personalInfo->text}
{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$personalInfo}
{/if}

