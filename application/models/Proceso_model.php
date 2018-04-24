<?php

class Proceso_model extends CI_Model
{
    
    public function insertproceso($dataProceso, $dataError, $proxestatus,$medioTramsmision){
        
        $this->db->trans_start();
        //cambiamos el estatus de la maquina al proximo estatus
        
        $this->db->set("id_estatus_maquina",$proxestatus);
        $this->db->set("medio_transmision",$medioTramsmision);
        $this->db->where("id",$dataProceso["id_maquina_votacion"]);
        $this->db->update("maquina_votacion");
        
        //isertamos en la tabla proceso
        $this->db->insert("proceso",$dataProceso);
        
        //si se selecconaron errores insertamos en la tabla proceso_error
        if (count($dataError) > 0){
            $this->db->select_max("id");
            $this->db->where("id_maquina_votacion",$dataProceso["id_maquina_votacion"]);
            $result = $this->db->get("proceso");
            
            
            foreach ($dataError as $error){
                foreach ($result->result() as $idproceso){
                    $error["id_proceso"] = $idproceso->id;
                }
                $this->db->insert("proceso_error",$error);
            }
        }
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }
    
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