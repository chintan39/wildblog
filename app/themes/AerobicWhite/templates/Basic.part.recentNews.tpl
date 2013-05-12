{if $recentNews->data.items}

{if $homepageArticle}

        	<div class="page-1-col-4 border-top">
            	<h3 class="top-1 cursive">{tg}Recent news{/tg}</h3>
                {foreach from=$recentNews->data.items item=news key=key}
                <div class="wrapper box-2">
                	<div class="number"><strong>{$key+1}</strong></div>
                    <div class="extra-wrap border-1">
                    	<a href="{$news->link}" class="color-5">{$news->title}</a>
                        <p class="line_height_18">{$news->published|date_format2:"%relative"}<br />{$news->text|strip_tags|truncate:200}</p>
                    </div>
                </div>
                {/foreach}
				<a href="{linkto package=Basic controller=News action=actionNewsList}" class="button">{tg}more news{/tg}</a>
            </div>    

{else}

        	<div class="page-2-col-3 border-top">
            	<h3 class="top-1 cursive">{tg}Recent news{/tg}</h3>
                {foreach from=$recentNews->data.items item=news key=key}
                <div class="box-4 wrapper border-1">
                	<div class="number"><strong>{$key+1}</strong></div>
                    <div class="extra-wrap">
                    	<a href="{$news->link}" class="color-2">{$news->title}</a>
                        <p class="color-3">{$news->published|date_format2:"%relative"}</p>
                    </div>
                </div>
                {/foreach}
				<a href="{linkto package=Basic controller=News action=actionNewsList}" class="link-1">{tg}more news{/tg}</a>
            </div>

{/if}
{/if}

{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

