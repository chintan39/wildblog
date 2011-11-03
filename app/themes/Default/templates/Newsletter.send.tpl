{require file='part.header'}

{if $title}
<h1>{$title}</h1>
{/if}

{$resultMessage}

{if $errorEmails}
<p>{tg}Problems where with the following e-mails:{/tg}</p>
<ul>
{foreach from=$errorEmails item=email}<li>{$email}</li>{/foreach}
</ul>
{/if}

{if $successEmails}
<p>{tg}Newsletter has been sent to the following e-mails:{/tg}</p>
<ul>
{foreach from=$successEmails item=email}<li>{$email}</li>{/foreach}
</ul>
{/if}

<div class="sending">
{tg}Messages not sent yet: {/tg}{$messagesToSend}
</div>

<div class="sending">
<a href="{$resendAction}" class="sending">{tg}Repeat sending{/tg}</a>
<div class="clear"></div>
</div>

{require file='part.footer'}

