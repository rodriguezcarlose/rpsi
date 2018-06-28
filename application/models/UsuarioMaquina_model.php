<?php

class UsuarioMaquina_model extends CI_Model
{
    
    public function selccionarMesa($param) {
        $this->db->insert("usuario_maquina",$param);
    }  
    
    public function desSelccionarMesa($param) {
        $this->db->where("id_usuario",$param["id_usuario"]);
        $this->db->where("id_maquina",$param["id_maquina"]);
        $this->db->delete("usuario_maquina");
    } 
    
    public function getusuarioMaquina($param) {
        $this->db->where("id_usuario",$param["id_usuario"]);
        $this->db->where("id_maquina",$param["id_maquina"]);
        return $this->db->count_all_results("usuario_maquina");
    }
    
    public function getmaquina($param) {
        $this->db->where("id_maquina",$param["id_maquina"]);
        $this->db->where_not_in("id_usuario",$param["id_usuario"]);
        return $this->db->count_all_results("usuario_maquina");
    }
    
    public function getCountUsuario($idusuario) {
        $this->db->where("id_usuario",$idusuario);
        return $this->db->count_all_results("usuario_maquina");
    }
    
    public function getMaquinaIDByUser($idusuario) {
        $this->db->select("id_maquina");
        $this->db->where("id_usuario",$idusuario);
        return $this->db->get("usuario_maquina")->row("id_maquina");
    }
}