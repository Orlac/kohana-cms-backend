<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Catch-all route for Captcha classes to run
Route::set('admin', 'admin/(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'admin',
		'action' => 'index',
		'group' => NULL));
