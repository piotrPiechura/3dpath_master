<?php /* Smarty version 2.6.20, created on 2015-12-13 22:26:24
         compiled from projectcmsedit.tpl */ ?>
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <div class="clearfix"></div>
    <div class="content">
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/cms_bradcrumbs.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div class="page-title"> <i class="icon-custom-left"></i>
        <h3>Form - <span class="semi-bold"><?php echo $this->_config[0]['vars']['subpageTitle']; ?>
</span></h3>
      </div>
	  <!-- BEGIN BASIC FORM ELEMENTS-->
        <div class="row">
            <div class="col-md-12">
                
        <?php echo $this->_tpl_vars['_form']->start($this->_tpl_vars['mainForm'],'mainForm',1); ?>
  
  
              <div class="grid simple">
                                <div class="grid-body no-border"> <br>
                  <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">Project Name</label>
                        <span class="help">e.g. "New Project"</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('projectName',null,'maxlength="255"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Location</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('projectLocation',null,'maxlength="255"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Description</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('projectDescription',null,'maxlength="255"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">System Datum Description</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('projectSystem',null,'maxlength="100"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Elevation</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('projectElevation',null,'maxlength="100"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        
                        <div class="controls">
                          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/cms_action_buttons_without_back_to_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php echo $this->_tpl_vars['_form']->end(); ?>
  
        </div>
	<!-- END BASIC FORM ELEMENTS-->	 
           

        
        
        
    </div>
    
    