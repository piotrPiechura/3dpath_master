<script src="assets/js/3dpath.js" type="text/javascript"></script>
{if !empty($defaultVsection)}
<script>
    var defaultVsection = {$defaultVsection};
</script>
{/if} 
<div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <div class="clearfix"></div>
    <div class="content">
      {include file='common/cms_bradcrumbs.tpl'}
      <div class="page-title"> <i class="icon-custom-left"></i>
        <h3>Form - <span class="semi-bold">{#subpageTitle#}</span></h3>
      </div>
	  <!-- BEGIN BASIC FORM ELEMENTS-->
          {include file="common/cms_messages.tpl"}
        <div class="row">
            <div class="col-md-6">
                
        {$_form->start($mainForm, 'mainForm', 1)}         
                <ul class="nav nav-tabs" id="tab-01">
            <li  id="atrtab1General" class="active"><a href="#tab1General">General</a></li>
            <li  id="atrtab1Location"><a href="#tab1Location">Location</a></li>
            <li  id="atrtab1Depth"><a href="#tab1Depth">Inputs</a></li>
            {*<li  id="atrtab1Pads"><a href="#tab1Pads">Pads</a></li>*}
            {*<li class="dropdown"> <a class="dropdown-toggle"
					data-toggle="dropdown"
					href="#"> Dropdown <b class="caret"></b> </a>
              <ul class="dropdown-menu">
                <li><a href="#">New Project</a></li>
                <li><a href="#">Edit Details</a></li>
                <li><a href="#">View More</a></li>
                <li class="divider"></li>
                <li><a href="#" class="text-error">Delete Project</a></li>
              </ul>
              
            </li>*}
           
            <li style='margin-left:50px;margin-top:5px;'>{include file="common/cms_action_buttons_without_back_to_list.tpl"}</li>
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
                        <label class="form-label">{#wellNameFieldCaption#}</label>
                        {*<span class="help">e.g. "Marketing"</span>*}
                        <div class="controls">
                            {$_form->field('wellName', null, 'maxlength="40"',"form-control")}
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Description</label>
                        {*<span class="help">e.g. "Marketing"</span>*}
                      <div class="controls">
                          {$_form->field('wellDescription', null, 'maxlength="100"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">U.W.I.</label>
                        {*<span class="help">e.g. "Marketing"</span>*}
                        <div class="controls">
                           {$_form->field('wellUWI', null, 'maxlength="255"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">API Number</label>
                        {*<span class="help">e.g. "Marketing"</span>*}
                         <div class="controls">
                          {$_form->field('wellAPI', null, 'maxlength="50"',"form-control")}
                        </div>
                      </div>
                       <div class="input-group demo2">
                            <a id="cp4" class="btn btn-primary form-colorpicker" data-colorpicker-guid="1" href="#" data-color-format="hex" data-color="rgb(255, 255, 255)" {if !empty($wellColor)}style="background-color:{$wellColor}"{/if}>Well color</a>
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
                        {*<span class="help">e.g. "some@example.com"</span>*}
                       <div class="controls">
                         {$_form->field('wellSlot', null, 'maxlength="100"',"form-control")}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tedit</label>
                        {*<span class="help">e.g. "some@example.com"</span>*}
                         <div class="controls">
                         {$_form->field('wellTedit', null, 'maxlength="100"',"form-control")}
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
                                    {$_form->field('wellType')}
                                   </div>
                           </div>
                                   
                            <div class="form-group trajectory3D">
                                   <label class="form-label">Trajectory counting method</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('well3DTrajectory', null, 'data-content="" title="" data-toggle="popover" data-original-title=""')}
                                   </div>
                           </div>
                           
                           <div class="form-group trajectory2D">
                                   <label class="form-label">Trajectory</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('wellTrajectory', null, 'data-content="" title="" data-toggle="popover" data-original-title=""')}
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
                                       
                                       {*<select id="grp_variant_j4" class="grpVariants">
                                           <option name="j4_H_alfa2_L1_L3">4H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                           <option name="j4_H_alfa2_L1_L3">H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                           <option name="j4_H_alfa2_L1_L3">H, &alpha;<sub>2</sub>, L<sub>1</sub>, L<sub>3</sub> </option>
                                       </select>*}
                                      
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
                                       
                                       
                                    {$_form->field('wellTrajectoryVariant')}
                                   </div>
                           </div>       
                                   
                            <div class="form-group varField" id="grp_A">
                                   <label class="form-label">{#AFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('A')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_A1">
                                   <label class="form-label">{#A1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('A1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A2">
                                   <label class="form-label">{#A2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('A2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A3">
                                   <label class="form-label">{#A3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('A3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_A4">
                                   <label class="form-label">{#A4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('A4')}
                                   </div>
                           </div>
                            
                            <div class="form-group varField" id="grp_H">
                                   <label class="form-label">{#HFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('H')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_H1">
                                   <label class="form-label">{#H1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('H1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H2">
                                   <label class="form-label">{#H2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('H2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H3">
                                   <label class="form-label">{#H3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('H3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_H4">
                                   <label class="form-label">{#H4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('H4')}
                                   </div>
                           </div>
                              
                            <div class="form-group varField" id="grp_L">
                                   <label class="form-label">{#LFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_L1">
                                   <label class="form-label">{#L1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L2">
                                   <label class="form-label">{#L2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L3">
                                   <label class="form-label">{#L3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_L4">
                                   <label class="form-label">{#L4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L4')}
                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_L5">
                                   <label class="form-label">{#L5FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('L5')}
                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_R">
                                   <label class="form-label">{#RFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('R')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_R1">
                                   <label class="form-label">{#R1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('R1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R2">
                                   <label class="form-label">{#R2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('R2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R3">
                                   <label class="form-label">{#R3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('R3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_R4">
                                   <label class="form-label">{#R4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('R4')}
                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_alfa">
                                   <label class="form-label">{#alfaFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('alfa')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_alfa1">
                                   <label class="form-label">{#alfa1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('alfa1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa2">
                                   <label class="form-label">{#alfa2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('alfa2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa3">
                                   <label class="form-label">{#alfa3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('alfa3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_alfa4">
                                   <label class="form-label">{#alfa4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('alfa4')}
                                   </div>
                           </div>
                                   
                            <div class="form-group varField" id="grp_delta">
                                   <label class="form-label">{#deltaFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('delta')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_delta1">
                                   <label class="form-label">{#delta1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('delta1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta2">
                                   <label class="form-label">{#delta2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('delta2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta3">
                                   <label class="form-label">{#delta3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('delta3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_delta4">
                                   <label class="form-label">{#delta4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('delta4')}
                                   </div>
                           </div>
                                 
                            <div class="form-group varField" id="grp_DLS">
                                   <label class="form-label">{#DLSFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('DLS')}
                                   </div>
                            </div>    
                            <div class="form-group varField" id="grp_DLS1">
                                   <label class="form-label">{#DLS1FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('DLS1')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS2">
                                   <label class="form-label">{#DLS2FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('DLS2')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS3">
                                   <label class="form-label">{#DLS3FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('DLS3')}
                                   </div>
                            </div>
                            <div class="form-group varField" id="grp_DLS4">
                                   <label class="form-label">{#DLS4FieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('DLS4')}
                                   </div>
                           </div>   
                            <div class="form-group varField" id="grp_Q">
                                   <label class="form-label">{#QFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('Q')}
                                   </div>
                           </div>  
                            <div class="form-group varField" id="grp_ro">
                                   <label class="form-label">{#roFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('ro')}
                                   </div>
                           </div>  
                            <div class="form-group trajectory2D" id="grp_azimuth">
                                   <label class="form-label">{#azimuthFieldCaption#}</label>
                                   <span class="help"></span>
                                   <div class="controls">
                                    {$_form->field('azimuth')}
                                   </div>
                           </div>  
                                   
                           
                            <div class="trajectory3D" style="margin-bottom: 15px">
                                <table id="tblAppendGrid">
                                </table>
                                <div style="visibility:hidden;display:none">{$_form->field('well3DPints')}</div>
                            </div>           
                            
                            <table class="table table-bordered no-more-tables">
				<thead>
                                    <tr>
					<th style="width:25%" class="text-center">{#tvectorXFieldCaption#}</th>
					<th style="width:25%" class="text-center">{#tvectorYFieldCaption#}</th>
					<th style="width:25%" class="text-center">{#tvectorZFieldCaption#}</th>
                                        <th style="width:25%" class="text-center">Vertical section</th>
                                    </tr>
				</thead>
				<tbody>
                                    <tr class="">
                                        <td class="text-center"  style="width:25%">{$_form->field('tvectorX', null, "style='width:100%'")}</td>
                                        <td class="text-center"  style="width:25%">{$_form->field('tvectorY', null, "style='width:100%'")}</td>
                                        <td class="text-center"  style="width:25%">{$_form->field('tvectorZ', null, "style='width:100%'")}</td>
                                        <td class="text-center"  style="width:25%">{$_form->field('vsection', null, "style='width:50%'")}<button class="submit btn btn-cons" style="margin-left:5px" id="vsectionDef">Default</button></td>
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
                
                
               {* 
              <div class="grid simple">
                <div class="grid-title no-border">
                  <h4>Simple <span class="semi-bold">Elemets</span></h4>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body no-border"> <br>dfgdf
                  <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">Your Name</label>
                        <span class="help">e.g. "Mona Lisa Portrait"</span>
                        <div class="controls">
                          <input type="text" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Password</label>
                        <span class="help">e.g. "Mona Lisa Portrait"</span>
                        <div class="controls">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Email</label>
                        <span class="help">e.g. "some@example.com"</span>
                        <div class="controls">
                          <input type="text" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Placeholder</label>
                        <span class="help">e.g. "some@example.com"</span>
                        <div class="controls">
                          <input type="text" placeholder="You can put anything here" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Disabled</label>
                        <span class="help">e.g. "some@example.com"</span>
                        <div class="controls">
                          <input type="text" placeholder="You can put anything here" class="form-control" disabled="disabled">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>*}
          {$_form->end()}  
        </div>
	<!-- END BASIC FORM ELEMENTS-->	 
           
       {* 
        <div class="row">
        <div class="col-md-12">
          <div class="grid simple">
            <div class="grid-title no-border">
              <h4>Condensed <span class="semi-bold">Layout</span></h4>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
            </div>
            <div class="grid-body no-border">
			<form class="form-no-horizontal-spacing" id="form-condensed">	
              <div class="row column-seperation">
                <div class="col-md-6">
                  <h4>Basic Information</h4>            
                    <div class="row form-row">
                      <div class="col-md-5">
                        <input name="form3FirstName" id="form3FirstName" type="text"  class="form-control" placeholder="First Name">
                      </div>
                      <div class="col-md-7">
                        <input name="form3LastName" id="form3LastName" type="text"  class="form-control" placeholder="Last Name">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-5">
                        <select name="form3Gender" id="form3Gender" class="select2 form-control"  >
                          <option value="1">Male</option>
                          <option value="2">Female</option>
                        </select>
                      </div>
                      <div class="col-md-7">
                        <input type="text" placeholder="Date of Birth" class="form-control" id="form3DateOfBirth" name="form3DateOfBirth">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-12">
                        <input name="form3Occupation" id="form3Occupation" type="text"  class="form-control" placeholder="Occupation">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-8">
                        <div class="radio">
                          <input id="male" type="radio" name="gender" value="male" checked="checked">
                          <label for="male">Male</label>
                          <input id="female" type="radio" name="gender" value="female">
                          <label for="female">Female</label>
                        </div>
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-12">
                        <input name="form3Email" id="form3Email" type="text"  class="form-control" placeholder="email@address.com">
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
				
                  <h4>Postal Information</h4>
                  
                    <div class="row form-row">
                      <div class="col-md-12">
                        <input name="form3Address" id="form3Address" type="text"  class="form-control" placeholder="Address">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-6">
                        <input name="form3City" id="form3City" type="text"  class="form-control" placeholder="City">
                      </div>
                      <div class="col-md-6">
                        <input name="form3State" id="form3State" type="text"  class="form-control" placeholder="State">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-8">
                        <input name="form3Country" id="form3Country" type="text"  class="form-control" placeholder="Country">
                      </div>
                      <div class="col-md-4">
                        <input name="form3PostalCode" id="form3PostalCode" type="text"  class="form-control" placeholder="Postal Code">
                      </div>
                    </div>
                    <div class="row form-row">
                      <div class="col-md-4">
                        <input name="form3TeleCode" id="form3TeleCode" type="text"  class="form-control" placeholder="+94">
                      </div>
                      <div class="col-md-8">
                        <input name="form3TeleNo" id="form3TeleNo" type="text"  class="form-control" placeholder="Phone Number">
                      </div>
                    </div>
                    <div class="row small-text">
					<p class="col-md-12">
					NOTE - Facts to be considered, Simply remove or edit this as for what you desire. Disabled font Color and size
					</p>
					</div>
             
                </div>
              </div>
				<div class="form-actions">
					<div class="pull-left">
					  <div class="checkbox checkbox check-success 	">
						<input type="checkbox" value="1" id="chkTerms">
						<label for="chkTerms">I Here by agree on the Term and condition. </label>
					  </div>
					</div>
					<div class="pull-right">
					  <button class="btn btn-danger btn-cons" type="submit"><i class="icon-ok"></i> Calculate</button>
					  <button class="btn btn-white btn-cons" type="button">Cancel</button>
					</div>
				  </div>
			</form>
            </div>
          </div>
        </div>
      </div>*}
      
      
          <script>
              {if (empty($recordId))}
              var emptyWell = 1;
              {else}
              var emptyWell = 0;    
              {/if}  
          </script>
      
            
        {if !empty($trajectoryParamsA)}
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
                                    {assign var="secNo" value=$sectionNumber|intval}
                                {section name=foo start=1 loop=$secNo+1 step=1}
                                    <tr>
                                        {assign var="index" value=$smarty.section.foo.index}
                                        <td>{$index}</td>
                                        <td>{if !empty($trajectoryParamsA.$index)}{$trajectoryParamsA.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsH.$index)}{$trajectoryParamsH.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsL.$index)}{$trajectoryParamsL.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsR.$index)}{$trajectoryParamsR.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsAlfa.$index)}{$trajectoryParamsAlfa.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsDelta.$index)}{$trajectoryParamsDelta.$index|number_format:2:",":""}{/if}</td>
                                        <td>{if !empty($trajectoryParamsDLS.$index)}{$trajectoryParamsDLS.$index|number_format:2:",":""}{/if}</td>
                                    </tr>
                                {/section}
                                    <tr>
                                        <td>&Sigma;</td>
                                        <td>{$trajectoryParamsA.sum|number_format:2:",":""}</td>
                                        <td>{$trajectoryParamsH.sum|number_format:2:",":""}</td>
                                        <td>{$trajectoryParamsL.sum|number_format:2:",":""}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>    
                                </table>
                                                   {*<a href="X">V Chart</a> | <a href="X">H Chart</a>
                                                    <br/>
                                                    <img src="assets/img/vh.png">*}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {/if}
    </div>
    {*// //*} 
    {include file='common/cms_3drender.tpl'}
    
    {if !empty($trajectoryTable)}
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
                                            {*<th style="text-transform: lowercase;">&alpha;</th>
                                            <th style="text-transform: lowercase;">&beta;</th>*}
                                            {*<th>section</th>*}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                {foreach item=_row from=$trajectoryTable name="dataTable"}
                                        {if $smarty.foreach.dataTable.iteration == 1}{assign var='_section' value=$_row.section}{/if}
                                        <tr class="{if $smarty.foreach.dataTable.iteration%2}odd{else}even{/if} gradeX" {if $_row.section == 'PP' || ($_section != $_row.section && $_section !='PP') || $smarty.foreach.dataTable.last || $smarty.foreach.dataTable.first}style='background-color: #aaa' {/if}>
                                            <td>{$_row.MD|number_format:2:",":""}</td>
                                            
                                            {*<td class="center">{$_row.Z|number_format:2:",":""}</td>
                                            <td>{$_row.Y|number_format:2:",":""}</td>
                                           <td>{$_row.X|number_format:2:",":""}</td>*}
                                            <td class="center">{$_row.Y|number_format:2:",":""}</td>
                                            <td>{$_row.X|number_format:2:",":""}</td>
                                            <td>{$_row.Z|number_format:2:",":""}</td>
                                             <td class="center">{if is_numeric($_row.alfa)}{$_row.alfa|number_format:2:",":""}{else}0.00 {$_row.alfa}{/if}</td>
                                            <td class="center">{if is_numeric($_row.beta)}{$_row.beta|number_format:2:",":""}{else}0.00 {$_row.beta}{/if}</td>
                                            <td>{if !empty($_row.DLS)}{$_row.DLS|number_format:2:",":""}{else}0.00{/if}</td>
                                            <td>{if !empty($_row.CL_DEP)}{$_row.CL_DEP|number_format:2:",":""}{else}0.00{/if}</td>
                                            <td>{if !empty($_row.CL_Angle)}{$_row.CL_Angle|number_format:2:",":""}{else}0.00{/if}</td>
                                            <td>{if !empty($_row.V_SECTION)}{$_row.V_SECTION|number_format:2:",":""}{else}0.00{/if}</td>
                                            <td>{*if !empty($_row.CL_DEP)}{$_row.CL_DEP|number_format:2:",":""}{/if*}</td>
                                            {*<td class="center">{if is_numeric($_row.alfa)}{$_row.alfa|number_format:2:",":""}{else}0 {$_row.alfa}{/if}</td>
                                            <td class="center">{if is_numeric($_row.beta)}{$_row.beta|number_format:2:",":""}{else}0 {$_row.beta}{/if}</td>*}
                                            {*<td class="center">{$_row.section}</td>*}
                                             <td class="center"></td>
                                        </tr>
                                        {assign var='_section' value=$_row.section}
                                {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
    
    <script>
        var points3d = null;
    {if !empty($points3d)}
     points3d = [
        {foreach item=_point from=$points3d}
            {literal}{{/literal} X: '{$_point.X}', Y: '{$_point.Y}', Z: '{$_point.Z}', LP: '{$_point.LP}', alfa: '{$_point.alfa}', beta: '{$_point.beta}' {literal}}{/literal},
        {/foreach}
            ];
    {/if}
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