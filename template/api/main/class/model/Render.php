<?php

//Presentacion de datos
class Render {

  public $advanced; //array multiple cuya raiz es [field,option,value], ejemplo: [["nombre","=","unNombre"],[["apellido","=","unApellido"],["apellido","=","otroApellido","OR"]]]
  public $search;
  public $order;
  public $page;
  public $size;
  public $history; //true (historicos), false (activos), null (todos)

  public function __construct() {
    $this->advanced = array();
    $this->history = array();
    $this->order = array();
    $this->page = 1;
    $this->size = false; //si es false o 0 se incluyen todas las paginas, no se define tamanio
    $this->search = null;
  }

  public function setAdvanced (array $advanced = null) { $this->advanced = $advanced; }

  public function addAdvanced ($advanced = null) { if(!empty($advanced)) array_push ( $this->advanced, $advanced ); }

  public function setSearch ($search = null) { $this->search = $search; }

  public function setHistory ($history = null) { $this->history = $history; }


  //Ordenamiento
  //@param array $order Ordenamiento
  //  array(
  //    nombre_field => asc | desc,
  //  )
  //@param array $orderDefault Ordenamiento por defecto.
  //  array(
  //    nombre_field => asc | desc,
  //  )
  //Dependiendo del motor de base de datos utilizado, puede requerirse que el campo utilizado en el ordenamiento sea incluido en los campos de la consulta
  public function setOrder (array $order = [], array $orderDefault = []) {
    foreach($order as $key => $value){
      if(array_key_exists($key, $orderDefault)){
        unset($orderDefault[$key]);
      }
    }

    $this->order = array_merge($order, $orderDefault);
  }

  public function setPagination($size, $page) {
    $this->size = $size;
    $this->page = $page;
  }

  public function setSize($size) { $this->size = $size; }

  public function setPage($page) { $this->page = $page; }

  public function getSize(){ return $this->size; }

  public function getPage(){ return $this->page; }

  public function getAdvanced(){ return $this->advanced; }

  public function getHistory(){ return $this->history; }

  public function getSearch(){ return $this->search; }

  public function getOrder(){ return $this->order; }

}
