<?php /* Smarty version 2.6.20, created on 2015-12-13 22:21:13
         compiled from companycmsedit.tpl */ ?>
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
         
                <ul class="nav nav-tabs" id="tab-01">
            <li class="active"><a href="#tab1hellowWorld">General</a></li>
            <li><a href="#tab1FollowUs">Calc defaults</a></li>
                       
            <li style='margin-left:50px;margin-top:5px;'><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/cms_action_buttons_without_back_to_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li>
          </ul>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1hellowWorld">
              <div class="row column-seperation">
               
                 <div class="col-md-12"> 
                  <div class="grid-body no-border"> <br>
                  <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <span class="help">e.g. "Polskie Górnictwo Naftowe i Gazownictwo SA"</span>
                        <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('companyName',null,'maxlength="40"',"form-control"); ?>

                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Division</label>
                        <span class="help">e.g. "Marketing"</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('companyDivision',null,'maxlength="100"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Address </label>
                        <span class="help">e.g. "Kasprzaka 25, 01-224 Warszawa"</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('companyAddress',null,'maxlength="255"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Phone</label>
                        <span class="help">e.g. "+48 22 589 45 55"</span>
                        <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('companyPhone',null,'maxlength="50"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Email</label>
                        <span class="help">e.g. "some@example.com"</span>
                        <div class="controls">
                         <?php echo $this->_tpl_vars['_form']->field('comapanyEmail',null,'maxlength="100"',"form-control"); ?>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                 </div> 
               
              </div>
            </div>
            <div class="tab-pane" id="tab1FollowUs">
              <div class="row">
                <div class="col-md-12">
                                  </div>
              </div>
            </div>
            <div class="tab-pane" id="tab1Inspire">
              <div class="row">
                <div class="col-md-12">
                  <h3>Follow us & get updated!</h3>
                  <p>Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news.</p>
                  <br>
                  <p><a href="#" class="btn-social"><i class="icon-facebook"></i></a> <a href="#" class="btn-social"><i class="icon-twitter"></i> </a> <a href="#" class="btn-social"><i class="icon-dribbble"></i></a> <a href="#" class="btn-social"><i class="icon-pinterest-sign"></i></a> <a href="#" class="btn-social"><i class="icon-tumblr"></i> </a> <a href="#" class="btn-social"><i class="icon-linkedin-sign"></i> </a> </p>
                </div>
              </div>
            </div>
          </div>
                
                
                         <?php echo $this->_tpl_vars['_form']->end(); ?>
  
        </div>
	<!-- END BASIC FORM ELEMENTS-->	 
           
               
        
        
    </div>
    
    