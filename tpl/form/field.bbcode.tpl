<div class="bbcode-editor-container">

{$_utils->includeOnce('js/external/jquery_bbcode_editor/jquery.bbcodeeditor-1.0.js')}
<script type="text/javascript">
	//<![CDATA[
	$(function(){literal}{{/literal}
		$('textarea[name={$_currentFieldStruct.field->getName()}]').bbcodeeditor(
		{literal}{{/literal}
			bold:$('.bbcode-editor-bold'),
			italic:$('.bbcode-editor-italic'),
			underline:$('.bbcode-editor-underline'),
			link:$('.bbcode-editor-link'),
			code:$('.bbcode-editor-code'),
			image:$('.bbcode-editor-image'),
			back:$('.bbcode-editor-back'),
			forward:$('.bbcode-editor-forward'),
			exit_warning:false,
			dsize:false,
			preview:false
			//preview:$('.bbcode-editor-preview')
		{literal}}{/literal});
	{literal}}{/literal});
	//]]>
</script>
<div class="bbcode-editor-btn bbcode-editor-bold" title="bold"></div>
<div class="bbcode-editor-btn bbcode-editor-italic"></div>
<div class="bbcode-editor-btn bbcode-editor-underline"></div>
<div class="bbcode-editor-btn bbcode-editor-link"></div>
{*
<div class="bbcode-editor-btn bbcode-editor-quote"></div>
<div class="bbcode-editor-btn bbcode-editor-code"></div>
*}
<div class="bbcode-editor-btn bbcode-editor-image"></div>
{*
<div class="bbcode-editor-btn bbcode-editor-usize"></div>
<div class="bbcode-editor-btn bbcode-editor-dsize"></div>
<div class="bbcode-editor-btn bbcode-editor-nlist"></div>
<div class="bbcode-editor-btn bbcode-editor-blist"></div>
<div class="bbcode-editor-btn bbcode-editor-litem"></div>
*}
<div class="bbcode-editor-btn bbcode-editor-back"></div>
<div class="bbcode-editor-btn bbcode-editor-forward"></div>
<div><textarea
	{include file="form/fieldtitle.tpl"}
	{include file="form/fieldclass.tpl"}
	name="{$_currentFieldStruct.field->getName()}"
	cols="64"
	rows="4"
	id="{$_currentFieldStruct.field->getHTMLId($_currentFormId)}"
	{$_currentFieldStruct.htmlAttributes}
>{$_currentFieldStruct.field->getHTMLValue()}</textarea></div>
<div class="bbcode-editor-preview"></div>

</div>

{include file="form/fielderrormessage.tpl"}