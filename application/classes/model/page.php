<?php
namespace model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Page extends \common\Ormtable {
    //put your code here
    
    public $id_, $parent_, $title_, $content_, $link_, $params_, $path_;
    
    protected $_table_name = 'page';
    
    public function _path()
    {
        $s = true;
        $parent = $this->parent();
        $pages = array();
        if($parent != 0)
        {
            while($s)
            {
                $p = new Page();
                $p->where('id', '=', $parent)->find()->reload();
                $pages[] = $p;
                if($p->parent() == 0)
                    $s = false;
            }
        }
        return $pages;
    }
    
    public function _set_path()
    {
        
    }
    
    public function getBayUrl($url)
    {
        $res = $this->where('link', '=', $url)
		->find()->reload();
        //print_r($res);
        return $this;
    }
    
    
}

?>
