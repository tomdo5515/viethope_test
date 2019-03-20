<?php
	$this->unset_jquery();
	$this->set_css($this->default_theme_path.'/bootstrap/css/dataTables.bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/bootstrap/css/implement.css');
	$this->set_js_config($this->default_theme_path.'/bootstrap/js/datatables-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>


<div class="page-title">
  <div>
    <h1><?php echo $this->l('list_record'); ?> <?php echo addslashes($subject); ?></h1>
    <p><?php echo $this->l('form_welcome'); ?></p>
  </div>
  <div>
    <ul class="breadcrumb">
      <li><i class="fa fa-home fa-lg"></i></li>
      <li><a href="/dashboard">Dashboard</a></li>
	  <li><?php echo addslashes($subject); ?></li>
    </ul>
  </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
	<?php echo form_open( $read_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Field</th>
					<th>Value</th>
				</tr>
			</thead>
		<?php
			$counter = 0;
			foreach($fields as $field)
			{
				$counter++;
		?>
				<tbody>
					<tr>
						<td><?php echo $counter; ?></td>
						<td><?php echo $field->field_name; ?></td>
						<td><?php echo $input_fields[$field->field_name]->input?></td>
					</tr>
				</tbody>
		<?php 
			}
		?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
		</table>

		<div class='buttons-box'>
			<div class="form-group">
				<button id="cancel-button" type="button" class="btn btn-default"><i class="glyphicon glyphicon-repeat"></i> <?php echo $this->l('form_back_to_list'); ?></button>
			</div>
		</div>
	</form>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
