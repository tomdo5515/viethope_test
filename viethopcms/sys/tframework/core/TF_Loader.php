<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."../sys/third_party/MX/Loader.php";

class TF_Loader extends MX_Loader {
	
    protected $_ci_library_paths =	array(SOLUTION, BASEPATH, APPPATH);

    protected $_ci_helper_paths =	array(SOLUTION, BASEPATH);

    protected $_ci_model_paths =	array(SOLUTION, APPPATH);

    protected $_db_config_loaded =	FALSE;
    
}