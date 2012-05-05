{include file='Base.part.header'}

<h1>{$category->title}</h1>
	
{$category->text}

{include file=Commodity.part.productList package=Commodity products=$category->products}

{include file='Basic.part.contactForm' package=Basic}
			
{include file='Base.part.footer'}

