{require file='part.header'}

{if $article}
{$article->text}
{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
{/if}

{if $isHomepage}
{require package=Basic file='part.recentNews'}
{/if}

{if $article->hasContactForm}
{require file=part.contactForm package=Basic}
{/if}

{require file='part.footer'}

