<?php
namespace controller\common;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ajax
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Ajax {
    //put your code here
    
    public static $_data = array();
    //public static $isAjax = false;
    
    public static function set($var, $value)
    {
	self::$_data[$var] = $value;
    }
    
    public static function isAjax()
    {
	return \Arr::get($_GET, 'ajax', false);
    }
    
    public static function render( \Controller_Template &$controller )
    {
	
	if(isset($_GET['ajax']))
	{
	    $controller->auto_render = false;
	    \Kohana::$config->load('debugbar')->set('auto_render', false);
	    if(count(self::$_data) > 0)
	    {
		echo json_encode (self::$_data);
		exit;
	    }
	}
	
		
    }
    
    public static function renderExtjs( \Controller_Template &$controller )
    {
	if(isset($_GET['ajax']))
	{
	    $controller->auto_render = false;
	    \Kohana::$config->load('debugbar')->set('auto_render', false);
	    $result = json_encode (self::$_data);
	    echo \Arr::get($_GET, 'callback').'('.$result.');';
	    exit;
	}	
    }
    
}

?>
