<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('debugbar/media', 'debugbar/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'debugbar',
		'file'       => NULL,
	));
	
register_shutdown_function('debugbar::render');