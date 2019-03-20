<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| OAuth2 config
|--------------------------------------------------------------------------
|
| this all information for provider social
|
*/
/*
| -------------------------------------------------------------------
|  Facebook App details
| -------------------------------------------------------------------
|
| To get an facebook app details you have to be a registered developer
| at http://developer.facebook.com and create an app for your project.
|
|  facebook_app_id               string   Your facebook app ID.
|  facebook_app_secret           string   Your facebook app secret.
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_login_redirect_url   string   URL tor redirect back to after login. Do not include domain.
|  facebook_logout_redirect_url  string   URL tor redirect back to after login. Do not include domain.
|  facebook_permissions          array    The permissions you need.
|  facebook_graph_version        string   Set Facebook Graph version to be used. Eg v2.7
|  facebook_auth_on_load         boolean  Set to TRUE to have the library to check for valid access token on every page load.
*/

$config['facebook_app_id']              = '298478210516449';
$config['facebook_app_secret']          = 'c36cf7777a5bd526674d4d1dd0835ceb';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'dashboard/facebookmanage/authenticate';
$config['facebook_logout_redirect_url'] = 'logout';
$config['facebook_permissions']         = array('public_profile','publish_pages','email','manage_pages','pages_show_list');
$config['facebook_graph_version']       = 'v3.1';
$config['facebook_auth_on_load']        = TRUE;
$config['facebook_session_expired']     = 7200;


//google
$config['googleplus_appid'] = '343001209834-tmrgkemieuhsm2ce34e0ignis0s3tk9a.apps.googleusercontent.com';
$config['googleplus_appsecret'] = 'AYv17XlqMDERF9evE_pm56jj';

//linkedin
$config['linkedin_appid'] = 'local';
$config['linkedin_appsecret'] = 'local';