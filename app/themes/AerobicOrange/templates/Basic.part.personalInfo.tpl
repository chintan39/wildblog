{if $personalInfo}
{if $personalInfo->title}<h2>{$personalInfo->title}</h2>{/if}
{$personalInfo->text}
{include  file='Basic.part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$personalInfo}
{/if}

