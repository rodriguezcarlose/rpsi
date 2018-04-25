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
        log_message('info', 'LoadCV|inicio do_upload');
        
        if (isset($_SESSION['table_temp_nom'])) {
            $this->MaquinaVotacion_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
            unset($_SESSION['table_temp_nom']);
        }
        
        if (isset($_SESSION['start_index_load_payment'])) {
            unset($_SESSION['start_index_load_payment']);
        }
        
        $data = new stdClass();
        $mv = new stdClass();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 0;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $this->load->library('upload', $config);
        
        if (! $this->upload->do_upload('userfile')) {
            $data->error = $this->upload->display_errors();
            $this->data = $data;
            log_message('error', 'Payment|do_upload|' . $data->error);
            $this->index();
        } else {
            
            // cargamos el arcivo
            $file = $this->upload->data('file_path') . $this->upload->data('file_name');
            
            // abrimos el archivo
            $fp = fopen($file, "r");
            
            $mv->records = array();
            
            $tablename = "maquina_voacion_temp" . $_SESSION['id'];
            
            // Creamos una tabla temporal para cargar los archivos
            $this->MaquinaVotacion_model->createTablepaymentsTem($tablename);
            
            $i = 0;
            log_message('info', 'Payment|do_upload|inicio recorrido archivo cargado');
            while ($datafile = fgetcsv($fp, 1000, ";")) {
                
                // descartamos la primera linea del archivo que contiene los nembres de los campos
                if (! $i == 0) {
                    
                    // creamos el arreglo con la informaci�n de cada registro que se pasara al metodo de inseci�n de datos en la
                    // tabla temporal
                    
                    $row = array(
                        "codigo_estado" => $datafile[0],
                        "estado" => $datafile[1],
                        "codigo_municipio" => $datafile[2],
                        "municipio" => $datafile[3],
                        "codigo_parroquia" => $datafile[4],
                        "parroquia" => $datafile[5],
                        "codigo_centrovotacion" => $datafile[6],
                        "centro_votacion" => $datafile[7],
                        "mesa" => $datafile[8],
                        "codigo_instalacion" => $datafile[9],
                        "codigo_apertura" => $datafile[10],
                        "codigo_cierre" => $datafile[11],
                        "codigo_transmision" => $datafile[12],
                        "modelo_maquina" => $datafile[13],
                        "id_estatus_maquina" => $datafile[14],
                        "numero_meson" => $datafile[15]
                    );
                    
                    // agregamos el registro en el arreglo
                    array_push($mv->records, $row);
                }
                $i ++;
            }
            log_message('info', 'LoadCV|do_upload|fin recorrido archivo cargado');
            
            // cerramos el archivo
            fclose($fp);
            
            // eliminamos el archivo cargado
            unlink($this->upload->data('file_path') . $this->upload->data('file_name'));
            
            // insertamos en la tabla temporal el arreglo con los registros
            $this->MaquinaVotacion_model->insertTablepaymentsTem($tablename, $mv->records);
            // $params = array();
            
            $_SESSION['table_temp_nom'] = $tablename;
            
            $data->success = "Archivo cargado con &Eacute;xito. Recuerde ir al final de la p&aacute;gina y aceptar, para que los cambion se almacenen en la BD.";
            $this->data = $data;
            
            $this->index();
        }
        log_message('info', 'LoadCV|fin do_upload');
    }

    public function guardar()
    {
        $data = new stdClass();
        
        if (isset($_SESSION['table_temp_nom'])) {
            $detalle = $this->MaquinaVotacion_model->getTablepaymentsTem($_SESSION['table_temp_nom']);
            $resultado = $this->MaquinaVotacion_model->updateMv($detalle->result());
            if ($resultado) {
                $data->success = 'Se han cargado con &eacute;xito los centros de votaci&oacute;n.';
            } else {
                $data->error = 'Ha acorrido un error inesperado, por favor intente de nuevo.';
            }
        } else {
            $data->error = 'Ha acorrido un error inesperado, tabla origen no encontrada. Por favor intente de nuevo.';
        }
        
        if (isset($_SESSION['table_temp_nom'])) {
            $this->MaquinaVotacion_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
            unset($_SESSION['table_temp_nom']);
        }
        $this->data = $data;
        
        $this->load->view('templates/header');
        $this->load->view('templates/Navigation', $this->data);
        $this->load->view('loadcv/loadcv');
        $this->load->view('templates/footer');
    }

    public function downloads()
    {
        $this->load->helper('download');
        force_download('./plantilla/plantilla.csv', NULL);
    }
}