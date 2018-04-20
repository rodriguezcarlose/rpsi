<?php

// session_start(); //we need to start session in order to access it through CI
class Registro_Prueba extends CI_Controller
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
        
        // Load database
        $this->load->model('RegistroPruebaDatabase_model');
    }

    // Show login page
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('test/admin_page');
        $this->load->view('templates/footer');
    }

    public function consulta_registro_prueba()
    {
        $data = new stdClass();
        $this->form_validation->set_rules('numero_meson', 'Numero de meson', 'trim|required|xss_clean',array('required' => 'El Campo Numero de meson es requerido'));
        $this->form_validation->set_rules('codigo_centrovotacion', 'Codigo de centro de votacion', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/admin_page');
            $this->load->view('templates/footer');
        } else {
            
            $data_input = array(
                'numero_meson' => $this->input->post('numero_meson'),
                'codigo_centrovotacion' => $this->input->post('codigo_centrovotacion'),
                'mesa' => $this->input->post('mesa')
            );
            
            $result = $this->RegistroPruebaDatabase_model->consulta_info_mesa($data_input);
            if ($result == TRUE) {
                
                
                $data_input = array(
                    'id_maquina' => $result[0]->id,
                    'codigo_estado' => $result[0]->codigo_estado,
                    'estado' => $result[0]->estado,
                    'codigo_municipio' => $result[0]->codigo_municipio,
                    'municipio' => $result[0]->municipio,
                    'codigo_parroquia' => $result[0]->codigo_parroquia,
                    'parroquia' => $result[0]->parroquia,
                    'codigo_centrovotacion' => $result[0]->codigo_centrovotacion,
                    'centro_votacion' => $result[0]->centro_votacion,
                    'mesa' => $result[0]->mesa,
                    'codigo_instalacion' => $result[0]->codigo_instalacion,
                    'codigo_apertura' => $result[0]->codigo_apertura,
                    'codigo_cierre' => $result[0]->codigo_cierre,
                    'codigo_transmision' => $result[0]->codigo_transmision,
                    'modelo_maquina' => $result[0]->modelo_maquina,
                    'id_estatus_maquina' => $result[0]->id_estatus_maquina,
                    'numero_meson' => $result[0]->numero_meson,
                    'desc_estatus_maquina' => $result[0]->desc_estatus_maquina
                
                );
                
                // EN QUE FASE ESTOY DE LAS PRUEBAS /////
                
                $data_fase = array(
                    'id_maquina_votacion' => $result[0]->id
                );
                
                $result_fase = $this->RegistroPruebaDatabase_model->consulta_fase_maquina($data_fase);
                
                if ($result_fase == TRUE) {
                    $data_input['fase_estatus'] = 'existe';
                    $data_input['lista_fase'] = $result_fase;
                } else {
                    $data_input['fase_estatus'] = 'no existe';
                    $data_input['lista_fase'] = "";
                }
                
                $this->load->view('templates/header');
                $this->load->view('templates/navigation');
                $this->load->view('test/admin_page');
                $this->load->view('templates/footer');
                
            } else {
                $data->error = 'No se encontro ningun registro que coincida con los parametros de busqueda';
                
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/admin_page', $data);
                $this->load->view('templates/footer');
            }
        }
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