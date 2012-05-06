{include file='Base.part.header.tpl'}

{if $article}
{$article->text}
{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
{/if}

{if $isHomepage}
{include package=Basic file='Basic.part.recentNews.tpl'}
{/if}

{if $article->hasContactForm}
{include file='Basic.part.contactForm.tpl' package=Basic}
{/if}

{include file='Base.part.footer.tpl'}

