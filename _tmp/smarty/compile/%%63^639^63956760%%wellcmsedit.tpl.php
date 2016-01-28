<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from wellcmsedit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', 'wellcmsedit.tpl', 743, false),array('modifier', 'number_format', 'wellcmsedit.tpl', 748, false),)), $this); ?>
<script src="assets/js/3dpath.js" type="text/javascript"></script>
<?php if (! empty ( $this->_tpl_vars['defaultVsection'] )): ?>
<script>
    var defaultVsection = <?php echo $this->_tpl_vars['defaultVsection']; ?>
;
</script>
<?php endif; ?> 
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
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/cms_messages.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="row">
            <div class="col-md-6">
                
        <?php echo $this->_tpl_vars['_form']->start($this->_tpl_vars['mainForm'],'mainForm',1); ?>
         
                <ul class="nav nav-tabs" id="tab-01">
            <li  id="atrtab1General" class="active"><a href="#tab1General">General</a></li>
            <li  id="atrtab1Location"><a href="#tab1Location">Location</a></li>
            <li  id="atrtab1Depth"><a href="#tab1Depth">Inputs</a></li>
                                   
            <li style='margin-left:50px;margin-top:5px;'><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/cms_action_buttons_without_back_to_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li>
          </ul>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1General">
              <div class="row column-seperation">
               
                 <div class="col-md-12"> 
                  <div class="grid-body no-border"> <br>
                  <div class="row">
                    <div class="col-md-12 col-sm-8 col-xs-12">
                      <div class="form-group">
                        <label class="form-label"><?php echo $this->_config[0]['vars']['wellNameFieldCaption']; ?>
</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('wellName',null,'maxlength="40"',"form-control"); ?>

                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Description</label>
                                              <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('wellDescription',null,'maxlength="100"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">U.W.I.</label>
                                                <div class="controls">
                           <?php echo $this->_tpl_vars['_form']->field('wellUWI',null,'maxlength="255"',"form-control"); ?>

                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">API Number</label>
                                                 <div class="controls">
                          <?php echo $this->_tpl_vars['_form']->field('wellAPI',null,'maxlength="50"',"form-control"); ?>

                        </div>
                      </div>
                       <div class="input-group demo2">
                            <a id="cp4" class="btn btn-primary form-colorpicker" data-colorpicker-guid="1" href="#" data-color-format="hex" data-color="rgb(255, 255, 255)" <?php if (! empty ( $this->_tpl_vars['wellColor'] )): ?>style="background-color:<?php echo $this->_tpl_vars['wellColor']; ?>
"<?php endif; ?>>Well color</a>
                        </div>
                    </div>
                  </div>
                </div>
                 </div> 
               
              </div>
            </div>
            <div class="tab-pane" id="tab1Location">
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Slot</label>
                                               <div class="controls">
                         <?php echo $this->_tpl_vars['_form']->field('wellSlot',null,'maxlength="100"',"form-control"); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tedit</label>
                                                 <div class="controls">
                         <?php echo $this->_tpl_vars['_form']->field('wellTedit',null,'maxlength="100"',"form-control"); ?>

                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab1Depth">
              <div class="row">
                 <div class="col-md-12">
                 
                            <div class="form-group">
                                   <label class="form-label">Type</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('wellType'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group trajectory3D">
                                   <label class="form-label">Trajectory counting method</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('well3DTrajectory',null,'data-content="" title="" data-toggle="popover" data-original-title=""'); ?>

                                   </div>
                           </div>
                           
                           <div class="form-group trajectory2D">
                                   <label class="form-label">Trajectory</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('wellTrajectory',null,'data-content="" title="" data-toggle="popover" data-original-title=""'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group trajectory2D">
                                   <label class="form-label">Trajectory Variant</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                       <select id="grp_variant_j1" class="grpVariants">
                                           <option name="j1_A_H_L1">A, H, L<sub>1</sub></option>
                                           <option name="j1_H_alfa2_DLS2">H, &alpha;<sub>2</sub>, DLS2 </option>
                                           <option name="j1_A_H_DLS2">A, H, DLS2</option>
                                           <option name="j1_A_H_alfa2">A, H, &alpha;2</option>
                                           <option name="j1_H_L1_alfa2">H, L1, &alpha;2</option>
                                       </select>
                                       
                                       <select id="grp_variant_j2" class="grpVariants">
                                           <option name="j2_A_H_L1_DLS">A, H, L1, DLS</option>
                                           <option name="j2_A_H_alfa2_DLS2">A, H, &alpha;<sub>2</sub>, DLS2</option>
                                           <option name="j2_A_H_L3_alfa2">A, H, L3, &alpha;<sub>2</sub></option>
                                           <option name="j2_H_L1_L3_alfa2">H, L1, L3, &alpha;<sub>2</sub></option>
                                           <option name="j2_A_H_L1_L3">A, H, L1, L3</option>
                                       </select>
                                       
                                       <select id="grp_variant_j3" class="grpVariants">
                                           <option name="j3_A_H_L1_alfa2_alfa4_DLS2_DLS4">A, H, L1, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                           <option name="j3_A_H_L3_alfa2_alfa4_DLS2_DLS4">A, H, L3, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                           <option name="j3_A_H_L5_alfa2_alfa4_DLS2_DLS4">A, H, L5, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                           <option name="j3_H_L1_L3_L5_alfa2_alfa4_DLS4">H, L1, L3, L5, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS4</option>
                                           <option name="j3_H_L1_L3_L5_alfa2_alfa4_DLS2">H, L1, L3, L5, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2</option>
                                       </select>
                                       
                                                                             
                                       <select id="grp_variant_s1" class="grpVariants">
                                            <option name="s1_A_A2_H_DLS2_DLS3">H, A2, H, DLS2, DLS3 </option>
                                            <option name="s1_A_A2_H_H2_alfa3">A, A2, H, H2, &alpha;<sub>3</sub></option>
                                            <option name="s1_A_H_alfa2_alfa3_DLS2">A, H, &alpha;<sub>2</sub>, &alpha;<sub>3</sub>, DLS2</option>
                                            <option name="s1_H_L2_alfa3_DLS2_DLS3">H, L1, &alpha;<sub>3</sub>, DLS2, DLS3</option>
                                            <option name="s1_A_H_alfa3_DLS2_DLS3">A, H, &alpha;<sub>3</sub>, DLS2, DLS3</option>
                                           
                                       </select>
                                       
                                       <select id="grp_variant_s2" class="grpVariants">
                                            <option name="s2_A_H_L4_alfa4_DLS2_DLS3">A, H, L4, &alpha;<sub>4</sub>, DLS2, DLS3 </option>
                                            <option name="s2_A_A2_H_H2_alfa4_DLS3">A, A2, H, H2, &alpha;<sub>4</sub>, DLS3 </option>
                                            <option name="s2_A_H_alfa2_alfa4_DLS2_DLS3">A, H, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS3 </option>
                                            <option name="s2_H_L1_L4_alfa4_DLS2_DLS3">H, L1, L4, &alpha;<sub>4</sub>, DLS2, DLS3 </option>
                                            <option name="s2_L1_L4_alfa2_alfa4_DLS2_DLS3">L1, L4 &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS3 </option>
      
                                       </select>
                                       
                                       <select id="grp_variant_s3" class="grpVariants">
                                            <option name="s3_A_H_L1_L3_alfa4_DLS4">A, H, L1, L3 &alpha;<sub>4</sub>, DLS3 </option>
                                            <option name="s3_A_H_L1_L3_alfa4_DLS2">A, H, L1, L3 &alpha;<sub>4</sub>, DLS2 </option>
                                            <option name="s3_A_H_alfa2_alfa4_DLS2_DLS4">A, H, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                            <option name="s3_H_L1_alfa2_alfa4_DLS2_DLS4">H, L1, &alpha;<sub>2</sub>, &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                            <option name="s3_A_H_L1_alfa4_DLS2_DLS4">A, H, L1,  &alpha;<sub>4</sub>, DLS2, DLS4</option>
                                       </select>
                                       
                                       <select id="grp_variant_s4" class="grpVariants">
                                           <option name="s4_H_alfa2_L1_L3">H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                           <option name="s4_H_alfa2_L1_L3">H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                           <option name="s4_H_alfa2_L1_L3">H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                       </select>
                                       
                                       <select id="grp_variant_catenary" class="grpVariants">
                                           <option name="catenary_H_A2_L1_L3_Q_ro">H, A<sub>2</sub>,L<sub>1</sub> , L<sub>3</sub>, Q, &rho; </option>
                                       </select>
                                       
                                       
                                    <?php echo $this->_tpl_vars['_form']->field('wellTrajectoryVariant'); ?>

                                   </div>
                           </div>       
                                   
                            <div class="form-group varField" id="grp_A">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['AFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('A'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_A1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['A1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('A1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['A2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('A2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['A3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('A3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['A4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('A4'); ?>

                                   </div>
                           </div>
                            
                            <div class="form-group varField" id="grp_H">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['HFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('H'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_H1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['H1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('H1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['H2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('H2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['H3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('H3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['H4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('H4'); ?>

                                   </div>
                           </div>
                              
                            <div class="form-group varField" id="grp_L">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['LFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_L1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L4'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_L5">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L5FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('L5'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_R">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['RFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('R'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_R1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['R1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('R1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['R2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('R2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['R3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('R3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['R4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('R4'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_alfa">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfaFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('alfa'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_alfa1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfa1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('alfa1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfa2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('alfa2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfa3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('alfa3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfa4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('alfa4'); ?>

                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_delta">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['deltaFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('delta'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_delta1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['delta1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('delta1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['delta2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('delta2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['delta3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('delta3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['delta4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('delta4'); ?>

                                   </div>
                           </div>
                                 
                            <div class="form-group varField" id="grp_DLS">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLSFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('DLS'); ?>

                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_DLS1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLS1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('DLS1'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLS2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('DLS2'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLS3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('DLS3'); ?>

                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS4">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLS4FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('DLS4'); ?>

                                   </div>
                           </div>   
                            <div class="form-group varField" id="grp_Q">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['QFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('Q'); ?>

                                   </div>
                           </div>  
                            <div class="form-group varField" id="grp_ro">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['roFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('ro'); ?>

                                   </div>
                           </div>  
                            <div class="form-group trajectory2D" id="grp_azimuth">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['azimuthFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <?php echo $this->_tpl_vars['_form']->field('azimuth'); ?>

                                   </div>
                           </div>  
                                   
                           
                            <div class="trajectory3D" style="margin-bottom: 15px">
                                <table id="tblAppendGrid">
                                </table>
                                <div style="visibility:hidden;display:none"><?php echo $this->_tpl_vars['_form']->field('well3DPints'); ?>
</div>
                            </div>           
                            
                            <table class="table table-bordered no-more-tables">
				<thead>
                                    <tr>
					<th style="width:25%" class="text-center"><?php echo $this->_config[0]['vars']['tvectorXFieldCaption']; ?>
</th>
					<th style="width:25%" class="text-center"><?php echo $this->_config[0]['vars']['tvectorYFieldCaption']; ?>
</th>
					<th style="width:25%" class="text-center"><?php echo $this->_config[0]['vars']['tvectorZFieldCaption']; ?>
</th>
                                        <th style="width:25%" class="text-center">Vertical section</th>
                                    </tr>
				</thead>
				<tbody>
                                    <tr class="">
                                        <td class="text-center"  style="width:25%"><?php echo $this->_tpl_vars['_form']->field('tvectorX',null,"style='width:100%'"); ?>
</td>
                                        <td class="text-center"  style="width:25%"><?php echo $this->_tpl_vars['_form']->field('tvectorY',null,"style='width:100%'"); ?>
</td>
                                        <td class="text-center"  style="width:25%"><?php echo $this->_tpl_vars['_form']->field('tvectorZ',null,"style='width:100%'"); ?>
</td>
                                        <td class="text-center"  style="width:25%"><?php echo $this->_tpl_vars['_form']->field('vsection',null,"style='width:50%'"); ?>
<button class="submit btn btn-cons" style="margin-left:5px" id="vsectionDef">Default</button></td>
                                    </tr>
				</tbody>
			</table>       
                           
                           
                    </div>
                   
                                   
                                   
              </div>
                                  
                                   
                                  
            </div>
            <div class="tab-pane" id="tab1Pads">
              <div class="row">
                  <div class="col-md-12">
                      
                      Pads Imputs
                      
                  </div>
              </div>
            </div>    
          </div>
                
                
                         <?php echo $this->_tpl_vars['_form']->end(); ?>
  
        </div>
	<!-- END BASIC FORM ELEMENTS-->	 
           
             
      
          <script>
              <?php if (( empty ( $this->_tpl_vars['recordId'] ) )): ?>
              var emptyWell = 1;
              <?php else: ?>
              var emptyWell = 0;    
              <?php endif; ?>  
          </script>
      
            
        <?php if (! empty ( $this->_tpl_vars['trajectoryParamsA'] )): ?>
        <div class="col-md-6 sortable">
            <div class="grid simple ">
                <div class="grid-title no-border">
                    <h4>Well <span class="semi-bold">Data</span></h4>
                    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body no-border">
                    <div class="scroller" data-height="320px">
                        <div class="row-fluid">
                            <div>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>A[m]</th>
                                        <th>H[m]</th>
                                        <th>L[m]</th>
                                        <th>R[m]</th>
                                        <th>&alpha;[<sup>o</sup>]</th>
                                        <th>&delta;[<sup>o</sup>]</th>
                                        <th>DLS [<sup>o</sup>/100ft]</th>
                                    </tr>
                                    <?php $this->assign('secNo', ((is_array($_tmp=$this->_tpl_vars['sectionNumber'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp))); ?>
                                <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)1;
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['secNo']+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
                                    <tr>
                                        <?php $this->assign('index', $this->_sections['foo']['index']); ?>
                                        <td><?php echo $this->_tpl_vars['index']; ?>
</td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsA'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsA'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsH'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsH'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsL'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsL'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsR'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsR'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsAlfa'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsAlfa'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsDelta'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsDelta'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                        <td><?php if (! empty ( $this->_tpl_vars['trajectoryParamsDLS'][$this->_tpl_vars['index']] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsDLS'][$this->_tpl_vars['index']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php endif; ?></td>
                                    </tr>
                                <?php endfor; endif; ?>
                                    <tr>
                                        <td>&Sigma;</td>
                                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsA']['sum'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsH']['sum'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['trajectoryParamsL']['sum'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>    
                                </table>
                                                                               </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
     
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/cms_3drender.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
    <?php if (! empty ( $this->_tpl_vars['trajectoryTable'] )): ?>
    <div class="row"> 
        <div class="col-md-12 sortable">
            <div class="grid simple vertical green">
                <div class="grid-title no-border">
                    <h4>Trajectory <span class="semi-bold">data</span></h4>
                    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body no-border">
                    <div class="scroller" data-height="auto" >
                        <div class="row-fluid">
                            <div class="span12">
                                             
                                <table class="table" id="example3" >
                                    <thead>
                                        <tr>
                                            <th>MD <span style="text-transform: lowercase;">[m]</span></th>
                                            <th>N<span style="text-transform: lowercase;">orth [m]</span></th>
                                            <th>E<span style="text-transform: lowercase;">ast[m]</span></th>
                                            <th>TVD <span style="text-transform: lowercase;">[m]</span></th>
                                            <th>I<span style="text-transform: lowercase;">nclination [o]</span></th>
                                            <th>A<span style="text-transform: lowercase;">zimuth [o]</span></th>
                                            <th>DLS <span style="text-transform: lowercase;">[o/100ft]</span></th>
                                            <th>C. D<span style="text-transform: lowercase;">epart. [m]</span></th>
                                            <th>C. A<span style="text-transform: lowercase;">zimuth [o]</span></th>
                                            <th>V. S<span style="text-transform: lowercase;">ection [m]</span></th>
                                                                                                                                    <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php $_from = $this->_tpl_vars['trajectoryTable']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['dataTable'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['dataTable']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_row']):
        $this->_foreach['dataTable']['iteration']++;
?>
                                        <?php if ($this->_foreach['dataTable']['iteration'] == 1): ?><?php $this->assign('_section', $this->_tpl_vars['_row']['section']); ?><?php endif; ?>
                                        <tr class="<?php if ($this->_foreach['dataTable']['iteration']%2): ?>odd<?php else: ?>even<?php endif; ?> gradeX" <?php if ($this->_tpl_vars['_row']['section'] == 'PP' || ( $this->_tpl_vars['_section'] != $this->_tpl_vars['_row']['section'] && $this->_tpl_vars['_section'] != 'PP' ) || ($this->_foreach['dataTable']['iteration'] == $this->_foreach['dataTable']['total']) || ($this->_foreach['dataTable']['iteration'] <= 1)): ?>style='background-color: #aaa' <?php endif; ?>>
                                            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['MD'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                            
                                                                                        <td class="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['Y'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['X'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['Z'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
</td>
                                             <td class="center"><?php if (is_numeric ( $this->_tpl_vars['_row']['alfa'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['alfa'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00 <?php echo $this->_tpl_vars['_row']['alfa']; ?>
<?php endif; ?></td>
                                            <td class="center"><?php if (is_numeric ( $this->_tpl_vars['_row']['beta'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['beta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00 <?php echo $this->_tpl_vars['_row']['beta']; ?>
<?php endif; ?></td>
                                            <td><?php if (! empty ( $this->_tpl_vars['_row']['DLS'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['DLS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00<?php endif; ?></td>
                                            <td><?php if (! empty ( $this->_tpl_vars['_row']['CL_DEP'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['CL_DEP'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00<?php endif; ?></td>
                                            <td><?php if (! empty ( $this->_tpl_vars['_row']['CL_Angle'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['CL_Angle'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00<?php endif; ?></td>
                                            <td><?php if (! empty ( $this->_tpl_vars['_row']['V_SECTION'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['V_SECTION'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", "") : number_format($_tmp, 2, ",", "")); ?>
<?php else: ?>0.00<?php endif; ?></td>
                                            <td></td>
                                                                                                                                     <td class="center"></td>
                                        </tr>
                                        <?php $this->assign('_section', $this->_tpl_vars['_row']['section']); ?>
                                <?php endforeach; endif; unset($_from); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        var points3d = null;
    <?php if (! empty ( $this->_tpl_vars['points3d'] )): ?>
     points3d = [
        <?php $_from = $this->_tpl_vars['points3d']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_point']):
?>
            <?php echo '{'; ?>
 X: '<?php echo $this->_tpl_vars['_point']['X']; ?>
', Y: '<?php echo $this->_tpl_vars['_point']['Y']; ?>
', Z: '<?php echo $this->_tpl_vars['_point']['Z']; ?>
', LP: '<?php echo $this->_tpl_vars['_point']['LP']; ?>
', alfa: '<?php echo $this->_tpl_vars['_point']['alfa']; ?>
', beta: '<?php echo $this->_tpl_vars['_point']['beta']; ?>
' <?php echo '}'; ?>
,
        <?php endforeach; endif; unset($_from); ?>
            ];
    <?php endif; ?>
    </script>
    
    <div style="visibility:hidden; display:none">
        <img src="assets/img/paths/3drill_S1.gif" />
        <img src="assets/img/paths/3drill_S2.gif" />
        <img src="assets/img/paths/3drill_S3.gif" />
        <img src="assets/img/paths/3drill_S4.gif" />
        <img src="assets/img/paths/3drill_J1.gif" />
        <img src="assets/img/paths/3drill_J2.gif" />
        <img src="assets/img/paths/3drill_J3.gif" />
    </div> 