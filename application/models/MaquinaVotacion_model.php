<?php

class MaquinaVotacion_model extends CI_Model
{

    /**
     * deleteTablepaymentsTem function, para la eliminac�n de una tabla temporal para la carga de archivos CSV
     *
     * @access public
     * @param
     *            $table
     * @return boolean
     */
    public function deleteTablepaymentsTem($table)
    {
        $result = $this->db->query("DROP TABLE IF EXISTS " . $table . ";");
    }

    /**
     * createTablepaymentsTem function, para la creaci�n de una tabla temporal para la carga de archivos CSV
     *
     * @access public
     * @param
     *            $table
     * @return boolean
     */
    public function createTablepaymentsTem($table)
    {
        $this->db->query("DROP TABLE IF EXISTS " . $table . ";");
        
        $result = $this->db->query("CREATE TABLE " . $table . " (
              `id` int(10) NOT NULL AUTO_INCREMENT,
              `codigo_estado` int(2),
              `estado` varchar(255),
              `codigo_municipio` int(2),
              `municipio` varchar(255),
              `codigo_parroquia` int(2),
              `parroquia` varchar(255),
              `codigo_centrovotacion` varchar(9),
              `centro_votacion` varchar(255),
              `mesa` int(2),
              `codigo_instalacion` int(6),
              `codigo_apertura` int(6),
              `codigo_cierre` int(6),
              `codigo_transmision` int(6),
              `modelo_maquina` varchar(9),
              `id_estatus_maquina` int(10),
              `numero_meson` int(3),
                PRIMARY KEY (`id`))");
    }

    /**
     * insertTablepaymentsTem function, para la inserci�n de tatos en la tabla temporar creada
     *
     * @access public
     * @param
     *            $table
     * @param
     *            $sql
     * @return boolean
     */
    public function insertTablepaymentsTem($table_name, $sql)
    {
        
        // si existe la tabla
        if ($this->db->table_exists($table_name)) {
            // si es un array y no est� vacio
            if (! empty($sql) && is_array($sql)) {
                // si se lleva a cabo la inserci�n
                if ($this->db->insert_batch($table_name, $sql)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

    /**
     * get_total function, Retorna la cantidad de Registros de la tabla
     *
     * @access public
     * @param
     *            $table
     * @return $data
     */
    public function get_total($table)
    {
        if ($this->db->table_exists($table)) {
            // log_message('info', 'Payment|get_total'.$sql = $this->db->last_query());
            return $this->db->count_all_results($table);
        }
    }

    /**
     * get_current_page_records function
     *
     * @access public
     * @param
     *            $table
     * @param
     *            $limit
     * @param
     *            $start
     * @return $data
     */
    public function get_current_page_records($table, $limit, $start)
    {
        // si existe la tabla
        if ($this->db->table_exists($table)) {
            $this->db->limit($limit, $start);
            $query = $this->db->get($table);
            // log_message('info', 'Payment|loadgrid '.$sql = $this->db->last_query());
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                
                return $data;
            }
            
            return false;
        } else {
            return false;
        }
    }

    public function updateMv($detalleMv)
    {
        $this->db->trans_start();
        $this->db->empty_table("maquina_votacion");
        $this->db->insert_batch("maquina_votacion", $detalleMv);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function getTablepaymentsTem($table_name)
    {
        return $this->db->get($table_name);
    }
}