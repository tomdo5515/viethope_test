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
    var output = $(this).parent().next();
    CKFinder.popup( {
        connectorPath : '/dashboard/filemanagement/connector',
        startupPath : "Images://",
        chooseFiles: true,
        width: 800,
        height: 500,
        onInit: function( finder ) {
            finder.on( 'files:choose', function( evt ) {
                var file = evt.data.files.first();
                output.val(file.getUrl());
            } );

            finder.on( 'file:choose:resizedImage', function( evt ) {
                output.val(evt.data.resizedUrl);
            } );
        }
    } );
});
