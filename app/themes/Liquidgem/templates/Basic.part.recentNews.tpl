{if $recentNews->data.items}
    <section id="news"> <!-- Work Links Section Start -->
                {foreach from=$recentNews->data.items item=news key=key}
    <aside id="about" class="{if $key % 2 == 0}left{else}right{/if}"> <!-- Text Section Start -->
        <h3><a href="{$news->link}" title="{$news->title}">{$news->title}</a></h3><!-- Replace all text with what you want -->
        <p>{$news->published|date_format:"%e. %B %Y"}</p>
        <p>{$news->text|strip_tags:true}</p>
    </aside>
                {/foreach}
{/if}
    <div class="clearfix"></div> <!-- Text Section End -->
    </section> <!-- Work Links Section End -->


{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

