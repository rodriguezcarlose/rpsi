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
            } // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
        $this->load->model('MaquinaVotacion_model');
        $this->load->model('Error_model');
        $this->load->model('Contingencia_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data = new stdClass();
        
        $resultCountModeloEstatus = $this->MaquinaVotacion_model->getCountModeloEstatus();
        $resultCountMedioTransmision = $this->MaquinaVotacion_model->getCountMedioTransmision();
        $resultCountTipReemplazo = $this->MaquinaVotacion_model->getCountTipReemplazo();
        $mv = $this->MaquinaVotacion_model->getModelosMV();
        $mt = $this->MaquinaVotacion_model->getCountTotalMedioTransmision();
        $tr = $this->MaquinaVotacion_model->getCountTotalTipReemplazo();

        $resultCountErrorTipo= $this->Error_model->getCountErrorTipo();
        $resultTotalErrorTipo= $this->Error_model->getTotalErrorTipo();

        // echo count($mv->result());
        $reports = array();
        
        foreach ($mv->result() as $modelomv) {
            $report = array();
            $report["modelo_maquina"] = $modelomv->modelo_maquina;
            if (count($resultCountModeloEstatus) > 0) {
                foreach ($resultCountModeloEstatus->result() as $resultCountModelo) {
                    if ($resultCountModelo->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountModelo->estatus] = $resultCountModelo->cantidad;
                    }
                }
            }
            
            if (count($resultCountMedioTransmision) > 0) {
                foreach ($resultCountMedioTransmision->result() as $resultCountMedio) {
                    if ($resultCountMedio->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountMedio->medio_transmision] = $resultCountMedio->cantidad;
                    }
                }
            }
            
            if (count($resultCountTipReemplazo) > 0) {
                foreach ($resultCountTipReemplazo->result() as $resultCountTipo) {
                    if ($resultCountTipo->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountTipo->descripcion] = $resultCountTipo->cantidad;
                    }
                }
            }
            
            array_push($reports, $report);
        }
        
        $data->countModelo = $mv;
        $data->mediotrans = $mt;
        $data->reemplazo = $tr;
        $data->countErrorTipo = $resultCountErrorTipo;
        $data->totalErrorTipo = $resultTotalErrorTipo;

        $data->reports = $reports;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('report/report', $reports);
        $this->load->view('templates/footer');
    }

    // Show index page
    public function report_mv()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('report/search_voting_machine');
        $this->load->view('templates/footer');
    }

    public function consulta_report_mv()
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
            'numeric' => 'La mesa solo permite números',
            'min_length' => 'La mesa debe indicar al menos 1 digitos',
            'max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'
        ));

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('report/search_voting_machine');
            $this->load->view('templates/footer');
        } else {

            $centrovotacion = $this->input->post('codigo_centrovotacion');
            $mesa = $this->input->post('mesa');

            $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
            $maquina_votacion = $result->row();
            $id_maquina = $maquina_votacion->id;
            $contingencia = $this->Contingencia_model->getReemplazosByMvReport($id_maquina);
            $errores = $this->Contingencia_model->getErrorsByMv($id_maquina);
            $votantes = $this->Contingencia_model->getVotersByCentroMesa($centrovotacion, $mesa);
            $operador = $this->Contingencia_model->getEmpleado($_SESSION["id"]);

            $dataVotingMachine = array(
                'consulta' => $result,
                'contingencia' => $contingencia,
                'errors' => $errores,
                'voters' => $votantes,
                'user' => $operador
            );

            if ($result != null) {
                $this->load->view('templates/header');
                $this->load->view('templates/navigation');
                $this->load->view('report/detail_voting_machine', $dataVotingMachine);
                $this->load->view('templates/footer');
            } else {
                $data->error = "No se encontrar&oacute;n los datos consultados.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('report/search_voting_machine');
                $this->load->view('templates/footer');
            }
        }
    }

    public function pdf_gen()
    {
        $centrovotacion = $this->input->post('codigo_centrovotacion');
        $mesa = $this->input->post('mesa');

        $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
        $maquina_votacion = $result->row();
        $id_maquina = $maquina_votacion->id;
        $contingencia = $this->Contingencia_model->getReemplazosByMvReport($id_maquina);
        $errores = $this->Contingencia_model->getErrorsByMv($id_maquina);
        $votantes = $this->Contingencia_model->getVotersByCentroMesa($centrovotacion, $mesa);
        $operador = $this->Contingencia_model->getEmpleado($_SESSION["id"]);

        $dataVotingMachine = array(
            'consulta' => $result,
            'contingencia' => $contingencia,
            'errors' => $errores,
            'voters' => $votantes,
            'user' => $operador
        );
        //load the view and saved it into $html variable
        $html=$this->load->view('report/report_pdf', $dataVotingMachine, true);

        //this the the PDF filename that user will get to download
        $time = time();
        $pdfFilePath = "reporte_pruebas_mv_". $centrovotacion . "_" . $mesa . ".pdf";

        //load mPDF library
        $this->load->library('m_pdf');

        //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    public function pdf_gen_auditoria()
    {
        $data = new stdClass();

        if ($this->input->post('id') != null) {
            $idmaquina = $this->input->post('id'); // anteriormente se obtenía el valor por la constante post, sin embargo se perdía el valor cuando se actualizaba la páginación.
        } else {
            $idmaquina = $this->UsuarioMaquina_model->getMaquinaIDByUser($_SESSION['id']);
        }
        $result = $this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);

        $centrovotacion = $this->input->post('codigo_centrovotacion');
        $mesa = $this->input->post('mesa');

        $consulta_votos_auditoria = $this->Audit_model->getVotesAuditReportPDFByMv($this->input->post('id'));

        $consulta_cargos_votos = $this->Audit_model->geCargosByMvAudit($this->input->post('id'));

        $arr = array();

        foreach ($consulta_cargos_votos->result() as $item) {
            $arr = $this->Audit_model->geVotosByCargosAudit($this->input->post('id'), $item->id_cargo);

            if (count($arr->result()) == 2) {
                $consulta_votos_totales = $arr->result()[0]->num_votos + $arr->result()[1]->num_votos;
                $consulta_votos_validos = $arr->result()[1]->num_votos;
                $consulta_votos_nulos = $arr->result()[0]->num_votos;
            } else {
                if (count($arr->result()) == 1) {
                    $consulta_votos_totales = $arr->result()[0]->num_votos;
                    $consulta_votos_validos = $arr->result()[0]->num_votos;
                    $consulta_votos_nulos = 0;
                } else {
                    if (count($arr->result()) == 0) {
                        $consulta_votos_totales = 0;
                        $consulta_votos_validos = 0;
                        $consulta_votos_nulos = 0;
                    }
                }
            }
            $operador = $this->Contingencia_model->getEmpleado($_SESSION["id"]);

            $dataVotingMachine = array(
                'consulta' => $result,
                'consulta_votos_auditoria' => $arr,
                'consulta_votos_totales' => $consulta_votos_totales,
                'consulta_votos_nulos' => $consulta_votos_nulos,
                'consulta_votos_validos' => $consulta_votos_validos,
                'user' => $operador
            );
            //load the view and saved it into $html variable
            $html = $this->load->view('report/report_audit_pdf', $dataVotingMachine, true);

            //this the the PDF filename that user will get to download
            $time = time();
            $pdfFilePath = "reporte_auditoria_mv_" . $centrovotacion . "_" . $mesa . ".pdf";

            //load mPDF library
            $this->load->library('m_pdf');

            //generate the PDF from the given html
            $this->m_pdf->pdf->WriteHTML($html);

            //download it.
            $this->m_pdf->pdf->Output($pdfFilePath, "D");
        }

    }

    public function errors_mv() {
        $result = $this->MaquinaVotacion_model->getErrorsVotingMachine();
        if ($result != null) {
            $errores = $result->result_array();
        } else {
            $errores = null;
        }
        $data = array(
            'consulta' => $errores
        );
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('report/report_errors');
        $this->load->view('templates/footer');
    }

    public function generar_excel(){
        $result = $this->MaquinaVotacion_model->getErrorsVotingMachine();
        if ($result != null) {
            $errores = $result->result();
            if(count($errores) > 0){

                //Cargamos la librería de excel.
                $this->load->library('excel'); $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle('Errores');

                //Contador de filas
                $contador = 1;

                //Le aplicamos ancho las columnas.
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

                //Le aplicamos negrita a los títulos de la cabecera.
                $this->excel->getActiveSheet()->getStyle("A{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);

                //Definimos los títulos de la cabecera.
                $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'Centro de Votación');
                $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'Mesa');
                $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Descripción del Error');
                $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'Módelo MV');
                $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Medio de Transmisión');
                $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'Estatus MV');
                $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'Reemplazo');

                //Definimos la data del cuerpo.
                foreach($errores as $l){
                    //Incrementamos una fila más, para ir a la siguiente.
                    $contador++;
                    //Informacion de las filas de la consulta.
                    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $l->codigo_centrovotacion);
                    $this->excel->getActiveSheet()->setCellValue("B{$contador}", $l->mesa);
                    $this->excel->getActiveSheet()->setCellValue("C{$contador}", $l->error);
                    $this->excel->getActiveSheet()->setCellValue("D{$contador}", $l->modelo_maquina);
                    if ($l->medio_transmision === "\x0d" || $l->medio_transmision == null) {
                        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NULL');
                    } else {
                        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $l->medio_transmision);
                    }
                    $this->excel->getActiveSheet()->setCellValue("F{$contador}", $l->estatus_maquina);
                    if ($l->reemplazo != null) {
                        $this->excel->getActiveSheet()->setCellValue("G{$contador}", $l->reemplazo);
                    } else {
                        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'NULL');
                    }
                }

                //Le ponemos un nombre al archivo que se va a generar.
                $archivo = "reporte_errores_mv.xls";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$archivo.'"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                //Hacemos una salida al navegador con el archivo Excel.
                $objWriter->save('php://output');
            }
        } else {
            $this->load->helper('url');
            redirect('/report/errors_mv', 'refresh');
        }
    }
}