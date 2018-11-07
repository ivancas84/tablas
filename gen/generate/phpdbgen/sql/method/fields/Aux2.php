Skip to content
Features
Business
Explore
Marketplace
Pricing

Search

Sign in or Sign up
1 0 0 ivancas84/tablas
 Code  Issues 0  Pull requests 0  Projects 0  Insights
Join GitHub today
GitHub is home to over 28 million developers working together to host and review code, manage projects, and build software together.

tablas/gen/generate/phpdbgen/sql/method/fields/Aux.php
7ca73cd  22 days ago
@ivancas84 ivancas84 refactorizacion del uso de prefijo de identificacion en clases del mo…
     
53 lines (34 sloc)  1.04 KB
<?php
require_once("generate/GenerateEntityRecursive.php");
class ClassSql_fieldsAux extends GenerateEntityRecursive {
  public $fields = [];
  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }
  protected function start(){
    $this->string .= "  public function fieldsAux(){
    \$fields = \$this->_fieldsAux();
";
  }
  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$f = Dba::sql('{$entity->getName()}', '{$prefix}')->_fieldsAux()) \$fields .= concat(\$f, ', ', '', \$fields);
";
  }
  protected function end(){
    //$pos = strrpos($this->string, ",");
    //$this->string = substr_replace($this->string , "" , $pos, 2);
    $this->string .= "    return \$fields;
  }
";
  }
}
© 2018 GitHub, Inc.
Terms
Privacy
Security
Status
Help
Contact GitHub
Pricing
API
Training
Blog
About
Press h to open a hovercard with more details.s