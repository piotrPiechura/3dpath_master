{assign var='_class' value='button '}
{if empty($_buttonHtmlAttrs)}{assign var='_buttonHtmlAttrs' value=$_utils->createArray()}{/if}
{foreach item='_item' from=$_currentFieldStruct.field->getActions()}
	{if $_item != 'None'}
	{assign var='_descriptionConfigVar' value='buttonCaption'|cat:$_item}
	{if $_item == 'DummyDelete'}
		<input
			{assign var='_class' value=$_item|cat:' submit'}{include file="form/fieldclass.tpl"}{assign var='_class' value=''}
			type="button"
			name="{$_currentFieldStruct.field->getName()}[{$_item}]"
			value="{$smarty.config.$_descriptionConfigVar}"
			id="{$_currentFieldStruct.field->getActionHTMLId($_currentFormId, $_item)}"
			{$_currentFieldStruct.htmlAttributes}
			{if !empty($_buttonHtmlAttrs.$_item)}{$_buttonHtmlAttrs.$_item}{else}onclick="alert('{#alertDummyDelete#}');"{/if}
		/>
	{else}
		<input
			{assign var='_class' value=$_item|cat:' submit'}{include file="form/fieldclass.tpl"}{assign var='_class' value=''}
			type="submit"
			name="{$_currentFieldStruct.field->getName()}[{$_item}]"
			value="{$smarty.config.$_descriptionConfigVar}"
			id="{$_currentFieldStruct.field->getActionHTMLId($_currentFormId, $_item)}"
			{$_currentFieldStruct.htmlAttributes}
			{if !empty($_buttonHtmlAttrs.$_item)}{$_buttonHtmlAttrs.$_item}{elseif $_item == 'Delete'}onclick="return confirm('{#alertDelete#}');"{elseif $_item == 'DeleteAll'}onclick="return confirm('{#alertDeleteAll#}');"{/if}
		/>
	{/if}
	{/if}
{/foreach}