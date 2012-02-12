{require file='part.header'}

<h1>{$product->title}</h1>
			
{if $product->image}
<a href="{$product->image}" class="float-left" rel="lightbox">
	<img src="{$product->image|thumbnail:300:400:'r'}" alt="#" title="{$product->title}" />
</a>
{/if}

<div class="clear"></div>

{$product->text}

{require file=part.contactForm package=Basic}

{require file='part.footer'}

