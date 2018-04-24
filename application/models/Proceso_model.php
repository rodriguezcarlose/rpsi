<?php

class Proceso_model extends CI_Model
{
 
    
    public function deleteProceso($idmaquina = ''){
        
        $result=$this->db->query("DELETE FROM proceso " .
            "WHERE id_maquina_votacion=" . $idmaquina );
        ///////
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
}