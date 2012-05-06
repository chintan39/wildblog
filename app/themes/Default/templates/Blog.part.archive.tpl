    <h3>archive</h3>
	<ul>
		{assign var=thisYear value=$smarty.now|date_format2:"%Y"}
		{assign var=thisMonth value=$smarty.now|date_format2:"%m"}
		<li><a href="{linkto package=Blog controller=Posts action=actionBlogPostArchivYear year=$thisYear regularExpression=true}">{$thisYear}</a>
			<ul>
				{section name=months start=$thisMonth loop=$thisMonth step=-1}
					<li><a href="{linkto package=Blog controller=Posts action=actionBlogPostArchivMonth month=$smarty.section.months.index_prev|month_format:'%mm' year=$thisYear regularExpression=true}">{$smarty.section.months.index_prev|month_format:"%name"}</a></li>
				{/section}
			</ul>
		</li>
		{section name=years max=$thisYear-2007 loop=$thisYear step=-1}
		<li><a href="{linkto package=Blog controller=Posts action=actionBlogPostArchivYear year=$smarty.section.years.index regularExpression=true}">{$smarty.section.years.index}</a>
			<ul>
			{section name=months start=12 loop=12 step=-1}
				<li><a href="{linkto package=Blog controller=Posts action=actionBlogPostArchivMonth month=$smarty.section.months.index_prev|month_format:'%mm' year=$smarty.section.years.index regularExpression=true}">{$smarty.section.months.index_prev|month_format:"%name"}</a></li>
			{/section}
			</ul>
		</li>
		{/section}
	</ul>

