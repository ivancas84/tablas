<?php

class AngularIoGen {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }


  public function appModuleTs(){
    require_once("generate/AppModule/AppModuleTs.php");
    $gen = new AppModuleTs($this->structure);
    $gen->generate();
  }


  public function appRoutingModuleTs(){
    require_once("generate/AppRoutingModule/AppRoutingModuleTs.php");
    $gen = new AppRoutingModuleTs($this->structure);
    $gen->generate();
  }




  public function appComponentOptions(){
    require_once("generate/AppOptionsComponent/AppOptionsComponentTs.php");
    $gen = new AppOptionsComponentTs($this->structure);
    $gen->generate();

    require_once("generate/AppOptionsComponent/AppOptionsComponentHtml.php");
    $gen = new AppOptionsComponentHtml($this->structure);
    $gen->generate();
  }












  public function dataDefinition(){
    /*
    require_once("generate/service/datadefinition/initFilterField/InitFilterField.php");
    $gen = new ServiceDataDefinition_InitFilterField($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/serverFilterField/ServerFilterField.php");
    $gen = new ServiceDataDefinition_ServerFilterField($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/checkField/CheckField.php");
    $gen = new ServiceDataDefinition_CheckField($this->structure);
    $gen->generate();


    require_once("generate/service/datadefinition/init/Init.php");
    $gen = new ServiceDataDefinition_Init($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/initRelation/InitRelation.php");
    $gen = new DataDefinition_InitRelation($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/extend/Extend.php");
    $gen = new ServiceDataDefinition_Extend($this->structure);
    $gen->generate();


    require_once("generate/service/datadefinition/form/Form.php");
    $gen = new ServiceDataDefinition_Form($this->structure);
    $gen->generate();


    require_once("generate/service/datadefinition/serverField/ServerField.php");
    $gen = new ServiceDataDefinition_ServerField($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/labelRow/LabelRow.php");
    $gen = new ServiceDataDefinition_LabelRow($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/labelRowMain/LabelRowMain.php");
    $gen = new ServiceDataDefinition_LabelRowMain($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/persistRow/PersistRow.php");
    $gen = new ServiceDataDefinition_PersistRow($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/persistRows/PersistRows.php");
    $gen = new ServiceDataDefinition_PersistRows($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/config/Config.php");
    $gen = new GenerateServiceDataDefinitionConfig($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/configField/ConfigField.php");
    $gen = new DataDefinitionConfigField($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/storageRow/StorageRow.php");
    $gen = new DataDefinition_StorageRow($this->structure);
    $gen->generate();

    require_once("generate/service/datadefinition/formField/FormField.php");
    $gen = new DataDefinition_FormField($this->structure);
    $gen->generate();*/


  }


  protected function dataDefinitionLoader(){
    require_once("generate/angulariogen/DataDefinitionLoaderService/DataDefinitionLoaderService.php");
    $gen = new DataDefinitionLoaderService($this->structure);
    $gen->generate();
  }


  public function component(){
    require_once("generate/angulariogen/component/Component.php");
    $gen = new GenerateComponent($this->structure);
    $gen->generate();
  }

  public function service(){
    require_once("generate/angulariogen/service/Service.php");
    $gen = new GenerateService($this->structure);
    $gen->generate();
  }

  public function entity(Entity $entity){
    require_once("generate/angulariogen/class/entity/EntityMain.php");
    $gen = new TypescriptEntityMain($entity);
    $gen->generate();
  }

  public function generate(){
    $this->dataDefinitionLoader();
    $this->component();
    $this->service();

    foreach($this->structure as $entity) {
      $this->entity($entity);
    }

    //$this->appModuleTs();
    //$this->appRoutingModuleTs();
    //$this->appComponentOptions();
    //$this->dataDefinition();
  }

}
