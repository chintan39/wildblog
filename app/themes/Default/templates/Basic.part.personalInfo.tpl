{if $personalInfo}
{$personalInfo->text}
{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$personalInfo}
{/if}

