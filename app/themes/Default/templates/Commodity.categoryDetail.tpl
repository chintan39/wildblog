{include file='part.header'}

<h1>{$category->title}</h1>
	
{$category->text}

{include file=part.productList package=Commodity products=$category->products}

{include file='part.footer'}

