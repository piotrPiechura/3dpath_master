{$_utils->includeOnce('js/external/tiny_mce/tiny_mce.js')}
<script type="text/javascript">
	//<![CDATA[
	tinyMCE.init({literal}{{/literal}
		
		{assign var='_actionField' value=$_currentForm->getField('_action')}
		{if !empty($_actionField) && !$_utils->inArray('Save', $_actionField->getActions())}
		readonly : true,
		{/if}
		
		mode : "exact",
		elements: "{$_currentFieldStruct.field->getHTMLId($_currentFormId)}",
		language: "{$interfaceLang}",
		plugins : "table",
		theme : "advanced",
		theme_advanced_layout_manager : "SimpleLayout",
		theme_advanced_toolbar_location : "top",
		theme_advanced_buttons1 : "undo,redo,|,formatselect,removeformat,|,cleanup,code",
		theme_advanced_buttons2 : "bold,italic,underline,|,sub,sup,|,forecolor",
		theme_advanced_buttons3 : "bullist,numlist,|,link,unlink",
		theme_advanced_blockformats : "p,h2,h3,h4",
		{if $_currentFieldStruct.showErrors && !empty($formErrorMessages) && $formErrorMessages->isBadField($_currentFieldStruct.field->getName())}
		content_css : "css/cms_tinymce_content_badField.css"
		{elseif $_currentFieldStruct.showErrors && !empty($formErrorMessages) && $formErrorMessages->isBadField($_currentFieldStruct.field->getName())}
		content_css : "css/cms_tinymce_content_affectedField.css"
		{else}
		content_css : "css/cms_tinymce_content.css"
		{/if}
	{literal}}{/literal});
	//]]>
</script>
<textarea
	{include file="form/fieldtitle.tpl"}
	{include file="form/fieldclass.tpl"}
	name="{$_currentFieldStruct.field->getName()}"
	cols="64"
	rows="4"
	id="{$_currentFieldStruct.field->getHTMLId($_currentFormId)}"
	{$_currentFieldStruct.htmlAttributes}
>{$_currentFieldStruct.field->getHTMLValue()}</textarea>
{include file="form/fielderrormessage.tpl"}