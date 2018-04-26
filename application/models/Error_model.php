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
    
    
    public function getCountTotalError(){
        
        $result=$this->db->query("SELECT mv.modelo_maquina, COUNT(*) cantidad
                                    FROM proceso_error pe, proceso p, maquina_votacion mv, error tr
                                    WHERE p.id = pe.id_proceso
                                    AND mv.id = p.id_maquina_votacion
                                    AND tr.id = pe.id_error
                                    GROUP BY mv.modelo_maquina");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    
    public function getCountError(){
        
        $result=$this->db->query("SELECT mv.modelo_maquina, tr.descripcion, COUNT(*) cantidad
                                    FROM proceso_error pe, proceso p, maquina_votacion mv, error tr
                                    WHERE p.id = pe.id_proceso
                                    AND mv.id = p.id_maquina_votacion
                                    AND tr.id = pe.id_error
                                    GROUP BY mv.modelo_maquina, pe.id_error");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    public function getCountErrorTipo(){
        
        $result=$this->db->query("SELECT tr.descripcion, COUNT(*) cantidad
                                    FROM proceso_error pe, error tr
                                    WHERE tr.id = pe.id_error
                                    GROUP BY tr.descripcion");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    public function getTotalErrorTipo(){
        
        $result=$this->db->query("SELECT COUNT(*) cantidad FROM proceso_error");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
}