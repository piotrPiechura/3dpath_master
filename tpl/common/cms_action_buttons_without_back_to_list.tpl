{assign var='_actionField' value=$mainForm->getField('_action')}
{assign var='_actions' value=$_actionField->getActions()}
{assign var='_firstAction' value=$_actions[0]}
<div class="adminAction {if $_firstAction == 'Save'}adminActionWithSave{else}adminActionWithoutSave{/if}">
	{$_form->field('_action',null,null,"btn btn-cons")}
</div>