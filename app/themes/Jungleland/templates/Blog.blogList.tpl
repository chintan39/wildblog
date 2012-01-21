{require file='part.header'}

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}

				<div class="post">
			
					<h2><a href="{$post->link}">{$post->title}</a></h2>
					
					{$post->text|strip_tags|truncate:"500"}
										
					{if $post->tags}
					<p class="tags">	
						<strong>{tg}Tags{/tg}: </strong> 
						{require file='part.tags' tags=$post->tags package=Blog}
					</p>
					{/if}
				
		</div>

{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts}

{require package=Base file='part.addNewItem' itemPackage=Blog itemController=Posts itemAction=actionNew}

{require file='part.footer'}
