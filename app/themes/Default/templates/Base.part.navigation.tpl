    {if $navigation}
	<div id="navi">{tg}Navigation:{/tg} 
	  {foreach from=$navigation item=nav name=nav}
		  <a href="{$nav->link}">{$nav->title}</a>{if !$smarty.foreach.nav.last} &gt;{/if}
	  {/foreach}
	</div>
	{/if}

