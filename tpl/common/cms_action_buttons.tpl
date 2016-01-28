<div class="adminAction {if !empty($_firstAction) && $_firstAction == 'Save'}adminActionWithSave{else}adminActionWithoutSave{/if}">
	{if $mainForm->hasField('_action')}
		{assign var='_actionField' value=$mainForm->getField('_action')}
		{assign var='_actions' value=$_actionField->getActions()}
		{assign var='_firstAction' value=$_actions[0]}
                
		{$_form->field('_action',null,null,"btn btn-cons")}
	{/if}
	{include file='common/cms_back_to_list.tpl'}
</div>

