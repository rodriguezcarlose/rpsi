<?php

class RegistroPruebaDatabase_model extends CI_Model
{

    public function consulta_info_mesa($data)
    {
        $condition = "a.numero_meson = " . $data['numero_meson'] . " and a.codigo_centrovotacion = '" . $data['codigo_centrovotacion'] . "' and a.mesa = " . $data['mesa'] . " and a.id_estatus_maquina = b.id ";
        
        $this->db->select('a.id');
        $this->db->select('a.codigo_estado');
        $this->db->select('a.estado');
        $this->db->select('a.codigo_municipio');
        $this->db->select('a.municipio');
        $this->db->select('a.codigo_parroquia');
        $this->db->select('a.parroquia');
        $this->db->select('a.codigo_centrovotacion');
        $this->db->select('a.centro_votacion');
        $this->db->select('a.mesa');
        $this->db->select('a.codigo_instalacion');
        $this->db->select('a.codigo_apertura');
        $this->db->select('a.codigo_cierre');
        $this->db->select('a.codigo_transmision');
        $this->db->select('a.modelo_maquina');
        $this->db->select('a.id_estatus_maquina');
        $this->db->select('a.numero_meson');
        $this->db->select('b.descripcion as desc_estatus_maquina');
        
        $this->db->from('maquina_votacion a');
        $this->db->from('estatus_maquina b');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // //////////////////////////////////////////////////////////////////////////////////////////
    public function consulta_fase()
    {
        $condition = "a.id > 0 ";
        
        $this->db->select('a.id');
        $this->db->select('a.descripcion');
        
        $this->db->from('fase a');
        
        $this->db->where($condition);
        // $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // //////////////////////////////////////////////////////////////////////////////////////////
    public function consulta_errores()
    {
        $condition = "a.id > 0 and a.id_tipo_error = b.id";
        
        $this->db->select('a.id');
        $this->db->select('a.descripcion as descripcion_error');
        $this->db->select('b.descripcion as descripcion_tipo_error');
        $this->db->select('a.orden');
        $this->db->select('a.id_tipo_error');
        
        $this->db->from('error a');
        $this->db->from('tipo_error b');
        
        $this->db->where($condition);
        
        $this->db->order_by("orden", "desc");
        $this->db->order_by("descripcion_error", "asc");
        
        // $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // //////////////////////////////////////////////////////////////////////////////////////////
    public function consulta_fase_maquina($data)
    {
        $condition = " a.id_maquina_votacion = " . $data['id_maquina_votacion'] . " and a.id_fase = b.id ";
        
        $this->db->select('a.id');
        $this->db->select('a.id_maquina_votacion');
        $this->db->select('a.id_usuario');
        $this->db->select('a.id_fase');
        $this->db->select('a.fechainicio');
        $this->db->select('a.fechafin');
        $this->db->select('b.descripcion as descripcion_fase');
        
        $this->db->from('proceso a');
        $this->db->from('fase b');
        
        $this->db->where($condition);
        // $this->db->limit(1);
        
        $this->db->order_by("id", "desc");
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}

?>