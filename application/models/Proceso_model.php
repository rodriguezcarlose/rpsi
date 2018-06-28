<?php

class Proceso_model extends CI_Model
{
    
    public function insertproceso($dataProceso, $dataError, $proxestatus,$medioTramsmision, $reemplazo){
        
        $this->db->trans_start();

        //cambiamos el estatus de la maquina al proximo estatus
        $this->db->set("id_estatus_maquina",$proxestatus);
        $this->db->set("medio_transmision",$medioTramsmision);
        $this->db->where("id",$dataProceso["id_maquina_votacion"]);
        $this->db->update("maquina_votacion");
        
        //isertamos en la tabla proceso
        $this->db->insert("proceso",$dataProceso);
        
        $this->db->select_max("id");
        $this->db->where("id_maquina_votacion",$dataProceso["id_maquina_votacion"]);
        $result = $this->db->get("proceso");
        
        //si se seleccionaron errores insertamos en la tabla proceso_error
        if (count($dataError) > 0){
            foreach ($dataError as $error){
                foreach ($result->result() as $idproceso){
                    $error["id_proceso"] = $idproceso->id;
                }
                $this->db->insert("proceso_error",$error);
            }
        }

        $result_1=$this->db->query("SELECT MAX(proceso_error.id)
                                    FROM proceso_error
                                    INNER JOIN proceso ON proceso_error.id_proceso=proceso.id
                                    INNER JOIN error ON proceso_error.id_error=error.id
                                    INNER JOIN tipo_error ON error.id_tipo_error=tipo_error.id
                                    WHERE proceso.id_maquina_votacion = '". $dataProceso["id_maquina_votacion"] ."' and tipo_error.id = '2';");

        if ($result_1->num_rows()>0) {
            $error_reemplazo = $result_1->result_array();
        } else {
            $error_reemplazo = null;
        }

        if ($reemplazo !== null &&  $reemplazo !== "" ){
            $reemplazoinsert = array();
            foreach ($result->result() as $idproceso){
                $reemplazoinsert["id_proceso"] = $idproceso->id;
            }
            $reemplazoinsert["id_reemplazo"] = $reemplazo;
            $reemplazoinsert["id_usuario_contingencia"] = $dataProceso['id_usuario'];
            $reemplazoinsert["id_fase"] = $dataProceso['id_fase'];
            $reemplazoinsert["fechainicio"] = $dataProceso['fechainicio'];
            $reemplazoinsert["entregado"] = 0;
            $reemplazoinsert["id_error"] = $error_reemplazo[0]["MAX(proceso_error.id)"];
            $this->db->insert("proceso_reemplazo",$reemplazoinsert);
        }
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }
    
    public function updateProceso($dataProceso, $dataError, $proxestatus,$medioTramsmision, $id, $reemplazo){
        
        $this->db->trans_start();

        //cambiamos el estatus de la maquina al proximo estatus
        $this->db->set("id_estatus_maquina",$proxestatus);
        $this->db->set("medio_transmision",$medioTramsmision);
        $this->db->where("id",$dataProceso["id_maquina_votacion"]);
        $this->db->update("maquina_votacion");
        
        //Actualizamos tabla proceso
        $this->db->set($dataProceso);
        $this->db->where("id",$id);
        $this->db->update("proceso");
        
        //si se seleccionaron errores insertamos en la tabla proceso_error
        if (count($dataError) > 0){
            foreach ($dataError as $error){
                $error["id_proceso"] = $id;
            }
            $this->db->insert("proceso_error",$error);
        }

        $result_1=$this->db->query("SELECT MAX(proceso_error.id)
                                    FROM proceso_error
                                    INNER JOIN proceso ON proceso_error.id_proceso=proceso.id
                                    INNER JOIN error ON proceso_error.id_error=error.id
                                    INNER JOIN tipo_error ON error.id_tipo_error=tipo_error.id
                                    WHERE proceso.id_maquina_votacion = '". $dataProceso["id_maquina_votacion"] ."' and tipo_error.id = '2';");

        if ($result_1->num_rows()>0) {
            $error_reemplazo = $result_1->result_array();
        } else {
            $error_reemplazo = null;
        }

        if ($reemplazo !== null &&  $reemplazo !== "" ){
            $reemplazoinsert = array();
            $reemplazoinsert["id_proceso"] = $id;
            $reemplazoinsert["id_reemplazo"] = $reemplazo;
            $reemplazoinsert["id_usuario_contingencia"] = $dataProceso['id_usuario'];
            $reemplazoinsert["id_fase"] = $dataProceso['id_fase'];
            $reemplazoinsert["fechainicio"] = $dataProceso['fechainicio'];
            $reemplazoinsert["entregado"] = 0;
            $reemplazoinsert["id_error"] = $error_reemplazo[0]["MAX(proceso_error.id)"];
            $this->db->insert("proceso_reemplazo",$reemplazoinsert);
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
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }
    
    public function countProcesoByIdMaquina($idmaquina, $usuario, $fase) {
        $this->db->where("id_maquina_votacion",$idmaquina);
        $this->db->where("id_usuario",$usuario);
        $this->db->where("id_fase",$fase);
        return $this->db->count_all_results("proceso");
    }
    
    public function getIdProcesoByIdMaquina($idmaquina, $usuario, $fase) {
        $this->db->select("id");
        $this->db->where("id_maquina_votacion",$idmaquina);
        $this->db->where("id_usuario",$usuario);
        $this->db->where("id_fase",$fase);
        return $this->db->get("proceso")->row("id");
        
    }
    
}