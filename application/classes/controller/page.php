<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rout
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Controller_Page extends \controller\common\Main {
    //put your code here
    
    public function action_index()
    {
        $req = Request::initial();
        $page = new \model\Page();
        $page->getBayUrl($req->uri());
        
        $menu = new \model\Menu();
        $list = $menu->getList();
        
        try
        {
            $content = \View::factory( THEME_PATH.'/'.$req->uri() ) ; 
        }  catch (View_Exception $e)
        {
            $content = \View::factory( THEME_PATH.'/page' ) ; 
        }
        try
        {
            $navigation = \View::factory( THEME_PATH.'/'.$req->uri().'/navigation' ) ; 
        }  catch (View_Exception $e)
        {
            $navigation = \View::factory( THEME_PATH.'/page/navigation' ) ; 
        }
        try
        {
            $mainmenu = \View::factory( THEME_PATH.'/'.$req->uri().'/mainmenu' ) ; 
        }  catch (View_Exception $e)
        {
            $mainmenu = \View::factory( THEME_PATH.'/page/mainmenu' ) ; 
        }
        
        $path = $page->path();
        $navigation->bind('path', $path);
        $mainmenu->bind('list', $list);
        $content->bind('page', $page);
        $content->navigation = $navigation->render();
        
        $this->template->content = $content;
        $this->template->mainmenu = $mainmenu;
        $this->template->title = $page->title();
        \controller\common\Ajax::render($this);
    }
}

?>
