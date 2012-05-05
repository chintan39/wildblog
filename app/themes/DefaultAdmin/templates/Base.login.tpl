{include file='Base.part.header.tpl'}
	<h1>Login</h1>
{if $actualUserInfo and $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto  controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>

{else}
{include file='Base.part.cleanForm.tpl' form=$loginForm}

<a href="{linkto  controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>
{/if}

{include file='Base.part.footer.tpl'}

