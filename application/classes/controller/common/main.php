<?php
namespace controller\common;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main
 *
 * @author Antonio
 * orlac@rambler.ru
 */
abstract class Main extends \Controller_Template {
    //put your code here
    
    public static $isAjax = false;
    public $viewPath = THEME_PATH;
    public $vars = array();
    
    public $template = '/layout/main';
    
    public function before()
    {
        
	$this->template = $this->viewPath.'/'.$this->template;
        \Kohana::$config->load('debugbar')->set('auto_render', false);
	if(!DEBUG)
	{
	    \Kohana::$config->load('debugbar')->set('auto_render', false);
	}
        parent::before();
        $this->template->bind('content', $comtent);
	$this->template->bind('title', $title);
    }
    
    public function after()
    {
	\controller\common\Ajax::render($this);
	parent::after();
    }
    
    public function bindAjax($var, $value)
    {
	$this->vars[$var] = $value;
    }
    
    
}

?>
