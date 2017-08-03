<?php
if($_POST)
{
	update_option( 'websnapr_developer_key', $_POST['websnapr_developer_key']);
}
$websnaprKey = get_option('websnapr_developer_key');
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br/></div>
	<h2>Websnapr Settings</h2>
	<form method="post" action="" name="form">
		<p>Please register yourself at <a href="http://www.websnapr.com">Websnapr</a> & enter the developer key here.</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th style="width:100px;text-align:right; padding-top:5px" align="right">Websnapr developer key</label></th>
					<td><input type="text" name="websnapr_developer_key" value="<?php echo $websnaprKey; ?>" size="30" /></td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" value="Save" name="update_websnapr_developer_key"/></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>