{include file='Base.part.header'}
	<h1>Login</h1>
{if $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{/if}

{include file='Base.part.cleanForm' }

<a href="{linkto package=Base controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>

{include file='Base.part.footer'}

