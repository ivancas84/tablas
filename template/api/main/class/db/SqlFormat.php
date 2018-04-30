<?php

/**
 * Metodos para dar formato sql y para verificar si los valores estan correctamente definidos. Se recibe en formato "estandar" y se retorna en formato sql para ser utilizado directamente en PHP
 * Esta clase no esta pensada para dar formato inverso, es decir, de sql a estandar
 * Importante, para los valores null de la base de datos se define el string "null"!!! Si un campo no tiene valor definido, no sera considerado en la administracion
 * Ejemplo de uso:
 *    $sqlFormat = new SqlFormat($db);
 *    $date = $sqlFormat->timestamp("2015-05-28 10:00:00"); //los dates se reciben en el formato indicado
 *    echo $date; //imprimira "'2015-05-28 10:00:00'" Se incluyen las comillas!!!
 * 
 *    $numeric = $sqlFormat->numeric("2"); //los numeric pueden recibirse como string o number
 *    echo $numeric; //imprimira "2" No se incluyen las comillas!!!
 * 
 *    $numeric = $sqlFormat->string("2"); 
 *    echo $numeric; //imprimira "'2'" Se incluyen las comillas!!!
 * 
 *    $date = $sqlFormat->date("null"); //se envia el string "null"
 *    echo $date; //imprimira "null" Se devuelve el string "null"!!!
 * 
 *    $date = $sqlFormat->date(null); //error, no es un tipo date
 */
class SqlFormat{
  
  private $db; //Db. Ciertos metodos, en particular escapeString, necesitan utilizar metodos definidos en una conexion de base de datos conectada
  
  public function __construct(DbInterface $db) { $this->db = $db; }
  
  public function getDb() { return $this->db; }
    
  /**
   * Definir valor numerico para la base de datos
   *
   * @param mixed $value Valor a definir.
   *    'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function numeric($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    if ( !is_numeric($value) ) throw new Exception('Valor numerico incorrecto: ' . $value);
    else return $value;
  }
  
  /**
   * Definir valor numerico entero mayor a 0 para la base de datos
   *
   * @param $value Valor a definir.
   *    'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function positiveIntegerWithoutZerofill($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    if ((!is_numeric($value)) && (!intval($value) > 0)) throw new Exception('Valor entero positivo sin ceros incorrecto: ' . $value);
    else return $value;
  }
  
  /**
   * Definir valor timestamp para la base de datos
   *
   * @param $value Valor a definir.
   *     'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function timestamp($value){      
    if($value == 'null') return 'null';
    
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
          
    if ( !$datetime ) throw new Exception('Valor fecha y hora incorrecto: ' . $value);
    else $newVal = "'" . $datetime->format('Y-m-d H:i:s') . "'";
      
    return $newVal;
  }
  
  /**
   * Definir valor time para la base de datos
   *
   * @param $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function time($value){      
    if($value == 'null') return 'null';
    
    $time = DateTime::createFromFormat('H:i', substr($value, 0, 5));
          
    if ( !$time ) throw new Exception('Valor fecha y hora incorrecto: ' . $value);
    else $newVal = "'" . $time->format('H:i') . "'";
    
    return $newVal;
  }
  
  
  /**
   * Definir valor date para la base de datos
   * 
   * @param $value Valor date a definir. 'null' Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function date($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    $date = DateTime::createFromFormat('Y-m-d', $value);
        
    if ( !$date ) throw new Exception('Valor fecha incorrecto');
    else $newVal = "'" . $date->format('Y-m-d') . "'";
          
    unset ( $date );
    
    return $newVal;
  }
  
  /**
   * Definir valor year para la base de datos
   * 
   * @param $value Valor date a definir. 'null' Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function year($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    $date = DateTime::createFromFormat('Y-m-d', $value);
    if ( !$date ) throw new Exception('Valor fecha incorrecto');
    else $newVal = "'" . $date->format('Y') . "'";
          
    return $newVal;
  }
  
  
  /**
   * Definir valor boolean para la base de datos
   *
   * @param $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
   */
  public function boolean($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    return ( settypebool($value) ) ? 'true' : 'false';
  }
  
  
  /**
   * Definir valor blob para la base de datos
   *
   * @param $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */  
  public function blob($value) {
    if(is_null($value) || ($value === 'null')) return 'null';
    
    throw new Exception('En construccion');
    
    //TODO en construccion
    
    /*
    if ( (isset($value['tmp_name']) ) && ( is_uploaded_file($value['tmp_name']) ) ) {
      //definir contenido
      $newVal['name'] = file_get_contents($value['tmp_name']);
      $newVal['name'] = "'" . $this->getDb()->escape_binary($newVal) . "'";
    
      //definir extension
      $pathinfo = pathinfo($value['name']);
      $newVal['extension'] = "'" . $pathinfo['extension'] . "'";
  
      //definir content-type
      $newVal['content_type'] = "'" . $value['type'] . "'";
  
    }*/
  }
  
  
  /**
   * Definir string
   *
   * @param string $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function string($value){
    if(is_null($value) || ($value === 'null')) return 'null';
    
    if (!is_string($value)) throw new Exception('Valor de caracteres incorrecto: ' . $value);
    else return "'" . $value . "'";
  }
  
  /**
   * Definir string
   *
   * @param string $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
   * @throws Exception si value no se encuentra correctamente definido
   */
  public function escapeString($value){
    if($value == 'null') return 'null';
    
    $v = (is_numeric($value)) ? strval($value) : $value;
    if (!is_string($v)) throw new Exception('Valor de caracteres incorrecto: ' . $v);
    else $escapedString = $this->getDb()->escapeString($v);
    return "'" . $escapedString . "'";
  }
  
}