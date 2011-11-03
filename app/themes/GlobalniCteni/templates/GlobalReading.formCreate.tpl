{require file='part.header'}

<h1>{$title}</h1>

{if $forms}
{foreach from=$forms item=formItem}
<div id="form_content_{$formItem->id}" style="display: none;">
{$formItem->text}
</div>
{/foreach}
{/if}

<div id="example_form" style="display: none;">
{$category->example}
</div>

<form action="" method="post" id="forms_form">
<fieldset>
<select id="formContents" name="formContent" onchange="initAll();">
<option value="">{tp}Begin with choosing a form{/tp}</option>
{if $forms}
{foreach from=$forms item=formItem}
<option value="{$formItem->id}"{if $lastFormId eq $formItem->id} selected="selected"{/if}>{$formItem->title}</option>
{/foreach}
{/if}
</select>
</fieldset>
</form>

<div class="buttons">
<a href="#" onclick="initAll(); return false;"><img src="{$iconsPath}64/eraser.png" alt="{tg}Erase{/tg}" /> {tg}Erase{/tg}</a>
<a href="#" onclick="return printForm();"><img src="{$iconsPath}64/printer.png" alt="{tg}Print{/tg}" /> {tg}Print{/tg}</a>
<a href="#" onclick="return savePDF();"><img src="{$iconsPath}64/filetype_pdf.png" alt="{tg}Save as PDF{/tg}" /> {tg}Save as PDF{/tg}</a>
<a href="#" onclick="return initExample();"><img src="app/themes/GlobalniCteni/images/template.png" alt="{tg}Show template{/tg}" /> {tg}Show template{/tg}</a>
</div>

<div id="workingpage">
{if $tableContentPreloaded}
{$tableContentPreloaded}
{/if}
</div>

<form class="hidden" action="{$thisUrl}" method="post" id="hidden_form">
<fieldset>
<input type="hidden" name="mode" value="" id="hidden_mode" />
<input type="hidden" name="lastFormId" value="" id="hidden_lastFormId" />
<input type="hidden" name="tableContent" value="" id="hidden_table" />
<input type="submit" name="submit" id="hidden_table_submit" class="submit" value="OK" />
</fieldset>
</form>

<script type="text/javascript"><!--

function updateImageCulomn(column, f_url, dir) {ldelim}
	var IM_THUMB_DIR = '.thumbs';
	var w = column.style.width.replace('px', '');
	var h = column.style.height.replace('px', '');
	var fileName = dir + f_url;
	var reg = new RegExp('(project\/images\/)(.*)\/([^\/]*)$');
	var thumb = w + "x" + h + "r";
	fileName = fileName.replace(reg, "$1" + IM_THUMB_DIR + "/$2/" + thumb + "_thumb_$3");
	column.innerHTML = '<img src="' + fileName + '" style="width: ' + w + '; height=' + h + ';" alt="" />';
{rdelim}

function chooseImage(column) {ldelim}
	var dir = '{$base}project/images/verejne';
	var outparam = {ldelim}
		f_base   : '{$base}',
		f_dir    : dir,
		f_url    : '',
		f_alt    : '',
		f_border : '',
		f_align  : '',
		f_vert   : '',
		f_horiz  : '',
		f_width  : '',
		f_height : ''
	{rdelim};
	Dialog('{$base}{$dirLibs}ImageManager/manager.php?type=images&base_url={$base}/&base_dir=../../../project/images/verejne/&mode=noupload', function (param) {ldelim}
		if (!param)
			return false;

		updateImageCulomn(column, param.f_url, dir);
			
		return false;
	{rdelim}, outparam);
{rdelim}

var actualColumn = false;
var toolTipSpanImg = '{tg}Click here and choose image.{/tg}';
var toolTipSpanTxt = '{tg}Click here to change text.{/tg}';

function initAll() {ldelim}
	var selectElem = $('formContents');
	if (selectElem.value) {ldelim}
		$('workingpage').innerHTML = $('form_content_' + selectElem.value).innerHTML;
		init();
		init2();
	{rdelim}
	return false;
{rdelim}

function initExample() {ldelim}
	$('workingpage').innerHTML = $('example_form').innerHTML;
	init();
	init2();
	return false
{rdelim}

function init() {ldelim}
	var tableColumns = $$('div#workingpage table tr td');
	tableColumns.each(function(column) {ldelim}
		/*
		// for example content 50x50 will set 50% width and 50% height
		var reg = /(\d+)x(\d+)/g;
		var res = column.innerHTML.match(reg);
		alert(parseInt(column.scrollWidth));
		alert(parseInt(column.scrollHeight));
		if (res) {ldelim}
			res2 = res.toString().split('x');
			column.style.width = Math.round(res2[0] * 4.1)+'px';
			column.style.height = Math.round(res2[1] * 6.30)+'px';
			column.innerHTML = column.innerHTML.replace(res.toString(), '')
		{rdelim}
		*/
		column.style.width = (column.style.width) ? (Math.round(parseInt(column.style.width) * 4.1)+'px') : column.scrollWidth;
		column.style.height = (column.style.height) ? (Math.round(parseInt(column.style.height) * 6.1)+'px') : column.scrollHeight;
		if (column.innerHTML.search('_image_') != -1) {ldelim}
			column.innerHTML = toolTipSpanImg;
			column.style.textAlign = 'center';
			column.addClassName('image');
		{rdelim}
		else if (column.innerHTML.search('\.(jpg|gif|png)$') != -1) {ldelim}
			var dir = '{$base}project/images/verejne';
			updateImageCulomn(column, '/' + column.innerHTML, dir);
			column.style.textAlign = 'center';
			column.addClassName('image');
		{rdelim}
		else if (column.innerHTML.search('_none_') != -1) {ldelim}
				column.innerHTML = '&nbsp;';
		{rdelim}
		else {ldelim}
			if (column.innerHTML.search('_default_') != -1) {ldelim}
				column.innerHTML = toolTipSpanTxt;
			{rdelim}
			column.addClassName('text');
		{rdelim}
		
	{rdelim});
	
	return false;
{rdelim}


function init2() {ldelim}
	var tableColumns = $$('div#workingpage table tr td');
	tableColumns.each(function(column) {ldelim}
		column.addClassName('tooltipOn');
		if (column.hasClassName('image')) {ldelim}
			new Tooltip(column, toolTipSpanImg);
			column.onclick = function() {ldelim}return chooseImage(this);{rdelim};
		{rdelim}
		else if (column.hasClassName('text')) {ldelim}
			new Tooltip(column, toolTipSpanTxt);
			column.onclick = function() {ldelim}return chooseText(this);{rdelim};
		{rdelim}
		
	{rdelim});
	
	
	return false;
{rdelim}

function chooseText(columnElem) {ldelim}
	actualColumn = columnElem;
	tinyMCE.editors['form_text'].setContent(columnElem.innerHTML);
	Effect.toggle('lighttexteditor', 'blind');
{rdelim}

function updateText() {ldelim}
	actualColumn.innerHTML = tinyMCE.editors['form_text'].getContent();
	Effect.BlindUp('lighttexteditor');
	return false;
{rdelim}

function savePDF() {ldelim}
	$('hidden_mode').value = 'pdf';
	$('hidden_lastFormId').value = $('formContents').value;
	$('hidden_table').value = $('workingpage').innerHTML;
	$('hidden_table_submit').click();
	return false;
{rdelim}

function printForm() {ldelim}
	//window.print();
	$('hidden_mode').value = 'print';
	$('hidden_lastFormId').value = $('formContents').value;
	$('hidden_table').value = $('workingpage').innerHTML;
	$('hidden_table_submit').click();
	return false;
{rdelim}


init2();

--></script>



<div id="lighttexteditor" style="display: none;">
<form action="" method="post">
<fieldset>
<h2>{tg}Edit text{/tg}</h2>

<textarea name="form_text" id="form_text" rows="4" cols="50"></textarea>
<span class="clear"></span><!-- clear flaoting -->

<a href="#" class="close" onclick="Effect.BlindUp('lighttexteditor');return false;"></a>

<input type="submit" name="submit" class="submit" value="OK" onclick="return updateText();" />
</fieldset>
</form>
</div>
<!-- addScriptaculouse -->

{require file='part.footer'}
