<?php
	$this->unset_jquery();
	$this->set_css($this->default_theme_path.'/bootstrap/css/dataTables.bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/bootstrap/css/implement.css');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/bootstrap/js/datatables-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>

<div class="page-title">
  <div>
    <h1><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $this->l('form_edit'); ?> <?php echo addslashes($subject); ?></h1>
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
	<?php echo form_open( $update_url, 'method="post" id="crudForm" enctype="multipart/form-data"'); ?>
		<div>
			<?php
				$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>

					<div class="form-group  <?php echo $even_odd?>" id="<?php echo $field->field_name; ?>_input_box">
					    <label for="field-<?php echo $field->field_name; ?>">
						    <?php echo $input_fields[$field->field_name]->display_as ?>
						    <?php echo ($input_fields[$field->field_name]->required) ? "<span class='required'>*</span> " : ""?>
					    </label>
					    <?php echo $input_fields[$field->field_name]->input?>
					</div>
			
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
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
			<div class='line-1px'></div>
			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>
		</div>
		<div class='buttons-box'>
			<div class="form-group">
				<button id="form-button-save" type="button" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> <?php echo $this->l('form_update_changes'); ?></button>

				<?php 	if(!$this->unset_back_to_list) { ?>
					<button id="save-and-go-back-button" type="button" class="btn  btn-info"><i class="glyphicon glyphicon-floppy-saved"></i> <?php echo $this->l('form_update_and_go_back'); ?></button>
				<?php   } ?>

				<button id="cancel-button" type="button" class="btn btn-default"><i class="glyphicon glyphicon-warning-sign"></i> <?php echo $this->l('form_cancel'); ?></button>
			</div>
			
			<div class='form-group'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
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