{include file='Base.part.header.tpl'}

{if $title}
<h1>{$title}</h1>
{/if}

{if $message}
<div class="message">{$message}</div>
{/if}

{if $warning}
<div class="warning">{$warning}</div>
{/if}

{if $error}
<div class="error">{$error}</div>
{/if}

{$text}

{include file='Base.part.footer.tpl'}

