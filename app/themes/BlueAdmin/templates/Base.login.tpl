{include file='Base.part.header'}
	<h1>Login</h1>
{if $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto  controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{/if}

{include file='Common.part.cleanForm' }

<a href="{linkto  controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>

{include file='Base.part.footer'}

