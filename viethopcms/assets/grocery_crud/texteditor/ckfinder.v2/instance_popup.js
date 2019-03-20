/*
Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
For licensing, see license.txt or http://cksource.com/ckfinder/license
*/
// 
// HTML demo
// <div class="form-control">
//     <input class="ckfinder-input" style="width:60%" type="text" value="">
//     <button class="ckfinder-popup button-a button-a-background" >Browse Server</button>
// </div>

//CKFinder.popup(basePath, width, height, selectActionFunction, callback) 
$( ".ckfinder-popup" ).on( "click", function() {
    var config = {};
    // Always use 100% width and height when nested using this middle page.
    config.width = config.height = '100%';
    config.rememberLastFolder = false;
    config.startupPath = "Images://";
    config.connectorPath = '/dashboard/filemanagement/connector';
    var ckfinder = new CKFinder(config);
    var select_upload = $(this).parent().next();
    ckfinder.selectActionFunction = function( fileUrl, data ) {
        console.log(data);
        select_upload.val(fileUrl);
    }
    ckfinder.popup({
        width: 800,
        height: 600
    });
});
