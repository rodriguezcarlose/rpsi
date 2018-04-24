<?php

class TipoReemplazo_model extends CI_Model
{
    
    public function getTipoReemplazo(){
        return $this->db->get("tipo_reemplazo");
    }
    
    
}