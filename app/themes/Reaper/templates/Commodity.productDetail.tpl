{include file='Base.part.header.tpl'}

<h1>{$product->title}</h1>
			
{if $product->image}
<a href="{$product->image}" class="float-left" rel="lightbox">
	<img src="{$product->image|thumbnail:300:400:'r'}" alt="#" title="{$product->title}" />
</a>
{/if}

<div class="clear"></div>

{$product->text}

{include file='Basic.part.contactForm.tpl' package=Basic}

{include file='Base.part.footer.tpl'}

