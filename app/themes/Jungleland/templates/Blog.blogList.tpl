{include file='Base.part.header.tpl'}

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}

				<div class="post">
			
					<h2><a href="{$post->link}">{$post->title}</a></h2>
					
					{$post->text|strip_tags|truncate:"500"}
										
					{if $post->tags}
					<p class="tags">	
						<strong>{tg}Tags{/tg}: </strong> 
						{include file='Blog.part.tags.tpl' tags=$post->tags package=Blog}
					</p>
					{/if}
				
		</div>

{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts}

{include  file='Base.part.addNewItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer.tpl'}
