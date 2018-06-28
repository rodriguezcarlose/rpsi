<?php
/**
 * Created by PhpStorm.
 * User: Humberto Fernández
 * Date: 4/6/2018
 * Time: 4:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 *
 * @extends CI_Model
 */
class Contingencia_model extends CI_Model
{
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getReemplazosByMv($id_maquina){
        $result=$this->db->query("SELECT proceso_reemplazo.id, tipo_reemplazo.descripcion as reemplazo, proceso_reemplazo.entregado, fase.descripcion as fase
                                FROM proceso_reemplazo
                                INNER JOIN fase ON proceso_reemplazo.id_fase = fase.id
                                INNER JOIN tipo_reemplazo ON proceso_reemplazo.id_reemplazo = tipo_reemplazo.id
                                INNER JOIN proceso ON proceso_reemplazo.id_proceso = proceso.id
                                INNER JOIN maquina_votacion ON proceso.id_maquina_votacion = maquina_votacion.id
                                WHERE proceso.id_maquina_votacion = '". $id_maquina ."' AND proceso_reemplazo.entregado = 0");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function getReemplazosByMvReport($id_maquina){
        $result=$this->db->query("SELECT DISTINCT
                                tipo_reemplazo.descripcion as reemplazo,
                                proceso_reemplazo.entregado,
                                fase.descripcion as fase
                                FROM proceso_reemplazo
                                INNER JOIN fase ON proceso_reemplazo.id_fase = fase.id
                                INNER JOIN tipo_reemplazo ON proceso_reemplazo.id_reemplazo = tipo_reemplazo.id
                                INNER JOIN proceso ON proceso_reemplazo.id_proceso = proceso.id
                                INNER JOIN maquina_votacion ON proceso.id_maquina_votacion = maquina_votacion.id
                                WHERE proceso.id_maquina_votacion = '". $id_maquina ."'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function liberarReemplazos($reemplazos, $fechafin) {
        $result=$this->db->query("UPDATE proceso_reemplazo
                                    SET entregado=1, fechafin='".$fechafin."'
                                    WHERE id IN ($reemplazos)");
        return $result;
    }

    public function getErrorsByMv($id_maquina) {
        $result=$this->db->query("SELECT DISTINCT
                                    error.id, 
                                    error.descripcion AS error,
                                    fase.descripcion AS fase,
                                    tipo_error.descripcion AS tipo_error
                                    FROM proceso_error
                                    INNER JOIN fase ON proceso_error.id_fase=fase.id
                                    INNER JOIN proceso ON proceso_error.id_proceso=proceso.id
                                    INNER JOIN maquina_votacion ON proceso.id_maquina_votacion=maquina_votacion.id
                                    INNER JOIN error ON proceso_error.id_error=error.id
                                    INNER JOIN tipo_error ON error.id_tipo_error=tipo_error.id
                                    WHERE proceso.id_maquina_votacion = '". $id_maquina ."'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function getVotersByCentroMesa($centro_votacion, $mesa) {
        $result=$this->db->query("SELECT tipo_documento, documento_identidad, nombre, apellido, voto
                                    FROM votantes
                                    WHERE codigo_centrovotacion='".$centro_votacion."' AND mesa='".$mesa."' AND voto='1'
                                    ORDER BY tipo_documento DESC, documento_identidad ASC");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function getEmpleado($id_empleado) {
        $result=$this->db->query("SELECT nombre, apellido
                                    FROM empleado
                                    WHERE id='".$id_empleado."'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

}