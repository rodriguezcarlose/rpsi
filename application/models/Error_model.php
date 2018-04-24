<?php

class Error_model extends CI_Model
{
    
    public function getError(){
        $this->db->order_by("orden","DESC");
        return $this->db->get("error");
    }
    
    public function getTipoErrorById($id){
        $this->db->select("id_tipo_error");
        $this->db->where_in("id",$id);
        $result = $this->db->get("error");
        return $result;
    }
    
    
}