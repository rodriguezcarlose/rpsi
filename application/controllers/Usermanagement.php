<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User class.
 *
 * @extends CI_Controller
 */
class Usermanagement extends CI_Controller {
   
    
    private $tipoDocumentoIdentidad = array();
    private $cargo = array();
    private $gerencia = array();
    private $rol = array();
    
    public function __construct() {
        parent::__construct();
        //Para impedir el acceso directo desde la URL
        //Validamos si es el path principal ? , si lo es deje accesar desde url
        /*if ($this->uri->uri_string()) {
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
        }*/
        
        $this->load->model('tipoDocumentoIdentidad_model');
       // $this->load->model('cargo_model');
       // $this->load->model('gerencia_model');
        $this->load->model('rol_Model');
        $this->load->model('user_model');
        $this->tipoDocumentoIdentidad =  $this->tipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        //$this->cargo =  $this->cargo_model->getCargos();
       // $this->gerencia =  $this->gerencia_model->getGerencia();
        $this->rol =  $this->rol_Model->getRol();
    }
    
    public function index(){
        
    }

    
    public function addUser(){
        $data = new stdClass();
        
        $data->tipoDocumentoIdentidad =  $this->tipoDocumentoIdentidad;
        $data->cargolist =  $this->cargo;
        $data->gerencialist =  $this->gerencia;
        $data->rollist =  $this->rol;
        
        
        $data->nombre = $this->input->post("nombre");
        $data->apellido = $this->input->post("apellido");
        $data->email = $this->input->post("email");
        $data->documento = $this->input->post("documento");
        $data->tipo_doc = $this->input->post("tipo_doc");
        $data->cargo = $this->input->post("cargo");
        $data->gerencia = $this->input->post("gerencia");
        $data->rol = $this->input->post("rol");
        $data->login = $this->input->post("login");
        
        $this->form_validation->set_rules('login', 'login', 'required|max_length[15]|min_length[4]|alpha_numeric', array('required' => 'El Campo login es requerido.', 
            'max_length' => 'El Campo login debe tener un maximo de 15 caracteres.',
            'min_length' => 'El Campo login debe tener un minimo de 4 caracteres.',
            'alpha_numeric'=> 'El campo login debe ser alfa numerico'));
        
        $this->form_validation->set_rules('nombre', 'nombre', 'required|alpha_spaces', array('required' => 'El Campo Nombre es requerido.','alpha_spaces'=>'El Campo Nombre debe ser alfabetico.'));
        $this->form_validation->set_rules('apellido', 'apellido', 'required|alpha_spaces', array('required' => 'El Campo Apellido es requerido.','alpha_spaces'=>'El Campo Apellido debe ser alfabetico.'));
        $this->form_validation->set_rules('email', 'email', 'valid_email', array('valid_email' => 'El Campo Email de ser su correo electronico v&aacute;lido.'));
        $this->form_validation->set_rules('documento', 'documento', 'required|numeric|max_length[9]', array('required' => 'El Campo Documento Identidad es requerido.',
                'numeric' => 'El Campo Documento Identidad debe ser n&uacute;merico.',
                'exact_length' => 'El Campo Documento Identidad debe ser de 9 digitos.'
            ));
        $this->form_validation->set_rules('tipo_doc', 'tipo_doc', 'required', array('required' => 'El Campo Tipo Documento es requerido, debe seleccionar uno de la lista.'));
       // $this->form_validation->set_rules('cargo', 'cargo', 'required', array('required' => 'El Campo Cargo es requerido, debe seleccionar uno de la lista.'));
       // $this->form_validation->set_rules('gerencia', 'gerencia', 'required', array('required' => 'El Campo Gerencia es requerido, debe seleccionar uno de la lista.'));
        $this->form_validation->set_rules('rol', 'rol', 'required', array('required' => 'El Campo Rol es requerido, debe seleccionar uno de la lista.'));
        
        if ($this->form_validation->run() == false){
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $this->load->view('user/crud/addUser',$data);
            $this->load->view('templates/footer');
            
        }else{
            $empleado = array();
            $empleado = [
                "id_tipo_documento"=>$this->input->post ("tipo_doc"),
                "documento_identidad"=>$this->input->post ("documento"),
                "nombre"=>$this->input->post ("nombre"),
                "apellido"=>$this->input->post ("apellido"),
               // "id_cargo"=>$this->input->post ("cargo"),
               // "id_gerencia"=>$this->input->post ("gerencia"),
            ];
            
            $usuario = array();
            $usuario = [
                "correo"=>$this->input->post ("email"),
                "clave"=>"",
                "id_empleado"=>"",
                "estatus" => "activo",
                "ingreso" => $this->input->post("login"),
                "id_rol"=>$this->input->post ("rol"),
            ];
            
            $result = $this->user_model->create_user($usuario, $empleado);
            if ($result == 1){
                $data->error = "Ya existe un Usuario con el mismo Documento de Identidad.";
            }else if ($result == 2){
                $data->error = "Ya existe un Usuario con el mismo Email.";
            }else if ($result == 3){
                $data->error = "Ocurrio un Error Inesperado.";
            }else{
                $data->success = "Se creo el usurio exitosamente.";
                $data->nombre = "";
                $data->apellido = "";
                $data->email = "";
                $data->documento = "";
                $data->tipo_doc = "";
                $data->cargo = "";
                $data->gerencia = "";
                $data->rol = "";
                $data->login = "";
            }
            
            
            //insertPaymentIndividual
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $this->load->view('user/crud/addUser',$data);
            $this->load->view('templates/footer');
        }
    }
    
    
    public function editUser(){
        $data = new stdClass();
        
        $data->tipoDocumentoIdentidad =  $this->tipoDocumentoIdentidad;
        $data->cargolist =  $this->cargo;
        $data->gerencialist =  $this->gerencia;
        $data->rollist =  $this->rol;
        
        if ($this->input->post("buscar")){
            
            $this->form_validation->set_rules('documentob', 'documentob', 'required|numeric|max_length[9]', array('required' => 'El Campo Documento Identidad es requerido.',
                    'numeric' => 'El Campo Documento Identidad debe ser n&uacute;merico.',
                    'exact_length' => 'El Campo Documento Identidad debe ser de 9 digitos.'
                ));
            
            if ($this->form_validation->run() == false){
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('user/crud/editUser');
                $this->load->view('templates/footer');
            }else{
                $result = $this->user_model->get_user_from_documento($this->input->post("documentob"));
                
                if (!empty($result)) {
               // foreach ($result->result() as $dataResult){
                    $data->id_usuario = $result->id_usuario;
                    $data->id_empleado = $result->id_empleado;
                    $data->estatus = $result->estatus;
                    $data->nombre = $result->nombre;
                    $data->apellido = $result->apellido;
                    $data->email = $result->correo;
                    $data->documento = $result->documento_identidad;
                    $data->tipo_doc = $result->id_tipo_documento;
                    $data->cargo = $result->id_cargo;
                    //$data->gerencia = $result->id_gerencia;
                    $data->rol = $result->id_rol;
                    $data->login = $result->ingreso;
                }else{
                    $data->error = "No se econtro el usuario con la C&eacute;dula ".$this->input->post("documentob");
                }
                

                
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('user/crud/editUser',$data);
                $this->load->view('templates/footer');
            }
            
            
        }else{
            
            $data->id_usuario =$this->input->post("id_usuario");
            $data->id_empleado =$this->input->post("id_empleado");
            $data->nombre = $this->input->post("nombre");
            $data->apellido = $this->input->post("apellido");
            $data->email = $this->input->post("email");
            $data->documento = $this->input->post("documento");
            $data->tipo_doc = $this->input->post("tipo_doc");
            $data->cargo = $this->input->post("cargo");
            $data->gerencia = $this->input->post("gerencia");
            $data->rol = $this->input->post("rol");
            
            
            $this->form_validation->set_rules('nombre', 'nombre', 'required|alpha_spaces', array('required' => 'El Campo Nombre es requerido.',
                'alpha_spaces'=>'El Campo Nombre debe ser alfabetico.'));
            $this->form_validation->set_rules('apellido', 'apellido', 'required|alpha_spaces', array('required' => 'El Campo Apellido es requerido.','alpha_spaces'=>'El Campo Apellido debe ser alfabetico.'));
           // $this->form_validation->set_rules('email', 'email', 'required|valid_email', array('required' => 'El Campo Email es requerido', 'valid_email' => 'El Campo Email de ser su correo electronico v&aacute;lido.'));
            $this->form_validation->set_rules('documento', 'documento', 'required|numeric|max_length[9]', array('required' => 'El Campo Documento Identidad es requerido.',
                    'numeric' => 'El Campo Documento Identidad debe ser n&uacute;merico.',
                    'exact_length' => 'El Campo Documento Identidad debe ser de 9 digitos.'
                ));
            $this->form_validation->set_rules('tipo_doc', 'tipo_doc', 'required', array('required' => 'El Campo Tipo Documento es requerido, debe seleccionar uno de la lista.'));
           // $this->form_validation->set_rules('cargo', 'cargo', 'required', array('required' => 'El Campo Cargo es requerido, debe seleccionar uno de la lista.'));
           // $this->form_validation->set_rules('gerencia', 'gerencia', 'required', array('required' => 'El Campo Gerencia es requerido, debe seleccionar uno de la lista.'));
            $this->form_validation->set_rules('rol', 'rol', 'required', array('required' => 'El Campo Rol es requerido, debe seleccionar uno de la lista.'));
            $this->form_validation->set_rules('login', 'login', 'required|max_length[15]|min_length[4]|alpha_numeric', array('required' => 'El Campo login es requerido.', 
            'max_length' => 'El Campo login debe tener un maximo de 15 caracteres.',
            'min_length' => 'El Campo login debe tener un minimo de 4 caracteres.',
            'alpha_numeric'=> 'El campo login debe ser alfa numerico'));
            
            if ($this->form_validation->run() == false){
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('user/crud/editUser',$data);
                $this->load->view('templates/footer');
                           
            }else{
                
                $resetear = false;
                $eliminar = false;
                
                if ($this->input->post("resetear")){
                    $resetear = true;
                }if($this->input->post("eliminar")){
                    $eliminar = true;
                }
                
                
                
                $empleado = array();
                $empleado = [
                    "id"=>$this->input->post ("id_empleado"),
                    "id_tipo_documento"=>$this->input->post ("tipo_doc"),
                    "documento_identidad"=>$this->input->post ("documento"),
                    "nombre"=>$this->input->post ("nombre"),
                    "apellido"=>$this->input->post ("apellido"),
                    //"id_cargo"=>$this->input->post ("cargo"),
                    //"id_gerencia"=>$this->input->post ("gerencia"),
                ];
                
                $usuario = array();
                $usuario = [
                    "id"=>$this->input->post ("id_usuario"),
                    "correo"=>$this->input->post ("email"),
                    "id_empleado"=>$this->input->post ("id_empleado"),
                    "id_rol"=>$this->input->post ("rol"),
                    "ingreso" => $this->input->post("login"),
                ];
                
                $result =  $this->user_model->edit_user($usuario, $empleado, $eliminar, $resetear);
                if ($result){
                    $data = new stdClass();
                    $data->success = "Usuario actualizado con &eacute;xito.";
                    $data->tipoDocumentoIdentidad =  $this->tipoDocumentoIdentidad;
                    $data->cargolist =  $this->cargo;
                    $data->gerencialist =  $this->gerencia;
                    $data->rollist =  $this->rol;
                    
                    $this->load->view('templates/header');
                    $this->load->view('templates/navigation',$data);
                    $this->load->view('user/crud/editUser',$data);
                    $this->load->view('templates/footer');
                }else{
                    $data->error = "Ocurrio un error inesperado, intente de nuevo.";
                    $this->load->view('templates/header');
                    $this->load->view('templates/navigation',$data);
                    $this->load->view('user/crud/editUser',$data);
                    $this->load->view('templates/footer');
                }
                
                
            }
        }

    }
    
    public function deleteUser(){
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        //$this->load->view('user/login/login');
        $this->load->view('templates/footer');
    }
    
    public function resetPassword(){
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        //$this->load->view('user/login/login');
        $this->load->view('templates/footer');
    }
    
    
}