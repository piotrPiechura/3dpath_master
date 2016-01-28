<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from form/field.select.tpl */ ?>
<select <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldtitle.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldclass.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> name="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getHTMLId($this->_tpl_vars['_currentFormId']); ?>
" <?php echo $this->_tpl_vars['_currentFieldStruct']['htmlAttributes']; ?>
 ><?php $this->assign('_fieldValue', $this->_tpl_vars['_currentFieldStruct']['field']->getHTMLValue()); ?><?php $_from = $this->_tpl_vars['_currentFieldStruct']['field']->getPossibleValues(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_value'] => $this->_tpl_vars['_description']):
?> <option value="<?php echo $this->_tpl_vars['_value']; ?>
"<?php if ($this->_tpl_vars['_value'] == $this->_tpl_vars['_fieldValue']): ?> selected="selected"<?php endif; ?>><?php if ($this->_tpl_vars['_description'] == '<choose>'): ?><?php echo $this->_config[0]['vars']['selectChoose']; ?>
<?php elseif ($this->_tpl_vars['_description'] == '<empty>'): ?><?php echo $this->_config[0]['vars']['selectEmpty']; ?>
<?php elseif ($this->_tpl_vars['_description'] == '<all>'): ?><?php echo $this->_config[0]['vars']['selectAll']; ?>
<?php else: ?><?php echo $this->_tpl_vars['_description']; ?>
<?php endif; ?></option><?php endforeach; endif; unset($_from); ?></select><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fielderrormessage.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>