<?php
namespace modules\html;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Antonio
 */
class Ielement {
    //put your code here
    
    public $name;
    /**
     * array 
     * http://kohanaframework.org/3.2/guide/kohana/security/validation
     * @param	array(array($nameRule, array $params))  ;
     */
    public $rules = array();
    
    /**
     * ÐµÑÐ»Ð¸ ÑÐµÐ»ÐµÐºÑ‚
     * http://kohanaframework.org/3.2/guide/api/Form#select
     */
    public $options;
    public $value;
    public $label;
    public $request;
    public $errors;
    public $element;
    public $fileErrors = 'validation';
    
    protected $rElement;
    
    public function __construct($name, $form = null, &$request = null )
    {
        $this->name = $name;
	$this->form = $form;
        $this->request = ($request !== null)? $request : $_POST;
    }
    
    public function attrs()
    {
	
	//return array('name' => $this->name, 'placeholder' => \I18n::get($this->label) );
	/*
	 * onfocus="if(this.value=='Ïàðîëü') this.value=''" onblur="if(this.value=='') this.value='Ïàðîëü';" value="Ïàðîëü"
	 */
	return array(
	    'name' => $this->name, 
	    'onfocus' => "if(this.value=='".\I18n::get($this->label) ."') this.value=''",
	    'onblur' => "if(this.value=='') this.value='".\I18n::get($this->label) ."';",
	    'placeholder' => \I18n::get($this->label) );
    }
    
    /**
     * array 
     * http://kohanaframework.org/3.2/guide/kohana/security/validation
     * @param	$nameRule, array $params  ;
     */
    public function hangRule($rule, $params = null)
    {
	$this->rules[] = array( $rule, $params );
    }
    
    public function render( $element )
    {
	$this->element = $element;
	$el = '<div id="field_'.$this->name.'" class="elementForm" >';
	$el .= $element;
	$el .= '</div>';
	$this->rElement = $el;
    }
    
    public function htmlName()
    {
	return ($this->form) ? $this->form.'['.$this->name.']' : $this->name;
    }
    
    public function show(  )
    {
	return $this->rElement;
    }
    
    public function showLabel(  )
    {
	$el = '<div id="label_'.$this->form.'_'.$this->name .'" class="labelForm" >';
	$el .= \I18n::get($this->label);
	$el .= '</div>';
	return $el;
    }
    
    public function validate()
    {
	$r = Userform::getRequest($this->request, $this->form);
	
	$v = \Validation::factory($r);
	if($v->offsetExists($this->name))
	    $this->value = $v->offsetGet($this->name);
	$rules = $this->rules;
	while($rule = array_shift($rules))
	{
	    $v = $v->rule($this->name, $rule[0], $rule[1]);
	}
	if ($v->check())
        {
	    return true;
        }
	$this->errors = $this->getErrors($v->errors($this->fileErrors), $this->name );
	
    }
    
    protected function getErrors($errors, $name)
    {
	$err = '';
	while($error = array_shift($errors))
	{
	    $err .= $error;
	}
	return $err;
    }
    
    protected $form = null;
    public function setParentFormName($form)
    {
        $this->form = $form;
    }
}

?>
