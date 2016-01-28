<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from common/cms_action_buttons_without_back_to_list.tpl */ ?>
<?php $this->assign('_actionField', $this->_tpl_vars['mainForm']->getField('_action')); ?>
<?php $this->assign('_actions', $this->_tpl_vars['_actionField']->getActions()); ?>
<?php $this->assign('_firstAction', $this->_tpl_vars['_actions'][0]); ?>
<div class="adminAction <?php if ($this->_tpl_vars['_firstAction'] == 'Save'): ?>adminActionWithSave<?php else: ?>adminActionWithoutSave<?php endif; ?>">
	<?php echo $this->_tpl_vars['_form']->field('_action',null,null,"btn btn-cons"); ?>

</div>