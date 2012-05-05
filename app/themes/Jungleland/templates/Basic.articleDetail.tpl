{include file='Base.part.header'}

{if $article}
{$article->text}
{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
{/if}

{if $isHomepage}
{include package=Basic file='part.recentNews'}
{/if}

{if $article->hasContactForm}
{include file=part.contactForm package=Basic}
{/if}

{include file='Base.part.footer'}

