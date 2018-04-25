<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Report class.
 *
 * @extends CI_Controller
 */
class Report extends CI_Controller
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
        $this->load->model('MaquinaVotacion_model');
    }

    public function index()
    {
        $data = new stdClass();
        

        $resultCountModeloEstatus = $this->MaquinaVotacion_model->getCountModeloEstatus();
        $resultCountMedioTransmision = $this->MaquinaVotacion_model->getCountMedioTransmision();
        $resultCountTipReemplazo = $this->MaquinaVotacion_model->getCountTipReemplazo();
        $mv = $this->MaquinaVotacion_model->getModelosMV();
        //echo count($mv->result());
        $reports = array();
        
        foreach ($mv->result() as $modelomv){
            $report = array();
            $report["modelo_maquina"] = $modelomv->modelo_maquina;
            foreach ($resultCountModeloEstatus->result() as $resultCountModelo){
                if($resultCountModelo->modelo_maquina == $modelomv->modelo_maquina){
                    $report[$resultCountModelo->estatus] = $resultCountModelo->cantidad;
                }
            }
            
            
            foreach ($resultCountMedioTransmision->result() as $resultCountMedio){
                if($resultCountMedio->modelo_maquina == $modelomv->modelo_maquina){
                    $report[$resultCountMedio->medio_transmision] = $resultCountMedio->cantidad;
                }
                
            }
            foreach ($resultCountTipReemplazo->result() as $resultCountTipo){
                if($resultCountTipo->modelo_maquina == $modelomv->modelo_maquina){
                    $report[$resultCountTipo->descripcion] = $resultCountTipo->cantidad;
                }
                
            }
            
            array_push($reports,$report);
        }
        
        
        $data->reports = $reports;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('report/report', $reports);
        $this->load->view('templates/footer');
    }
}