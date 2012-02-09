{if $actionProducts and $actionProducts->data.items}

<h2>{tg}Action products{/tg}</h2>

{foreach from=$actionProducts->data.items item=product}
<h3><a href="{$product->link}">{$product->title}</a></h3>
<p><a href="{$product->link}">{if $product->image}<img src="{$product->image}" width="190" height="90" alt="sekacka" class="float-left" />{/if}</a>
{$product->text|truncate}</p>  
<p class="price">{tg}Price including WAT{/tg}: {*$product->price*},-</p>
<div class="clear"></div>
<div class="separator"></div>
{/foreach}

{/if}

