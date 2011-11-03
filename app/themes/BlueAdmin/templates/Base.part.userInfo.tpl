<div class="user">{if $actualUserInfo->id}
	{tg}Logged{/tg}: <a href="#">{$actualUserInfo->email}</a>
	<a href="{linkto package=Base controller=Users action=actionEditProfile}"><img src="{$iconsPath}16/user_edit.png" title="{tg}Edit your profile{/tg}"/></a>
{else}
{tg}No user is logged{/tg}
{/if}
</div>
{if $actualUserInfo->id}
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{else}
	<a href="{linkto package=Base controller=Users action=actionLogin}" class="logging">{tg}Login{/tg}</a>
{/if}

