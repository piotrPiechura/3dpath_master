{if !empty($_currentFormErrorMessages)}{assign var='_currentFieldName' value=$_currentFieldStruct.field->getName()}{if !empty($_currentFormErrorMessages.$_currentFieldName.messages)}{assign var='_currentFieldErrorMessages' value=$_currentFormErrorMessages.$_currentFieldName.messages}<span class="errorBadFieldMsg">{foreach item='_message' from=$_currentFieldErrorMessages}{$smarty.config.$_message}.{/foreach}</span>{/if}{/if}