<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * twig - Twig template engine implementation for CodeIgniter
 *
 * twig is not just a simple implementation of Twig template engine 
 * for CodeIgniter. It supports themes, layouts, templates for regular
 * apps and also for apps that use HMVC (module support).
 * 
 * @package   			CodeIgniter
 * @subpackage			twig
 * @category  			Config
 * @author    			Edmundas Kondrašovas <as@edmundask.lt>
 * @license   			http://www.opensource.org/licenses/MIT
 * @version   			0.8.5
 * @copyright 			Copyright (c) 2012 Edmundas Kondrašovas <as@edmundask.lt>
 */
/*
|--------------------------------------------------------------------------
| Template file extension
|--------------------------------------------------------------------------
|
| This lets you define the extension for template files. It doesn't affect
| how twig deals with templates but this may help you if you want to
| distinguish different kinds of templates. For example, for CodeIgniter
| you may use *.html.twig template files and *.html.jst for js templates.
|
*/

$config['twig']['template_file_ext'] = '.twig';


/*
|--------------------------------------------------------------------------
| Syntax Delimiters
|--------------------------------------------------------------------------
|
| If you don't like the default Twig syntax delimiters or if they collide 
| with other languages (for example, you use handlebars.js in your
| templates), here you can change them.
|
| Ruby erb style:
|
|	'tag_comment' 	=> array('<%#', '#%>'),
|	'tag_block'   	=> array('<%', '%>'),
|	'tag_variable'	=> array('<%=', '%>')
|
| Smarty style:
|
|    'tag_comment' 	=> array('{*', '*}'),
|    'tag_block'   	=> array('{', '}'),
|    'tag_variable'	=> array('{$', '}'),
|
*/

$config['twig']['delimiters'] = array
(
	'tag_comment' 	=> array('{#', '#}'),
	'tag_block'   	=> array('{%', '%}'),
	'tag_variable'	=> array('{{', '}}')
);


/*
|--------------------------------------------------------------------------
| Environment Options
|--------------------------------------------------------------------------
|
| These are all twig-specific options that you can set. To learn more about
| each option, check the official documentation.
|
| NOTE: cache option works slightly differently than in Twig. In Twig you
| can either set the value to FALSE to disable caching, or set the path
| to where the cached files should be stored (which means caching would be
| enabled in that case). This is not entirely convenient if you need to 
| switch between enabled or disabled caching for debugging or other reasons.
|
| Therefore, here the value can be either TRUE or FALSE. Cache directory
| can be set separately.
|
*/

$config['twig']['environment']['cache']              	= FALSE;
$config['twig']['environment']['debug']              	= FALSE;
$config['twig']['environment']['charset']            	= 'utf-8';
$config['twig']['environment']['base_template_class']	= 'Twig_Template';
$config['twig']['environment']['auto_reload']        	= NULL;
$config['twig']['environment']['strict_variables']   	= FALSE;
$config['twig']['environment']['autoescape']         	= FALSE;
$config['twig']['environment']['optimizations']      	= -1;



//$config['twig']['timezone']   	= 'Asia/Ho_Chi_Minh';
/*
|--------------------------------------------------------------------------
| Twig Cache Dir
|--------------------------------------------------------------------------
|
| Path to the cache folder for compiled twig templates. It is relative to
| CodeIgniter's base directory.
|
*/

$config['twig']['twig_cache_dir'] = APPPATH . 'cache/twig/';

/*
|--------------------------------------------------------------------------
| Themes Base Dir
|--------------------------------------------------------------------------
|
| Directory where themes are located at. This path is relative to 
| CodeIgniter's base directory OR module's base directory. For example:
|
| $config['themes_base_dir'] = 'themes/';
|
| It will actually mean that themes should be placed at:
|
| {APPPATH}/themes/ and {APPPATH}/modules/{some_module}/themes/.
|
| NOTE: modules do not necessarily need to be in {APPPATH}/modules/ as
| twig will figure out the paths by itself. That way you can package 
| your modules with themes.
|
| Also, do not forget the trailing slash!
|
*/

$config['twig']['themes_base_dir'] = 'views/themes/';


/*
|--------------------------------------------------------------------------
| Include APPPATH
|--------------------------------------------------------------------------
|
| This lets you include the APPPATH for the themes base directory (only for
| the application itself, not the modules). See the example below.
|
| Suppose you have:
| $config['themes_base_dir'] = 'themes/'
| $config['include_apppath'] = TRUE
|
| Then the path will be {APPPATH}/themes/ but if you set this option to
| FALSE, then you will have themes/.
|
| This is useful for when you want to have the themes folder outside the
| application (APPPATH) folder.
|
*/

$config['twig']['include_apppath'] = TRUE;


/*
|--------------------------------------------------------------------------
| Default frontend theme
|--------------------------------------------------------------------------
*/

$config['twig']['default_theme'] = 'default';

/*
|--------------------------------------------------------------------------
| Default backend theme
|--------------------------------------------------------------------------
*/

$config['twig']['default_backend_theme'] = 'backend';

/*
|--------------------------------------------------------------------------
| Default layout
|--------------------------------------------------------------------------
*/

$config['twig']['default_layout'] = 'index';


/*
|--------------------------------------------------------------------------
| Default template
|--------------------------------------------------------------------------
*/

$config['twig']['default_template'] = 'index';


/*
|--------------------------------------------------------------------------
| Auto-reigster functions
|--------------------------------------------------------------------------
|
| Here you can list all the functions that you want twig to automatically
| register them for you.
|
| NOTE: only registered functions can be used in Twig templates. 
|
*/

$config['twig']['register_functions'] = array
(
	'md5',
	'site_url', 
	'current_url', 
	'current_menu', 
	'form_open', 
	'validation_errors', 
	'time_elapsed_string', 
	'slugify', 
	'GMT0toGMT7', 
	'isMobile', 
	'lang',
	'shortens'//'site_url', 'base_url', 'uri_string', 'anchor', 'url_title'
);


/*
|--------------------------------------------------------------------------
| Auto-reigster filters
|--------------------------------------------------------------------------
|
| Much like with functions, list filters that you want twig to 
| automatically register them for you.
|
| NOTE: only registered filters can be used in Twig templates. Also, keep
| in mind that a filter is nothing more than just a regular function that
| acceps a string (value) as a parameter and outputs a modified/new string.
|
*/

$config['twig']['register_filters'] = array
(

);

/*
|--------------------------------------------------------------------------
| Title separator
|--------------------------------------------------------------------------
|
| Lets you specify the separator used in separating sections of the title 
| variable.
|
*/

$config['twig']['title_separator'] = ' | ';

/*
|--------------------------------------------------------------------------
| Title site default
|--------------------------------------------------------------------------
|
| Lets you specify the separator used in separating sections of the title 
| variable.
|
*/

$config['twig']['title'] = 'Viethop studio';