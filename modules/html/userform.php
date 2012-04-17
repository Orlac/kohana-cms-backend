<?php
namespace modules\html;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Userform {
    //put your code here
    
    protected $elements = array(), $name = null;
    public $request;
    public $errors = array();
    public $showError = false;
    
    /**
     * $request = $_POST/$_GET;
     */
    public function __construct($name = null, &$request = null) {
        $this->name = $name;
        $this->request = ($request !== null)? $request : $_POST;
    }
    
    public function addElement( Ielement $element )
    {
        $element->setParentFormName($this->name);
        $element->request = &$this->request;
	$r = self::getRequest($this->request, $this->name) ;
	$v = \Validation::factory($r);
	if($v->offsetExists($element->name))
	    $element->value = $v->offsetGet($element->name);
	$this->elements[] = $element;
        
    }
    
    public function removeElement( Ielement $element )
    {
	unset( $this->elements[ array_search($element, $this->elements ) ] );
    }
    
    public function getElements( )
    {
        return $this->elements;
    }
    
    
    
    public function validate()
    {
	$r = self::getRequest($this->request, $this->name);
	if(!\Validation::factory($r)->data())
        {
            return ;
        }
            
        foreach( $this->elements as $element )
        {
            if( !$element->validate() )
             {
                //print_r($element->name.'<hr>');
                $this->errors[$element->name] = $element->errors; 
                //return false;
             }  
        }
        if(count($this->errors) > 0)
        {
            return false;
        }       
        return true;
    }
    
    
    public static function getRequest($request,  $name = null)
    {
	if($name)
	{
	    return isset($request[$name]) ? is_array($request[$name]) ? $request[$name] : array()  : array();
	}else
	{
	    return $request;
	}
    }
    
    public function showErrorBox()
    {
        //print_r($this->errors);
	
	if(count($this->errors) == 0)
                return '';
        return '<div class="errorBox" id="'.$this->name.'_errorBox" style="color: red; border-color: red; border-style: solid; border-width: 1px;padding:10px;">'.implode('<br>', $this->errors).'</div>';
    }
    
    public function data()
    {
        $r = ($this->name)? $this->request[$this->name] : $this->request;
	return \Validation::factory($r)->data();
    }
    
}

?>
