<?php

class LoadCV extends CI_Controller
{

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    private $data;

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
        
        $this->data = new stdClass();
        $this->load->model('MaquinaVotacion_model');
    }

    public function index()
    {
        $per_page_valid = 25;
        $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // consultamos de sesion el nombre de la tabla temporal que se esta trabajando.
        if (isset($_SESSION['table_temp_nom'])) {
            $this->load->library('pagination');
            $per_page_valid = 25;
            $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $tablename = $_SESSION['table_temp_nom'];
            $total_records = $this->MaquinaVotacion_model->get_total($tablename);
            
            $params["results"] = $this->MaquinaVotacion_model->get_current_page_records($tablename, $per_page_valid, $start_index);
            $params["total_records"] = $total_records;
            
            $settings = $this->config->item('pagination');
            $settings['total_rows'] = $total_records;
            $settings['base_url'] = base_url() . 'index.php/loadCV/index';
            
            // use the settings to initialize the library
            $this->pagination->initialize($settings);
            
            // build paging links
            $params["links"] = $this->pagination->create_links();
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation', $this->data);
            $this->load->view('loadcv/loadcv', $params);
            $this->load->view('templates/footer');
        } else {
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation', $this->data);
            $this->load->view('loadcv/loadcv');
            $this->load->view('templates/footer');
        }
    }

    public function do_upload()
    {
        
        //load xls en realidad
        log_message('info', 'LoadCV|inicio do_upload');
        
        if (isset($_SESSION['table_temp_nom'])) {
            $this->MaquinaVotacion_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
            unset($_SESSION['table_temp_nom']);
        }
        
        if (isset($_SESSION['start_index_load_payment'])) {
            unset($_SESSION['start_index_load_payment']);
        }
        
        $data = new stdClass();
        $config['upload_path'] = './uploads/';
        $config['allowed_types']  = 'xls|xlsx';
        $config['max_size'] = 0;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $this->load->library('upload', $config);
        
        if (! $this->upload->do_upload('userfile')) {
            $data->error = $this->upload->display_errors();
            $this->data = $data;
            log_message('error', 'LoadCV|do_upload|' . $data->error);
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $this->load->view('loadcv/loadcv',$data);
            $this->load->view('templates/footer');
        } else {
            
            // cargamos el archivo
            $file = $this->upload->data('file_path') . $this->upload->data('file_name');
            
            $this->load->library('PHPExcel/Classes/PHPExcel');
            $archivo = $file;
            $inputFileType = PHPExcel_IOFactory::identify($archivo);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($archivo);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
                        
            $data->records = array();
            
            $tablename = "maquina_voacion_temp" . $_SESSION['id'];
            
            // Creamos una tabla temporal para cargar los archivos
            $this->MaquinaVotacion_model->createTablepaymentsTem($tablename);

            log_message('info', 'Loadcv|do_upload|inicio recorrido archivo cargado');
            
            for ($row = 2; $row <= $highestRow; $row++){
                    $fila = array(
                        "codigo_estado" =>$sheet->getCell("A".$row),
                        "estado" =>$sheet->getCell("B".$row),
                        "codigo_municipio" =>$sheet->getCell("C".$row),
                        "municipio" =>$sheet->getCell("D".$row),
                        "codigo_parroquia" =>$sheet->getCell("E".$row),
                        "parroquia" =>$sheet->getCell("F".$row),
                        "codigo_centrovotacion" =>$sheet->getCell("G".$row),
                        "centro_votacion" =>$sheet->getCell("H".$row),
                        "mesa" =>$sheet->getCell("I".$row),
                        "codigo_instalacion" =>$sheet->getCell("J".$row),
                        "codigo_apertura" =>$sheet->getCell("K".$row),
                        "codigo_cierre" =>$sheet->getCell("L".$row),
                        "codigo_transmision" =>$sheet->getCell("M".$row),
                        "modelo_maquina" =>$sheet->getCell("N".$row),
                        "id_estatus_maquina" =>$sheet->getCell("O".$row),
                    );
                    
                    // agregamos el registro en el arreglo
                    array_push($data->records, $fila);
                }
                
            }
            log_message('info', 'LoadCV|do_upload|fin recorrido archivo cargado');
            
            // cerramos el archivo
            
            // eliminamos el archivo cargado
            unlink($this->upload->data('file_path') . $this->upload->data('file_name'));
            
            // insertamos en la tabla temporal el arreglo con los registros
            $this->MaquinaVotacion_model->insertTablepaymentsTem($tablename, $data->records);
            // $params = array();
            
            $_SESSION['table_temp_nom'] = $tablename;
            
            $data->success = "Archivo cargado con &Eacute;xito. Recuerde ir al final de la p&aacute;gina y Guardar, para que los cambion se almacenen en la BD.";
            $this->data = $data;
            
            $this->index();
      
        log_message('info', 'LoadCV|fin do_upload');
    
    }
    
    public function guardar(){
        
        $data = new stdClass();
        
        if (isset($_SESSION['table_temp_nom']) ) {

            $detalle =$this->MaquinaVotacion_model->getTablepaymentsTem($_SESSION['table_temp_nom']);
            $truncateLoadTemplateData =$this->MaquinaVotacion_model->truncateLoadTemplateData();
            if ($truncateLoadTemplateData) {
                // $this->payments_model->insertPayment($this->input->post("descripcion"),$this->input->post("id_proyecto"),$this->input->post("id_gerencia"),$_SESSION['id'],$detalle);
                $resultado =  $this->MaquinaVotacion_model->updateMv($detalle->result());
                if ($resultado){
                    $data->success = 'Se ha Cargado con &Eacutexito el Archivo.';
                }else{
                    $data->error = 'Ha acorrido un error inesperado, por favor intente de nuevo.';
                }
            } else {
                $data->error = 'Ha acorrido un error inesperado vaciando las tablas Por favor intente de nuevo.';
            }
        }else{
            $data->error = 'Ha acorrido un error inesperado, tabla origen no encontrada. Por favor intente de nuevo.';
        }
        
        if (isset($_SESSION['table_temp_nom'])){
            $this->MaquinaVotacion_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
            unset($_SESSION['table_temp_nom']);
            
        }
        if (isset($_SESSION['start_index_load_payment'])){
            unset($_SESSION['start_index_load_payment']);
        }
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation',$data);
        $this->load->view('loadcv/loadcv');
        $this->load->view('templates/footer');
        
    }

    public function downloads()
    {
        $this->load->helper('download');
        force_download('./plantilla/plantilla.xlsx', NULL);
    }
}