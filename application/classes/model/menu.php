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
class Menu extends \common\Ormtable {
    //put your code here
    
    public $id_, $parent_, $title_, $link_, $params_ ;
    
    protected $_table_name = 'page';
    
    public function getList($parent = 0)
    {
        $arr = array();
        $res = $this->where('menu', '=', '1')->order_by('parent', 'asc')
		->find_all()->as_array();
        $arr = $this->getChilds($res, 0);
        return $arr;
    }
    
    protected function getChilds($list, $parent)
    {
        $res = array();
        foreach($list as $key => $val)
        {
            if($val->parent() == $parent)
            {
                $a = array();
                $a['childs'] = $this->getChilds($list, $val->id());
                $a['title'] = $val->title();
                $a['link'] = $val->link();
                $res[] = $a;
            }
        }
        return $res;
    }
    
    
}

?>
