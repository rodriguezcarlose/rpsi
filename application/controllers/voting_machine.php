<?php

// session_start(); //we need to start session in order to access it through CI
class voting_machine extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        // Load form helper library
        $this->load->helper('form');
        
        // Load form validation library
        $this->load->library('form_validation');
        
        // Load session library
        $this->load->library('session');

    }

    // Show login page
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('test/search_voting_machine');
        $this->load->view('templates/footer');
    }

    public function consultar()
    {
        $data = new stdClass();
        $this->form_validation->set_rules('numero_meson', 'Numero de meson', 'trim|required|xss_clean|numeric|min_length[1]|max_length[3]',array('required' => 'El Numero de meson es requerido','numeric' => 'El Numero de meson solo permite numeros','min_length' => 'El Numero de meson debe indicar al menos 1 digitos','max_length' => 'El Numero de meson debe indicar m&aacute;ximo 2 digitos'));
        $this->form_validation->set_rules('codigo_centrovotacion', 'C&oacute;digo de centro de votacion', 'trim|required|xss_clean|exact_length[9]', array('required' => 'El centro de votaci&oacute;n es requerido','numeric' => 'El centro de votaci&oacute;n solo permite numeros','exact_length' => 'El centro de votaci&oacute;n debe indicar 9 digitos'));
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean|numeric|min_length[1]|max_length[2]',array('required' => 'La mesa es requerida','numeric' => 'La mesa solo permite numeros','min_length' => 'La mesa debe indicar al menos 1 digitos','max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'));
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/search_voting_machine');
            $this->load->view('templates/footer');
        } else {
            
            $centrovotacion=$this->input->post('codigo_centrovotacion');
            $mesa=$this->input->post('mesa');
            
            $this->load->model('MaquinaVotacion_model');
            $result=$this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion,$mesa);
            $dataVotingMachine=array('consulta'=>$result);
            
            
            if($result != null){
                $this->load->view('templates/header');
                $this->load->view('templates/navigation');
                $this->load->view('test/detail_voting_machine',$dataVotingMachine);
            }else{
                $data->error = "No se encontr&oacute; el n&uacute;mero consultado.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/voting_machine');
                $this->load->view('templates/footer');
            }
            
        }
    }

    public function seleccionada()
    {
        echo "aqui ";
        $idmaquina=$this->input->post('id');
        echo $idmaquina;
        
        $this->load->model('MaquinaVotacion_model');
        $result=$this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);
        $dataVotingMachine=array('consulta'=>$result);
    
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('test/test_voting_machine',$dataVotingMachine);
        $this->load->view('templates/footer');
        
    }

    public function consulta_lista_errores()
    {
        
        $data = new stdClass();
        $result = $this->RegistroPruebaDatabase_model->consulta_errores();
        if ($result == TRUE) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/error_page', $data);
            $this->load->view('templates/footer');
        } else {
            $data->error = 'Los errores no se han cargado';

            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/error_page', $data);
            $this->load->view('templates/footer');
        }
    }
}

?>