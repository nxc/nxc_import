{ezcss_require( array( 'csvimport.css' ) )}
{ezscript_require( array( 'csvimport.js' ) )}

{if $errors|count|gt( 0 )}
<div class="message-error">
	<h2>{'Config could not be stored.'|i18n( 'extension/nxc_import' )}</h2>
	<p>{'Required data is either missing or is invalid:'|i18n( 'extension/nxc_import' )}</p>
	<ul>
		{foreach $errors as $attr => $error}
		<li>{$attr}: {$error}</li>
		{/foreach}
	</ul>
</div>
{/if}

<form method="post" action="{'csv_import/add_config'|ezurl( 'no' )}">

	<div class="context-block">
		<div class="box-header">
			<h1 class="context-title">{'New Configuration'|i18n( 'extension/nxc_import' )}</h1>
			<div class="header-mainline"></div>
		</div>

		<div class="box-content">

			<div class="context-attributes">
				<div class="block">
					<label for="csv-import-config-name">{'Name'|i18n( 'extension/nxc_import' )}:</label>
	    			<input class="halfbox" id="csv-import-config-name" name="name" value="{$config.name|wash}" />
				</div>
			</div>

			<div class="context-attributes">
				<div class="block">
					<label for="csv-import-parent-node-id">{'Parent node'|i18n( 'extension/nxc_import' )}:</label>
	    			{if $config.parent_node}<a href="{$config.parent_node.url_alias|ezurl( 'no' )}" target="_blank">{$config.parent_node.name|wash}</a>{/if}
	    			<input type="hidden" name="parent_node_id" value="{if $config.parent_node}{$config.parent_node.node_id}{/if}"/>
					<input class="button" type="submit" id="csv-import-parent-node-id" name="BrowseParentNode" value="{'Browse'|i18n( 'extension/nxc_import' )}" />
				</div>
			</div>

			<div class="context-attributes">
				<div class="block">
					<label for="csv-import-class-id">{'Content class'|i18n( 'extension/nxc_import' )}:</label>
					<select id="csv-import-class-id" name="class_id" data-url="{'csv_import/attributes_mapping/CLASS_ID'|ezurl( 'no' )}">
						<option value="-1">{'-- Select --'|i18n( 'extension/nxc_import' )}</option>
						{foreach $classes as $class}
						<option value="{$class.id}"{if eq( $class.id, $config.class_id )} selected="selected"{/if}>{$class.name}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="context-attributes">
				<div class="block">
					<label>{'Attributes mapping'|i18n( 'extension/nxc_import' )}:</label>
					<div class="attributes-mapping-wrapper">
						{if gt( $class_attributes|count, 0 )}
							{include uri='design:csv_import/config/attributes_mapping.tpl'}
						{else}
							<p>{'Please select content class'|i18n( 'extension/nxc_import' )}</p>
						{/if}
					</div>
					<div class="attributes-mapping-loader" style="display: none;">
						<div class="loader-icon">
							<img src={'transparent_loader.gif'|ezimage} alt="{'Loading...'|i18n( 'extension/nxc_import' )}" title="{'Loading...'|i18n( 'extension/nxc_import' )}" />
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="block">
			<div class="controlbar">
				<div class="box-bc">
					<div class="block">
						<input class="defaultbutton" type="submit" name="StoreButton" value="{'Save'|i18n( 'extension/nxc_import' )}" />
						<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'extension/nxc_import' )}" />
					</div>
				</div>
			</div>
		</div>

	</div>

</form>