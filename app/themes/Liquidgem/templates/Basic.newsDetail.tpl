{include file='Base.part.header.tpl'}

{include file='Basic.part.headerimage.tpl'}

    <header><!-- Work Showcase Section Start -->
    
    	<h1>{$news->title}</h1>
    </header>

     <section id="workbody"><!-- Project images start -->
<p>{tg}Published:{/tg} {$news->published|date_format:"%e. %B %Y"}</p>
<p>&nbsp;</p>
{$news->text|addlinks}
<p>&nbsp;</p>
<p>&nbsp;</p>
    </section><!-- Project images end -->

	{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=News itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$news}

{include file='Base.part.footer.tpl'}

