<?php
namespace common;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IUTable
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Ormtable  extends \ORM {
    //put your code here

    public $errors = array();

    const ASC = 'asc', DESC = 'desc';
    
    public function __call($name, $arguments) 
    {    
	if( count($arguments) > 0 )
        {
            if(method_exists($this, '_set_'.$name))
            {
                call_user_func_array(array($this, '_set_'.$name),  $arguments );
		
            }else
            {
		parent::__set($name, $arguments);
            }
        }elseif(method_exists($this, '_'.$name))
        {
            $m = '_'.$name;
            return $this->$m();
        }else
        {
            return parent::__get($name);
        }
        return NULL;
    }
}

?>
