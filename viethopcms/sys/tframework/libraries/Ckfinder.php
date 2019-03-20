<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
* fix error
* 1-global $config in line 79 in file conntector.php
* 2-replate $connector by $GLOBALS['connector']; in line 99,102,105 in file conntector.php
*/

class Ckfinder
{
    // TÊN CỦA PROJECT
    public function setProjectPath($path){
        define('CKFINDER_PROJECT_PATH', $path);
    }
    
    // ĐƯỜNG DẪN TỚI FOLDER CHỨA CKFINDER
    public function setCkfinderSourcePath($path){
        define('CKFINDER_SOURCE_PATH', $path);
    }
    
    // THIẾT LẬP QUYỀN
    public function setAuthentication($status){
        define('CKFINDER_CAN_USE', $status);
    }
    
    // THIẾT LẬP QUYỀN
    public function setConnectorPath($path){
        define('CKFINDER_CONNECTOR_PATH', $path);
    }
    
    // THIẾT LẬP FOLDER UPLOAD
    public function setFolderUpload($path){
        define ('CKFINDER_FOLDER_UPLOAD', $path);
    }
    
    // BẮT ĐẦU XỬ LÝ CONNECTOR
    public function startConnector(){
        require_once './'.CKFINDER_SOURCE_PATH.'/core/connector/php/connector.php';
    }
    
    // HÀM TẠO MÃ HTML CKFINDER
    public function getHTML(){ ?>
        <!DOCTYPE html>
        <!--
        Copyright (c) 2007-2017, CKSource - Frederico Knabben. All rights reserved.
        For licensing, see LICENSE.html or http://cksource.com/ckfinder/license
        -->
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="robots" content="noindex, nofollow" />
            <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
            <title>CKFinder 3 - File Browser</title>
        </head>
        <body>

        <script type="text/javascript" src="<?php echo get_instance()->config->base_url(CKFINDER_SOURCE_PATH.'/ckfinder.js'); ?>"></script>
        <script type="text/javascript">
            //<![CDATA[
            (function ()
            {
                var config = {};
                // Always use 100% width and height when nested using this middle page.
                config.width = config.height = '100%';
                config.connectorPath = '<?php echo CKFINDER_CONNECTOR_PATH; ?>';
                // var ckfinder = new CKFinder(config);
                // ckfinder
                CKFinder.start(config);
                // CKFinder.start();
            })();
            //]]>
        </script>

        </body>
        </html>
    <?php }
}