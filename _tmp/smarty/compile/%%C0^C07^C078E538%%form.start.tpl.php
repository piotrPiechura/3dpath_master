<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from form/form.start.tpl */ ?>
<form
	<?php if ($this->_tpl_vars['_currentForm']->getMethod() == 'post'): ?>enctype="multipart/form-data"<?php endif; ?>
	action="<?php echo $this->_tpl_vars['_currentForm']->getActionHTML(); ?>
"
	method="<?php echo $this->_tpl_vars['_currentForm']->getMethod(); ?>
"
	<?php if ($this->_tpl_vars['_currentFormId']): ?>id="<?php echo $this->_tpl_vars['_currentFormId']; ?>
"<?php endif; ?>
>
	<fieldset>