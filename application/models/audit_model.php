<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RolMenu_model class.
 *
 * @extends CI_Model
 */
class Audit_model extends CI_Model { 
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        $this->load->database();
        
    }
    
    
    /**
     * get_rol_menud_from_rolid function.
     *
     * @access public
     * @param mixed $id
     * @return int the id menu
     */
    public function getCargos() {
        
        $result=$this->db->query("SELECT * FROM cargo ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }
    public function getCandidatos() {
        
        $result=$this->db->query("SELECT * FROM candidato ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }
    
    public function getOrganizacionPoliticas() {
        
        $result=$this->db->query("SELECT * FROM organizacion_politica ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }

    public function saveVotesAuditNull($cod_voto, $id_boleta, $id_maquina, $id_cargo, $estatus) {

        $result=$this->db->query("INSERT INTO voto (cod_voto, id_opcion_boleta, id_maquina, id_cargo, estatus) 
                                  VALUES (" . $cod_voto . ", null, " . $id_maquina . ", " . $id_cargo . ", 0 )");
        if ($result){
            return $result;
        }else {
            return null;
        }

    }

    public function saveVotesAudit($cod_voto, $id_boleta, $id_maquina, $id_cargo, $estatus) {

        $query = ("INSERT INTO voto (cod_voto, id_opcion_boleta, id_maquina, id_cargo, estatus) 
                                  VALUES ('" . $cod_voto . "', '" . $id_boleta . "', '" . $id_maquina . "', '" . $id_cargo . "', '" . $estatus . "')");
        //echo $query;
        $result = $this->db->query($query);
        if ($result){
            return $result;
        }else {
            return null;
        }

    }

    public function getVotesAuditByMv($id_maquina) {

        $result=$this->db->query("SELECT
                                voto.cod_voto,
                                cargo.descripcion AS cargo,
                                candidato.candidato AS candidato,
                                organizacion_politica.siglas AS organizacion_politica
                                FROM voto
                                LEFT JOIN opcion_boleta ON id_opcion_boleta = opcion_boleta.id
                                LEFT JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                LEFT JOIN cargo ON postulacion.id_cargo = cargo.id
                                LEFT JOIN candidato ON postulacion.id_candidato = candidato.id
                                LEFT JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica = organizacion_politica.id
                                WHERE id_maquina = '" . $id_maquina . "'");
        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getVotesAuditReportPDFByMv($id_maquina) {

        $result=$this->db->query("SELECT 
                                 COUNT(*) as num_votos,
                                 cargo.descripcion as cargo,
                                 organizacion_politica.siglas AS organizacion_politica,
                                 candidato.candidato AS candidato
                                FROM voto
                                INNER JOIN opcion_boleta ON id_opcion_boleta = opcion_boleta.id
                                INNER JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                INNER JOIN cargo ON postulacion.id_cargo = cargo.id
                                INNER JOIN candidato ON postulacion.id_candidato = candidato.id
                                INNER JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica = organizacion_politica.id
                                WHERE id_maquina = '" . $id_maquina . "'
                                GROUP BY organizacion_politica.siglas
                                order by cargo.descripcion, candidato.candidato");
        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function geCargosByMvAudit($id_maquina) {

        $result=$this->db->query("SELECT DISTINCT id_cargo
                                                    FROM voto
                                                    WHERE id_maquina = '" . $id_maquina . "'");
        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function geVotosByCargosAudit($id_maquina, $id_cargo) {
        $result=$this->db->query("SELECT COUNT(*) AS num_votos,
                                     cargo.descripcion AS cargo,
                                     organizacion_politica.siglas AS organizacion_politica,
                                     candidato.candidato AS candidato
                                    FROM voto
                                    LEFT JOIN opcion_boleta ON id_opcion_boleta = opcion_boleta.id
                                    LEFT JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                    LEFT JOIN cargo ON voto.id_cargo = cargo.id
                                    LEFT JOIN candidato ON postulacion.id_candidato = candidato.id
                                    LEFT JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica = organizacion_politica.id
                                    WHERE voto.id_cargo='" . $id_cargo . "' AND voto.id_maquina='" . $id_maquina . "'
                                    GROUP BY candidato.candidato
                                    ORDER BY candidato.candidato, organizacion_politica.siglas");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    /*
     * Consulta los cargos disponiblles por maquina mesa 
     */
    public function getCantidadCargosXCentroMesa($codigo_centrovotacion, $mesa) {
        $result = $this->db->query("select distinct cargo.id, cargo.descripcion
                                    from opcion_boleta
                                    INNER JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                    INNER JOIN cargo ON postulacion.id_cargo = cargo.id
                                    INNER JOIN centromesa_cargo ON centromesa_cargo.codigo_cargo=cargo.cod_cargo
                                    where centromesa_cargo.codigo_centrovotacion = '". $codigo_centrovotacion . "'
                                    and centromesa_cargo.mesa = '" . $mesa . "'");
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    public function getCargoCandidatoPartido($codigo_centrovotacion, $mesa) {

        $result=$this->db->query("SELECT opcion_boleta.id as id_opcion_boleta,
                                    cargo.id as id_cargo,
                                    cargo.descripcion as cargo,
                                    candidato.candidato,
                                    organizacion_politica.siglas as organizacion_politica
                                    FROM opcion_boleta
                                    INNER JOIN postulacion ON opcion_boleta.id_postulacion=postulacion.id
                                    INNER JOIN cargo ON postulacion.id_cargo=cargo.id
                                    INNER JOIN centromesa_cargo ON centromesa_cargo.codigo_cargo=cargo.cod_cargo
                                    INNER JOIN candidato ON postulacion.id_candidato=candidato.id
                                    INNER JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica=organizacion_politica.id										
                                    WHERE centromesa_cargo.codigo_centrovotacion='" . $codigo_centrovotacion . "' and centromesa_cargo.mesa='" . $mesa ."'
                                    ORDER BY cargo.orden_cargo ASC, opcion_boleta.orden");
       
        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getCurrentVote($id_maquina) {

        $result=$this->db->query("SELECT MAX(cod_voto)
                                    FROM voto
                                    INNER JOIN maquina_votacion on voto.id_maquina=maquina_votacion.id
                                    WHERE voto.id_maquina='" . $id_maquina . "'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getTotalVotes($id_maquina) {

        $result=$this->db->query("SELECT COUNT(*)
                                FROM voto
                                WHERE id_maquina = '" . $id_maquina . "'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getTotalVotesNull($id_maquina) {

        $result=$this->db->query("SELECT COUNT(*)
                                FROM voto
                                WHERE id_maquina = '" . $id_maquina . "' AND estatus = '0'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getTotalVotesValides($id_maquina) {

        $result=$this->db->query("SELECT COUNT(*)
                                FROM voto
                                WHERE id_maquina = '" . $id_maquina . "' AND estatus = '1'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    // nuevas funciones para consultar los datos para el reporte de auditoria
    /*
     * Consulta de total de votos por maquina y cargo
     */
    public function getTotalVotosByIdMaquinaAndIdCargo($id_maquina, $id_cargo) {
        $result = $this->db->query("Select count(distinct cod_voto) as total_votos, id_cargo
                                    from voto 
                                    where id_maquina = '" . $id_maquina . "' 
                                    and id_cargo = " . $id_cargo);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    /*
     * Consulta de total de votos validos por maquina y cargo
     */
    public function getTotalVotosValidosByIdMaquinaAndIdCargo($id_maquina, $id_cargo) {
        $result = $this->db->query("Select count(distinct cod_voto) as total_votos, id_cargo
                                    from voto 
                                    where id_maquina = '" . $id_maquina . "' 
                                    and id_opcion_boleta is not null 
                                    and id_cargo = " . $id_cargo);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    /*
     * Consulta de total de votos nulos por maquina y cargo
     */
    public function getTotalVotosNullByIdMaquinaAndIdCargo($id_maquina, $id_cargo) {
        $result = $this->db->query("Select count(distinct cod_voto) as total_votos, id_cargo 
                                    from voto 
                                    where id_maquina = '" . $id_maquina . "' 
                                    and id_opcion_boleta is null 
                                    and id_cargo = " . $id_cargo);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    /**
     * consultamos los candidatos  por cargo
     */
    public function getCandidatosByCentroMesaCargo($codigo_centrovotacion, $mesa, $cargoid) {
        $result = $this->db->query("SELECT DISTINCT
                            	    cargo.id AS id_cargo,
                            	    candidato.candidato,
                            	    candidato.id AS candidato_id
                            	    FROM opcion_boleta
                            	    INNER JOIN postulacion ON opcion_boleta.id_postulacion=postulacion.id
                            	    INNER JOIN cargo ON postulacion.id_cargo=cargo.id
                            	    INNER JOIN candidato ON postulacion.id_candidato=candidato.id
                            	    INNER JOIN centromesa_cargo ON centromesa_cargo.codigo_cargo=cargo.cod_cargo
                            	    WHERE centromesa_cargo.codigo_centrovotacion = '" . $codigo_centrovotacion . "' AND centromesa_cargo.mesa= '" . $mesa . "' AND cargo.id = '" . $cargoid . "' 
                            	    ORDER BY candidato.candidato, opcion_boleta.orden ASC");
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    /**
     * Consultamos los votos por candidato, organizacion politica y cargo
     */
    public function getVotosByMaquinaCargoCandidato($maquina, $cargoid, $candidatoid) {
        //Consultamos las organizaciones politicas de candidato
        $result = $this->db->query("SELECT organizacion_politica.id AS organizacion_politica_id,organizacion_politica.siglas,candidato.id AS candidadto_id, postulacion.id_cargo, candidato.candidato, '0' AS cantidad 
                                	    FROM voto 
                                	    INNER JOIN maquina_votacion ON maquina_votacion.id = voto.id_maquina
                                	     INNER JOIN opcion_boleta ON opcion_boleta.`id` = voto.`id_opcion_boleta`
                                	    INNER JOIN organizacion_politica ON organizacion_politica.id = opcion_boleta.id_organizacion_politica 
                                	    INNER JOIN postulacion ON postulacion.id = opcion_boleta.id_postulacion 
                                	    INNER JOIN candidato ON candidato.id = postulacion.id_candidato 
                                	    INNER JOIN cargo ON cargo.id = postulacion.id_cargo 
                                	    WHERE voto.id_maquina = '" . $maquina . "'
                                	    AND postulacion.id_cargo = '" . $cargoid . "' 
                                	    AND candidato.id = '" . $candidatoid . "'
                                	    GROUP BY organizacion_politica.siglas, organizacion_politica.organizacion_politica 
                                	    ORDER BY  opcion_boleta.orden   ");
        if ($result->num_rows() > 0) {
            $result2 = $this->db->query("select organizacion_politica.id as organizacion_politica_id,organizacion_politica.siglas,
                                        candidato.id as candidadto_id, count(*) as cantidad
                                        from voto 
                                        inner join opcion_boleta on opcion_boleta.id = voto.id_opcion_boleta 
                                        inner join organizacion_politica on organizacion_politica.id = opcion_boleta.id_organizacion_politica 
                                        inner join postulacion on postulacion.id = opcion_boleta.id_postulacion 
                                        inner join candidato on candidato.id = postulacion.id_candidato 
                                        where voto.id_maquina = '" . $maquina . "' 
                                        and voto.id_cargo = '" . $cargoid . "' 
                                        and candidato.id = '" . $candidatoid . "'
                                        group by organizacion_politica.siglas, organizacion_politica.organizacion_politica, candidato.candidato 
                                        order by candidato.id,opcion_boleta.orden ");
            if ($result2->num_rows() > 0) {
                foreach($result->result() as $results){
                    foreach($result2->result() as $results2){
                        if ($results->organizacion_politica_id == $results2->organizacion_politica_id){
                            $results->cantidad = $results2->cantidad;
                        }
                    }
                }
            } 
           // return $result;
        } //else {
           // return null;
        //}
        return $result;
        /*if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
         * 
         */
    }
    //***********DEPRCAR ESTAS DOS
    public function getVotosCandidatoOrganizacionPolitica($id_maquina, $id_cargo) {
        $result = $this->db->query("select organizacion_politica.id,organizacion_politica.siglas, organizacion_politica.organizacion_politica,
                                    candidato.candidato, count(*) as cantidad
                                    from voto 
                                    inner join opcion_boleta on opcion_boleta.id = voto.id_opcion_boleta 
                                    inner join organizacion_politica on organizacion_politica.id = opcion_boleta.id_organizacion_politica 
                                    inner join postulacion on postulacion.id = opcion_boleta.id_postulacion 
                                    inner join candidato on candidato.id = postulacion.id_candidato 
                                    where voto.id_maquina = '" . $id_maquina . "' 
                                    and voto.id_cargo = " . $id_cargo . " 
                                    group by organizacion_politica.siglas, organizacion_politica.organizacion_politica, candidato.candidato 
                                    order by candidato.id,opcion_boleta.orden");
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    public function getCandidatoOrganizacionPolitica($id_maquina, $id_cargo) {
        $result = $this->db->query("select organizacion_politica.id,organizacion_politica.siglas, organizacion_politica.organizacion_politica,candidato.candidato,cargo.id as cargoid, cargo.descripcion, '0' as cantidad 
                                    from voto 
                                    inner join maquina_votacion on maquina_votacion.id = voto.id_maquina 
                                    inner join opcion_boleta on opcion_boleta.codigo_centrovotacion = maquina_votacion.codigo_centrovotacion  
                                    inner join organizacion_politica on organizacion_politica.id = opcion_boleta.id_organizacion_politica 
                                    inner join postulacion on postulacion.id = opcion_boleta.id_postulacion 
                                    inner join candidato on candidato.id = postulacion.id_candidato 
                                    inner join cargo on cargo.id = postulacion.id_cargo 
                                    where voto.id_maquina = '" . $id_maquina . "' 
                                    and postulacion.id_cargo = " . $id_cargo . " 
                                    group by organizacion_politica.siglas, organizacion_politica.organizacion_politica 
                                    order by candidato.id, opcion_boleta.orden");
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
}