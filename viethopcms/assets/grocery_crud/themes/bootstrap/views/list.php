<?php if(!empty($list)) { ?>
	<?php foreach($list as $num_row => $row){ ?>
	<tr id='row-<?php echo $num_row?>'>
		<!-- action first -->
		<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
		<td class='actions'>
			<?php if(!empty($row->action_urls)){
				foreach($row->action_urls as $action_unique_id => $action_url){
					$action = $actions[$action_unique_id];
			?>
					<a href="<?php echo $action_url; ?>" class="btn btn-xs btn-default" role="button"> 
						<i class="glyphicon <?php echo $action->css_class; ?> <?php echo $action_unique_id;?>"></i> <?php echo $action->label?>
					</a>
			<?php }
			}
			?>
			
			<?php if(!$unset_edit){?>
				<a href="<?php echo $row->edit_url?>" class="btn btn-xs btn-default" role="button">
					<i class="glyphicon glyphicon-pencil"></i> <?php echo $this->l('list_edit'); ?>
				</a>
			<?php }?>
			
			<?php if(!$unset_read || !$unset_delete){ ?>
			<div class="btn-group">
	            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
	                More <span class="caret"></span>
	            </button>

	            <ul class="dropdown-menu">
	            	<?php if(!$unset_read){?>
	                <li>
						<a href="<?php echo $row->read_url?>" class="" role="button">
							<i class="glyphicon glyphicon-eye-open"></i> View
						</a>
	                </li>
	                <?php }?>
	                <?php if(!$unset_delete){?>
	                <li>
						<a href="<?php echo $row->delete_url?>" class="delete-row" role="button">
							<i class="glyphicon glyphicon-trash text-danger"></i> <span class="text-danger"><?php echo $this->l('list_delete'); ?></span>
						</a>
	                </li>
	                <?php }?>
	            </ul>
	        </div>
			<?php }?>
		</td>
		<?php }?>

		<!-- column -->
		<?php foreach($columns as $column){?>
			<td><?php echo $row->{$column->field_name}?></td>
		<?php }?>

	</tr>
	<?php }?>
<?php }else { ?>
	<tr>
		<td colspan="<?php echo sizeof($columns) + 1; ?>"><?php echo $this->l('list_no_items'); ?></td>
	</tr>
<?php }?>