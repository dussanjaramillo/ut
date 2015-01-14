<?php

class Asrefiscalizacion extends MY_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);

    }

    function index(){
        $this->manage();
    }

    function manage(){

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asrefiscalizacion/manage'))
            {
                $this->template->set('title', 'Asignar - Reasignar Manualmente Una Fiscalizacion');
                //template data
                $this->add();

                $this->data['message']=$this->session->flashdata('message');

            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/inicio');
            }

        }else
        {
            redirect(base_url().'index.php/auth/login');
        }
    }

    function add()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asrefiscalizacion/add'))
            {

                $this->load->library('form_validation');
                $this->data['custom_error'] = '';


                $this->form_validation->set_rules('hallazgo', 'Hallazgo', 'trim|xss_clean|max_length[20]');
                $this->form_validation->set_rules('radicado', 'Radicado', 'trim|xss_clean|max_length[20]');
                $this->form_validation->set_rules('regional_id', 'Regional', 'required|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('fiscalizador', 'Fiscalizador',  'required|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('motivo', 'Motivo Reasignacion',  'required|xss_clean|numeric|greater_than[0]');
                if ($this->form_validation->run() == false)
                {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                } else
                {
                    $seleccion = $this->input->post('motivo');
                    $id_fiscalizador = $this->input->post('fiscalizador');
                    $datafiscalizador = $this->codegen_model->getSelect('USUARIOS','EMAIL,CORREO_PERSONAL','WHERE IDUSUARIO = '.$id_fiscalizador);
                    foreach ($datafiscalizador as $row)
                    {
                        $emaile = $row->EMAIL;
                        $emailp = $row->CORREO_PERSONAL;

                        $destinatarios = array($emaile, $emailp);

                    }

                    $datamotivo = $this->codegen_model->getSelect('MOTIVOREASIGNACION','NOMBRE_MOTIVO','WHERE COD_MOTIVO_REASIGNACION = '.$this->input->post('motivo'));
                    foreach ($datamotivo as $row)
                    {
                        $motivo = $row->NOMBRE_MOTIVO;

                    }

                    $dataregional = $this->codegen_model->getSelect('REGIONAL','NOMBRE_REGIONAL','WHERE COD_REGIONAL = '.$this->input->post('regional_id'));
                    foreach ($dataregional as $row)
                    {
                        $regional = $row->NOMBRE_REGIONAL;

                    }

                    $nit = $this->input->post('nit');
                    $rsocial = $this->input->post('razonsocial');
                    $ciiu = $this->input->post('ciiu');
                    $ciudad = $this->input->post('ciudad');




                    if($seleccion == 6)
                    {
                        echo "entre a la validacion del formulario onbase";
                        $this->form_validation->set_rules('radicacion', 'Numero De Radicacion', 'required|xss_clean|numeric|max_length[12]');
                        $this->form_validation->set_rules('nis', 'NIS', 'required|xss_clean|trim|max_length[12]');
                        $this->form_validation->set_rules('fechar', 'Fecha De Radicado', 'required|xss_clean|trim|max_length[10]');
                        $this->form_validation->set_rules('enviado', 'Enviado Por', 'required|xss_clean|trim|max_length[80]');
                        $this->form_validation->set_rules('cargo_id', 'Cargo',  'required|xss_clean|numeric|greater_than[0]');
                        $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|xss_clean|trim|max_length[500]');

                        if ($this->form_validation->run() == false)
                        {
                            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);

                        }else
                        {

                            $dateonbase = array(
                                'FECHA_RADICADO' => set_value('fechar')

                            );

                            $dataonbase = array(

                                //datos que se cargaran en la tabla DATOSENLACE
                                'COD_REASIGNACION' => set_value(''),//campo no nulo
                                'COD_ONBASE_UGPP' => set_value(''),
                                'NRO_RADICADO_ONBASE' => set_value('radicacion'),
                                'ENVIADO_POR' => set_value('enviado'),
                                'COD_CARGO' => set_value('cargo_id'),
                                'OBSERVACIONES' => set_value('observaciones')

                            );
                        }

                    }else
                    {
                        echo "no eligio onbase";
                        $date = array(
                            'FECHA_REASIGNACION' => date("d/m/Y")


                        );
                        //datos que se cargaran en la tabla REASIGNACIONFISCALIZADOR
                        $data = array(

                            'COD_REASIGNACION' => set_value(''),//no nulo
                            'COD_FISCALIZADOR_ACTUAL' => set_value('fiscalizador'),
                            'COD_FISCALIZADOR_NUEVO' => set_value('fiscalizador'),
                            'CODMOTIVOREASIGNACION' => set_value('motivo'),
                            'COMENTARIOS' => set_value(''),//no nulo
                            'COD_ASIG_FISCALIZACION' => set_value(''),//no nulo
                            'HALLAZGO_UGPP' => set_value('hallazgo'),
                            'RADICADO_UGPP' => set_value('radicado'),


                        );

                        if($seleccion == 6)
                        {
                            echo "entre a la insercion de dataonbase";
                            $date = array(
                                'FECHA_REASIGNACION' => date("d/m/Y")


                            );
                            $data = array(

                                //datos que se cargaran en la tabla REASIGNACIONFISCALIZADOR
                                'COD_REASIGNACION' => set_value(''),//no nulo
                                'COD_FISCALIZADOR_ACTUAL' => set_value('fiscalizador'),
                                'COD_FISCALIZADOR_NUEVO' => set_value('fiscalizador'),
                                'CODMOTIVOREASIGNACION' => set_value('motivo'),
                                'COMENTARIOS' => set_value(''),//no nulo
                                'COD_ASIG_FISCALIZACION' => set_value(''),//no nulo
                                'HALLAZGO_UGPP' => set_value('hallazgo'),
                                'RADICADO_UGPP' => set_value('radicado'),
                            );
                            if ($this->codegen_model->add('REASIGNACIONFISCALIZADOR',$data,$date) == TRUE && $this->codegen_model->add('DATOSENLACE',$dataonbase,$dateonbase)  == TRUE)
                            {

                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La re-asignacion se ha realizado con éxito.</div>');
                                redirect(base_url().'index.php/asrefiscalizacion');

                                echo "llego a enviar el email";
                                $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad);
                            }else
                            {
                                $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';

                            }

                        }else
                        {
                            echo "insercion diferente a onbase";
                            echo "llego a enviar el email";
                            $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad);
                            if ($this->codegen_model->add('REASIGNACIONFISCALIZADOR',$data,$date) == TRUE)
                            {

                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La re-asignacion se ha realizado con éxito.</div>');
                                redirect(base_url().'index.php/asrefiscalizacion');

                                echo "llego a enviar el email";
                                $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad);

                            }else
                            {
                                $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';

                            }

                        }




                    }


                }
                //$this->data['result'] = $this->codegen_model->get('EMPRESA','CODEMPRESA,COD_MUNICIPIO,RAZON_SOCIAL,DIRECCION,CIIU,COD_TIPOENTIDAD','CODEMPRESA = '.$this->uri->segment(3),1,1,true);

                //add style an js files for inputs selects
                $this->data['style_sheets']= array(
                    'css/chosen.css' => 'screen',
                    'css/bootstrap.min.css' => 'screen'
                );
                $this->data['javascripts']= array(
                    'js/chosen.jquery.min.js',
                    'js/bootstrap.min.js'

                );
                $f = "'S'";
                $this->data['fiscalizadores']  = $this->codegen_model->getSelect('USUARIOS','IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL','WHERE FISCALIZADOR = '.$f);
                $this->data['regional']  = $this->codegen_model->getSelect('REGIONAL','COD_REGIONAL,NOMBRE_REGIONAL');
                $this->data['cargos']  = $this->codegen_model->getSelect('CARGOS','IDCARGO,NOMBRECARGO');
                $this->data['motivos']  = $this->codegen_model->getSelect('MOTIVOREASIGNACION','COD_MOTIVO_REASIGNACION,NOMBRE_MOTIVO');
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'asrefiscalizacion/asrefiscalizacion_add', $this->data);
            }else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/asrefiscalizacion');
            }
        }else
        {
            redirect(base_url().'index.php/auth/login');
        }
    }

    function llenarcombo()
    {


        $options = "";

        if($this->input->post('regional_id'))
        {
            $regional = $this->input->post('regional_id');

            $fiscalizador = $this->codegen_model->getSelect('USUARIOS','IDUSUARIO,NOMBRES,APELLIDOS','WHERE IDGRUPO = 2 AND COD_REGIONAL ='.$regional);


            foreach($fiscalizador as $row)
            {
                $selectf[$row->IDUSUARIO] = $row->NOMBRES.' '.$row->APELLIDOS;

                /* Forma alternativa de llenar el combo
                *?>

                <option value="'<? echo $row->IDUSUARIO ?>'"><? echo $row->NOMBRES.' '.$row->APELLIDOS ?></option>

                <?php*/
            }
            echo form_dropdown('fiscalizador', $selectf,'','id="fiscalizador" class="chosen" placeholder="seleccione..." ');

        }
    }


    function enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad)
    {

        $this->load->library('email');

        $this->email->from('recaudoycobro@sena.com.co', 'SENA || Sistema gestión de recaudo y cobro');
        $this->email->to($destinatarios,'jleoramirezm@hotmail.com');
        $this->email->cc('');
        $this->email->bcc('');

        $this->email->subject('Nueva asignacion - '.$rsocial);
        $this->email->message('Nueva asignacion - '.$rsocial.
            '<br>NIT. '.$nit.
            '<br>Razón Social. '.$rsocial.
            '<br>CIIU. '.$ciiu.
            '<br>Ciudad. '.$ciudad.
            '<br>Regional. '.$regional.
            '<br>Motivo De Asignación. '.$motivo.'<br>'
        );

        $this->email->send();



    }
}




