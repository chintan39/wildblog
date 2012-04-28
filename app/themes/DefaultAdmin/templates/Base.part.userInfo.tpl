<div class="user">{if $actualUserInfo and $actualUserInfo->id}
	{tg}Logged{/tg}: <a href="{linkto package=Base controller=Users action=actionEditProfile}">{$actualUserInfo->email}</a>
	<a href="{linkto package=Base controller=Users action=actionEditProfile}"><img src="{$iconsPath}24/user_edit.png" title="{tg}Edit your profile{/tg}" alt="{tg}Edit your profile{/tg}" /></a>
{else}
{tg}No user is logged{/tg}
{/if}
</div>
{if $actualUserInfo and $actualUserInfo->id}
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging logout">{tg}Log out{/tg}</a>
{else}
	<a href="{linkto package=Base controller=Users action=actionLogin}" class="logging">{tg}Log in{/tg}</a>
{/if}

