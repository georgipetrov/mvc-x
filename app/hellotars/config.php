<?php
/*** CONFIGURE THIS APP BELOW ***/

/*	 DOMAIN, DIRECTORY AND DATABASE
 * 	 url - here you should add your site public url without protocol and subdomain, e.g. mysite.com 
 *   dir - the name of the app directory on your server e.g. mysite
 *   db - the database configuration of the app
 *   debug_mode - If you enable debug mode, you will see debug information at the bottom of your page. To put this app in debug mode you need to set it to 1, otherwise leave it 0.
 */

$config[] = array(
'url'=>array('hellotars.com'),
'dir'=>'hellotars',
'db' => array(
	'type'=>'mysql',
	'host'=>'localhost',
	'name'=>'',
	'username'=>'',
	'password'=>'',
	'table_prefix'=>''
	),
'debug_mode'=> 1
);



