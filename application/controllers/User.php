<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User class.
 *
 * @extends CI_Controller
 */
class User extends CI_Controller
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
        
        // Para impedir el acceso directo desde la URL
        // Validamos si es el path principal ? , si lo es deje accesar desde url
        if ($this->uri->uri_string()) {
            // Carga Libraria User_agent
            $this->load->library('user_agent');
            // Verifica si llega desde un enlace
            if ($this->agent->referrer()) {
                // Busca si el enlace llega de una URL diferente
                $post = strpos($this->agent->referrer(), base_url());
                if ($post === FALSE) {
                    // Podemos aqui crear un mensaje antes de redirigir que informe
                    redirect(base_url());
                }
            }            // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
    }

    public function index()
    {}

    /**
     * login function.
     *
     * @access public
     * @return void
     */
    public function login()
    {
        
        // create the data object
        $data = new stdClass();
        
        // set validation rules
        $this->form_validation->set_rules('ingreso', 'ingreso', 'trim|required|alpha_numeric|min_length[5]|max_length[15]', array(
            'required' => 'El Campo Usuario es requerido',
            'alpha_numeric' => 'El Campo Usuario solo permite datos alfanum&acutericos',
            'min_length' => 'El Campo Usuario debe indicar al menos 5 digitos',
            'max_length' => 'El Campo Usuario debe indicar maximo 15 digitos'
        ));
        $this->form_validation->set_rules('password', 'Clave', 'required', array(
            'required' => 'El Campo Clave es requerido'
        ));
        $this->form_validation->set_rules('numero_meson', 'Numero de meson', 'trim|required|xss_clean|numeric|min_length[1]|max_length[3]', array(
            'required' => 'El Numero de meson es requerido',
            'numeric' => 'El Numero de meson solo permite numeros',
            'min_length' => 'El Numero de meson debe indicar al menos 1 digitos',
            'max_length' => 'El Numero de meson debe indicar m&aacute;ximo 2 digitos'
        ));
        
        // validaci�n del captcha
        // $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
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
            $user = $this->input->post('ingreso');
            $password = $this->input->post('password');
            $meson = $this->input->post('numero_meson');
            
            if ($this->user_model->resolve_user_login($user, $password, $meson)) {
                
                // consultamos los datos del usuario
                $user_id = $this->user_model->get_user_id_from_username($user);
                $user = $this->user_model->get_user($user_id);
                
                // si esta activo
                if ($user->estatus == "activo") {
                    
                    // consultamos el men� que le corresponde al rol asignado al usuario
                    $this->load->model('Menu_model');
                    $menu = $this->Menu_model->get_menu($user->id_rol);
                    
                    // set session user datas
                    $_SESSION['id'] = (int) $user->id;
                    $_SESSION['documento_identidad'] = (string) $user->documento_identidad;
                    $_SESSION['id_rol'] = (int) $user->id_rol;
                    $_SESSION['id_empleado'] = (int) $user->id_empleado;
                    $_SESSION['estatus'] = (string) $user->estatus;
                    $_SESSION['nombre'] = (string) $user->nombre;
                    $_SESSION['apellido'] = (string) $user->apellido;
                    $_SESSION['logged_in'] = (bool) true;
                    $_SESSION['menu'] = (array) $menu;
                    // user login ok
                    redirect(base_url());
                } else if ($user->estatus == "nuevo") {
                    
                    // tmb se deben borrar estas dolineas si se desea restituir el reseteo automatico
                    $this->load->model('Menu_model');
                    $menu = $this->Menu_model->get_menu($user->id_rol);
                    
                    //esto se borra si se desea poner el reset password
                    $_SESSION['id'] = (int) $user->id;
                    $_SESSION['documento_identidad'] = (string) $user->documento_identidad;
                    $_SESSION['id_rol'] = (int) $user->id_rol;
                    $_SESSION['id_empleado'] = (int) $user->id_empleado;
                    $_SESSION['estatus'] = (string) $user->estatus;
                    $_SESSION['nombre'] = (string) $user->nombre;
                    $_SESSION['apellido'] = (string) $user->apellido;
                    $_SESSION['logged_in'] = (bool) true;
                    $_SESSION['menu'] = (array) $menu;
                    redirect(base_url());
                    //$this->resetpassword($user->id);  se qita reseteo de password
                }
            } else {
                
                // login failed
                $data->error = 'Error de autenticaci&oacuten.';
                // send error to the view
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('user/login/login', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function resetpassword($id)
    {
        $data = new stdClass();
        $data->id_user = $id;
        $this->form_validation->set_rules('password', 'password', 'required', array(
            'required' => 'El Campo Clave es requerido'
        ));
        $this->form_validation->set_rules('confirpassword', 'confirpassword', 'required|matches[password]', array(
            'required' => 'El Campo Confirmar Clave es requerido',
            'matches' => 'El campo Confirmar Clave debe ser igual al campo Clave'
        ));
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('user/login/resetpassword', $data);
            $this->load->view('templates/footer');
        } else {
            $data = new stdClass();
            $result = $this->user_model->resetpassword($id, $this->input->post("password"));
            if ($result) {
                $data->success = "cambio de Clave exitoso.";
            } else {
                $data->error = "ocurrio un error con el cambio de clave.";
            }
            
            redirect(base_url() . "index.php/user/login/" . $data);
        }
    }

    /**
     * logout function.
     *
     * @access public
     * @return void
     */
    public function logout()
    {
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

    public function create()
    {
        if ($this->form_validation->run() == false) {
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('user/admin/create');
            $this->load->view('templates/footer');
        }
    }
}
