<?php 

namespace App\Clases\Carrito;

use App\Clases\Carrito\Ecommerce;

class Envio{
	public $idCostoEnvio;
	public $nombre;
	public $opciones;
	public $costo;

	function __construct($xIdCostoEnvio=null,$xNombre=null,$xOpciones=null,$xCosto=null){
		$this->idCostoEnvio = $xIdCostoEnvio;
		$this->nombre = $xNombre;
		$this->opciones = $xOpciones;
		$this->costo = $xCosto;
	}

	//Esta funcion me tiene que cargar la lista de zonadep del id ... 
	public function getOpcionesEnviobyId($xIdCostoEnvio=null){
		if ($xIdCostoEnvio != null && isset(session('carrito')->envio)){
			$this->idCostoEnvio = session('carrito')->envio->idCostoEnvio;
			$this->nombre = session('carrito')->envio->nombre;
			$this->opciones = session('carrito')->envio->opciones;
			
			$xEcom = new Ecommerce();
			$xEcom->ecommerceConfiguracion();
			$xLista = $xEcom->GetZonaDepbyId($xIdCostoEnvio);
			
			$xId = $xLista[0][0]; //esto igual $xIdCostoEnvio
			$xNombre = $xLista[0][1];
			$xCosto = $xLista[0][2];
			
			print_r("<br>costo<br>".$xCosto);
			
			if(!($xCosto > 0)){
				$xCosto = null;
			}

			//el primer elemento de la lista es relativo al propio id
			$this->costo = $xCosto;
			$opcionenvio = new OpcionEnvio();

			for ($i=1; $i < count($xLista); $i++){
				$opcionenvio->listaCostoEnvioZona[]=$xLista[$i];
			}

			$flag=false;
			
			//nivel0
			if ($this->idCostoEnvio == null or $this->idCostoEnvio == $xIdCostoEnvio){	
				print_r("Cargamos Nivel 0<br>");
				$this->opciones[0] = $opcionenvio;
				$this->opciones[1] = null;
				$this->opciones[2] = null;
			}
			else{ 
				for ($i=0; $i<2; $i++){
					print_r("cargamos nivel".$i."<br>");
					
					if ($flag == false){
						if (empty($this->opciones[$i+1])){
							$this->costo=$xCosto;
							$this->opciones[$i+1] = $opcionenvio;
							$this->opciones[$i]->idActivo = $xIdCostoEnvio;
							$flag = true;
						}
						else if	($this->opciones[$i]->idActivo == $xIdCostoEnvio) {
							$this->costo = $xCosto;
							$this->opciones[$i+1] = $opcionenvio;
							$this->opciones[$i]->idActivo = $xIdCostoEnvio;
							$flag = true;
						}	
					}
					else
					{
						$this->opciones[$i+1]=null;
					}
				}
			}
		}
		return $this;
	}	

	public function getEnviobyIdCosto($xIdCostoEnvioZona){
		//
	}
}
?>