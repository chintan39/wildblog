{include file='Base.part.header.tpl'}

<h1>{$category->title}</h1>
	
{$category->text}

{include file='Commodity.part.productList.tpl' products=$category->products}

{include file='Base.part.footer.tpl'}

