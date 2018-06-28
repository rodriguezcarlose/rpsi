<?php
/**
 * Created by PhpStorm.
 * User: Humberto Fernández
 * Date: 4/6/2018
 * Time: 9:39 AM
 */

class Contingencia extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // load db
        $this->load->database();

        // load Pagination library
        $this->load->library('pagination');

        // load URL helper
        $this->load->helper('url');

        // Load form helper library
        $this->load->helper('form');

        // Load form validation library
        $this->load->library('form_validation');

        // Load session library
        $this->load->library('session');

        $this->load->model('Error_model');
        $this->load->model('TipoReemplazo_model');
        $this->load->model('MaquinaVotacion_model');
        $this->load->model('Proceso_model');
        $this->load->model('Fase_model');
        $this->load->model('UsuarioMaquina_model');
        $this->load->model('User_model');
        $this->load->model('Contingencia_model');
        $data = new stdClass();
    }

    // Show index page
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('contingencia/search_voting_machine');
        $this->load->view('templates/footer');
    }

    public function consulta()
    {
        $data = new stdClass();
        // validaciones de formulario
        $this->form_validation->set_rules('codigo_centrovotacion', 'C&oacute;digo de centro de votaci&oacute;n', 'trim|required|xss_clean|numeric|exact_length[9]', array(
            'required' => 'El centro de votaci&oacute;n es requerido',
            'numeric' => 'El centro de votaci&oacute;n s&oacute;lo permite n&uacute;meros',
            'exact_length' => 'El centro de votaci&oacute;n debe ser de 9 digitos'
        ));
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean|numeric|min_length[1]|max_length[2]', array(
            'required' => 'La mesa es requerida',
            'numeric' => 'La mesa solo permite numeros',
            'min_length' => 'La mesa debe indicar al menos 1 digitos',
            'max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'
        ));

        if ($this->form_validation->run() == FALSE) {
            log_message('info', 'Voting_machine|resettest|validacion run');

            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('contingencia/search_voting_machine');
            $this->load->view('templates/footer');
        } else {
            log_message('info', 'Voting_machine|resettest|validacion else run');

            $centrovotacion = $this->input->post('codigo_centrovotacion');
            $mesa = $this->input->post('mesa');

            $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
            $maquina_votacion = $result->row();
            $id_maquina = $maquina_votacion->id;
            $contingencia = $this->Contingencia_model->getReemplazosByMv($id_maquina);

            $dataVotingMachine = array(
                'consulta' => $result,
                'contingencia' => $contingencia
            );

            if ($contingencia === null) {
                $data->error = "No hay reemplazos disponibles para está Máquina de Votación.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('contingencia/search_voting_machine');
                $this->load->view('templates/footer');
            } else if ($result != null) {
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('contingencia/detail_voting_machine', $dataVotingMachine);
                $this->load->view('templates/footer');
            } else {
                $data->error = "No se encontrar&oacute;n los datos consultados.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('contingencia/search_voting_machine');
                $this->load->view('templates/footer');
            }
        }
        log_message('info', 'Voting_machine|resettest|fin');
    }

    public function liberar()
    {
        $data = new stdClass();
        $reemplazos = $this->input->post('reemplazo');
        if ($reemplazos != null) {
            $reemplazos_separado_por_comas = implode(",", $reemplazos);
            $fechafin = date('Y-m-d H:i:s');
            $result = $this->Contingencia_model->liberarReemplazos($reemplazos_separado_por_comas, $fechafin);
            if ($result) {
                    $data->success = "Reemplazos liberados éxitosamente";
            }
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('contingencia/search_voting_machine');
            $this->load->view('templates/footer');
        }
    }

    public function cancelar()
    {
        $this->index();
    }

}