{require file='part.header'}

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}

				<div class="post">
			
					<h2><a href="{$post->link}">{$post->title}</a></h2>
					
					<p class="post-info">Posted by <a href="#">Petra</a></p>
				
					{$post->text}
										
					{if $post->tags}
					<p class="tags">	
						<strong>{tg}Tags{/tg}: </strong> 
						{require file='part.tags' tags=$post->tags package=Blog}
					</p>
					{/if}
				
					<p class="postmeta">		
						<a href="{$post->link}#comments" class="comments">Comments ({$post->commentsCount})</a> |
						<span class="date">{$post->published|date_format:"%e"}. {$post->published|date_format:"%m"|month_format:"%nam"} {$post->published|date_format:"%Y"}</span> 
					</p>
		</div>

{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts}

{require package=Base file='part.addNewItem' itemPackage=Blog itemController=Posts itemAction=actionNew}

{require file='part.footer'}
