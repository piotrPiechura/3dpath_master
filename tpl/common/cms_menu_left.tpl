<!-- BEGIN SIDEBAR MENU -->	
		{*<p class="menu-title">BROWSE<span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>*}
		<ul>	
			
			<!-- END SINGLE LINK --> 
                        <!-- BEGIN TWO LEVEL MENU -->
			{*<li class="">
				<a href="javascript:;">
					<i class="fa fa-gass-station"></i>
					<span class="title">AGH (Complany)</span>
					<span class="arrow"></span>
				</a>
				<ul class="sub-menu" style="display: block;">
					<!--<li><a href="platform.html"><i class="fa fa-gass-tower"></i>Eksploatacja Gazu (Project)</a></li>-->
					<li>
						<a href="javascript:;">
                                                    <i class="fa fa-gass-tower"></i>
                                                    <span class="title">Gaz (Project)</span><span class="arrow "></span></a>
						<ul class="sub-menu">
							<li>
                                                            <a href="javascript:;">
                                                                <i class="fa fa-gass-tower-fire"></i>
                                                                <span class="title">Złoże WWNG (Site) </span><span class="arrow "></span></a>
                                                            <ul class="sub-menu">
                                                                    <li><a href="borhole.html"> <i class="fa fa-junction"></i>WWNG(Wellbore)<span class="arrow "></span></a>
                                                                        <ul class="sub-menu">
                                                                            <li><a href="borhole.html"><i class="fa fa-valve"></i>Well(Well)</a></li>
                                                                            <li><a href="borhole.html"><i class="fa fa-valve"></i>Well(Well)</a></li>
                                                                        </ul>     
                                                                    </li>
                                                                    <li><a href="borhole.html"><i class="fa fa-junction"></i>Well(Wellbore)</a></li>
                                
                                                                    <li><a href="borhole.html"><i class="fa fa-junction"></i>Well(Wellbore)</a></li>
                                                            </ul>
                                                        </li>
                                                         <li><a href="site.html"><i class="fa fa-gass-tower-fire"></i><span class="title">Złoże NWWNG(Site)</span></a></li>
                                                        
						</ul>
                                        <li><a href="project.html"><i class="fa fa-gass-tower"></i><span class="title">Ropa(Project)</span></a></li>
					</li>
				</ul>
			</li>*}
			<!-- END TWO LEVEL MENU -->	
			<!-- BEGIN ONE LEVEL MENU -->
			{*<li class="">
				<a href="javascript:;">
					<i class="fa fa-gass-station"></i>
					<span class="title">Nova (Company)</span>
					<span class="arrow"></span>
				</a>
				<ul class="sub-menu">
					<li><a href="project.html">Proj One (Project)</a></li>
				</ul>
			</li>*}
			<!-- END ONE LEVEL MENU -->
			<!-- BEGIN SELECTED LINK -->
			<!--<li class="start <!--active->">
				<a href="project.html">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
					<span class="selected"></span>
					<span class="badge badge-important pull-right">5</span>
				</a>
                            
			</li>-->
			<!-- END SELECTED LINK -->
			<!-- BEGIN BADGE LINK -->
			<!--<li class="">
				<a href="#">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
					<span class="badge badge-disable pull-right">203</span>
				</a>
			</li>-->
			<!-- END BADGE LINK -->     
			<!-- BEGIN SINGLE LINK -->
			<!--<li class="">
				<a href="#">
					<i class="fa fa-gass-station"></i>
					<span class="title">Project X</span>
				</a>
			</li>-->
                        {if !empty($company)}
                        {foreach item=_company from=$company}
                        <li class="">
				<a href="javascript:;">
					<i class="fa fa-gass-station"></i>
					<span class="title" onClick='window.open("{$url->createHTML('_m','Company','_o','CMSEdit','id',$_company.id)}","_self")'>{$_company.companyName}</span>
					<span class="arrow {if !empty($pagePath.companyId) && $pagePath.companyId == $_company.id}open{/if}"></span>
                                            <ul class="sub-menu" {if !empty($pagePath.companyId) && $pagePath.companyId == $_company.id}style="display: block;"{/if}>
                                            {if !empty($project[$_company.id])}
                                            {foreach item="_project" from=$project[$_company.id]}  
                                                <li>
                                                    <a href="javascript:;">
                                                    <i class="fa fa-gass-tower"></i>
                                                    <span class="title" onClick='window.open("{$url->createHTML('_m','Project','_o','CMSEdit','id',$_project.id)}","_self")'>{if !empty($pagePath.siteId) && $pagePath.projectId == $_project.id}<b>{/if}{$_project.projectName}{if !empty($pagePath.projectId) && $pagePath.projectId == $_project.id}</b>{/if}</span>
                                                    <span class="arrow {if !empty($pagePath.projectId) && $pagePath.projectId == $_project.id}open{/if}"></span></a>
                                                    <ul class="sub-menu" {if !empty($pagePath.projectId) && $pagePath.projectId == $_project.id}style="display: block;"{/if}>
                                                        {if !empty($site[$_project.id])}
                                                        {foreach item="_site" from=$site[$_project.id]}
                                                        <li>
                                                            <a href="javascript:;">
                                                                <i class="fa fa-gass-tower-fire"></i>
                                                                <span class="title" onClick='window.open("{$url->createHTML('_m','Site','_o','CMSEdit','id',$_site.id)}","_self")'>{if !empty($pagePath.siteId) && $pagePath.siteId == $_site.id}<b>{/if}{$_site.siteName}{if !empty($pagePath.siteId) && $pagePath.siteId == $_site.id}</b>{/if}</span>
                                                                <span class="arrow {if !empty($pagePath.siteId) && $pagePath.siteId == $_site.id}open{/if}"></span></a> 
                                                                <ul class="sub-menu" {if !empty($pagePath.siteId) && $pagePath.siteId == $_site.id}style="display: block;"{/if}>
                                                                            {if !empty($well[$_site.id])}
                                                                                {foreach item="_well" from=$well[$_site.id]}
                                                                                    <li><a href="{$url->createHTML('_m','Well','_o','CMSEdit','id',$_well.id)}"><i class="fa fa-valve"></i>{if !empty($pagePath.wellId) && $pagePath.wellId == $_well.id}<b>{/if}{$_well.wellName}{if !empty($pagePath.wellId) && $pagePath.wellId == $_well.id}</b>{/if}</a></li>
                                                                                {/foreach}    
                                                                                
                                                                            {/if}    
                                                                            <li><a href="{$url->createHTML('_m','Well','_o','CMSEdit','site',$_site.id)}">
                                                                                 <i class="fa fa-plus-circle"></i>Add Well</a></li>
                                                                </ul> 
                                                        </li>
                                                        {/foreach}
                                                        {/if}
                                                        
                                                        <li>
                                                            <a href="{$url->createHTML('_m','Site','_o','CMSEdit', 'proj', $_project.id)}">
                                                            <i class="fa fa-plus-circle"></i>
                                                            <span class="title">Add new site</span>
                                                            </a>
                                                        </li>
                                                        
                                                    </ul>
                                                </li>
                                            {/foreach}    
                                               
                                            {/if}
                                            <li>
                                                    <a href="{$url->createHTML('_m','Project','_o','CMSEdit', 'comp', $_company.id)}">
                                                        <i class="fa fa-plus-circle"></i>
                                                        <span class="title">Add new project</span>
                                                    </a>
                                            </li>
                                            </ul>
                                
			</li>    
                            
                        
                        {/foreach}
                        {/if}
                        <li class="">
				<a href="{$url->createHTML('_m','Company','_o','CMSEdit')}">
					<i class="fa fa-plus-circle"></i>
					<span class="title">Add new company</span>
					
				</a>
			</li>
		</ul>
		<!-- END SIDEBAR MENU -->
		<!-- BEGIN SIDEBAR WIDGETS -->
		{*<div class="side-bar-widgets">
			<!-- BEGIN FOLDER WIDGET -->
			<p class="menu-title">FOLDER<span class="pull-right"><a href="#" class="create-folder"><i class="icon-plus"></i></a></span></p>
			<ul class="folders">
				<li><a href="#"><div class="status-icon green"></div>Task 1</a></li>
				<!-- BEGIN HIDDEN INPUT BOX (FOR ADD FOLDER LINK) -->
				<li class="folder-input" style="display:none">
					<input type="text" placeholder="Name of folder" class="no-boarder folder-name" name="" id="folder-name">
				</li>
				<!-- END HIDDEN INPUT BOX (FOR ADD FOLDER LINK) -->
			</ul>
			<!-- END FOLDER WIDGET -->
			<!-- BEGIN PROJECTS WIDGET -->
			<p class="menu-title">PROJECTS</p>
			<!-- BEGIN EXAMPLE 1 -->
			<div class="status-widget">
				<div class="status-widget-wrapper">
					<div class="title">Project Title<a href="#" class="remove-widget"><i class="icon-custom-cross"></i></a></div>
					<p>Project Description</p>
				</div>
			</div>
			<!-- END EXAMPLE 1 -->
			<!-- END PROJECTS WIDGET -->
		</div>*}
		<div class="clearfix"></div>
		<!-- END SIDEBAR WIDGETS -->