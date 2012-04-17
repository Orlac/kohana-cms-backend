<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('MODPATH', realpath( dirname(__FILE__) ).'/'.$modules.'/');
define('SYSPATH', realpath( dirname(__FILE__) ).'/'.$system.'/');


define('DEBUG', true);
//define('DEBUG', false);

define('DEBUG_MAIL', 'dev@namars.ru, antonio.lightsoft@gmail.com');
define('CONTACT_MAIL', 'faq@good-food.ru, dev@namars.ru, antonio.lightsoft@gmail.com');
define('FROM_MAIL', 'dev@namars.ru');
define('FROM_NAME', 'good-food');

define('THEME_PATH', 'default');
