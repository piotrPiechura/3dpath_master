<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:48
         compiled from common/cms_menu_left.tpl */ ?>
<!-- BEGIN SIDEBAR MENU -->	
				<ul>	
			
			<!-- END SINGLE LINK --> 
                        <!-- BEGIN TWO LEVEL MENU -->
						<!-- END TWO LEVEL MENU -->	
			<!-- BEGIN ONE LEVEL MENU -->
						<!-- END ONE LEVEL MENU -->
			<!-- BEGIN SELECTED LINK -->
			<!--<li class="start <!--active->">
				<a href="project.html">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
					<span class="selected"></span>
					<span class="badge badge-important pull-right">5</span>
				</a>
                            
			</li>-->
			<!-- END SELECTED LINK -->
			<!-- BEGIN BADGE LINK -->
			<!--<li class="">
				<a href="#">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
					<span class="badge badge-disable pull-right">203</span>
				</a>
			</li>-->
			<!-- END BADGE LINK -->     
			<!-- BEGIN SINGLE LINK -->
			<!--<li class="">
				<a href="#">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
				</a>
			</li>-->
                        <?php if (! empty ( $this->_tpl_vars['company'] )): ?>
                        <?php $_from = $this->_tpl_vars['company']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_company']):
?>
                        <li class="">
				<a href="javascript:;">
					<i class="fa fa-gass-station"></i>
					<span class="title" onClick='window.open("<?php echo $this->_tpl_vars['url']->createHTML('_m','Company','_o','CMSEdit','id',$this->_tpl_vars['_company']['id']); ?>
","_self")'><?php echo $this->_tpl_vars['_company']['companyName']; ?>
</span>
					<span class="arrow <?php if (! empty ( $this->_tpl_vars['pagePath']['companyId'] ) && $this->_tpl_vars['pagePath']['companyId'] == $this->_tpl_vars['_company']['id']): ?>open<?php endif; ?>"></span>
                                            <ul class="sub-menu" <?php if (! empty ( $this->_tpl_vars['pagePath']['companyId'] ) && $this->_tpl_vars['pagePath']['companyId'] == $this->_tpl_vars['_company']['id']): ?>style="display: block;"<?php endif; ?>>
                                            <?php if (! empty ( $this->_tpl_vars['project'][$this->_tpl_vars['_company']['id']] )): ?>
                                            <?php $_from = $this->_tpl_vars['project'][$this->_tpl_vars['_company']['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_project']):
?>  
                                                <li>
                                                    <a href="javascript:;">
                                                    <i class="fa fa-gass-tower"></i>
                                                    <span class="title" onClick='window.open("<?php echo $this->_tpl_vars['url']->createHTML('_m','Project','_o','CMSEdit','id',$this->_tpl_vars['_project']['id']); ?>
","_self")'><?php if (! empty ( $this->_tpl_vars['pagePath']['siteId'] ) && $this->_tpl_vars['pagePath']['projectId'] == $this->_tpl_vars['_project']['id']): ?><b><?php endif; ?><?php echo $this->_tpl_vars['_project']['projectName']; ?>
<?php if (! empty ( $this->_tpl_vars['pagePath']['projectId'] ) && $this->_tpl_vars['pagePath']['projectId'] == $this->_tpl_vars['_project']['id']): ?></b><?php endif; ?></span>
                                                    <span class="arrow <?php if (! empty ( $this->_tpl_vars['pagePath']['projectId'] ) && $this->_tpl_vars['pagePath']['projectId'] == $this->_tpl_vars['_project']['id']): ?>open<?php endif; ?>"></span></a>
                                                    <ul class="sub-menu" <?php if (! empty ( $this->_tpl_vars['pagePath']['projectId'] ) && $this->_tpl_vars['pagePath']['projectId'] == $this->_tpl_vars['_project']['id']): ?>style="display: block;"<?php endif; ?>>
                                                        <?php if (! empty ( $this->_tpl_vars['site'][$this->_tpl_vars['_project']['id']] )): ?>
                                                        <?php $_from = $this->_tpl_vars['site'][$this->_tpl_vars['_project']['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_site']):
?>
                                                        <li>
                                                            <a href="javascript:;">
                                                                <i class="fa fa-gass-tower-fire"></i>
                                                                <span class="title" onClick='window.open("<?php echo $this->_tpl_vars['url']->createHTML('_m','Site','_o','CMSEdit','id',$this->_tpl_vars['_site']['id']); ?>
","_self")'><?php if (! empty ( $this->_tpl_vars['pagePath']['siteId'] ) && $this->_tpl_vars['pagePath']['siteId'] == $this->_tpl_vars['_site']['id']): ?><b><?php endif; ?><?php echo $this->_tpl_vars['_site']['siteName']; ?>
<?php if (! empty ( $this->_tpl_vars['pagePath']['siteId'] ) && $this->_tpl_vars['pagePath']['siteId'] == $this->_tpl_vars['_site']['id']): ?></b><?php endif; ?></span>
                                                                <span class="arrow <?php if (! empty ( $this->_tpl_vars['pagePath']['siteId'] ) && $this->_tpl_vars['pagePath']['siteId'] == $this->_tpl_vars['_site']['id']): ?>open<?php endif; ?>"></span></a> 
                                                                <ul class="sub-menu" <?php if (! empty ( $this->_tpl_vars['pagePath']['siteId'] ) && $this->_tpl_vars['pagePath']['siteId'] == $this->_tpl_vars['_site']['id']): ?>style="display: block;"<?php endif; ?>>
                                                                            <?php if (! empty ( $this->_tpl_vars['well'][$this->_tpl_vars['_site']['id']] )): ?>
                                                                                <?php $_from = $this->_tpl_vars['well'][$this->_tpl_vars['_site']['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_well']):
?>
                                                                                    <li><a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Well','_o','CMSEdit','id',$this->_tpl_vars['_well']['id']); ?>
"><i class="fa fa-valve"></i><?php if (! empty ( $this->_tpl_vars['pagePath']['wellId'] ) && $this->_tpl_vars['pagePath']['wellId'] == $this->_tpl_vars['_well']['id']): ?><b><?php endif; ?><?php echo $this->_tpl_vars['_well']['wellName']; ?>
<?php if (! empty ( $this->_tpl_vars['pagePath']['wellId'] ) && $this->_tpl_vars['pagePath']['wellId'] == $this->_tpl_vars['_well']['id']): ?></b><?php endif; ?></a></li>
                                                                                <?php endforeach; endif; unset($_from); ?>    
                                                                                
                                                                            <?php endif; ?>    
                                                                            <li><a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Well','_o','CMSEdit','site',$this->_tpl_vars['_site']['id']); ?>
">
                                                                                 <i class="fa fa-plus-circle"></i>Add Well</a></li>
                                                                </ul> 
                                                        </li>
                                                        <?php endforeach; endif; unset($_from); ?>
                                                        <?php endif; ?>
                                                        
                                                        <li>
                                                            <a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Site','_o','CMSEdit','proj',$this->_tpl_vars['_project']['id']); ?>
">
                                                            <i class="fa fa-plus-circle"></i>
                                                            <span class="title">Add new site</span>
                                                            </a>
                                                        </li>
                                                        
                                                    </ul>
                                                </li>
                                            <?php endforeach; endif; unset($_from); ?>    
                                               
                                            <?php endif; ?>
                                            <li>
                                                    <a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Project','_o','CMSEdit','comp',$this->_tpl_vars['_company']['id']); ?>
">
                                                        <i class="fa fa-plus-circle"></i>
                                                        <span class="title">Add new project</span>
                                                    </a>
                                            </li>
                                            </ul>
                                
			</li>    
                            
                        
                        <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>
                        <li class="">
				<a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Company','_o','CMSEdit'); ?>
">
					<i class="fa fa-plus-circle"></i>
					<span class="title">Add new company</span>
					
				</a>
			</li>
		</ul>
		<!-- END SIDEBAR MENU -->
		<!-- BEGIN SIDEBAR WIDGETS -->
				<div class="clearfix"></div>
		<!-- END SIDEBAR WIDGETS -->