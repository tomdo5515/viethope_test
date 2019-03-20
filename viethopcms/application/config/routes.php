<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the 'welcome' class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'frontend/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//route example: http://domain.tld/en/controller => http://domain.tld/controller
// $route['(\w{2})/(.*)'] = '$2';
// $route['(\w{2})'] = $route['default_controller'];

// rss
$route['feed'] = 'frontend/Rss/loadrss';

// save cookie
$route['student'] = 'frontend/TypeSwitcher/switchType/student';
$route['supporter'] = 'frontend/TypeSwitcher/switchType/supporter';
$route['member'] = 'frontend/TypeSwitcher/switchType/member';

//subscribe
$route['mail/subscribe/(([\w+-]+)(\.[\w+-]+)*@([a-zA-Z\d-]+\.)+[a-zA-Z]{2,6})']['get'] = 'frontend/Mailchimps/subscribe/$1';
$route['mail/unsubscribe/(([\w+-]+)(\.[\w+-]+)*@([a-zA-Z\d-]+\.)+[a-zA-Z]{2,6})']['get'] = 'frontend/Mailchimps/unsubscribe/$1';

// home
$route['home'] = 'frontend/home';

// paypal
$route['payment/success/ordertoken/(:num)'] = 'frontend/Paypal/success/$1';
$route['payment/canceled/ordertoken/(:num)'] = 'frontend/Paypal/canceled/$1';

// bài viết
$route['latest-news'] = 'frontend/article/news';
$route['latest-news/page/(:num)'] = 'frontend/article/news/$1';

$route['meet-our-scholars'] = 'frontend/article/meetsholar';
$route['meet-our-scholars/page/(:num)'] = 'frontend/article/meetsholar/$1';

$route['newsletter'] = 'frontend/article/newsletter';
$route['newsletter/page/(:num)'] = 'frontend/article/newsletter/$1';

$route['category/(:any)'] = 'frontend/article/category/$1';
$route['category/(:any)/page/(:num)'] = 'frontend/article/category/$1/$2';

$route['tag/(:any)'] = 'frontend/article/tag/$1';
$route['tag/(:any)/page/(:num)'] = 'frontend/article/tag/$1/$2';

$route['author/(:any)'] = 'frontend/article/author/$1';
$route['author/(:any)/page/(:num)'] = 'frontend/article/author/$1/$2';

$route['search/(:any)'] = 'frontend/search/searchfulltext/$1';
$route['search/(:any)/page/(:num)'] = 'frontend/search/searchfulltext/$1/$2';

$route['news/:any-(:num)'] = 'frontend/article/detail/$1';

$route['contact'] = 'frontend/contact';

//dashboard
$route['dashboard'] = 'dashboard/home';

//Auth
$route['dashboard/login'] = 'dashboard/auth/login';
$route['dashboard/logout'] = 'dashboard/auth/logout';

//RestFul
//$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
//$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8

//page
$route[':any-(:num)'] = 'frontend/Page/LoadContent/$1';
$route[':any-(:num)/:any-(:num)'] = 'frontend/Page/LoadChildPage/$1/$2';