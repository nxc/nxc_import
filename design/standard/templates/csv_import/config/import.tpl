{if $counter|count|gt( 0 )}
<div class="message-feedback">
	<h2>{'The import was successful'|i18n( 'extension/nxc_import' )}</h2>
	<ul>
		{def $action_text = ''}
		{foreach $counter as $action => $count}
			{if eq( $action, 'skip' )}
				{set $action_text = 'Skipped %count objects'}
			{elseif eq( $action, 'create' )}
				{set $action_text = 'Created %count objects'}
			{elseif eq( $action, 'update' )}
				{set $action_text = 'Updated %count objects'}
			{elseif eq( $action, 'remove' )}
				{set $action_text = 'Removed %count objects'}
			{/if}
			<li>{$action_text|i18n( 'extension/nxc_import', '', hash( '%count', $count ) )}</li>
		{/foreach}
	</ul>
</div>
{/if}

{if $errors|count|gt( 0 )}
<div class="message-error">
	<h2>{'Import could not be executed'|i18n( 'extension/nxc_import' )}</h2>
	<ul>
		{foreach $errors as $error}
		<li>{$error}</li>
		{/foreach}
	</ul>
</div>
{/if}

<div class="context-block">

	<div class="box-header">
		<div class="box-ml">
			<div class="header-mainline"></div>
		</div>
	</div>

	<form action="{'csv_import/import'|ezurl( 'no' )}" method="post" enctype="multipart/form-data">
		<div class="box-content">

			<div class="context-attributes">
				<div class="block">
					<label for="csv-import-config-id">{'Configuration'|i18n( 'extension/nxc_import' )}:</label>
					<select id="csv-import-config-id" name="config_id">
						<option value="-1">{'-- Select --'|i18n( 'extension/nxc_import' )}</option>
						{foreach $configs as $config}
						<option value="{$config.id}"{if and( $selected_config, eq( $selected_config.id, $config.id ) )} selected="selected"{/if}>{$config.name}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="context-attributes">
				<div class="block">
					<label for="csv-import-file">{'File'|i18n( 'extension/nxc_import' )}:</label>
					<input type="file" name="file" id="csv-import-file" />
				</div>
			</div>

		</div>

		<div class="block">
			<div class="controlbar">
				<div class="block">
					<input class="defaultbutton" type="submit" name="ImportButton" value="{'Import'|i18n( 'extension/nxc_import' )}" title="{'Import'|i18n( 'extension/nxc_import' )}" />
				</div>
			</div>
		</div>
	</form>

</div>
