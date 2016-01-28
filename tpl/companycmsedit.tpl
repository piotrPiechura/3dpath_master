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
                <ul class="nav nav-tabs" id="tab-01">
            <li class="active"><a href="#tab1hellowWorld">General</a></li>
            <li><a href="#tab1FollowUs">Calc defaults</a></li>
            {*<li><a href="#tab1Inspire">Hello Three</a></li>
            <li class="dropdown"> <a class="dropdown-toggle"
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
                            {$_form->field('companyName', null, 'maxlength="40"',"form-control")}
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Division</label>
                        <span class="help">e.g. "Marketing"</span>
                        <div class="controls">
                          {$_form->field('companyDivision', null, 'maxlength="100"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Address </label>
                        <span class="help">e.g. "Kasprzaka 25, 01-224 Warszawa"</span>
                        <div class="controls">
                          {$_form->field('companyAddress', null, 'maxlength="255"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Phone</label>
                        <span class="help">e.g. "+48 22 589 45 55"</span>
                        <div class="controls">
                          {$_form->field('companyPhone', null, 'maxlength="50"',"form-control")}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Email</label>
                        <span class="help">e.g. "some@example.com"</span>
                        <div class="controls">
                         {$_form->field('comapanyEmail', null, 'maxlength="100"',"form-control")}
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
                  {*<h3>“ Nothing is<span class="semi-bold"> impossible</span>, the word itself says 'I'm <span class="semi-bold">possible</span>'! ”</h3>
                  <p>A style represents visual customizations on top of a layout. By editing a style, you can use Squarespace's visual interface to customize your...</p>
                  <br>
                  <p class="pull-right">
                    <button type="button" class="btn btn-white btn-cons">White</button>
                    <button type="button" class="btn btn-success btn-cons">Success</button>
                  </p>*}
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
					  <button class="btn btn-danger btn-cons" type="submit"><i class="icon-ok"></i> Save</button>
					  <button class="btn btn-white btn-cons" type="button">Cancel</button>
					</div>
				  </div>
			</form>
            </div>
          </div>
        </div>
      </div>*}
        
        
        
    </div>
    
    