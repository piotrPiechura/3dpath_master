<select {include file="form/fieldtitle.tpl"} {assign var='_class' value='multiple'}{include file="form/fieldclass.tpl"}{assign var='_class' value=''} name="{$_currentFieldStruct.field->getName()}[]" id="{$_currentFieldStruct.field->getHTMLId($_currentFormId)}" {$_currentFieldStruct.htmlAttributes} multiple="multiple">
	{assign var='_fieldValue' value=$_currentFieldStruct.field->getHTMLValue()}	
	{foreach key='_value' item='_description' from=$_currentFieldStruct.field->getPossibleValues()}
		<option value="{$_value}" {if $_fieldValue && $_value|in_array:$_fieldValue} selected="selected"{/if}>{if $_description == '<choose>'}{#selectChoose#}{elseif $_description == '<empty>'}{#selectEmpty#}{elseif $_description == '<any>'}{#selectAny#}{else}{$_description}{/if}</option>
	{/foreach}
</select>
{include file="form/fielderrormessage.tpl"}