{include file='Base.part.header.tpl'}

<h1>{$manofacturer->title}</h1>
			
{if $manofacturer->image}<img src="{$manofacturer->image}" alt="#" title="{$manofacturer->title}" class="no-border float-right" />{/if}

{if $manofacturer->link}<p>{tg}Web{/tg}: <a href="{$manofacturer->link}" rel="external">{$manofacturer->link}</a></p>{/if}

{include file=Commodity.part.productList.tplpackage=Commodity products=$manofacturer->products}

{$manofacturer->text}

{include file='Base.part.footer.tpl'}

