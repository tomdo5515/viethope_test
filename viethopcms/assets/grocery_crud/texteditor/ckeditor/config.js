/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'en';
	// config.uiColor = '#AADC6E';
	
	// added code for ckfinder ------>
	config.filebrowserBrowseUrl = '/dashboard/filemanagement';
	config.filebrowserImageBrowseUrl = '/dashboard/filemanagement?type=Images';
	config.filebrowserFlashBrowseUrl = '/dashboard/filemanagement?type=Flash';
	config.filebrowserUploadUrl 	= '/dashboard/filemanagement?type=Files';
	config.filebrowserImageUploadUrl = '/dashboard/filemanagement?type=Images';
	config.filebrowserFlashUploadUrl = '/dashboard/filemanagement?type=Flash';
	// end: code for ckfinder ------>
	config.allowedContent = true;
	config.autoParagraph = false;

	config.contentsCss = '/themes/backend/css/bootstrap.min.css';
	config.extraPlugins = 'youtube';
	// Config toolbar
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms', 'list', 'indent' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
	
	config.removeButtons = 'Preview,Print,Save,NewPage,ShowBlocks';
};