<?php /* Smarty version 2.6.20, created on 2015-12-11 21:54:27
         compiled from adminlogin.tpl */ ?>
<div class="row login-container animated fadeInUp">  
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
		 <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10"> 
                     <h2 class="normal">Sign in to <br/>3D Path Designer</h2>
                          <div class="row form-row ">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/cms_messages.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php echo $this->_tpl_vars['_form']->start($this->_tpl_vars['loginForm'],'loginForm','class="animated fadeIn" style="display:none" '); ?>

                      <div class="col-sm-6">
                        <?php echo $this->_tpl_vars['_form']->field('adminName',null,'class="form-control" placeholder="Username" style="width:120px"'); ?>

                                              </div>
                      <div class="col-sm-6">
                         <?php echo $this->_tpl_vars['_form']->field('password',null,'class="form-control" placeholder="Password" style="width:135px;margin-left:-20px;"'); ?>
 
                                              </div>
                    </div>
                 
		  <button type="button" class="btn btn-primary btn-cons" id="login_toggle" onClick="document.getElementById('loginForm').submit();">Login</button> or&nbsp;&nbsp;<button type="button" class="btn btn-info btn-cons" id="register_toggle"> Create an account</button>
                  
        </div>
		<div class="tiles grey p-t-20 p-b-20 text-black">
			  
                    
				<div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
				  <div class="control-group  col-md-10">
					<div class="checkbox checkbox check-success"> <a href="#">Trouble login in?</a>&nbsp;&nbsp;
					  <input type="checkbox" id="checkbox1" value="1">
					  <label for="checkbox1">Keep me reminded </label>
					</div>
				  </div>
				  </div>
			  <?php echo $this->_tpl_vars['_form']->end(); ?>

			
		</div>   
      </div>   
