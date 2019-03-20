<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileManagement extends BackendController {

	public function __construct() {
        parent::__construct();
        $this->load->library('Ckfinder');
        //$this->ckfinder->setProjectPath('my_project');              // Tên project của bạn
        $this->ckfinder->setFolderUpload($this->config->base_url('/uploads'));       // Cấu hình folder upload
        $this->ckfinder->setCkfinderSourcePath('grocery_crud/texteditor/ckfinder');  // Đường dẫn tới source ckfinder
        $this->ckfinder->setAuthentication(true);                   // Nếu true => cho phép sử dụng ckfinder
                                                                    // ngược lại false thì ko được phép sử dụng
        // Đường dẫn tuyệt đối dẫn đến function connector ở bên dưới
        $this->ckfinder->setConnectorPath($this->config->base_url('/dashboard/filemanagement/connector'));
    }
    
    public function connector()
    {   
        $this->ckfinder->startConnector();
    }
     
    public function index(){
        $this->ckfinder->getHTML();
    }

    public function iframe(){
        $this->twig->display('filemanager');
    }
}