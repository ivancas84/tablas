<?php

require_once("class/model/Render.php");

//Presentacion de datos
class RenderAux extends Render {
  /**
   * Implementa metodos auxiliares a los que utiliza Render
   * Habitualmente son utilizados para consultas avanzadas que utilicen group by y funciones de agregacion
   */



  protected $group = array(); //campos de agrupacion
  protected $avg = array(); //campos a los que se aplicara avg
  protected $count = array(); //campos a los que se aplicara count
  protected $sum = array(); //campos a los que se aplicara sum
  protected $min = array(); //campos a los que se aplicara min
  protected $max = array(); //campos a los que se aplicara max
  /**
   * Cada elemento del array sigue el siguiente formato: ["alias utilizado en la agregacion" => "alias del campo" ] 
   * el "alias del campo" sera interpretado a traves del metodo de mapeo de campos
   */

  protected $having = array(); //condicion avanzada de agrupamiento
  /**
   * formato: ["condicion", "conexion"], puede ser array multiple
   */

  public function setGroup (array $group = null) { $this->group = $group; }
  public function getGroup () { return $this->group; }

  public function setCount (array $count = null) { $this->count = $count; }
  public function getCount () { return $this->count; }

  public function setSum (array $sum = null) { $this->sum = $sum; }
  public function getSum () { return $this->sum; }

  public function setMin (array $min = null) { $this->min = $min; }
  public function getMin () { return $this->min; }

  public function setAvg (array $avg = null) { $this->avg = $avg; }
  public function getAvg () { return $this->avg; }

  public function setMax (array $max = null) { $this->max = $max; }
  public function getMax () { return $this->max; }
}
