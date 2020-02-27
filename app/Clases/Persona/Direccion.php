<?php
namespace App\Clases\Persona;

/**
 *  [setFromJsonString description]  -> 
 *  [setFromObject description]  -> 
 *  [outputAsString description]  -> 
 *  [stringify description]  ->
 *  [getDireccionesJson description]  -> 
 *  [setNewDireccion description]  -> 
 *  [deleteDireccion description]  -> 
 *  [editDireccion description]  -> 
 *  [saveDireccionesToFile description]  -> 
 *  [setSessionAddressesFromDrive description]  -> Esta funcion es estática para poder invocarla en el login sin tener que instanciar en ese contexto
 *  [staticGetArrFromFile description]  -> 
 *  [getArrFromFile description]  -> 
 *  [sanitizeFields description]  -> 
*/

class Direccion {
    public $calle;
    public $numero;
    public $piso;
    public $dpto;
    public $barrio;
    public $localidad;
    public $provincia;
    public $pais;
    public $cp;

    public function __construct($oPersonaAPI = null){
        $this->calle       = isset($oPersonaAPI->direccion) ? $oPersonaAPI->direccion : null;
        $this->numero      = isset($oPersonaAPI->numero)    ? $oPersonaAPI->numero    : null;
        $this->piso        = isset($oPersonaAPI->piso)      ? $oPersonaAPI->piso      : null;
        $this->dpto        = isset($oPersonaAPI->dpto)      ? $oPersonaAPI->dpto      : null;
        $this->barrio      = isset($oPersonaAPI->barrio)    ? $oPersonaAPI->barrio    : null;
        $this->localidad   = isset($oPersonaAPI->localidad) ? $oPersonaAPI->localidad : null;
        $this->provincia   = isset($oPersonaAPI->provincia) ? $oPersonaAPI->provincia : null;
        $this->pais        = isset($oPersonaAPI->pais)      ? $oPersonaAPI->pais      : null;
        $this->cp          = isset($oPersonaAPI->cp)        ? $oPersonaAPI->cp        : null;
    }

    public function setFromJsonString( $inputJson ) {
        if( is_string( $inputJson ) )
           return $this->setFromObject( json_decode( $inputJson ) );

        if( is_object( $inputJson ) ) 
           return $this->setFromObject( $inputJson );

        return FALSE;
    }

    public function setFromObject( $object ) {   
        try
        {
            $this->calle       = isset($object->calle)     ? $object->calle     : '' ;
            $this->numero      = isset($object->numero)    ? $object->numero    : '' ;
            $this->piso        = isset($object->piso)      ? $object->piso      : '' ;
            $this->dpto        = isset($object->dpto)      ? $object->dpto      : '' ;
            $this->barrio      = isset($object->barrio)    ? $object->barrio    : '' ;
            $this->localidad   = isset($object->localidad) ? $object->localidad : '' ;
            $this->provincia   = isset($object->provincia) ? $object->provincia : '' ;
            $this->pais        = isset($object->pais)      ? $object->pais      : '' ;
            $this->cp          = isset($object->cp)        ? $object->cp        : '' ;
            $this->sanitizeFields();
            return TRUE;

        }catch( Exception $e ) { 
            Log::error($e); 
        }
    }

    public function outputAsString( $optionsArr ) {
        $outputString = '';
        foreach( $optionsArr as $nombreCampo => $valor ) 
        {
            $nombreCampo = strtolower($nombreCampo);
            if( !!$valor ) 
            {
                $outputString .= is_string( $valor )
                    ? $valor
                    : '' ;
                $outputString .= is_string( $this->$nombreCampo ) 
                    ? $this->$nombreCampo
                    : '' ;
                $outputString .= ' ' ; 
            }
        }
        return $outputString;
    }

    public function stringify(){
        $outputString  = '';
        $outputString .= !empty($this->calle)     ? $this->calle                : '';
        $outputString .= !empty($this->numero)    ? ' Nº'    . $this->numero    : '';
        $outputString .= !empty($this->piso)      ? ' Piso-' . $this->piso      : '';
        $outputString .= !empty($this->dpto)      ? ' Dpto-' . $this->dpto      : '';
        $outputString .= ',';
        $outputString .= !empty($this->barrio)    ? ' Bº'    . $this->barrio    : '';
        $outputString .= !empty($this->localidad) ? ' '      . $this->localidad : '';
        $outputString .= !empty($this->provincia) ? ' , '    . $this->provincia : '';
        $outputString .= !empty($this->pais)      ? ', '     . $this->pais      : '';
        $outputString .= !empty($this->cp)        ? ' C.P:'  . $this->cp        : '';
        return $outputString;
    }
    
    public function getDireccionesJson(){
        $userId          = sesion('ap')->idPersona;
        $oDireccionArr   = sesion('direcciones');
        return print( json_encode( $oDireccionArr ) );
    }
    
    public function setNewDireccion() {
        try 
        {
            print('{"notice":"setting new address"},');

            $userId          = sesion('ap')->idPersona;

            $userDirecciones = sesion('direcciones');

            if( !isset($this->calle) || empty($this->calle) ) 
                throw new Exception ('{"error":"el campo calle es obligatorio"}');
            if( !isset($this->numero) || empty($this->numero) ) 
                throw new Exception ('{"error":"el campo numero es obligatorio"}');
            if( !isset($this->localidad) || empty($this->localidad) ) 
                throw new Exception ('{"error":"el campo localidad es obligatorio"}');  
            if( !isset($this->provincia) || empty($this->provincia) ) 
                throw new Exception ('{"error":"el campo provincia es obligatorio"}');
            
            $userDirecciones[] = clone $this;
            sesion()->put('direcciones',$userDirecciones);

            $this->saveDireccionesToFile( $userDirecciones, $userId );
        
        }catch( Exception $e ) { 
            Log::error($e);
        }
    }

    public function deleteDireccion( $indexToDelete = null ) {
        try 
        {
            $userId          = session('ap')->idPersona;
            $userDirecciones = session('direcciones');

            if( !is_numeric($indexToDelete) )
                throw new Exception ('{"error":"invalid index requested"}');
            if( !$userId )
                throw new Exception ('{"error":"Not authorized"}');
            if( !isset( $userDirecciones[$indexToDelete] ) )
                throw new Exception ('{"error":"No such index found"}');
    
            array_splice( $userDirecciones, $indexToDelete, 1 );
            
            session()->put('direcciones',$userDirecciones);
            
            print '{"success":"Dirección borrada de la sesión"},'; 

            $this->saveDireccionesToFile( $userDirecciones, $userId );
        
        }catch( Exception $e ){ 
            Log::error($e); 
        }
    }

    public function editDireccion( $indexToEdit ){
        try 
        {
            $userId          = session('ap')->idPersona;
            $userDirecciones = session('direcciones');

            if( !is_numeric($indexToEdit) )
                throw new Exception ('{"error":"invalid index requested"}');
            if( !$userId )
                throw new Exception ('{"error":"No Autorizado"}');
            if( !isset( $userDirecciones[$indexToEdit] ) )
                throw new Exception ('{"error":"No se ha encontrado tal índice"}');
    
            $userDirecciones[$indexToEdit] = clone $this;
            
            session()->put('direcciones',$userDirecciones);
            
            print '{"success":"Dirección editada en sesión"},'; 

            $this->saveDireccionesToFile( $userDirecciones, $userId );
        
        }catch( Exception $e ){ 
            Log::error($e);
        }
    }

    protected function saveDireccionesToFile( $productoIdsArr, $userId ) {
        try
        {
            $unserializedDataBlob = $this->getArrFromFile();
            $unserializedDataBlob[ $userId ]['direcciones'] = $productoIdsArr;
            $serializedDataBlob = serialize( $unserializedDataBlob );
            file_put_contents( __DIR__ . "/../../storage/users", $serializedDataBlob );

            return print('{"success":"Direcciones almacenadas exitosamente"},');
        
        }catch( Exception $e ){ 
            Log::error($e); 
        }   
    }

    // Esta funcion es estática para poder invocarla en el login sin tener que instanciar en ese contexto
    public static function setSessionAddressesFromDrive(){
        try
        {
            $userId = session('ap')->idPersona;
            $unserializedDataBlob = self::staticGetArrFromFile();
            $productoIdsArr = isset($unserializedDataBlob[ $userId ]['direcciones']) ? $unserializedDataBlob[ $userId ]['direcciones'] : [];
            session()->put('direcciones',$productoIdsArr);
            print "Direcciones de usuario cargadas exitosamente";
        
        }catch( Exception $e ){
            Log::error($e); 
        }
    }

    private static function staticGetArrFromFile(){
        $serializedDataBlob = file_get_contents(__DIR__ . "/../../storage/users" );
        return unserialize( $serializedDataBlob );
    }

    protected function getArrFromFile(){
        $serializedDataBlob = file_get_contents(__DIR__ . "/../../storage/users" );
        return unserialize( $serializedDataBlob );
    }

    private function sanitizeFields(){
        $this->calle       = Caracteres::convertToUrl($this->calle);
        $this->numero      = Caracteres::convertToUrl($this->numero);
        $this->piso        = Caracteres::convertToUrl($this->piso);
        $this->dpto        = Caracteres::convertToUrl($this->dpto);
        $this->barrio      = Caracteres::convertToUrl($this->barrio);
        $this->localidad   = Caracteres::convertToUrl($this->localidad);
        $this->provincia   = Caracteres::convertToUrl($this->provincia);
        $this->pais        = Caracteres::convertToUrl($this->pais);
        $this->cp          = Caracteres::convertToUrl($this->cp);
    }
}