<?php

require_once("generate/GenerateEntity.php");


class ComponentFieldsetArrayTs_formGroup extends ComponentFieldsetTs_formGroup {


  public function generate() {
    $this->start();
    $this->nf();
    $this->fk();
    $this->end();

    return $this->string;
  }


  protected function start() {
    $this->string .= "  formGroup(index: string | number = null, sync: { [index: string]: any } = null): void {
    let fg: FormGroup = this.dd.fb.group({
      id:'',
";
  }


  protected function end() {
    $this->string .= "
    var r = new {$this->entity->getName("XxYy")};
    if(index !== null) object.assign(r, this.rows[index]);
    fg.reset(r);
    this.fieldsetForm.push(fg);
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
