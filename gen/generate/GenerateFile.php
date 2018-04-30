<?php

require_once("generate/Generate.php");

/**
 * Generar un elemento (archivo).
 */
abstract class GenerateFile extends Generate{
	
	protected $directorio;
	protected $nombreArchivo;
	
	public function __construct($dir, $file) {
		parent::__construct();
    $this->setDir($dir);
    $this->setFile($file);		
	}
  
  public function setDir($dir){
    $this->directorio = $dir;
  }
  
  public function setFile($file){
    $this->nombreArchivo = $file;
  }
	

	
	public function getPath(){
		return $this->directorio . $this->nombreArchivo;
	}

	public function generateFile(){
    echo "<br><b>" . $this->getPath() . "</b><br>";
    
		$length = strpos($this->directorio,"/");

		while ($length!==false) {
			if ( !file_exists(substr($this->directorio,0,$length+1)) ) {

				mkdir(substr($this->directorio,0,$length+1),0777);
			}
			$length = strpos($this->directorio,"/",$length+1);
	
		}

		$this->string = htmlspecialchars_decode($this->string); 

		if (strlen($this->string)) {
			$handle = fopen( $this->getPath() , "w" );
			fwrite ( $handle , $this->string );
			//chmod ( $filename , 0777 );
			fclose  ( $handle  );
			unset ($handle);
		} elseif (file_exists($this->getPath())){
			unlink($this->getPath());
			echo "---- El archivo no sera generado porque esta vacio. El archivo existente ha sido eliminado.<br>";
		} else {
			echo "---- El archivo no sera generado porque esta vacio.<br>";
		}

		unset($length);	

	}
	
	/**
	 * Generar codigo
	 * Metodo semiabstracto
	 * @return string
	 */
	protected function generateCode(){
		$this->string .= "";
	}
	
	/**
	 * Generar codigo y archivo
	 */
	public function generate(){
		$this->generateCode();
		$this->generateFile();
	}
	
	public function generateIfNotExists(){
    echo "<br><b>" . $this->getPath() . "</b><br>";

		if ( file_exists($this->getPath())) {
			echo "---- El archivo ya existe. No sera generado" ;
			return ;
		}
		
		$this->generate();
	}
	
}
