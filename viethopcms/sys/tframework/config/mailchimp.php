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
| KEY API
|--------------------------------------------------------------------------
|
| This lets you define the extension for template files. It doesn't affect
| how twig deals with templates but this may help you if you want to
| distinguish different kinds of templates. For example, for CodeIgniter
| you may use *.html.twig template files and *.html.jst for js templates.
|
*/

$config['mailchimp_api_key'] = 'ef560c862e0a6bf65ef8b59c278d0f52-us19';

$config['mailchimp_list_id'] = '08fee73379';

$config['mailchimp_verify_ssl'] = false;

$config['api_endpoint']= 'https://<dc>.api.mailchimp.com/3.0';