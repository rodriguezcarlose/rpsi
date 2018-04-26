<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * TipoDocumentoIdentidad_model class.
 *
 * @extends CI_Model
 */
class TipoDocumentoIdentidad_model extends CI_Model {
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        $this->load->database();
        
    }
    
    public function  getTipoDocumentoIdentidad(){
        
        return $this->db->get('tipo_documento_identidad');
        
    }
    
    public function  getTipoDocumentoIdentidadbyTipo($tipo){
    
        $result=$this->db->query("SELECT id, nombre, descripcion
                                FROM 	tipo_documento_identidad tdi
                                WHERE tdi.nombre='" . $tipo . "'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }
    
    
}
