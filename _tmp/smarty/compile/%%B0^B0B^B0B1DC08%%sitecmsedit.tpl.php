<?php /* Smarty version 2.6.20, created on 2015-12-13 22:26:48
         compiled from sitecmsedit.tpl */ ?>
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3></h3>
      </div>
      <div class="modal-body">  </div>
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
        <div class="row">
        <div class="col-md-12">
        <?php echo $this->_tpl_vars['_form']->start($this->_tpl_vars['mainForm'],'mainForm',1); ?>
  
          <div class="grid simple">
            <div class="grid-title no-border">
              <h4></h4>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
            </div>
            <div class="grid-body no-border">
				
              <div class="row column-seperation">
                <div class="col-md-6">
                  <h4>General Information</h4>  
                  
                    <div class="form-group">
                        <label class="form-label">Site Name</label>
                        <span class="help">e.g. "First Site"</span>
                        <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteName',null,'maxlength="255"',"form-control"); ?>

                          
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="form-label">District</label>
                      
                        <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteDistrict',null,'maxlength="255"',"form-control"); ?>

                          
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="form-label">Block</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteBlock',null,'maxlength="255"',"form-control"); ?>

                          
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="form-label">Default Site Elevation</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteElevation',null,'maxlength="11"',"form-control"); ?>

                          
                        </div>
                     </div>
                        
                   
                </div>
                <div class="col-md-6">
				
                  <h4>Location Information</h4>
                    <div class="form-group">
                        <label class="form-label">Center Location</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteLocation',null,'maxlength="11"',"form-control"); ?>

                          
                        </div>
                     </div>
                    <div class="form-group">
                        <label class="form-label">Tedit Northing</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteTeditNorthing',null,'maxlength="11"',"form-control"); ?>

                          
                        </div>
                     </div>
                    <div class="form-group">
                        <label class="form-label">Tedit Easting</label>
                                                <div class="controls">
                            <?php echo $this->_tpl_vars['_form']->field('siteTeditEasting',null,'maxlength="11"',"form-control"); ?>

                          
                        </div>
                     </div>
                </div>
              </div>
				<div class="form-actions">
					<div class="pull-left">
					  					</div>
					<div class="pull-right">
					  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/cms_action_buttons_without_back_to_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
				  </div>
		
            </div>
          </div>
          <?php echo $this->_tpl_vars['_form']->end(); ?>
 
        </div>
      </div>
      <?php if (! empty ( $this->_tpl_vars['recordId'] )): ?>
       <div class="row"> 
        <div class="col-md-12 nocsroll">
           <div class="grid simple">
                <div class="grid-title">
                
                    <h4>Create  <span class="semi-bold"> Pads</span></h4>
                    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body">
                    <div class="row column">
                    <form id="slotForm" method="post" action="" enctype="multipart/form-data">
                        <div class="col-md-3">
                    
                   
                   
                                    <div class="form-group" id="grp_Name">
                                        <label class="form-label"><?php echo $this->_config[0]['vars']['NameFieldCaption']; ?>
</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                         <input id="slotForm_Name" class="text " type="text" value="" name="Name">
                                        </div>
                                    </div>       
                       
                       
                   <div class="form-group">
                                   <label class="form-label">Trajectory Variant</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                <select id="grp_variant_j2ajax" class="grpVariants">
                                     <option value="j2_A_H_L1_DLS">A, H, L1, DLS</option>
                                     <option value="j2_A_H_alfa2_DLS2">A, H, &alpha;<sub>2</sub>, DLS2</option>
                                     <option value="j2_A_H_L3_alfa2">A, H, L3, &alpha;<sub>2</sub></option>
                                     <option value="j2_H_L1_L3_alfa2">H, L1, L3, &alpha;<sub>2</sub></option>
                                     <option value="j2_A_H_L1_L3">A, H, L1, L3</option>
                                 </select>
                                </div>
                   </div> 
                            <div class="form-group varField" id="grp_A">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['AFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <input id="slotForm_A" class="text " type="text" value="" name="A">
                                   </div>
                            </div>    
                          
                            
                            <div class="form-group varField" id="grp_H">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['HFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <input id="slotForm_H" class="text " type="text" value="" name="H">
                                   </div>
                            </div>    
                            
                              
                            
                            <div class="form-group varField" id="grp_L1">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L1FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <input id="slotForm_L1" class="text " type="text" value="" name="L1">
                                   </div>
                            </div>
                            
                            <div class="form-group varField" id="grp_L3">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['L3FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    <input id="slotForm_L3" class="text " type="text" value="" name="L3">
                                   </div>
                            </div>
                           
                            <div class="form-group varField" id="grp_alfa2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['alfa2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                   <input id="slotForm_alfa2" class="text " type="text" value="" name="alfa2">
                                   </div>
                            </div>
                            
                                 
                            <div class="form-group varField" id="grp_DLS">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLSFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                   <input id="slotForm_DLS" class="text " type="text" value="" name="DLS">
                                   </div>
                            </div>  
                            <div class="form-group varField" id="grp_DLS2">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['DLS2FieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                   <input id="slotForm_DLS2" class="text " type="text" value="" name="DLS2">
                                   </div>
                            </div>        
                             <div class="form-group" id="grp_targetInclination">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['targetInclinationFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                   <input id="slotForm_targetInclination" class="text " type="text" value="90" name="targetInclination">
                                   </div>
                            </div>
                            <div class="form-group" id="grp_targetAzimuth">
                                   <label class="form-label"><?php echo $this->_config[0]['vars']['targetAzimuthFieldCaption']; ?>
</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                   <input id="slotForm_targetAzimuth" class="text " type="text" value="" name="targetAzimuth">
                                   </div>
                            </div> 
                                   
                            
                            <table class="table table-bordered no-more-tables">
				<thead>
                                    <tr>
					<th style="width:12%" class="text-center"><?php echo $this->_config[0]['vars']['deltaXFieldCaption']; ?>
</th>
					<th style="width:22%" class="text-center"><?php echo $this->_config[0]['vars']['deltaYFieldCaption']; ?>
</th>
					<th style="width:6%" class="text-center"><?php echo $this->_config[0]['vars']['deltaZFieldCaption']; ?>
</th>
                                    </tr>
				</thead>
				<tbody>
                                    <tr class="">
                                        <td class="text-center"><input id="slotForm_DeltaX" class="text " type="text" value="" name="deltaX"></td>
                                        <td class="text-center"><input id="slotForm_DeltaY" class="text " type="text" value="" name="deltaY"></td>
                                        <td class="text-center"><input id="slotForm_DeltaZ" class="text " type="text" value="" name="deltaZ"></td>									
                                    
                                    </tr>
				</tbody>
			</table>     
                   
                    </div>
                                   <div class="col-md-3">
                                    <div class="form-group" id="grp_SR">   
                                        <table class="slotDirections">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['SideFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <tr><td><input id="slotForm_TL" class="text " type="checkbox" value="" name="TopLeft"></td><td><input id="slotForm_TR" class="text " type="checkbox" value="" checked name="TopRight"></td></tr>
                                            <tr><td><input id="slotForm_BL" class="text " type="checkbox" value="" name="BottomLeft"></td><td><input id="slotForm_BR" class="text " type="checkbox" value="" name="BottomRight"></td></tr>
                                        </table>
                                    </div>  
                                      <div class="form-group" id="grp_Azimuth">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['AzimuthFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <div class="controls">
                                            <input id="slotForm_Azimuth" class="text " type="text" value="" name="Azimuth">
                                            </div>
                                    </div>        
                                        <div class="form-group" id="grp_SR">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['SRFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <div class="controls">
                                            <input id="slotForm_SR" class="text " type="text" value="" name="SR">
                                            </div>
                                    </div>
                                    <div class="form-group" id="grp_SR">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['STFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <div class="controls">
                                            <input id="slotForm_ST" class="text " type="text" value="" name="ST">
                                            </div>
                                    </div>
                                    <div class="form-group" id="grp_SM">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['SMFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <div class="controls">
                                            <input id="slotForm_SM" class="text " type="text" value="" name="SM">
                                            </div>
                                        </div>     
                                    <div class="form-group" id="grp_NOS">
                                            <label class="form-label"><?php echo $this->_config[0]['vars']['NOSFieldCaption']; ?>
</label>
                                            <span class="help"></span>
                                            <div class="controls">
                                                <input id="slotForm_NOS" class="text " type="text" value="" name="NOS">
                                            </div>  
                                    </div>  
                                    
                                       
                                       <div class="pull-right">
					  <div class="adminAction adminActionWithSave">
                                            <input id="slotSubmit" class="Save submit btn btn-cons" type="submit" value="Create" name="_action[Create]">

                                            </div>
                                    </div>    
                                   
                                </div>
                    </form>
                </div> 
            </div>    
        </div> 
       </div> 
       <script>
        <?php echo '
            $(document).ready(function(){
               emptyWell = 1;
               points3d = null;
               
               function showFormFieldsAjax(trajectoryType){
                    var fields = trajectoryType.split(\'_\');
                    console.log(fields);
                    fields.shift();
                    $.each(fields, function(i,val){
                        $("#grp_" + val).show(); 
                    });
                } 
                
               function hideFields(){
                    $("#grp_A").hide();
                    $("#grp_H").hide();
                    $("#grp_L1").hide();
                    $("#grp_DLS").hide();
                    $("#grp_DLS2").hide();
                    $("#grp_alfa2").hide();
                    $("#grp_L3").hide();
                    
                    $("#slotForm_A").val("");
                    $("#slotForm_H").val("");
                    $("#slotForm_L1").val("");
                    $("#slotForm_DLS").val("");
                    $("#slotForm_DLS2").val("");
                    $("#slotForm_alfa2").val("");
                    $("#slotForm_L3").val("");
               }

                
               $(\'#grp_variant_j2ajax\').show();
                var trajectoryVariant = $("#grp_variant_j2ajax").val();
                 showFormFieldsAjax(trajectoryVariant);
               
               
               $("#grp_variant_j2ajax").on(\'change\', function(){
                   hideFields();
                    var trajectoryVariant = $("#grp_variant_j2ajax").val();
                    showFormFieldsAjax(trajectoryVariant);
                });
                
                $("#slotSubmit").on(\'click\', function(e){
                    e.preventDefault();
                    //$("#slotSubmit").attr("disabled","disabled");
                    //alert("!!!");
                    $("#slotForm_A, #slotForm_H, #slotForm_L1, #slotForm_DLS, #slotForm_DLS2, #slotForm_alfa2, #slotForm_L3, #slotForm_NOS").css({border: \'1px solid #e5e9ec;\'});
                    // VALIDATOR
                    var allFieldsFiled = true;
                    var trajectoryVariant = $("#grp_variant_j2ajax").val();
                    var fields = trajectoryVariant.split(\'_\');
                    fields.shift();
                    console.log(fields);
                    $.each(fields, function(i,val){
                        var fieldValue = $("#slotForm_" + val).val(); 
                        if (fieldValue == "" || fieldValue == null){
                            allFieldsFiled = false;
                            $("#slotForm_" + val).css({ border: \'1px solid #f35958\'});
                        }
                    });
                    var fieldValue = $("#slotForm_NOS").val() 
                    if (fieldValue == "" || fieldValue == null){
                        allFieldsFiled = false;
                        $("#slotForm_NOS").css({ border: \'1px solid #f35958\'});
                    }
                    console.log(allFieldsFiled);
                   if (allFieldsFiled == true){ 
                    var dataToSend = {
                        A: $("#slotForm_A").val(),
                        H: $("#slotForm_H").val(),
                        L1: $("#slotForm_L1").val(),
                        DLS: $("#slotForm_DLS").val(),
                        DLS2: $("#slotForm_DLS2").val(),
                        alfa2: $("#slotForm_alfa2").val(),
                        L3: $("#slotForm_L3").val(),
                        Azimuth: $("#slotForm_Azimuth").val(),
                        targetInclination: $("#slotForm_targetInclination").val(),
                        targetAzimuth: $("#slotForm_targetAzimuth").val(),
                        DeltaX: $("#slotForm_DeltaX").val(),
                        DeltaY: $("#slotForm_DeltaY").val(),
                        DeltaZ: $("#slotForm_DeltaZ").val(),
                        TR: $("#slotForm_TR").is(\':checked\'),
                        TL: $("#slotForm_TL").is(\':checked\'),
                        BR: $("#slotForm_BR").is(\':checked\'),
                        BL: $("#slotForm_BL").is(\':checked\'),
                        SR: $("#slotForm_SR").val(),
                        ST: $("#slotForm_ST").val(),
                        SM: $("#slotForm_SM").val(),
                        NOS: $("#slotForm_NOS").val(),
                        Variant: trajectoryVariant,
                        Name: $("#slotForm_Name").val(),
                        SiteId: '; ?>
<?php echo $this->_tpl_vars['recordId']; ?>
<?php echo '
                    };
                    
                    $.ajax({
                    type: "POST",
                    url: BaseURL + "?_m=Trajectory&_o=AjaxSlot",
                    beforeSend: function(){
                        
                    },
                    complete: function(){
                        
                    },
                    data: dataToSend
                    }).done(function( data, textStatus, jqXHR ) {
                        location.reload();
                        if (data[\'status\'] == \'OK\') {
                            
                        }    
                    });
                    }
                });  
            });
        '; ?>
   
        </script>                                     
                                            
                                            
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/cms_3drenderSite.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
    </div>
    
        
     <?php endif; ?>   