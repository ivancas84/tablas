<?php

/**
 * Generar un elemento (archivo).
 */
abstract class GenerateFileField extends GenerateFile{
	
		
	protected $fieldConfig;

	public function __construct($directorio, $nombreArchivo, Field $fieldConfig){
		$this->fieldConfig = $fieldConfig;
		parent::__construct($directorio, $nombreArchivo);
		
	}
	
	function getField() {
		return $this->fieldConfig;
	}

	function setField(Field $fieldConfig) {
		$this->fieldConfig = $fieldConfig;
	}
}
