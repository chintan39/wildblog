{if $personalInfo}
{if $personalInfo->title}<h2>{$personalInfo->title}</h2>{/if}
{$personalInfo->text}
{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$personalInfo}
{/if}
