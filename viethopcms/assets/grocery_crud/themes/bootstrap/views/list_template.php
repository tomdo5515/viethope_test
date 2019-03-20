<?php
	// $this->set_css($this->default_theme_path.'/bootstrap/css/bootstrap.min.css'); // set when code not use bootstrap
	$this->unset_jquery();
	$this->set_css($this->default_theme_path.'/bootstrap/css/dataTables.bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/bootstrap/css/implement.css');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	if (!$this->is_IE7()) {
		$this->set_js_lib($this->default_javascript_path.'/common/list.js');
	}

	$this->set_js($this->default_theme_path.'/bootstrap/js/cookies.js');
	$this->set_js($this->default_theme_path.'/bootstrap/js/datatables.js');
	$this->set_js($this->default_theme_path.'/bootstrap/js/jquery.printElement.min.js');
	$this->set_js($this->default_theme_path.'/bootstrap/js/filter_list.js');

	/** Fancybox */
	$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');
?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';

	var subject = '<?php echo addslashes($subject); ?>';
	var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
	var unique_hash = '<?php echo $unique_hash; ?>';

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";
</script>
<?php
	if(!empty($actions)){
?>
	<style type="text/css">
		<?php foreach($actions as $action_unique_id => $action){?>
			<?php if(!empty($action->image_url)){ ?>
				.<?php echo $action_unique_id; ?>{
					background: url('<?php echo $action->image_url; ?>') !important;
				}
			<?php }?>
		<?php }?>
	</style>
<?php
	}
?>
<?php if($unset_export && $unset_print){?>
<style type="text/css">
	.datatables-add-button
	{
		position: static !important;
	}
</style>
<?php }?>

<div id='list-report-error' class='report-div error report-list'></div>
<div id='list-report-success' class='report-div success report-list' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php
 if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php } ?>
</div>
<div class="page-title">
<div>
  <h1><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo addslashes($subject); ?></h1>
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

<div class="grocerycrud-container card" data-unique-hash="<?php echo $unique_hash; ?>">
	<div id="hidden-operations" class="hidden-operations"></div>
	<?php if(!$unset_add || !$unset_export || !$unset_print){?>
	<div class="toolbar">
		<div class="datatables-add-button floatL">
			<?php if(!$unset_add){?>
				<a role="button" class="btn btn-default" href="<?php echo $add_url?>">
					<span class="glyphicon glyphicon-plus"></span>
					<span><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
				</a>
			<?php }?>

			<?php 
			if(!empty($extra_functions)){
				foreach($extra_functions as $_function){
			?>
					<a role="button" class="btn btn-default" href="<?php echo $_function->link_url; ?>">
						<i class="glyphicon <?php echo $_function->css_class; ?>" ></i>
						<span><?php echo $_function->label; ?></span>
					</a>
			<?php }
			}
			?>

		</div>

		<div class="floatR">
		<?php if(!$unset_export) { ?>
            <a class="btn btn-default export-anchor" data-url="<?php echo $export_url; ?>" target="_blank">
	            <i class="glyphicon glyphicon-cloud-download"></i>
	            <span class="hidden-xs"><?php echo $this->l('list_export');?></span>
	        </a>
		<?php } ?>
		<?php if(!$unset_print) { ?>
	        <a class="btn btn-default print-anchor" data-url="<?php echo $print_url; ?>">
                <i class="glyphicon glyphicon-print"></i>
                <span class="hidden-xs"><?php echo $this->l('list_print');?></span>
            </a>
		<?php }?>
		</div>

		<div class="clearfix"></div>
	</div>
	<?php }?>

	<div class="bootstraptheme">
		
		<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable groceryCrudTable" id="<?php echo uniqid(); ?>">
			<thead>
				<tr>
					<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
						<th class='actions'><?php echo $this->l('list_actions'); ?></th>
					<?php }?>

					<?php foreach($columns as $column){?>
						<th><?php echo $column->display_as; ?></th>
					<?php }?>
				</tr>
				<tr>
					<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
						<th>
							<div class="btn-group btn-group-sm" role="group" aria-label="...">
								<button id="ajax_refresh_and_loading" class="btn btn-sm btn-default ajax_refresh_and_loading" role="button" >
									<span class="glyphicon glyphicon-refresh"></span>
								</button>
							</div>
						</th>
					<?php }?>
					
					<th colspan="<?php echo sizeof($columns); ?>">
						<div class="quickSearchBox" id='quickSearchBox'>
							<div class="row">
								<div class="col-md-2 input-group-sm">
									<select class="form-control" name="search_field" id="search_field">
										<option value=""><?php echo $this->l('list_search_all');?></option>
										<?php foreach($columns as $column){?>
										<option value="<?php echo $column->field_name?>"><?php echo $column->display_as?>&nbsp;&nbsp;</option>
										<?php }?>
									</select>
								</div>
								<div class="col-md-6">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control search_text" name="search_text" size="30" id='search_text'>
										<span class="input-group-btn">
											<input type="button" value="<?php echo $this->l('list_search');?>" class="btn btn-sm btn-default crud_search" id='crud_search'>
										</span>
									</div>
								</div>
								
								<div class="col-md-2">
									
								</div>
							</div>
					        
						</div>
					</th>
				</tr>
			</thead>
			<tbody id='ajax_list' class="ajax_list">
				<?php echo $list_view?>
			</tbody>
		
		</table>

		<div class="footer-tools">
			<div class="row">

				<div class="col-md-4">
					<div class="floatL">
						<div class="floatL">
							<?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
							<?php echo $show_lang_string; ?>
						</div>
						<div class="floatL">
							<div class="input-group input-group-sm">
								<select name="per_page" id='per_page' class="form-control per_page">
									<?php foreach($paging_options as $option){?>
										<option value="<?php echo $option; ?>" <?php if($option == $default_per_page){?>selected="selected"<?php }?>><?php echo $option; ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="floatR">
							<?php echo $entries_lang_string; ?>
							<input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting' value='<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>' />
							<input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'  value='<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>'/>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="floatL">
						<span class="pPageStat">
							<?php $paging_starts_from = "<span id='page-starts-from' class='page-starts-from'>1</span>"; ?>
							<?php $paging_ends_to = "<span id='page-ends-to' class='page-ends-to'>". ($total_results < $default_per_page ? $total_results : $default_per_page) ."</span>"; ?>
							<?php $paging_total_results = "<span id='total_items' class='total_items'>$total_results</span>"?>
							<?php echo str_replace( array('{start}','{end}','{results}'),
													array($paging_starts_from, $paging_ends_to, $paging_total_results),
													$this->l('list_displaying')
												   ); ?>
						</span>
					</div>
					<div class="floatR">
						
						<ul class="pagination">
							<li class="paging-first"><a href="javascript:void(0);"><i class="glyphicon glyphicon-step-backward"></i></a></li>
							<li class="prev paging-previous"><a href="javascript:void(0);"><i class="glyphicon glyphicon-chevron-left"></i></a></li>
							<li>
	                            <span class="input-group input-group-sm page-number-input-container">
	                            	<input name='page' type="number" value="1" id='crud_page' class="crud_page form-control page-number-input">
	                            </span>
	                        </li>
	                        <li class="disabled">
	                        	<span>
		                        	<?php echo $this->l('list_paging_of'); ?>
		                        	<span id='last-page-number' class="last-page-number"><?php echo ceil($total_results / $default_per_page)?></span>
	                        	</span>
	                        </li>
	                        <li class="next paging-next"><a href="javascript:void(0);"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
	                        <li class="paging-last"><a href="javascript:void(0);"><i class="glyphicon glyphicon-step-forward"></i></a></li>
						</ul>
						
				        <div class="btn-group input-group-sm floatR">
	                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                            <i class="glyphicon glyphicon-cog"></i>
	                            <span class="caret"></span>
	                        </button>

	                        <ul class="dropdown-menu dropdown-menu-right">
	                            <li>
	                                <a href="javascript:void(0)" class="clear-filtering">
	                                    <i class="glyphicon glyphicon-erase"></i> <?php echo $this->l('list_clear_filtering');?>
	                                </a>
	                            </li>
	                        </ul>
	                    </div>

                    </div>
		        </div>

			</div>
		</div>
		<?php echo form_close(); ?>
		</div>

	</div>

</div>