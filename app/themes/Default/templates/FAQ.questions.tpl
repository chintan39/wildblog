{require file='part.header'}

<h1>{$title}</h1>

<p><a href="{linkto package=FAQ controller=Questions action=actionQuestionAdd}" class="faq-add-question">Přidat vzkaz</a></p>

{if $questions->data.items}
{foreach from=$questions->data.items item=item}
<div class="faq-delim"></div>
<p class="question-title">
{$item->author_name}
{if $item->author_email or $item->author_web}
{strip}
({if $item->author_email}<a href="mailto: {$item->author_email}">{$item->author_email}</a>{/if}
{if $item->author_web}{if $item->author_email}, {/if}<a href="{$item->author_web}" rel="external">{$item->author_web}</a>{/if}
){/strip}
{/if}
</p>
<p class="question-text">&quot;{$item->text}&quot;</p>
{if $item->answer}<p class="question-answer">{$item->answer}</p>{/if}
{/foreach}
{else}
	<p>{tg}No questions found.{/tg}</p>
{/if}

{generate_paging collection=$questions}
			
{require file='part.footer'}
