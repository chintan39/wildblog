{include file='Base.part.header.tpl'}
	<h1>Login</h1>
{if $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto  controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{/if}

{include file='Base.part.cleanForm.tpl' }

<a href="{linkto  controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>

{include file='Base.part.footer.tpl'}

