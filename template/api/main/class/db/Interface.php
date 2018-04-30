<?php


interface DbInterface {
  
  //datos de la base de datos
  public function getDbms();  
  public function getSchema();  
  public function getSchemaDot();
  
  //ejecutar consulta a la base de datos   
  public function query($query);
  public function multiQuery($query);
  public function multiQueryTransaction($query);
  
  //procesar resultado de consulta
  public function numRows($result);
  public function fetchAll($result);
  public function fetchAllColumns($result, $fieldNumber);
  public function fetchAssoc($result);
  public function fetchRow($result);
  public function begin();
  public function commit();
  public function rollback();
  public function close();
  
  
  //public function dataSeek($result, $rowNumber);
  
}
