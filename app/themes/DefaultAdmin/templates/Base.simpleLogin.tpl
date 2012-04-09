{require file='part.header' theme=Common}
	<h1>Login</h1>
{if $actualUserInfo and $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>

{else}
{require file='part.cleanForm' theme=Common form=$loginForm}

<a href="{linkto package=Base controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>
{/if}

{require file='part.footer' theme=Common}

