{if $recentNews->data.items}

									<header>
										<h2>{tg}Recent news{/tg}</h2>
									</header>

                {foreach from=$recentNews->data.items item=news key=key}
                        <p><strong><a href="{$news->link}" title="{$news->title}">{$news->published|date_format:"%e. %B %Y"}</a></strong> - {$news->text|strip_tags:true|truncate}</p>
                {/foreach}
		<a href="{linkto package=Basic controller=News action=actionNewsList}" class="button">{tg}more news{/tg}</a>
            </div>

{/if}

{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

