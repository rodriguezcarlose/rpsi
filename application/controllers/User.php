<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User class.
 *
 * @extends CI_Controller
 */
class User extends CI_Controller {
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        
        
        //Para impedir el acceso directo desde la URL
        //Validamos si es el path principal ? , si lo es deje accesar desde url
        if ($this->uri->uri_string()) {
            //Carga Libraria User_agent
            $this->load->library('user_agent');
            //Verifica si llega desde un enlace
            if ($this->agent->referrer()) {
                //Busca si el enlace llega de una URL diferente
                $post = strpos($this->agent->referrer(), base_url());
                if ($post === FALSE) {
                    //Podemos aqui crear un mensaje antes de redirigir que informe
                    redirect(base_url());
                }
            }
            //Si no se llega desde un enlace se redirecciona al inicio
            else {
                //Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
               
       
    }
    
    
    public function index() {
        
        
        
    }
    

    /**
     * login function.
     *
     * @access public
     * @return void
     */
    public function login() {
        
        // create the data object
        $data = new stdClass();
        
        // set validation rules
        $this->form_validation->set_rules('username', 'Usuario', 'required|valid_email', array('required' => 'El Campo Usuario es requerido', 'valid_email'=> 'El Campo Usuario de ser su correo electronico'));
        $this->form_validation->set_rules('password', 'Clave', 'required',array('required' => 'El Campo Clave es requerido'));

        //validaci�n del captcha
        //$this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
        // Eliminamos la session en caso que se encuentre activa
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
        }
        
        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('user/login/login');
            $this->load->view('templates/footer');
            
        } else {
            
            // set variables from the form
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            if ($this->user_model->resolve_user_login($username, $password)) {
                
               //consultamos los datos del usuario
                $user_id = $this->user_model->get_user_id_from_username($username);
                $user    = $this->user_model->get_user($user_id);
                
            
                //consultamos el men� que le corresponde al rol asignado al usuario 
                $this->load->model('Menu_model');
                $menu = $this->Menu_model->get_menu($user->id_rol);
                
                // set session user datas
                $_SESSION['id']      = (int)$user->id;
                $_SESSION['email']     = (string)$user->email;
                $_SESSION['id_rol']     = (int)$user->id_rol;
                $_SESSION['id_empleado'] = (int)$user->id_empleado;
                $_SESSION['estatus']     = (string)$user->estatus;
                $_SESSION['nombre']     = (string)$user->nombre;
                $_SESSION['apellido']     = (string)$user->apellido;
                $_SESSION['logged_in']    = (bool)true;
                $_SESSION['menu']    = (array)$menu;
                // user login ok
                redirect(base_url());
                
            } else {
                
                // login failed
                $data->error = 'Error de autenticaci�n.';
                
                // send error to the view
                $this->load->view('templates/header');
                $this->load->view('templates/Navigation', $data);
                $this->load->view('user/login/login', $data);
                $this->load->view('templates/footer');
            }
            
        }
        
    }
    
    /**
     * logout function.
     *
     * @access public
     * @return void
     */
    public function logout() {
        $data = new stdClass();
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
            redirect(base_url());
            
        } else {
            redirect(base_url());
        }
    }

    public function create(){
        
        
        
        if ($this->form_validation->run() == false) {
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('user/admin/create');
            $this->load->view('templates/footer');
            
        } 
    }
    
}
