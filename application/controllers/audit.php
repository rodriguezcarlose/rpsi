<?php 
class audit extends CI_Controller{
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
        $this->load->model('Audit_model');
    }

    public function consultar()
    {
        $data = new stdClass();
        $this->form_validation->set_rules('codigo_centrovotacionmesa', 'C&oacute;digo de centro de votacion', 'trim|required|xss_clean|exact_length[14]', array(
            'required' => 'El centro de votaci&oacute;n es requerido',
            'numeric' => 'El centro de votaci&oacute;n solo permite numeros',
            'exact_length' => 'El centro de votaci&oacute;n debe indicar 14 digitos'
        ));
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('audit/audit_consultar');
            $this->load->view('templates/footer');
        } else {
            $data_alert = new stdClass();

            $centrovotacionmesa = $this->input->post('codigo_centrovotacionmesa');

            $campos = explode(".", $centrovotacionmesa);
            if (count($campos) == 3) {
                $centrovotacion = $campos[0];
                $mesa = $campos[1];

                $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);

                if ($result != null) {

                    $maquina_votacion = $result->row();
                    $id_maquina = $maquina_votacion->id;

                    $consulta_cargo_candidato_partido = $this->Audit_model->getCargoCandidatoParido($centrovotacion, $mesa);
                    $consulta_votos_auditoria = $this->Audit_model->getVotesAuditByMv($id_maquina);

                    $estatus_mv = $maquina_votacion->estatus;
                    if ($estatus_mv == "AUDITADA") {
                        $data_alert->error = "la m&aacute;quina ya finalizó la fase de auditoria";
                    }

                    $data = array(
                        'consulta' => $result,
                        'consulta_cargo_candidato_partido' => $consulta_cargo_candidato_partido,
                        'consulta_votos_auditoria' => $consulta_votos_auditoria
                    );

                }

                if ($result != null) {
                    if ($maquina_votacion->estatus == "TRANSMITIDA" || $maquina_votacion->estatus == "AUDITADA") {
                        $this->load->view('templates/header');
                        $this->load->view('templates/navigation', $data_alert);
                        $this->load->view('audit/audit_detail', $data);
                        $this->load->view('templates/footer');
                    } else {
                        $data = new stdClass();
                        $data->error = "La M&aacute;quina no se encuentra Transmitida";
                        $this->load->view('templates/header');
                        $this->load->view('templates/navigation', $data);
                        $this->load->view('audit/audit_consultar');
                        $this->load->view('templates/footer');
                    }
                } else {
                    $data = new stdClass();
                    $data->error = "No se encontrar&oacute;n los datos consultados.";
                    $this->load->view('templates/header');
                    $this->load->view('templates/navigation', $data);
                    $this->load->view('audit/audit_consultar');
                    $this->load->view('templates/footer');
                }
            }
        }
    }
    
    public function consultada()
    {
        if ($this->input->post('id') != null) {
            $idmaquina = $this->input->post('id'); // anteriormente se obtenía el valor por la constante post, sin embargo se perdía el valor cuando se actualizaba la páginación.
        } else {
            $idmaquina = $this->UsuarioMaquina_model->getMaquinaIDByUser($_SESSION['id']);
        }

        $data = new stdClass();
        $data->consulta = $this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);

        $centrovotacion = $this->input->post('codigo_centrovotacion');
        $mesa = $this->input->post('mesa');

        $consulta_cargo_candidato_partido = $this->Audit_model->getCargoCandidatoParido($centrovotacion, $mesa);
        $data->consulta_cargo_candidato_partido = $consulta_cargo_candidato_partido;

        $consulta_votos_auditoria = $this->Audit_model->getVotesAuditByMv($this->input->post('id'));
        $data->consulta_votos_auditoria = $consulta_votos_auditoria;

        $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
        $maquina_votacion = $result->row();
        $estatus_mv = $maquina_votacion->estatus;

        if ($estatus_mv == "AUDITADA") {
            $data->error = "la m&aacute;quina ya finalizó la fase de auditoria";
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('audit/audit_detail', $data);
        $this->load->view('templates/footer');
    }

    public function procesar()
    {
        if ($this->input->post('id') != null) {
            $idmaquina = $this->input->post('id'); // anteriormente se obtenía el valor por la constante post, sin embargo se perdía el valor cuando se actualizaba la páginación.
        } else {
            $idmaquina = $this->UsuarioMaquina_model->getMaquinaIDByUser($_SESSION['id']);
        }

        $currentVote =  $this->Audit_model->getCurrentVote($idmaquina);
        $currentTemp = $currentVote->result_array();
        $current = $currentTemp[0]["MAX(cod_voto)"] + 1;

        foreach ($_POST as $clave=>$valor) {
            if ($valor !== "" && $clave != "id" && $clave != "codigo_centrovotacion" && $clave != "mesa" && $clave != "estatus") {

                if ($valor == 0) {
                    $result =  $this->Audit_model->saveVotesAuditNull($current, null, $idmaquina, 0);
                } else {
                    $result =  $this->Audit_model->saveVotesAudit($current, $valor, $idmaquina, 1);
                }
            }
        }
        $this->consultada();
    }

    public function finishAudit()
    {
        $centrovotacion = $this->input->post('codigo_centrovotacion');
        $mesa = $this->input->post('mesa');
        $this->MaquinaVotacion_model->updateMvEstatusAuditoria($centrovotacion, $mesa);
        $this->consultada();
    }

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('audit/audit_consultar');
        $this->load->view('templates/footer');
    }
}