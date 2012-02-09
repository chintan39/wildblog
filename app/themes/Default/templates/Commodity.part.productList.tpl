{if $products->data.items}
{foreach from=$products->data.items item=product}
<h3><a href="{$product->link}">{$product->title}</a></h3>
<p><a href="{$product->link}">{if $product->image}<img src="{$product->image|thumbnail:150:150:'r'}" alt="sekacka" class="float-left" />{/if}</a>
{$product->text|strip_tags|truncate}</p>  
<p class="price">{tg}Price including WAT{/tg}: {$product->price|price}&nbsp;Kč</p>
<div class="clear"></div>
{/foreach}
{else}
	<p>{tg}No products found.{/tg}</p>
{/if}

{generate_paging collection=$products}

