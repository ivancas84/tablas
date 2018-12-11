<?php

require_once("generate/GenerateEntity.php");


class ComponentFieldsetTs_formGroup extends GenerateEntity {


  public function generate() {
    $this->start();
    $this->nf();
    $this->fk();
    $this->end();

    return $this->string;
  }


  protected function start() {
    $this->string .= "  formGroup(): FormGroup {
    let fg: FormGroup = this.dd.fb.group({
      id:'',
";
  }

  protected function nf() {
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "checkbox": $this->checkbox($field); break;
        case "email": $this->email($field); break;
        case "dni": $this->dni($field); break;
        default: $this->defecto($field); //name, email, date, timestamp
          /**
           * La administracion de timestamp solo se define en el typescript para el caso de que se desee algun tipo de control
           * No se incluye en el html debido a que no hay un controlador actualmente definido que soporte timestamp
           * Para el caso de que se requiera se deben definir campos adicionales para la fecha y hora independientes.
           * Si el timestamp es un dato requerido, la validacion del formulario en el cliente proporcionara un error, es importante definir valor por defecto
           */
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
    $this->string .= "      {$field->getName()}: ['', {
";
    if($field->isNotNull()) $this->string .= "        validators: Validators.required,
";
    if($field->isUnique()) $this->string .= "        asyncValidators: this.checkUniqueField('{$field->getName()}'),
";
    $this->string .= "      }],
";
  }

  protected function email(Field $field) {
    $validators = array("Validators.email");
    if($field->isNotNull()) array_push($validators, "Validators.required");

    $asyncValidators = array();
    if($field->isUnique()) array_push($asyncValidators, "this.checkUniqueField('{$field->getName()}')");

    $this->string .= "      {$field->getName()}: ['', {
        validators: [" . implode(',', $validators) . "],
        asyncValidators: [" . implode(',', $asyncValidators) . "],
      }],
";
  }

  protected function dni(Field $field) {
    $validators = array("Validators.minLength(7)", "Validators.maxLength(9)", "Validators.pattern('^[0-9]*$')");
    if($field->isNotNull()) array_push($validators, "Validators.required");

    $asyncValidators = array();
    if($field->isUnique()) array_push($asyncValidators, "this.checkUniqueField('{$field->getName()}')");

    $this->string .= "      {$field->getName()}: ['', {
        validators: [" . implode(',', $validators) . "],
        asyncValidators: [" . implode(',', $asyncValidators) . "],
      }],
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

      $this->string .= "    if(this.isSync('{$field->getName()}')) fg.addControl('{$field->getName()}', new FormControl(''{$validator}));
";
  }

}
