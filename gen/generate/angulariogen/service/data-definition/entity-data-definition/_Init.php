<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Init extends GenerateEntity {


  public function generate() {
    //if(!$this->entity->hasRelations()) return "";

    $this->start();
    $this->body();
    $this->fk();
    $this->u_();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  //Inicializar datos, puede requerir datos de otras entidades que obligatoriamente deben estar en la cache
  init(row: { [index: string]: any }, sync: { [index: string]: any } = null): { [index: string]: any } {
    if(!row) return null;
    let row_: { [index: string]: any } = Object.assign({}, row);

";
  }

  protected function body(){
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "date":
        case "year":
          $this->date($field);  break;

        case "timestamp":
        case "time":
          $this->timestamp($field); break;
      }
    }
  }


  protected function fk(){
    $fields = $this->getEntity()->getFieldsFk();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->labelGet($field);
      }
    }
  }

  protected function u_(){
    $fields = $this->getEntity()->getFieldsU_();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->labelGetU_($field);
      }
    }
  }




  protected function end(){
    $this->string .= "    return row_;
  }

";
  }






  protected function date(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) row_[\"" . $field->getName() . "\"] = this.dd.parser.date(row[\"" . $field->getName() . "\"]);
";
  }


  protected function timestamp(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) row_[\"" . $field->getName() . "\"] = this.dd.parser.timestamp(row[\"" . $field->getName() . "\"]);
";
  }

  protected function labelGet(Field $field){
    $this->string .= "    if(this.dd.isSync('" . $field->getName() . "', sync)) row_[\"" . $field->getName() ."_\"] = this.dd.labelGet(\"" . $field->getEntityRef()->getName() . "\", row[\"" . $field->getName() . "\"]);
";
  }

  protected function labelGetU_(Field $field){
    $this->string .= "    if(this.dd.isSync('" . $field->getName() . "', sync)) this.dd.labelGet(\"" . $field->getEntity()->getName() . "\", row[\"" . $field->getAlias("_") . "\"]);
";
  }
}
