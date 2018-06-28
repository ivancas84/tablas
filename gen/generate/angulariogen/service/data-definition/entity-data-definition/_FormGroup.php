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


  protected function start() {
    $this->string .= "  formGroup(sync: { [index: string]: any } = null): FormGroup {
    let fg: FormGroup = this.dd.fb.group({
      id:'',
";
  }

  protected function nf() {
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "checkbox": $this->checkbox($field); break;
        case "timestamp": $this->timestamp($field); break;

        default: $this->defecto($field); //name, email
      }
    }

    $this->string .= "    });
";
  }

  protected function fk() {
    $fields = $this->getEntity()->getFieldsFk();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->defectoFk($field); //name, email
      }
    }
  }



  protected function u_() {
    $fields = $this->getEntity()->getFieldsU_();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->fieldU_($field);
      }
    }
  }


  protected function end() {
    $this->string .= "    return fg;
  }

";
  }



  protected function checkbox(Field $field) {
      $this->string .= "      " . $field->getName() . ": false,
";
  }

  protected function defecto(Field $field) {
      if($field->isNotNull()) $this->string .= "      " . $field->getName() . ": ['', Validators.required ],
";
      else $this->string .= "      " . $field->getName() . ": '',
";
  }

  protected function timestamp(Field $field) {
    $this->string .= "      " . $field->getName() . ": this.dd.fb.group({
";
    if($field->isNotNull()) {
      $this->string .= "        date: ['', Validators.required ],
        time: ['', Validators.required ],
";
    } else {
      $this->string .= "        date: '',
        time: ''
";
    }
    $this->string .= "      }),
";
  }


  protected function defectoFk(Field $field) {
      $validator = ($field->isNotNull()) ?  ", Validators.required" : "";

      $this->string .= "    if(this.dd.isSync('{$field->getName()}', sync)) fg.addControl('{$field->getName()}', new FormControl(''{$validator}));
";
  }

}
