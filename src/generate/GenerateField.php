<?php

/**
 * Generar un elemento (archivo).
 */
abstract class GenerateField extends Generate{
	
	protected $fieldConfig;

	public function __construct(Field $fieldConfig){
		$this->fieldConfig = $fieldConfig;
		parent::__construct();
		
	}
	
	function getField() {
		return $this->fieldConfig;
	}

	function setField(Field $fieldConfig) {
		$this->fieldConfig = $fieldConfig;
	}

	
}
