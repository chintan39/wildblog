{if $personalInfo}
{if $personalInfo->title}<h2>{$personalInfo->title}</h2>{/if}
{$personalInfo->text}
{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$personalInfo}
{/if}

