    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <div class="clearfix"></div>
    <div class="content">
      {*include file='common/cms_bradcrumbs.tpl'*}
      <div class="page-title"> <i class="icon-custom-left"></i>
        <h3><span class="semi-bold">{#subpageTitle#}</span></h3>
      </div>
	  <!-- BEGIN BASIC FORM ELEMENTS-->
      <div class="row-fluid">
        <div class="span12">
          <div class="grid simple ">
            <div class="grid-title">
              <h4>{*Table <span class="semi-bold">Styles</span>*}</h4>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
            </div>
            <div class="grid-body ">
                {if empty($_recordList)}{assign var='_recordList' value=$recordList}{/if}
                {if empty($_shownColumns)}{assign var='_shownColumns' value=$shownColumns}{/if}
                {include file="common/cms_messages.tpl"}
		{$_form->start($filterForm, 'filterForm')}
              <table class="table table-hover table-condensed" id="example">
                {if $superadmin}
			<tr class="search_box">
                            <td></td>
				{foreach key='_colName' item='_params' from=$_shownColumns}
				{assign var='_description' value=$_colName|cat:'TableHeader'}
				<td class="search_element">
					<span class="label_grey">{$smarty.config.$_description}</span>
						{$_form->field($_colName)}
				</td>
				{/foreach}
				  <td>
					{*
					{if empty($addButtonOff)}
						{if empty($_addButtonUrl)}{assign var='_addButtonUrl' value=$url->createHTML('_m', $controllerModule, '_o', 'CMSEdit')}{/if}
						<a class="add" href="{$_addButtonUrl}" >{#addRecord#}</a>
					{/if}
					*}
					<input class="btn btn-white btn-cons" type="submit" value="Filter"  alt="filter"/>
                                        {if empty($addButtonOff)}
                                            {if empty($_addButtonUrl)}{assign var='_addButtonUrl' value=$url->createHTML('_m', $controllerModule, '_o', 'CMSEdit')}{/if}
                                            <a class="btn btn-primary btn-cons" href="{$_addButtonUrl}" >{#addRecord#}</a>
                                            {/if}
				</td>
			</tr>
			{/if}
				{if !empty($_recordList)}
				{foreach item='_record' from=$_recordList}
				<tr>
                                        <td >    
					{if !empty($highlight)}
						{assign var='_highlightValue' value=$highlight->getValue($_record)}
						{*if $_highlightValue == 10}class="grey"{elseif $_highlightValue == 20}class="orange"{elseif $_highlightValue == 40}class="green"{/if*}
                                                {if $_highlightValue == 10}{elseif $_highlightValue == 20}{elseif $_highlightValue == 40}<span class="label label-success">ACTIVE</span>{else}<span class="label label-important">BLOCKED</span>{/if}
					{/if}
                                        </td>
					{foreach key='_colName' item='_params' from=$_shownColumns}
					<td>{if $_record.$_colName == ''}&nbsp;{else}{$_record.$_colName}{/if}</td>
					{/foreach}
					<td class="last_table_column"><a class="edit" href="{$url->createHTML('_m', $controllerModule, '_o', 'CMSEdit', 'id', $_record.id)}">{#editLinkCaption#}</a></td>
				</tr>
				{/foreach}
				{/if}
			</tbody></table>
		{$_form->end()}
		{if !empty($_recordList)}
			{*include file='common/cms_pagination.tpl'*}
		{else}
			<p class="emptyList">{#noRecords#}</p>
		{/if}
            </div>
          </div>
        </div>
      </div>    
    </div>      