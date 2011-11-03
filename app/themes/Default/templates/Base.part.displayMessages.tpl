{*pop_messages_by_type*}
{if $messages.error}
<div class="messages error"> 
  {foreach from=$messages.error item=message name=mess}
	  {$message->text}
	  {if !$smarty.foreach.mess.last}<br />{/if}
  {/foreach}
</div>
{/if}

{if $messages.warning}
<div class="messages warning"> 
  {foreach from=$messages.warning item=message name=mess}
	  {$message->text}
	  {if !$smarty.foreach.mess.last}<br />{/if}
  {/foreach}
</div>
{/if}

{if $messages.info}
<div class="messages info"> 
  {foreach from=$messages.info item=message name=mess}
	  {$message->text}
	  {if !$smarty.foreach.mess.last}<br />{/if}
  {/foreach}
</div>
{/if}

