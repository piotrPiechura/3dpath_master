<div class="row login-container animated fadeInUp">  
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
		 <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10"> 
                     <h2 class="normal">Sign in to <br/>3D Path Designer</h2>
          {*<p>Use Facebook, Twitter or your email to sign in.<br></p>*}
                <div class="row form-row ">
                    {include file='common/cms_messages.tpl'}
                    {$_form->start($loginForm, 'loginForm','class="animated fadeIn" style="display:none" ')}
                      <div class="col-sm-6">
                        {$_form->field('adminName', null , 'class="form-control" placeholder="Username" style="width:120px"') }
                        {*<input name="login_username" id="login_username" type="text"  class="form-control" placeholder="Username">*}
                      </div>
                      <div class="col-sm-6">
                         {$_form->field('password', null, 'class="form-control" placeholder="Password" style="width:135px;margin-left:-20px;"')} 
                        {*<input name="login_pass" id="login_pass" type="password"  class="form-control" placeholder="Password">*}
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
			  {$_form->end()}
			
		</div>   
      </div>   

{*
<div class="page_title">
	<h2 class="big_grey left">{#subpageTitle#}</h2> 	
	<div class="clear"></div>
</div>
<div class="admin_login_form">
{include file='common/cms_messages.tpl'}
{$_form->start($loginForm, 'loginForm')}
	<div class="form_line">
		<label>{#adminNameFieldCaption#}</label> 
		{$_form->field('adminName')}
	</div>
	<div class="form_line">
		<label>{#passwordFieldCaption#}</label> 
		{$_form->field('password')}
	</div>
	<div class="loginSub btn_grey_small">{$_form->field('_login', null, null, 'adminLoginButton')}</div>
{$_form->end()}
</div>
<div class="clear"></div>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function () {literal}{{/literal}document.getElementById('loginForm_adminName').focus();{literal}}{/literal});
//]]>
</script>
*}