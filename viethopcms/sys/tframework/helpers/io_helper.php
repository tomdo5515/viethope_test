<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function GenerateUploadFolder($type)
{
	$folder_create = 'uploads/images/'.$type;
	if(!realpath(FCPATH.$folder_create))
	{
		mkdir(FCPATH.$folder_create, 0777, true);
	}
	return $folder_create;
}

/* End of file io_helper.php */
/* Location: .//C/www/bigbetpro/sys/gogosolution/helpers/io_helper.php */