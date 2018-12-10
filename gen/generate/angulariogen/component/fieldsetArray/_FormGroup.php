<?php

require_once("generate/GenerateEntity.php");


class ComponentFieldsetArrayTs_formGroup extends ComponentFieldsetTs_formGroup {


  protected function start() {
    $this->string .= "  formGroup(index: string | number = null, sync: { [index: string]: any } = null): void {
    let fg: FormGroup = this.dd.fb.group({
      id:'',
";
  }


  protected function end() {
    $this->string .= "
    var r = new {$this->entity->getName("XxYy")};
    if(index !== null) Object.assign(r, this.rows[index]);
    fg.reset(r);
    this.fieldsetForm.push(fg);
  }

";
  }


}
