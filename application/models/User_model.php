<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 *
 * @extends CI_Model
 */
class User_model extends CI_Model {
    
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
     * create_user function.
     *
     * @access public
     * @param mixed $username
     * @param mixed $email
     * @param mixed $password
     * @return bool true on success, false on failure
     */
    public function create_user($usuario, $empleado) {
        
        //validamos si ya existe un empleado con la misma cedula
        $this->db->where("documento_identidad", $empleado["documento_identidad"]);
        if( $this->db->count_all_results('empleado') > 0){
            return 1;
        }
        
        //validamos si ya existe un usuario con el mimso correo
        $this->db->where("correo", $usuario["correo"]);
        if( $this->db->count_all_results('usuario') > 0)
            return 2;
            
            $this->db->trans_start();
            //$this->db->insert_batch("empleado", $empleado);
            $this->db->insert('empleado', $empleado);
            
            $this->db->select("id");
            $this->db->where("documento_identidad",$empleado["documento_identidad"]);
            $result = $this->db->get("empleado");
            log_message('info', 'Usermanagement_model|insertPaymentIndividual: '.$sql = $this->db->last_query());
            foreach ($result->result() as $idEmpleado){
                $usuario["id_empleado"] = $idEmpleado->id;
            }
            $usuario["clave"] = password_hash("Abcd1234++", PASSWORD_BCRYPT);
            $this->db->insert('usuario', $usuario);
            
            $this->db->trans_complete();
            
            log_message('info', 'Usermanagement_model|insertPaymentIndividual: '.$sql = $this->db->last_query());
            
            if ($this->db->trans_status() === FALSE){
                return 3;
            }else{
                return 0;
            }
        
    }
    
    /**
     * resolve_user_login function.
     *
     * @access public
     * @param mixed $username
     * @param mixed $password
     * @return bool true on success, false on failure
     */
    public function resolve_user_login($email, $clave, $meson) {
        
        $this->db->select('clave');
        $this->db->from('usuario');
        $this->db->where('correo', $email);
        $this->db->where_in('estatus',array('nuevo', 'activo'));
        $hash = $this->db->get()->row('clave');
        
        if ($this->verify_password_hash($clave, $hash)){
            $this->db->set("meson",$meson);
            $this->db->set("fecha_hora_ultima_conexion",date('Y-m-d H:i:s'));
            $this->db->where('correo', $email);
            $this->db->update('usuario');
            
            
            
            
            return true;
        }else{
            return false;
        }
        
       // return $this->verify_password_hash($clave, $hash);
        
    }
    
    /**
     * get_user_id_from_username function.
     *
     * @access public
     * @param mixed $username
     * @return int the user id
     */
    public function get_user_id_from_username($email) {
        
        $this->db->select('id');
        $this->db->from('usuario');
        $this->db->where('correo', $email);
        
        return $this->db->get()->row('id');
        
    }
    
    /**
     * get_user function.
     *
     * @access public
     * @param mixed $user_id
     * @return object the user object
     */
    public function get_user($user_id) {
        
       /* $this->db->from('usuario');
        $this->db->where('id', $user_id);
        return $this->db->get()->row();*/
        
        $this->db->select('u.id, u.correo, u.estatus, e.nombre, e.apellido, e.id_cargo, u.id_rol');
        $this->db->from('usuario u');
        $this->db->join('empleado e', 'e.id = u.id_empleado');
        $this->db->where('u.id',$user_id);
        return $this->db->get()->row();
        
    }
    
    public function get_user_from_documento($document) {
        
        /* $this->db->from('usuario');
         $this->db->where('id', $user_id);
         return $this->db->get()->row();*/
        log_message('info', 'User_model|get_user_from_documento inicio ');
        $this->db->select('u.id as id_usuario, u.correo, u.estatus,e.id as id_empleado, e.nombre, e.apellido, e.id_cargo, e.documento_identidad, e.id_tipo_documento,u.id_rol');
        $this->db->from('empleado e');
        $this->db->join('usuario u', 'u.id_empleado = e.id');
        $this->db->where('e.documento_identidad',$document);
        $this->db->where_in('u.estatus',array('nuevo', 'activo'));
        
        
        $query = $this->db->get()->row();
        log_message('info', 'User_model|get_user_from_documento '.$sql = $this->db->last_query());
        log_message('info', 'User_model|get_user_from_documento fin ');
        return $query;
        
    }
    
    /**
     * hash_password function.
     *
     * @access private
     * @param mixed $password
     * @return string|bool could be a string on success, or bool false on failure
     */
    private function hash_password($password) {
        
        return password_hash($password, PASSWORD_BCRYPT);
        
    }
    
    /**
     * verify_password_hash function.
     *
     * @access private
     * @param mixed $password
     * @param mixed $hash
     * @return bool
     */
    private function verify_password_hash($password, $hash) {
        
        return password_verify($password, $hash);
        
    }
    
    
    public function edit_user($user, $employee, $delecte, $reset){
        
        
        //$this->db->trans_start();
        //$this->db->trans_complete();
        $this->db->trans_start();
        if ($delecte){
            $this->db->set("estatus", "eliminado");
        }else if ($reset){
            $this->db->set("clave", password_hash("Abcd1234++", PASSWORD_BCRYPT));
            $this->db->set("estatus", "nuevo");
        }
            
            //usuario
        if ($reset){
            $this->db->set("clave", password_hash("Abcd1234++", PASSWORD_BCRYPT));
            $this->db->set("estatus", "nuevo");
        }
           
            $this->db->set("correo", $user["correo"]);
            $this->db->set("id_rol", $user["id_rol"]);
            $this->db->where("id",$user["id"]);
            $this->db->update('usuario');
            
            $this->db->set("id_tipo_documento", $employee["id_tipo_documento"]);
            $this->db->set("documento_identidad", $employee["documento_identidad"]);
            $this->db->set("nombre", $employee["nombre"]);
            $this->db->set("apellido", $employee["apellido"]);
           // $this->db->set("id_cargo", $employee["id_cargo"]);
           // $this->db->set("id_gerencia", $employee["id_gerencia"]);
            $this->db->where("id",$employee["id"]);
            $this->db->update('empleado');
           
                
        
        $this->db->trans_complete();
        
        
        
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }
    
    public function resetpassword($userid, $password){
        $this->db->set("clave", password_hash($password, PASSWORD_BCRYPT));
        $this->db->set("estatus","activo");
        $this->db->where("id",$userid);
        $this->db->update('usuario');
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
        
        
    }
    
}
