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
  /*  public function create_user($username, $email, $password) {
        
        $data = array(
            'username'   => $username,
            'email'      => $email,
            'password'   => $this->hash_password($password),
            'created_at' => date('Y-m-j H:i:s'),
        );
        
        return $this->db->insert('users', $data);
        
    }*/
    
    /**
     * resolve_user_login function.
     *
     * @access public
     * @param mixed $username
     * @param mixed $password
     * @return bool true on success, false on failure
     */
    public function resolve_user_login($email, $clave) {
        
        $this->db->select('clave');
        $this->db->from('usuario');
        $this->db->where('correo', $email);
        $hash = $this->db->get()->row('clave');
        
        return $this->verify_password_hash($clave, $hash);
        
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
    
}
