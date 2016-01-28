<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from common/cms_bradcrumbs.tpl */ ?>
    <ul class="breadcrumb">
        <li>
          <p>YOU ARE HERE</p>
        </li>
        <?php $_from = $this->_tpl_vars['pagePath']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['pagePath'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pagePath']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_menuItem']):
        $this->_foreach['pagePath']['iteration']++;
?>
        <?php if ($this->_foreach['pagePath']['iteration'] % 2 == 0): ?>
        <li><a href="#" <?php if (($this->_foreach['pagePath']['iteration'] == $this->_foreach['pagePath']['total'])): ?>class="active"<?php endif; ?>><?php if ($this->_foreach['pagePath']['iteration'] == 2): ?><?php echo $this->_tpl_vars['pagePath']['companyName']; ?>
<?php endif; ?><?php if ($this->_foreach['pagePath']['iteration'] == 4): ?><?php echo $this->_tpl_vars['pagePath']['projectName']; ?>
<?php endif; ?><?php if ($this->_foreach['pagePath']['iteration'] == 6): ?><?php echo $this->_tpl_vars['pagePath']['siteName']; ?>
<?php endif; ?><?php if ($this->_foreach['pagePath']['iteration'] == 8): ?><?php echo $this->_tpl_vars['pagePath']['wellName']; ?>
<?php endif; ?></a> </li>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    </ul>