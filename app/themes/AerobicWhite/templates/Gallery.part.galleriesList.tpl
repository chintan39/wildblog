{if $latestGalleriesList->data.items}

<div class="page-1-col-2 right-40 border-top">
<h3 class="top-1 cursive">{tg}Recent Galleries{/tg}</h3>
{foreach from=$latestGalleriesList->data.items item=gallery}
	<div class="box-1 wrapper">
        {if $gallery->titleimage}
		<img src="{$gallery->titleimage|thumbnail:100:100:'c'}" alt="{$gallery->title}" class="img-indent-2" >
        {/if}
		<div class="extra-wrap">
			<a href="{$gallery->link}" class="color-2">{$gallery->title}</a>
			<p class="color-3">{$gallery->text|strip_tags|truncate:100}</p>
		</div>
	</div>
{/foreach}
    <a href="{linkto package=Gallery controller=Galleries action=actionGalleriesList}" class="link-1">{tg}more galleries{/tg}</a>
</div>        

{/if}

