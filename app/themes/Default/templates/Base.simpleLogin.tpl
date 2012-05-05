{*require file='Base.part.header' *}
<!-- ##title={tg}Login{/tg}## -->
<!-- ##size=500x300## -->
{if $actualUserInfo and $actualUserInfo->id}
Logged: <a href="#">{$actualUserInfo->email}</a><br />
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>

{else}
{include file='Common.part.cleanForm'  form=$loginForm ajax=1}

<a href="{linkto package=Base controller=LostPassword action=actionLostPassword}">{tg}Password forgotten?{/tg}</a>
{/if}

{*require file='Base.part.footer' *}

