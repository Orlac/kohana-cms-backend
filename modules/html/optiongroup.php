<?php
namespace modules\html;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of captcha
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Optiongroup extends Ielement {
    //put your code here
    
    private $values;
    
    /**
     * @param array $values array(label => value)
     * 
     */
    public function setValues( array $values )
    {
	$this->values = $values;
	$this->hangRule('in_array', array(':value', array_values($values)) );
    }
    
    public function render( $element = null )
    {
	$captcha = \Captcha::instance();
	$captcha->render();
	$element = \Form::input($this->htmlName(), $this->value, $this->attrs() );
	
	$options = array();
	foreach($this->values as $key => $val)
	{
	    $element = new \modules\html\Ielement($this->htmlName());	
	    $attrs = array();
	    $attrs['id'] = $this->htmlName().$val;
	    $element->render(\Form::radio($this->htmlName(), $val, ($this->value == $val), $attrs ));	    	    	    
	    
	    $options[] = '<li><label for="'.$attrs['id'].'">'.$key.'</label>'.$element->show().'</li>';
	}
	
	$strEl = implode('', $options);
	$strEl = '<ul>'.$strEl.'</ul>';
	parent::render($strEl);
    }
    
}

?>
