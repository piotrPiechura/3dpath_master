<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from common/cms_messages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'common/cms_messages.tpl', 10, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['formErrorMessages'] )): ?>
	<div class='alert alert-danger'> 
		<h3><?php echo $this->_config[0]['vars']['formErrors']; ?>
</h3>
		<ul>
		<?php $_from = $this->_tpl_vars['formErrorMessages']->getMessages(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_messageStruct']):
?>
			<li>
				<?php if (isset ( $this->_tpl_vars['_messageStruct']['fieldCaptions'] )): ?>
					<b>
					<?php $_from = $this->_tpl_vars['_messageStruct']['fieldCaptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['_messageStructFieldsIter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_messageStructFieldsIter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_fieldName']):
        $this->_foreach['_messageStructFieldsIter']['iteration']++;
?>
						<?php $this->assign('_fieldCaption', ((is_array($_tmp=$this->_tpl_vars['_fieldName'])) ? $this->_run_mod_handler('cat', true, $_tmp, 'FieldCaption') : smarty_modifier_cat($_tmp, 'FieldCaption'))); ?>
						<?php echo $this->_config[0]['vars'][$this->_tpl_vars['_fieldCaption']]; ?>
<?php if (($this->_foreach['_messageStructFieldsIter']['iteration'] == $this->_foreach['_messageStructFieldsIter']['total'])): ?>: <?php else: ?>, <?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
					</b>
				<?php endif; ?>
				<?php $_from = $this->_tpl_vars['_messageStruct']['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_message']):
?>
					<?php echo $this->_config[0]['vars'][$this->_tpl_vars['_message']]; ?>
.
				<?php endforeach; endif; unset($_from); ?>
			</li>
		<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
<?php elseif (! empty ( $this->_tpl_vars['successMessageType'] )): ?>
	<div class='alert alert-success'> 
		<?php $this->assign('_successMessageConfigVar', ((is_array($_tmp='successMessage')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['successMessageType']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['successMessageType']))); ?><?php echo $this->_config[0]['vars'][$this->_tpl_vars['_successMessageConfigVar']]; ?>
.
        </div>
<?php endif; ?>