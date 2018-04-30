<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_FormGroup extends GenerateEntity {


  public function generate() {

    $this->start();
    $this->nf();
    $this->fk();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  formGroup(sync: { [index: string]: any } = null): FormGroup {
    return this.dd.fb.group({
      id:'',
";
  }

  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {

        default: $this->defecto($field); //name, email
      }
    }
  }

  protected function fk(){
    $fields = $this->getEntity()->getFieldsFk();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {

        default: $this->defecto($field); //name, email
      }
    }
  }



  protected function u_(){
    $fields = $this->getEntity()->getFieldsU_();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->fieldU_($field);
      }
    }
  }


  protected function end(){
    $this->string .= "    });
  }

";
  }





  protected function defecto(Field $field) {
      if($field->isNotNull()) $this->string .= "      " . $field->getName() . ": ['', Validators.required ],
";
      else $this->string .= "      " . $field->getName() . ": '',
";
  }






}
