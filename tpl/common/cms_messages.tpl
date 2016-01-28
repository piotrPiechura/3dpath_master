{if !empty($formErrorMessages)}
	<div class='alert alert-danger'> 
		<h3>{#formErrors#}</h3>
		<ul>
		{foreach item='_messageStruct' from=$formErrorMessages->getMessages()}
			<li>
				{if isset($_messageStruct.fieldCaptions)}
					<b>
					{foreach name='_messageStructFieldsIter' item='_fieldName' from=$_messageStruct.fieldCaptions}
						{assign var='_fieldCaption' value=$_fieldName|cat:'FieldCaption'}
						{$smarty.config.$_fieldCaption}{if $smarty.foreach._messageStructFieldsIter.last}: {else}, {/if}
					{/foreach}
					</b>
				{/if}
				{foreach item='_message' from=$_messageStruct.messages}
					{$smarty.config.$_message}.
				{/foreach}
			</li>
		{/foreach}
		</ul>
	</div>
{elseif !empty($successMessageType)}
	<div class='alert alert-success'> 
		{assign var='_successMessageConfigVar' value='successMessage'|cat:$successMessageType}{$smarty.config.$_successMessageConfigVar}.
        </div>
{/if}