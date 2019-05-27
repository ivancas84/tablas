<?php

class GenerateComponent {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }


  public function generate(){
    foreach($this->structure as $entity){
      $this->table($entity);
      $this->show($entity);
      $this->search($entity);
      $this->admin($entity);
      $this->fieldset($entity);
      $this->fieldsetArray($entity);

    }
  }

  protected function admin($entity){
    require_once("generate/angulariogen/component/admin/AdminTs.php");
    $gen = new ComponentAdminTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/admin/AdminHtml.php");
    $gen = new ComponentAdminHtml($entity);
    $gen->generate();
  }


  protected function fieldset($entity){
    require_once("generate/angulariogen/component/fieldset/FieldsetTs.php");
    $gen = new ComponentFieldsetTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/fieldset/FieldsetHtml.php");
    $gen = new ComponentFieldsetHtml($entity);
    $gen->generate();
  }


  protected function fieldsetArray($entity){
    require_once("generate/angulariogen/component/fieldsetArray/FieldsetArrayTs.php");
    $gen = new ComponentFieldsetArrayTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/fieldsetArray/FieldsetArrayHtml.php");
    $gen = new ComponentFieldsetArrayHtml($entity);
    $gen->generate();
  }



  protected function search($entity){
    require_once("generate/angulariogen/component/search/SearchTs.php");
    $gen = new ComponentSearchTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/search/SearchHtml.php");
    $gen = new ComponentSearchHtml($entity);
    $gen->generate();
  }



  protected function show($entity){
    require_once("generate/angulariogen/component/show/ShowTs.php");
    $gen = new ComponentShowTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/show/ShowHtml.php");
    $gen = new ComponentShowHtml($entity);
    $gen->generate();
  }



  protected function table($entity){
    require_once("generate/angulariogen/component/table/TableTs.php");
    $gen = new ComponentTableTs($entity);
    $gen->generate();

    require_once("generate/angulariogen/component/table/TableHtml.php");
    $gen = new ComponentTableHtml($entity);
    $gen->generate();
  }

}
