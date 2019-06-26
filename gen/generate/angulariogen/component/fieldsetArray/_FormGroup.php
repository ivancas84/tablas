<?php

require_once("generate/GenerateEntity.php");


class ComponentFieldsetArrayTs_formGroup extends FieldsetTs_formGroup {


  protected function start() {
    $this->string .= "  formGroup(values: { [index: string]: any } = null): void {
    let fg: FormGroup = this.dd.fb.group({
      id:'',
";
  }


  protected function end() {
    $this->string .= "
    this.pushFormGroup(fg, values);
  }

";
  }


}
