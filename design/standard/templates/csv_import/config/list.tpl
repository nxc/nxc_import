<div class="context-block">

	<div class="box-header">
		<div class="box-ml">
			<h1 class="context-title">{'CSV Import Configurations'|i18n( 'extension/nxc_import' )}</h1>
			<div class="header-mainline"></div>
		</div>
	</div>

	<form action="{'csv_import/configs'|ezurl( 'no' )}" method="post" name="configList">
		<div class="box-bc"><div class="box-ml"><div class="box-content">

		<table class="list" cellspacing="0" summary="{'List of CSV Import Configurations'|i18n( 'extension/nxc_import' )}">
			<tr>
				<th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/class/grouplist' )}" title="{'Invert selection.'|i18n( 'design/admin/class/grouplist' )}" onclick="ezjs_toggleCheckboxes( document.configList, 'ConfigIDs[]' ); return false;"/></th>
				<th>{'Name'|i18n( 'extension/nxc_import' )}</th>
				<th>{'Parent node'|i18n( 'extension/nxc_import' )}</th>
				<th>{'Content class'|i18n( 'extension/nxc_import' )}</th>
				<th class="tight">&nbsp;</th>
			</tr>
			{foreach $configs as $config sequence array( 'bgdark', 'bglight' ) as $style}
			<tr class="{$style}">
				<td><input type="checkbox" name="ConfigIDs[]" value="{$config.id}" title="{'Select configuration for removal.'|i18n( 'extension/nxc_import' )}" /></td>
				<td>{$config.name}</td>
				<td>{if $config.parent_node}<a href="{$config.parent_node.url_alias|ezurl( 'no' )}" target="_blank">{$config.parent_node.name|wash}</a>{else}{'not selected/removed'|i18n( 'extension/nxc_import' )}{/if}</td>
				<td>{if $config.class}{$config.class.name}{else}{'not selected/removed'|i18n( 'extension/nxc_import' )}{/if}</td>
				<td class="tight"><a href="{concat( 'csv_import/edit_config/', $config.id )|ezurl( 'no' )}"><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'extension/nxc_import' )}" title="{'Edit'|i18n( 'extension/nxc_import' )}" /></a></td>
			</tr>
			{/foreach}
		</table>

		</div></div></div>

		<div class="block">
			<div class="controlbar">
				<div class="block">
					<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'extension/nxc_import' )}" title="{'Remove selected'|i18n( 'extension/nxc_import' )}" />
					<input class="button" type="submit" name="NewButton" value="{'New Configuration'|i18n( 'extension/nxc_import' )}" title="{'New Configuration'|i18n( 'extension/nxc_import' )}" />
				</div>
			</div>
		</div>
	</form>

</div>
