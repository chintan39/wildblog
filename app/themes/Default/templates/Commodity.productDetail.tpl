{include file='part.header'}

<h1>{$product->title}</h1>
			
{if $product->image}
<a href="{$product->image}" class="float-left" rel="lightbox">
	<img src="{$product->image|thumbnail:300:400:'r'}" alt="#" title="{$product->title}" />
</a>
{/if}

{$product->text}
<p class="price">{tg}Price including WAT{/tg}: <span class="price">{$product->price|price}&nbsp;Kč</span></p>

<div class="clear"></div>

{include file='part.footer'}

