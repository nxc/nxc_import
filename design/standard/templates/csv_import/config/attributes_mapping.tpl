<table class="list" cellspacing="0">
	<tbody>
		<tr>
			<th>{'Attribute'|i18n( 'extension/nxc_import' )}</th>
			<th>{'Identifier'|i18n( 'extension/nxc_import' )}</th>
			<th>{'Data type'|i18n( 'extension/nxc_import' )}</th>
			<th>{'CSV Column'|i18n( 'extension/nxc_import' )}</th>
			<th>{'Static value'|i18n( 'extension/nxc_import' )}</th>
			<th>{'Callback'|i18n( 'extension/nxc_import' )}</th>
		</tr>
		{foreach $class_attributes as $class_attribute}
		<tr>
			<td>{$class_attribute.name}</td>
			<td>{$class_attribute.identifier}</td>
			<td>{$class_attribute.data_type_string}</td>
			<td>
				<select name="attributes_mapping[{$class_attribute.identifier}][csv_column]">
					<option value="-1">{'Is not imported'|i18n( 'extension/nxc_import' )}</option>
					{for 0 to 999 as $i}
					<option value="{$i}"{if and( is_set( $config.attributes_mapping[$class_attribute.identifier] ), eq( $config.attributes_mapping[$class_attribute.identifier].csv_column, $i ) )} selected="selected"{/if}>{$i}</option>
					{/for}
				</select>
			</td>
			<td><input type="text" name="attributes_mapping[{$class_attribute.identifier}][static_value]" value="{$config.attributes_mapping[$class_attribute.identifier].static_value}" /></td>
			<td><input type="text" name="attributes_mapping[{$class_attribute.identifier}][callback]" value="{$config.attributes_mapping[$class_attribute.identifier].callback}" /></td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="3"><strong>{'Remote ID'|i18n( 'extension/nxc_import' )}</strong></td>
			<td>
				<select name="attributes_mapping[remote_id][csv_column]">
					<option value="-1">{'Is not imported'|i18n( 'extension/nxc_import' )}</option>
					{for 0 to 999 as $i}
					<option value="{$i}"{if and( is_set( $config.attributes_mapping.remote_id ), eq( $config.attributes_mapping.remote_id.csv_column, $i ) )} selected="selected"{/if}>{$i}</option>
					{/for}
				</select>
			</td>
			<td>&nbsp;</td>
			<td><input type="text" name="attributes_mapping[remote_id][callback]" value="{$config.attributes_mapping.remote_id.callback}" /></td>
		</tr>
	</tbody>
</table>