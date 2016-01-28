<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from form/fielderrormessage.tpl */ ?>
<?php if (! empty ( $this->_tpl_vars['_currentFormErrorMessages'] )): ?><?php $this->assign('_currentFieldName', $this->_tpl_vars['_currentFieldStruct']['field']->getName()); ?><?php if (! empty ( $this->_tpl_vars['_currentFormErrorMessages'][$this->_tpl_vars['_currentFieldName']]['messages'] )): ?><?php $this->assign('_currentFieldErrorMessages', $this->_tpl_vars['_currentFormErrorMessages'][$this->_tpl_vars['_currentFieldName']]['messages']); ?><span class="errorBadFieldMsg"><?php $_from = $this->_tpl_vars['_currentFieldErrorMessages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_message']):
?><?php echo $this->_config[0]['vars'][$this->_tpl_vars['_message']]; ?>
.<?php endforeach; endif; unset($_from); ?></span><?php endif; ?><?php endif; ?>