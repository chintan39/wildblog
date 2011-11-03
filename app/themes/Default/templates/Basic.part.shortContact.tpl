{if $shortContact}
{$shortContact->text}
{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$shortContact}
{/if}

