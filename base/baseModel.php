<?php

// Base for models
class BaseModel{

  protected $validators;
  public function __construct($attributes = null){
    foreach($attributes as $attribute => $value){
      if(property_exists($this, $attribute)){
        $this->{$attribute} = $value;
      }
    }
  }
  public function errors(){

    $errors = array();
    foreach($this->validators as $validator){
      $validator_errors = $this->{$validator}();
      $errors = array_merge($errors, $validator_errors);
    }
    return $errors;
  }

  public function validateStringLength($string, $length, $errors) {
    if (strlen($string) > $length) {
      $errors[] = 'Liian pitkä merkkijono!';
    }

    return $errors;
  }
}

?>