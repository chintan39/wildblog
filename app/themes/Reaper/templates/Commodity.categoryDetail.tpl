{require file='part.header'}

<h1>{$category->title}</h1>
	
{$category->text}

{require file=part.productList package=Commodity products=$category->products}

{require file=part.contactForm package=Basic}
			
{require file='part.footer'}

