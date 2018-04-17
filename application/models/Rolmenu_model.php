<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RolMenu_model class.
 *
 * @extends CI_Model
 */
class Rolmenu_model extends CI_Model {
    
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
    public function get_rol_menud_from_rolid($id) {
        
        $this->db->select('id_menu');
        $this->db->from('rol_menu');
        $this->db->where('id_rol', $id);
        return $this->db->get()->row();
        
    }
    
    
    
}