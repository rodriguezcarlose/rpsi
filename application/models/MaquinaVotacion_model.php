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
    
    public function getDetailVotingMachine($codigocentro = '', $mesa=''){
        
        $result=$this->db->query("SELECT 	mv.id,
                                            mv.codigo_estado,
                                        	mv.estado,
                                        	mv.codigo_municipio,
                                        	mv.municipio,
                                        	mv.codigo_parroquia,
                                        	mv.parroquia,
                                        	mv.codigo_centrovotacion,
                                        	mv.centro_votacion,
                                        	mv.mesa,
                                        	mv.modelo_maquina,
                                            mv.id_estatus_maquina,
                                        	em.descripcion estatus
                                        FROM maquina_votacion mv, estatus_maquina em
                                        WHERE mv.id_estatus_maquina=em.id
                                        AND mv.codigo_centrovotacion='" . $codigocentro . "' " .
                                        "AND mv.mesa='" . $mesa . "'");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    public function getDetailVotingMachinebById($id){
        
        $this->db->select('mv.id, mv.codigo_estado, mv.estado, mv.codigo_municipio, mv.municipio, mv.codigo_parroquia, mv.parroquia, 
                            mv.codigo_centrovotacion, mv.centro_votacion, mv.mesa, mv.modelo_maquina, mv.id_estatus_maquina, em.descripcion estatus');
        $this->db->from('maquina_votacion mv');
        $this->db->join('estatus_maquina em', 'mv.id_estatus_maquina=em.id', 'inner');
        $this->db->where('mv.id', $id);
        return $query = $this->db->get();
    }
    
    public function getDetailTestVotingMachine($id){
        
        $result=$this->db->query("SELECT 	mv.id,
                                            mv.codigo_estado,
                                        	mv.estado,
                                        	mv.codigo_municipio,
                                        	mv.municipio,
                                        	mv.codigo_parroquia,
                                        	mv.parroquia,
                                        	mv.codigo_centrovotacion,
                                        	mv.centro_votacion,
                                        	mv.mesa,
                                        	mv.modelo_maquina,
                                            mv.id_estatus_maquina,
                                        	em.descripcion estatus
                                        FROM maquina_votacion mv, estatus_maquina em
                                        WHERE mv.id_estatus_maquina=em.id
                                        AND mv.id=" . $id );
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    public function getCodigoByStatusId($estatus,$id){
        
        switch ($estatus) {
            case "SELECCIONADA":
               $this->db->select("codigo_instalacion");
               break;
            case "INSTALADA":
                $this->db->select("codigo_apertura");
                break;
            case "APERTURADA":
                $this->db->select("codigo_cierre");
                break;
            case "CERRADA":
                $this->db->select("codigo_transmision");
                break;
                
        }
        
        $this->db->where("id",$id);
        $result =  $this->db->get("maquina_votacion")->row();
        return $result;
        
    }
    
    public function resetVotingMachine($idmaquina='', $status=''){        
        $this->db->set("id_estatus_maquina",$status);
        $this->db->set("medio_transmision",NULL);
        $this->db->where("id",$idmaquina);
        $this->db->update("maquina_votacion");
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }        
    }
    
    public function setMeson($id, $meson) {
        $this->db->set("numero_meson",$meson);
        $this->db->where("id",$id);
        $this->db->update("maquina_votacion");
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }    
    
    }
    
    public function getCountModeloEstatus(){
        
        $result=$this->db->query("SELECT mv.modelo_maquina, em.descripcion estatus, COUNT(*) cantidad
                            FROM maquina_votacion mv, estatus_maquina em
                            WHERE em.id = mv.id_estatus_maquina
                            AND em.id = 1
                            GROUP BY mv.id_estatus_maquina,mv.modelo_maquina
                            UNION
                            SELECT mv.modelo_maquina, em.descripcion estatus, COUNT(*) cantidad
                            FROM maquina_votacion mv, estatus_maquina em
                            WHERE em.id = mv.id_estatus_maquina
                            AND em.id = 6
                            GROUP BY mv.id_estatus_maquina,mv.modelo_maquina
                            UNION
                            SELECT mv.modelo_maquina,'INICIADA' estatus, COUNT(*) cantidad
                            FROM maquina_votacion mv, estatus_maquina em
                            WHERE em.id = mv.id_estatus_maquina
                            AND em.id IN (2,3,4,5)
                            GROUP BY estatus,mv.modelo_maquina");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    public function getCountMedioTransmision(){
        
        $result=$this->db->query("SELECT DISTINCT mv.modelo_maquina,mv.medio_transmision, COUNT(*) cantidad
                            FROM maquina_votacion mv
                            WHERE mv.medio_transmision IS NOT NULL
                            GROUP BY mv.modelo_maquina,mv.medio_transmision");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    
    public function getCountTipReemplazo(){
        
        $result=$this->db->query("SELECT mv.modelo_maquina, tr.descripcion, COUNT(*) cantidad
                                    FROM proceso_error pe, proceso p, maquina_votacion mv, tipo_reemplazo tr
                                    WHERE pe.id_tipo_reemplazo IS NOT NULL
                                    AND p.id = pe.id_proceso
                                    AND mv.id = p.id_maquina_votacion
                                    AND tr.id = pe.id_tipo_reemplazo
                                    GROUP BY mv.modelo_maquina, pe.id_tipo_reemplazo");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    }
    
    public function getModelosMV(){
        $result=$this->db->query("SELECT DISTINCT modelo_maquina FROM maquina_votacion");
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }

       
        
    }
}