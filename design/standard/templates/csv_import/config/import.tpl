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
						<option value="{$config.id}">{$config.name}</option>
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
