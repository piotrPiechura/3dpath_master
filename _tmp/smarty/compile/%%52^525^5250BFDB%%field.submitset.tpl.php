<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from form/field.submitset.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'form/field.submitset.tpl', 5, false),)), $this); ?>
<?php $this->assign('_class', 'button '); ?>
<?php if (empty ( $this->_tpl_vars['_buttonHtmlAttrs'] )): ?><?php $this->assign('_buttonHtmlAttrs', $this->_tpl_vars['_utils']->createArray()); ?><?php endif; ?>
<?php $_from = $this->_tpl_vars['_currentFieldStruct']['field']->getActions(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_item']):
?>
	<?php if ($this->_tpl_vars['_item'] != 'None'): ?>
	<?php $this->assign('_descriptionConfigVar', ((is_array($_tmp='buttonCaption')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['_item']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['_item']))); ?>
	<?php if ($this->_tpl_vars['_item'] == 'DummyDelete'): ?>
		<input
			<?php $this->assign('_class', ((is_array($_tmp=$this->_tpl_vars['_item'])) ? $this->_run_mod_handler('cat', true, $_tmp, ' submit') : smarty_modifier_cat($_tmp, ' submit'))); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldclass.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $this->assign('_class', ''); ?>
			type="button"
			name="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getName(); ?>
[<?php echo $this->_tpl_vars['_item']; ?>
]"
			value="<?php echo $this->_config[0]['vars'][$this->_tpl_vars['_descriptionConfigVar']]; ?>
"
			id="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getActionHTMLId($this->_tpl_vars['_currentFormId'],$this->_tpl_vars['_item']); ?>
"
			<?php echo $this->_tpl_vars['_currentFieldStruct']['htmlAttributes']; ?>

			<?php if (! empty ( $this->_tpl_vars['_buttonHtmlAttrs'][$this->_tpl_vars['_item']] )): ?><?php echo $this->_tpl_vars['_buttonHtmlAttrs'][$this->_tpl_vars['_item']]; ?>
<?php else: ?>onclick="alert('<?php echo $this->_config[0]['vars']['alertDummyDelete']; ?>
');"<?php endif; ?>
		/>
	<?php else: ?>
		<input
			<?php $this->assign('_class', ((is_array($_tmp=$this->_tpl_vars['_item'])) ? $this->_run_mod_handler('cat', true, $_tmp, ' submit') : smarty_modifier_cat($_tmp, ' submit'))); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldclass.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $this->assign('_class', ''); ?>
			type="submit"
			name="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getName(); ?>
[<?php echo $this->_tpl_vars['_item']; ?>
]"
			value="<?php echo $this->_config[0]['vars'][$this->_tpl_vars['_descriptionConfigVar']]; ?>
"
			id="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getActionHTMLId($this->_tpl_vars['_currentFormId'],$this->_tpl_vars['_item']); ?>
"
			<?php echo $this->_tpl_vars['_currentFieldStruct']['htmlAttributes']; ?>

			<?php if (! empty ( $this->_tpl_vars['_buttonHtmlAttrs'][$this->_tpl_vars['_item']] )): ?><?php echo $this->_tpl_vars['_buttonHtmlAttrs'][$this->_tpl_vars['_item']]; ?>
<?php elseif ($this->_tpl_vars['_item'] == 'Delete'): ?>onclick="return confirm('<?php echo $this->_config[0]['vars']['alertDelete']; ?>
');"<?php elseif ($this->_tpl_vars['_item'] == 'DeleteAll'): ?>onclick="return confirm('<?php echo $this->_config[0]['vars']['alertDeleteAll']; ?>
');"<?php endif; ?>
		/>
	<?php endif; ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>