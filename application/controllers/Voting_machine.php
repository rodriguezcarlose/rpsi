<?php

// session_start(); //we need to start session in order to access it through CI
class Voting_machine extends CI_Controller
{
    private $data;
    public function __construct()
    {
        parent::__construct();
        
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
    }

    // Show login page
    public function index()
    {
        $data = new stdClass();
        
        if ( $this->UsuarioMaquina_model->getCountUsuario($_SESSION['id']) > 0){
            $idmaquina = $this->UsuarioMaquina_model->getMaquinaIDByUser($_SESSION['id']);
            $result = $this->MaquinaVotacion_model->getDetailVotingMachinebById($idmaquina);
            $dataVotingMachine=array('consulta'=>$result);
            $data->success = "Usted Ya tiene seleccionada una M&aacute;quina previamente. Presione Aceptar para continuar, o cancelar para seleccionar otra M&aacute;quina.";
            if($result != null){
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/detail_voting_machine',$dataVotingMachine);
            }
        }else{
            $data = $this->data;
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $this->load->view('test/search_voting_machine');
            $this->load->view('templates/footer');
        }
    }

    public function consultar()
    {
        $data = new stdClass();
        $this->form_validation->set_rules('numero_meson', 'Numero de meson', 'trim|required|xss_clean|numeric|min_length[1]|max_length[3]',array('required' => 'El Numero de meson es requerido','numeric' => 'El Numero de meson solo permite numeros','min_length' => 'El Numero de meson debe indicar al menos 1 digitos','max_length' => 'El Numero de meson debe indicar m&aacute;ximo 2 digitos'));
        $this->form_validation->set_rules('codigo_centrovotacionmesa', 'C&oacute;digo de centro de votacion', 'trim|required|xss_clean|exact_length[14]', array('required' => 'El centro de votaci&oacute;n es requerido','numeric' => 'El centro de votaci&oacute;n solo permite numeros','exact_length' => 'El centro de votaci&oacute;n debe indicar 14 digitos'));
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/search_voting_machine');
            $this->load->view('templates/footer');
        } else {
            
            $centrovotacionmesa=$this->input->post('codigo_centrovotacionmesa');
            
            $campos= explode(".",$centrovotacionmesa);
            
            if(count($campos)==3){
                $centrovotacion=$campos[0];
                $mesa=$campos[1];
                
               
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
                    $this->load->view('test/search_voting_machine');
                    $this->load->view('templates/footer');
                }
            }else{
                $data->error = "No se encontr&oacute; el n&uacute;mero consultado. Formato Invalido";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/search_voting_machine');
                $this->load->view('templates/footer');
            }
            
        }
    }

    public function seleccionada()
    {
        $data = new stdClass();
        
        $idmaquina=$this->input->post('id');
        
        $data->consulta = $this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);
        $data->errormv = $this->Error_model->getError();
        $data->tiporeemplazo = $this->TipoReemplazo_model->getTipoReemplazo();
        
        $fila=$data->consulta->result();
        $usuariomaquina = array();
        $usuariomaquina["id_usuario"] = $_SESSION['id'];
        $usuariomaquina["id_maquina"] = $fila[0]->id;
        
        
        if ($this->UsuarioMaquina_model->getmaquina($usuariomaquina) > 0){
            $data = new stdClass();
            $data->error = "la m&aacute;quina ya se ecuentra selccionada por otro usuario.";
            $this->data = $data;
            $this->index();
            
        }else{
            
            if ($this->UsuarioMaquina_model->getusuarioMaquina($usuariomaquina) == 0){
                //marcamos la mesa como selccionada para el usuario
                $this->UsuarioMaquina_model->selccionarMesa($usuariomaquina);
            }
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/test_voting_machine',$data);
            $this->load->view('templates/footer');
        }
        
       
    }
    public function resettest()
    {
        $data = new stdClass();
        $seleccionada=1;
        $this->form_validation->set_rules('codigo_centrovotacion', 'C&oacute;digo de centro de votacion', 'trim|required|xss_clean|exact_length[9]', array('required' => 'El centro de votaci&oacute;n es requerido','numeric' => 'El centro de votaci&oacute;n solo permite numeros','exact_length' => 'El centro de votaci&oacute;n debe indicar 9 digitos'));
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean|numeric|min_length[1]|max_length[2]',array('required' => 'La mesa es requerida','numeric' => 'La mesa solo permite numeros','min_length' => 'La mesa debe indicar al menos 1 digitos','max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'));
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('test/reset_test_voting_machine');
            $this->load->view('templates/footer');
        } else {
            
            $centrovotacion=$this->input->post('codigo_centrovotacion');
            $mesa=$this->input->post('mesa');
            
            $this->load->model('MaquinaVotacion_model');
            $result=$this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion,$mesa);

            if($result != null){
                $dataVotingMachine=$result->result();
                $dataVotingMachine[0]->id;
                $result=$this->MaquinaVotacion_model->updateStatusVotingMachine($dataVotingMachine[0]->id,$seleccionada);

                if ($result){
                    $data->success = "Reiniciada Exitosamente";
                }else{
                    $data->error = "Error al Reiniciar M&aacute;quina Votaci&oacute;n";
                }
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/reset_test_voting_machine');
            
            }else{
                $data->error = "No se encontr&oacute; el n&uacute;mero consultado.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/reset_test_voting_machine');
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
    
    public function procesar(){
        
        $data = new stdClass();
        $errosrselect = array();
        $cantError = 0;
        $reemplazo = false;
        $validation = true;
        $idmaquina=$this->input->post('id');
        $data->consulta = $this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);
        $data->errormv = $this->Error_model->getError();
        $data->tiporeemplazo = $this->TipoReemplazo_model->getTipoReemplazo();
        
        
        //si se selecciono un error de la lista validamos el tipo de error
        $cantError = count($this->input->post('error'));
        if ($cantError > 0){
            foreach ($this->input->post('error') as $error){
                array_push($errosrselect,$error);
            }
            $result = $this->Error_model->getTipoErrorById($errosrselect);
            
            foreach ($result->result() as $tipoErrorSelect){
                //si el alguno de los tipos de error es 2 validamos que haya selecconado un  tipo de reemplazos
                if ($tipoErrorSelect->id_tipo_error == "2"){
                    $reemplazo = true;
                    break;
                }
            }
        }
       
        // si el tipo de error seleccionado requiere reemplazo validamos que haya selecionado uno.
        if ($reemplazo){
            if ($this->form_validation->required($this->input->post('tiporeemplazo')) == false){
                $validation = false;
                $data->error = "Debe seleccionar un tipo de reemplazo para el error seleccionado.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/test_voting_machine',$data);
                $this->load->view('templates/footer');
            }
        }
       
        if ($validation){
            $codigo = $this->MaquinaVotacion_model->getCodigoByStatusId($this->input->post('estatusmv'),$this->input->post('id'));
            $proxEstatus = "";
            $idproxEstatus = $this->input->post('idestatusmaquina');
            $fase = 0;
            switch ($this->input->post('estatusmv')) {
                case "SELECCIONADA":
                    $codigo = $codigo->codigo_instalacion;
                    $proxEstatus = "Instalaci&oacute;n";
                    $idproxEstatus = 2;
                    $fase = 1;
                    break;
                case "INSTALADA":
                    $codigo = $codigo->codigo_apertura;
                    $proxEstatus = "Apertura";
                    $idproxEstatus = 3;
                    $fase = 2;
                    break;
                case "APERTURADA":
                    $codigo = $codigo->codigo_cierre;
                    $proxEstatus = "Votacion";
                    $idproxEstatus = 4;
                    $fase = 3;
                    break;
                case "VOTACION":
                    $codigo = $codigo->codigo_cierre;
                    $proxEstatus = "Cierre";
                    $idproxEstatus = 5;
                    $fase = 4;
                    break;
                case "CERRADA":
                    $codigo = $codigo->codigo_transmision;
                    $proxEstatus = "Transmisi&oacute;n";
                    $idproxEstatus = 6;
                    $fase = 5;
                    break;
                    
            }
            //validamos el c�digo de Validaci�n
            // Si no seleciono un error el c�digo de validaci�n es requerido
            if($cantError == 0 && $this->input->post('idestatusmaquina') !== "3" && $this->form_validation->required($this->input->post('codigo')) == false){
                $data->error = "El C&oacute;digo Validac&oacute;n es requerido";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/test_voting_machine',$data);
                $this->load->view('templates/footer');
                //si no selecciono error y el codigo de validaci�n no pertenece al proximo estatus
            }else if($cantError == 0 && $this->input->post('idestatusmaquina') !== "3" && $codigo !== $this->input->post('codigo')){
                $data->error = "El c&oacute;digo no es v&aacute;lido, se esperra el c&oacute;digo de ".$proxEstatus.".";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/test_voting_machine',$data);
                $this->load->view('templates/footer');
                //si selecciono un error y coloco un coigo de estatus invalido.
            }else if ($cantError > 0 && $this->input->post('idestatusmaquina') !== "3" && $this->input->post('codigo') !== "" && $codigo !== $this->input->post('codigo')){
                $data->error = "El c&oacute;digo no es v&aacute;lido, se esperra el c&oacute;digo de ".$proxEstatus.".";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/test_voting_machine',$data);
                $this->load->view('templates/footer');
            }else if($this->input->post('idestatusmaquina') == "5" && $this->form_validation->required($this->input->post('medio')) == false){
                $data->error = "El Medio de  Transmisi&oacute;n es requerido.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('test/test_voting_machine',$data);
                $this->load->view('templates/footer');
            }else{
                $proceso = array();
                $errorselect = array();
                $proceso = [
                    "id_maquina_votacion"=>$this->input->post("id"),
                    "id_usuario"=>$_SESSION['id'],
                    "id_fase"=>$fase,
                    "fechainicio"=>date('Y-m-d H:i:s'),
                    "fechafin"=>date('Y-m-d H:i:s'),
                ];
                
                $procesoError = array();
                
                
                
                if ($cantError > 0){
                    foreach ($this->input->post('error') as $error){
                        $reemplazo = null;
                        $result = $this->Error_model->getTipoErrorById($error);
                        
                        if ($tipoErrorSelect->id_tipo_error == "2"){
                            $reemplazo = $this->input->post('tiporeemplazo');
                        }
                        
                        if ($reemplazo !== null){
                            $errorselect = [
                                "id_proceso"=>"",
                                "id_error"=>$error,
                                "id_tipo_reemplazo"=>$reemplazo,
                                "fecha"=>date('Y-m-d H:i:s'),
                            ];
                        }else{
                            $errorselect = [
                                "id_proceso"=>"",
                                "id_error"=>$error,
                                "fecha"=>date('Y-m-d H:i:s'),
                            ];
                        }
                        
                        array_push($procesoError,$errorselect);
                    }
                   
                }
                if($codigo === $this->input->post('codigo') || $this->input->post('idestatusmaquina') === "3") {
                    $this->Proceso_model->insertproceso($proceso,$procesoError,$idproxEstatus,$this->input->post('medio'));
                }else{
                    $this->Proceso_model->insertproceso($proceso,$procesoError,$this->input->post('idestatusmaquina'),$this->input->post('medio'));
                }
                //des selecionamos la máquina del usurio
                
                $fila=$data->consulta->result();
                $usuariomaquina = array();
                $usuariomaquina["id_usuario"] = $_SESSION['id'];
                $usuariomaquina["id_maquina"] = $this->input->post('id');
                $this->UsuarioMaquina_model->desSelccionarMesa($usuariomaquina);
                
                $data = new stdClass();
                $data->success = "Se ha registrado con &eacute;xito el proceso de la m&aacute;quina.";
                $this->data = $data;
                $this->index();
                
            }
        }
    }

    public function cancelar() {
        $usuariomaquina = array();
        $usuariomaquina["id_usuario"] = $_SESSION['id'];
        $usuariomaquina["id_maquina"] = $this->input->post('id');
        $this->UsuarioMaquina_model->desSelccionarMesa($usuariomaquina);
        //data->success = "Se ha registrado con &eacute;xito el proceso de la m&aacute;quina.";
        $this->index();
    }
}

?>