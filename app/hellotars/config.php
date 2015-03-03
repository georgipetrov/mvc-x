<?php
/*** CONFIGURE THIS APP BELOW ***/

/*	 DOMAIN, DIRECTORY AND DATABASE
 * 	 url - Here you should add your site public url without protocol and subdomain, e.g. mysite.com 
 *   dir - The name of the app directory on your server e.g. mysite
 *   db - The database configuration of the app
 *   template - Specifies the name of the template you want to use. The template name should be a folder in /view/. If set to false, it will not use a template.
 *   smart_elements - If true, the response will parse all smart elements. A smart element is e.g. [widget:breadcrumb]
 *   debug_mode - If you enable debug mode, you will see debug information at the bottom of your page. To put this app in debug mode you need to set it to 1, otherwise leave it 0.
 */

$config[] = array(
'url'=>array('joro.isenselabs.com/mvc-x'),
'dir'=>'hellotars',
'db' => array(
	'type'=>'mysql',
	'host'=>'localhost',
	'name'=>'',
	'username'=>'',
	'password'=>'',
	'table_prefix'=>''
	),
'template'=> false,
'smart_elements'=> true,
'debug_mode'=> 0
);



