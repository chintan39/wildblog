{include file='Base.part.header'}

{if $article}
{$article->text}
{include  file='Basic.part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
{/if}

{if $isHomepage}
{include package=Basic file='Basic.part.recentNews'}
{/if}

{if $article->hasContactForm}
{include file='Basic.part.contactForm' package=Basic}
{/if}

{include file='Base.part.footer'}

