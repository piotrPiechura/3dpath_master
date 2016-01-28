<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from form/field.text.tpl */ ?>
<input <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldtitle.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php $this->assign('_class', 'text'); ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldclass.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php $this->assign('_class', ''); ?> type="text" name="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getName(); ?>
" value="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getHTMLValue(); ?>
" id="<?php echo $this->_tpl_vars['_currentFieldStruct']['field']->getHTMLId($this->_tpl_vars['_currentFormId']); ?>
" <?php echo $this->_tpl_vars['_currentFieldStruct']['htmlAttributes']; ?>
/><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fielderrormessage.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>