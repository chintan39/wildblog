{include file='Base.part.header.tpl'}

{if $homepageArticle}

  <section id="content">
      <div class="container_16">
      	<div class="grid_16">
        	<div class="page-1-col-1 wrapper">
            	<img src="{$base}app/themes/{$generalTheme}/images/ome.png" alt="" class="img-indent">
                <div class="extra-wrap">
            	<h2 class="top-1">{$article->title}</h2>
                <p class="color-4">{$article->text}</p>
                <p>&nbsp;</p>
				{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
				<a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=9}" class="button">více o stylech cvičení</a>
				<a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=36}" class="button">více o kosmetice</a>
				<a style="float: right;" href="https://www.facebook.com/pokorna.jitka.9" rel="external" class="facebook page"><img src="{$iconsPath}48/facebook.png" alt="Facebook" /></a>
                </div>
            </div>
        </div>    
        <div class="grid_10">
			<!-- Latest news list-->
			{include file='Basic.part.recentNews.tpl'}
			<!-- Latest news list END -->
        </div>     
      	<div class="grid_6">
			<!-- Last photogaleries list-->
        	{include file='Gallery.part.galleriesList.tpl'}
			<!-- Last photogaleries list end-->
			<!-- LinkBuilding list-->
        	{include file='LinkBuilding.part.partnerLinks.tpl'}
			<!-- LinkBuilding end -->
        </div>
        <div class="clear"></div>
      </div>
  </section> 
{else}
  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$article->title}</h2>
                {$article->text}
                </div>
                {include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
        </div>
        <div class="grid_5">
        	{include file='Basic.part.recentNews.tpl'}
			<!-- LinkBuilding list-->
        	{include file='LinkBuilding.part.partnerLinks.tpl'}
			<!-- LinkBuilding end -->
        </div>     
        <div class="clear"></div>
      </div>
  </section> 
{/if}

{include file='Base.part.footer.tpl'}

