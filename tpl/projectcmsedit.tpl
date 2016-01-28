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
        <div class="row">
            <div class="col-md-12">
                
        {$_form->start($mainForm, 'mainForm', 1)}  
  
              <div class="grid simple">
                {*<div class="grid-title no-border">
                  <h4>Simple <span class="semi-bold">Elemets</span></h4>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>*}
                <div class="grid-body no-border"> <br>
                  <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">Project Name</label>
                        <span class="help">e.g. "New Project"</span>
                        <div class="controls">
                          {$_form->field('projectName', null, 'maxlength="255"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Location</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          {$_form->field('projectLocation', null, 'maxlength="255"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Description</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          {$_form->field('projectDescription', null, 'maxlength="255"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">System Datum Description</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          {$_form->field('projectSystem', null, 'maxlength="100"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Elevation</label>
                        <span class="help">e.g. ""</span>
                        <div class="controls">
                          {$_form->field('projectElevation', null, 'maxlength="100"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        
                        <div class="controls">
                          {include file="common/cms_action_buttons_without_back_to_list.tpl"}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          {$_form->end()}  
        </div>
	<!-- END BASIC FORM ELEMENTS-->	 
           

        
        
        
    </div>
    
    