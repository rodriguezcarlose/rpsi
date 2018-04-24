<?php
class Fase_model extends CI_Model
{
    public function getfaseBydescripcion($descripcion){
        $this->db->select("id");
        $this->db->where("descripcion", $descripcion);
        
        $result = $this->db->get("fase");
        echo $this->db->last_query();
        return $result;
        
    }
    
}