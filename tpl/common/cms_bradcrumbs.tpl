    <ul class="breadcrumb">
        <li>
          <p>YOU ARE HERE</p>
        </li>
        {foreach item="_menuItem" from=$pagePath name="pagePath"}
        {if $smarty.foreach.pagePath.iteration % 2 == 0}
        <li><a href="#" {if $smarty.foreach.pagePath.last}class="active"{/if}>{if $smarty.foreach.pagePath.iteration == 2}{$pagePath.companyName}{/if}{if $smarty.foreach.pagePath.iteration == 4}{$pagePath.projectName}{/if}{if $smarty.foreach.pagePath.iteration == 6}{$pagePath.siteName}{/if}{if $smarty.foreach.pagePath.iteration == 8}{$pagePath.wellName}{/if}</a> </li>
        {/if}
        {/foreach}
    </ul>