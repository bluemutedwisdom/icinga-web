<?php

class InvalidStoreFilterException extends AppKitException {}

/**
* GenericStoreFilter
* Container classes for filtering and apiProvider parsing.
* 
* Fields for the apiProvider parser must be defined in the subclasses.
* Because we need compatibility for PHP <5.3 we can't use the nice
* way via late static binding, so these fields are abstract and will be called
* from object instances instead.
*
* @author Jannis Moßhammer <jannis.mosshammer@netways.de>
**/
abstract class GenericStoreFilter extends StoreFilterBase 
{ 
    /**
    * The possible filter fields lie here, but should only be accessed  
    * via the addFilterField method
    * @var fields
    * @private 
    **/
    protected $__fields = array();
  
    protected $field;
    protected $value;
    protected $operator;
    
    /**
    * Creates a new filter
    * @params String    The filter field to set
    * @params String    The operator for the filter
    * @params mixed     The value that will be set for filtering
    *
    * @throws InvalidStoreFilterException   If validation fails
    **/
    public function __construct($field,$operator,$value) {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $errors = $this->checkIfValid();
        if($errors !== true) {
            throw new InvalidStoreFilterException($errors);
        }
        $this->customFormatter();
    }

    /**
    * Adds a filterable field to this StoreFilter 
    * @params GenericFilterField The filter to add
    **/
    protected function addFilterField(StoreFilterField $filter) {
        $this->__fields[] = $filter;
    }
    public function getPossibleFields() {
        return $this->__fields;
    }



    /**
    * The creation of @see GenericFilterField is done here in our subclasses
    **/
    abstract public function initFieldDefinition();

    /**
    * Custom function for customized filter validity checks. 
    * Returns true if no errors occured or a String that will be thrown as 
    * an InvalidStoreFilterException on error
    * @return Boolean/String    True if valid, errormessage instead
    **/
    protected function checkIfValid() {
        return true;   
    }

    /**
    * If any transformations on the field, operator or value should be performed 
    * they can be applied here in the filter subclass
    **/
    protected function customFormatter() {}

    public function __toArray() {
        return array(
            "field" => $this->field,
            "value" => is_array($this->value) ? impode($this->value) : $this->value,
            "operator" => $this->operator
        );
    }
    public function __toString() {
        return json_encode($this->__toArray());
    }
}