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

			{include file="common/cms_messages.tpl"}
			<div class="grid simple">
                        {*<div class="grid-title no-border">
                          <h4>Simple <span class="semi-bold">Elemets</span></h4>
                          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                        </div>*}
                        <div class="grid-body no-border"> <br>
                          <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-8">
			{if $mainForm->hasField('adminName')}
                        <div class="form-group">
                            <label class="form-label">{#adminNameFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminName', null, 'maxlength="40"')}
                            </div>
                        </div>
			{else}
                        <div class="form-group">
                            <label class="form-label">{#adminNameFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              <input type="text" value="{$recordOldValues.adminName}" disabled="disabled" />
                            </div>
                        </div>
			{/if}
                        <div class="form-group">
                            <label class="form-label">{#adminPasswordFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminPassword', null, 'maxlength="40"')}
                            </div>
                        </div>
			<div class="form-group">
                            <label class="form-label">{#adminPasswordConfirmFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminPasswordConfirm', null, 'maxlength="40"')}
                            </div>
                        </div>
			{if $mainForm->hasField('adminFirstName')}
                        <div class="form-group">
                            <label class="form-label">{#adminFirstNameFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminFirstName', null, 'maxlength="80"')}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{#adminSurnameFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminSurname', null, 'maxlength="80"')}
                            </div>
                        </div>
			
			{/if}
			{if $mainForm->hasField('adminRole')}
                         <div class="form-group">
                            <label class="form-label">{#adminRoleFieldCaption#}</label>
                            <span class="help"></span>
                            <div class="controls">
                              {$_form->field('adminRole', 'selectsmartyconfig')}
                            </div>
                        </div>
			
			{/if}	
                        <div class="form-group">
                                {include file="common/cms_action_buttons.tpl"}
                        </div>
	</div>
{$_form->end()}

            </div>
        </div>
     </div>