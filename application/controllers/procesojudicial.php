<?php

class Procesojudicial extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper'));
        $this->load->model('titulo_model', '', TRUE);
        $this->load->model('demanda_model', '', TRUE);
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js'
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("ABOGADO_PROCESOS_JUDICIALES", "45");
        define("DIRECTOR_REGIONAL", "61");
        define("COORDINADOR_GRUPO_GC", "62");
        define("TITULO_REGISTRADO", "422");
        define("ABOGADO_ASIGNADO", "423");
        define("TITULO_EXIGIBLE", "424");
        define("TITULO_NO_EXIGIBLE", "425");
        define("DEMANDA_REGISTRADA", "426");
        define("DEMANDA_RECHAZADA_POR_COMPETENCIA", "427");
        define("DEMANDA_RECHAZADA", "428");
        define("LIBRAR_MANDAMIENTO_DE_DEMANDA", "429");
        define("DEMANDA_INADMITIDA", "430");
        define("DEMANDA_SUBSANADA", "431");
        define("DEMANDA_RETIRADA", "432");
        define("DEMANDA_PRESENTADA_OTRA_VEZ", "433");
        define("DEMANDADO_EXCEPCIONA", "434");
        define("DEMANDADO_NO_EXCEPCIONA", "435");
        define("PROFERIMIENTO_VERIFICADO", "436");
        define("PRONUNCIAMIENTO_EN_EXCEPCIONES", "437");
        define("DECRETARON_PRUEBAS", "438");
        define("NO_DECRETARON_PRUEBAS", "439");
        define("TITULO_CON_ALEGATO_DE_CONCLUSION", "440");
        define("FAVORABLE_AL_SENA", "441");
        define("NO_FAVORABLE_AL_SENA", "442");
        define("LIQUIDACION_GENERADA", "443");
        define("DEMANDA_NO_SUBSANADA", "444");
        define("LIQUIDACION_ENTREGADA", "445");
        define("LIBRAR_MANDAMIENTO_APROBADO", "786");
        define("NEGAR_MANDAMIENTO", "1329");
        define("DEMANDA_RECHAZADA_COMPLETAMENTE", "1330");
        define("SOPORTE_ENTREGA_LIQUIDACION", "1331");
        define("MEDIDA_CAUTELAR_EXISTE", "1332");
        define("MEDIDA_CAUTELAR_NO_EXISTE", "1333");
        define("DEUDA_PAGADA", "1334");
        define("DEUDA_VIGENTE", "1335");
        define("SOPORTE_TERMINACION", "1336");
        define("SOPORTE_COSTAS", "1370");
        define("SOPORTE_AVALUOS", "1371");
        define("ACUERDO_PAGO_GENERADO", "1372");
        define("ACUERDO_PAGO_INCUMPLIDO", "1373");
        define("ACUERDO_PAGO_CUMPLIDO", "1374");
        define("TITULO_MODIFICADO", "1377");

    }

    function index() {
        $this->Listar_Menu();
    }

    /* @param type $rol
     * @return boolean/
     * verifica si el rol pertenece el usuario logeado evaluando 
     * 45 abogado procesos judiciales
     * 61-- director regional
     * 62-- coordinador grupo gestion de cobro coactivo
     */

    private function verificar_rol($rol) {
        $usuario = $this->ion_auth->user()->row();
        $id_usuario = $usuario->IDUSUARIO;
        if ($this->titulo_model->verificar_permiso($id_usuario, $rol)) {
            return true;
        } else {
            return false;
        }
    }

    function Recepcion_Titulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Recepcion_Titulos')) {
                $this->template->set('title', 'Titulos Ejecutivos');
                $this->data['estados_seleccionados'] = $this->titulo_model->get_procesotransversal();
                $this->data['grupo'] = 'Abogado de Cobro Coactivo';
                $this->data['ruta'] = 'Registrar_Titulo';
                $this->template->load($this->template_file, 'titulosjudiciales/gestionproceso_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MODIFICAR TITULOS
     */

    function Lista_ModificarTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_ModificarTitulos') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES) || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $this->template->set('title', 'Modificar Titulo');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulosmodificar();
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Modificar_Titulo_Judicial';
                $this->data['titulo'] = 'Modificar Titulos Judiciales'; 
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data); //vista de la lista de titulos con abogado asignado
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Agregar_Titulo_Judicial
     * RQ27_CU001_Registrar Proceso Judicial
     * verifica que cada campo cumpla con el debido requerimiento de campos
     * posteriormente guarda el titulo e inicializa la gestion de titulo
     */

    function Modificar_Titulo_Judicial() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Titulo_Judicial') || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('codigo_reg', 'Codigo Regional', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('n_escritura', 'No. Escritura', 'required|numeric|max_length[8]');
                $this->form_validation->set_rules('nombre_Not', 'Notaria', 'required|numeric|max_length[3]');
                $this->form_validation->set_rules('id_ciudad', 'Ciudad', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_departamento', 'Departamento', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_propietario', 'Id. Propietario', 'required|numeric|max_length[15]');
                $this->form_validation->set_rules('nombre_propietario', 'Nombre Propietario', 'required|trim|max_length[30]');
                $this->form_validation->set_rules('n_matri_inmov', 'Numero de Matricula Inmoviliaria', 'required|max_length[15]');
                $this->form_validation->set_rules('dirinmueble', 'Direccion del Inmueble', 'required|trim|max_length[30]');
                $this->form_validation->set_rules('correo_propietario', 'Correo del Propietario', 'required|trim|max_length[30]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else { 
                    $informacion = array( 
                        'cod_titulo' => $this->input->post('cod_titulo'),
                        'cod_respuesta' => TITULO_MODIFICADO,
                        'cod_regional' => set_value('codigo_reg'),
                        'cod_ciudad' => set_value('id_ciudad'),
                        'cod_propietario' => set_value('id_propietario'),
                        'num_escritura' => set_value('n_escritura'),
                        'notaria' => set_value('nombre_Not'),
                        'nombre_propietario' => set_value('nombre_propietario'),
                        'num_matricula' => set_value('n_matri_inmov'),
                        'direccion_inmueble' => set_value('dirinmueble'),
                        'cod_departamento' => set_value('id_departamento'),
                        'correo_propietario' => set_value('correo_propietario'),
                        'fecha' => $this->input->post('tb_fecha')
                    ); 
                    if ($this->titulo_model->modificar_titulo($informacion)) {
                        $this->Traza_PJ($informacion['cod_respuesta'], $informacion['cod_titulo'], $comentarios = "TITULO JUDICIAL TITULO MODIFICADO");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El titulo ha sido registrado con Éxito.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_ModificarTitulos');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                    }
                }
                $this->template->set('title', 'Títulos ejecutivos');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['departamento'] = $this->titulo_model->obtener_departamentos();
                $this->data['regional'] = $this->titulo_model->obtener_regional();
                $this->data['custom_error'] = "";
                $ID = $this->input->post('cod_titulo'); //id del titulo     
                $this->data['cod_titulo'] = $ID;
                $this->data['valor_titulo'] = $this->titulo_model->get_titulo($ID);
                $this->template->load($this->template_file, 'titulosjudiciales/titulo_edit', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Listar_Menu RQ27_CU001_Registrar Proceso Judicial
     * visualiza el menu general * 
     */

    function Listar_Menu() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Listar_Menu') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES) || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                //template data
                $this->template->set('title', 'Menu Procesos Judiciales');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_grupo = $usuario->IDGRUPO;
                $this->data['grupo'] = $id_grupo;
                $this->data['admin'] = $this->ion_auth->is_admin();
                $this->template->load($this->template_file, 'titulosjudiciales/menuprocesosjudiciales_opc', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     *  Redireccionar_Menu recoje la opcion dada en la vista RQ27_CU001_Registrar Proceso Judicial
     * redirijida en listar menu
     */

    function Redireccionar_Menu() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Listar_Menu') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES) || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                redirect(base_url() . 'index.php/' . $this->input->post('id_opcion'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * Registrar_Titulo 
     * RQ27_CU001_Registrar Proceso Judicial
     * visualizacion del formulario prototipo 3 
     * REGISTRO DEL TITULO JUDICIAL
     */

    function Registrar_Titulo() {//Caso de Uso 1
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Registrar_Titulo') || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $this->template->set('title', 'Títulos ejecutivos');
                $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
                if ($cod_fiscalizacion != '') {
                    $this->data['traslado'] = $this->titulo_model->get_procesotransversal($cod_fiscalizacion);
                }
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['departamento'] = $this->titulo_model->obtener_departamentos();
                $this->data['regional'] = $this->titulo_model->obtener_regional();
                $this->data['custom_error'] = "";
                $this->template->load($this->template_file, 'titulosjudiciales/titulo_add', $this->data); //vista prototipo 3 caso de uso 1
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Agregar_Titulo_Judicial
     * RQ27_CU001_Registrar Proceso Judicial
     * verifica que cada campo cumpla con el debido requerimiento de campos
     * posteriormente guarda el titulo e inicializa la gestion de titulo
     */

    function Agregar_Titulo_Judicial() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Titulo_Judicial') || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm = 0;
                $this->form_validation->set_rules('codigo_reg', 'Codigo Regional', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('n_escritura', 'No. Escritura', 'required|numeric|max_length[8]');
                $this->form_validation->set_rules('nombre_Not', 'Notaria', 'required|numeric|max_length[3]');
                $this->form_validation->set_rules('id_ciudad', 'Ciudad', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_departamento', 'Departamento', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_propietario', 'Id. Propietario', 'required|numeric|max_length[15]');
                $this->form_validation->set_rules('nombre_propietario', 'Nombre Propietario', 'required|trim|max_length[30]');
                $this->form_validation->set_rules('n_matri_inmov', 'Numero de Matricula Inmoviliaria', 'required|max_length[15]');
                $this->form_validation->set_rules('dirinmueble', 'Direccion del Inmueble', 'required|trim|max_length[30]');
                $this->form_validation->set_rules('correo_propietario', 'Correo del Propietario', 'required|trim|max_length[30]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $usuario = $this->ion_auth->user()->row();
                    $id_usuario = $usuario->IDUSUARIO;
                    $informacion = array(
                        'cod_regional' => set_value('codigo_reg'),
                        'cod_respuesta' => TITULO_REGISTRADO, //ESTADO ENTREGADO
                        'cod_ciudad' => set_value('id_ciudad'),
                        'cod_propietario' => set_value('id_propietario'),
                        'num_escritura' => set_value('n_escritura'),
                        'notaria' => set_value('nombre_Not'),
                        'nombre_propietario' => set_value('nombre_propietario'),
                        'num_matricula' => set_value('n_matri_inmov'),
                        'direccion_inmueble' => set_value('dirinmueble'),
                        'num_tp_abogado' => '0',
                        'claridad_titulo' => '0',
                        'titulo_expreso' => '0',
                        'titulo_cargado' => '1',
                        'cod_departamento' => set_value('id_departamento'),
                        'correo_propietario' => set_value('correo_propietario'),
                        'fecha' => $this->input->post('tb_fecha')
                    );
                    if ($this->input->post('cod_fiscalizacion') != '') {
                        trazar(511, 1258, $this->input->post('cod_fiscalizacion'), $this->input->post('nit_empresa'), $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, 'Integracion de Traslado a Procesos Judiciales');
                    }
                    if ($this->titulo_model->adicionar_titulo($informacion)) {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El titulo ha sido registrado con �xito.</div>');
                        
                        // Registra la traza de la insercion
                        $this->Traza_PJ($informacion['cod_respuesta'], '', $comentarios = "TITULO JUDICIAL REGISTRADO");

                        redirect(base_url() . 'index.php/procesojudicial/Registrar_Titulo');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU001_Registrar Proceso Judicial
     * Lista_Asignacion_Abogado
     * Se obtiene los titulos con estado de entregado para hacer gestion de asignar un abogado
     */

    function Lista_Asignacion_Abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Asignacion_Abogado') || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                //template data
                $this->template->set('title', 'Asignar Abogado');
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(TITULO_REGISTRADO); //llamar datos del estado 1 del modelo
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Asignacion_Abogado';
                $this->data['titulo'] = 'Asignacion de Abogado';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data); //vista de la lista de titulos con abogado asignado
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * Agregar_Asignacion_Abogado
     * RQ27_CU001_Registrar Proceso Judicial
     * Registra el abogado a un titulo y se lo asigna 
     */

    function Agregar_Asignacion_Abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Asignacion_Abogado') || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $ID = $this->input->post('cod_titulo'); //id del titulo                
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay titulo al cual asignar abogado.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Asignacion_Abogado');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('id_abogado', 'Abogado', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $data = array(
                            'COD_TITULO' => $ID,
                            'COD_ABOGADO_ASIGNADO' => $this->input->post('id_abogado'),
                            'COD_RESPUESTA' => ABOGADO_ASIGNADO //entra ha estado abogado asignado 
                        );
                        
                        if ($this->titulo_model->actualizar_titulo($data)) {
                            $this->Traza_PJ($data['COD_RESPUESTA'], $data['COD_TITULO'], $comentarios = "TITULO JUDICIAL ABOGADO ASIGNADO");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha asignado correctamente el Abogado.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Asignacion_Abogado');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    $regional_proceso = $this->titulo_model->get_regionaltitulo($ID);
                    $this->data['cod_titulo'] = $ID;
                    $this->data['abogados'] = $this->titulo_model->get_usuariosgrupo(ABOGADO_PROCESOS_JUDICIALES, $regional_proceso[0]->COD_REGIONAL); //lista de abogados judiciales
                    $this->template->load($this->template_file, 'titulosjudiciales/asignarabogado_add', $this->data); //vista para asignacion de abogados
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU002_Verificar exigibilidad
     * Lista_Verificar_Exigibilidad
     * Se obtiene todos los titulos judiciales con estado de abogado asignado
     */

    function Lista_Verificar_Exigibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Verificar_Exigibilidad') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Verificar Exigibilidad');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulosabo(ABOGADO_ASIGNADO, $id_usuario); //llamar datos del estado 1 del modelo
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Verificacion_Exigibilidad';
                $this->data['titulo'] = 'Verificar Exigibilidad del Titulo';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data); //vista de la lista de titulos con abogado asignado
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Verificacion_Exigibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Verificacion_Exigibilidad') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual verificar exigibilidad.' . $id_titulo . '</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Verificar_Exigibilidad');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('ta_observacion', 'Observaciones', 'required|max_length[300]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $consulta_correo = $this->titulo_model->obtener_correo_Onbase($id_titulo);
                        $correo_propietario = $consulta_correo["CORREO_PROPIETARIO"];
                        $cantidad_aprobada = 0;
                        $usuario = $this->ion_auth->user()->row();
                        $id_usuario = $usuario->IDUSUARIO;
                        for ($i = 0; $i <= $this->input->post('tb_cantidad'); $i++) {
                            if ($this->input->post('id_opcion' . $i) != '') {
                                $informacion_ex = array(
                                    'cod_titulo' => $id_titulo,
                                    'cod_tipoexigilidad' => $this->input->post('id_opcion' . $i),
                                    'observaciones' => $this->input->post('ta_observacion'),
                                    'confirmado' => 's', // 1-confirmado 2-no confirmado
                                    'confirmado_por' => $id_usuario,
                                    'fecha_confirmacion' => $this->input->post('tb_fecha')
                                );
                                $this->titulo_model->agregar_titulosexegibles($informacion_ex);
                                $cantidad_aprobada++;
                            }
                        }
                        if ($cantidad_aprobada == $this->input->post('tb_cantidad')) {
                            $estado = TITULO_EXIGIBLE; //estado exigible
                            $exigible = 's';
                            $observacion = 'se cambio estado de abogado asignado a estado de titulo exigible';
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado la exigibilidad del titulo.</div>');
                        } else {
                            $estado = TITULO_NO_EXIGIBLE; //estado no exigible
                            $exigible = 'n';
                            $observacion = 'se cambio estado de abogado asignado a estado de titulo no exigible';
                            enviarcorreosena($correo_propietario, 'Notificacion de Titulo Judicial SENA', 'Su titulo ha entrado en evaluacion y ha sido evaluado como no exigible');
                            $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado la no exigibilidad del titulo.</div>');
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'TITULO_EXIGIBLE' => $exigible,
                            'COD_RESPUESTA' => $estado //entra al siguiente estado de titulo exigible o no exigible 
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICACION EXIGIBILIDAD");
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Verificar_Exigibilidad');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    $this->data['id_codigo'] = $id_titulo;
                    $this->data['tipos_exigibilidad'] = $this->titulo_model->get_tipos_exigibilidad(); //cargar caracteristics para ser exigible
                    $this->template->load($this->template_file, 'titulosjudiciales/verificarexigibilidad_add', $this->data); //vista caso de uso 3
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU003_Presentar Demanda
     * Lista_Presentar_Demanda
     * Se obtiene todos los titulos con estado exigible para presentar por primera vez demanda
     */

    function Lista_Presentar_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Presentar_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Presentar Demanda');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulosabo(TITULO_EXIGIBLE, $id_usuario); //llamar datos del estado 1 del modelo
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Presentar_Demanda';
                $this->data['titulo'] = 'Presentar Demanda';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Presentar_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Presentar_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                $cod_abogado = $this->titulo_model->get_abogadoproceso($id_titulo);
                $id_abogado = $cod_abogado["COD_ABOGADO_ASIGNADO"];
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Presentar_Demanda');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('n_radicacion', 'Numero de Radicacion', 'required|max_length[23]');
                    $this->form_validation->set_rules('nom_juzgado', 'Nombre del Juzgado', 'required|max_length[30]');
                    $this->form_validation->set_rules('id_departamento', 'Departamento', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('id_ciudad', 'Ciudad', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('id_demandado', 'Identificacion del Demandado', 'required|max_length[15]');
                    $this->form_validation->set_rules('tpabogado', 'T.P del Abogado', 'required|max_length[15]');
                    $this->form_validation->set_rules('nombredemandado', 'Nombre del Demandado', 'required|max_length[30]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_multi_upload($id_titulo, 'SoporteDemanda');
                        $nombre_Soporte = $file[0]["upload_data"]["file_name"];
                        $informacion_soporte = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_Soporte,
                            'observaciones' => 'Soporte de la Demanda'
                        );
                        $informacion = array(
                            'cod_titulo' => $id_titulo,
                            'cod_ciudad' => $this->input->post('id_ciudad'),
                            'cod_demandado' => $this->input->post('id_demandado'),
                            'cod_estadodemanda' => DEMANDA_REGISTRADA, // 1-demanda presentada
                            'num_radi_proceso' => $this->input->post('n_radicacion'),
                            'cod_abogado' => $id_abogado,
                            'juzgado' => $this->input->post('nom_juzgado'),
                            'num_tp_abogado' => $this->input->post('tpabogado'),
                            'nombre_demandado' => $this->input->post('nombredemandado'),
                            'cod_departamento' => $this->input->post('id_departamento')
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => DEMANDA_REGISTRADA //5- demanda presentada
                        );
                        if ($this->titulo_model->adicionar_documentosoporte($informacion_soporte) and $this->demanda_model->adicionar_demanda($informacion) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL PRESENTAR DEMANDA");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la demanda del titulo.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Presentar_Demanda');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    //carga de datos primera vez
                    $this->data['id_titulo'] = $id_titulo;
                    if ($this->input->post('id_ciudad') == '') {
                        $this->data['custom_error'] = '';
                    }
                    $this->data['demanda_n'] = '1';
                    $this->data['class'] = "";
                    $this->data['departamento'] = $this->titulo_model->obtener_departamentos();
                    $this->template->load($this->template_file, 'titulosjudiciales/presentardemanda_add', $this->data); //vista al prototipo 2 del caso de uso presentar demanda
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/asignarabogado');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU004_Registrar resultado de la demanda
     * Lista_Resultado_Demanda
     * Se obtiene todos los titulos con demanda presentada por primera o por N vez
     * 
     */

    function Lista_Resultado_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Resultado_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Resultado de la Demanda');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulo'] = 'Notificar Resultado de la Demanda';
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Resultado_Demanda';
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(DEMANDA_REGISTRADA, DEMANDA_PRESENTADA_OTRA_VEZ, DEMANDA_RECHAZADA_POR_COMPETENCIA, $id_usuario); //-5 demanda presentada -12 Demanda Presentada otra vez
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Resultado_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Resultado_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                $cod_demanda = $this->demanda_model->get_obtenerdemanda($id_titulo, DEMANDA_REGISTRADA); //1- estado de la demanda -demanda presentada
                $id_demanda = $cod_demanda["COD_DEMANDA"];
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Resultado_Demanda');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/resultadodemanda_add', $this->data); //vista prototipo 2 del caso de uso resultado de la demanda
                    } else {
                        if ($respuesta == "rechazo") {
                            $rechazo = 's';
                            $liberar_mandamiento = 'n';
                            $inadmitida = 'n';
                            $porcompetencia = $this->input->post('competencia');
                            if ($porcompetencia == 's') {
                                $estado = DEMANDA_RECHAZADA_POR_COMPETENCIA; //6-rechazo por competencia
                            } else {
                                $estado = DEMANDA_RECHAZADA; //7-rechazo
                            }
                            $observacion = "la demanda del titulo fue registrada como rechazada";
                        }
                        if ($respuesta == "librar_mandamiento") {
                            $rechazo = 'n';
                            $liberar_mandamiento = 's';
                            $inadmitida = 'n';
                            $porcompetencia = 'n';
                            $estado = LIBRAR_MANDAMIENTO_DE_DEMANDA; //8-librar mandamiento
                            $observacion = "la demanda del titulo fue registrada como librar mandamiento";
                        }
                        if ($respuesta == "inadmitida") {
                            $rechazo = 'n';
                            $liberar_mandamiento = 'n';
                            $inadmitida = 's';
                            $porcompetencia = 'n';
                            $estado = DEMANDA_INADMITIDA; //9-inadmitida
                            $observacion = "la demanda del titulo fue registrada como inadmitida";
                        }
                        if ($respuesta == "negar_mandamiento") {
                            $rechazo = 'n';
                            $liberar_mandamiento = 'n';
                            $inadmitida = 'n';
                            $porcompetencia = 'n';
                            $estado = NEGAR_MANDAMIENTO; //9-inadmitida
                            $observacion = "la demanda del titulo fue registrada como mandamiento negado";
                        }

                        $informacion = array(
                            'cod_demanda' => $id_demanda,
                            'liberar_mandamiento' => $liberar_mandamiento,
                            'inadmitida' => $inadmitida,
                            'rechazo' => $rechazo,
                            'por_competencia' => $porcompetencia
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado
                        );
                        if ($this->demanda_model->adicionar_resultadodemanda($informacion) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL REGISTRAR RESULTADO DE LA DEMANDA");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado el resultado de la demanda.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Resultado_Demanda');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU005_Subsanar Demanda
     * Lista_Subsanar_Demanda
     * Se obtiene todos los titulos con estado de demanda inadmitida o rechazo por competencia
     */

    function Lista_Subsanar_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Subsanar_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Demanda Subsanada');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO; 
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(DEMANDA_INADMITIDA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Subsanar_Demanda';
                $this->data['titulo'] = 'Subsanar Defectos';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Subsanar_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Subsanar_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                $fecha = $this->input->post('tb_fecha');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/Lista_Subsanar_Demanda');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/subsanardemanda_add', $this->data); //vista prototipo nuevo caso de uso subsanar demanda
                    } else {
                        $cod_demanda = $this->demanda_model->get_obtenerdemanda($id_titulo, DEMANDA_REGISTRADA); //1- estado de demanda presentada en la tabla demanda
                        $id_demanda = $cod_demanda["COD_DEMANDA"];
                        $usuario = $this->ion_auth->user()->row();
                        $id_usuario = $usuario->IDUSUARIO;
                        if ($respuesta == 's') {
                            $informacion_demandasubsanada = array(
                                'cod_demanda' => $id_demanda,
                                'fecha_subsanacion' => $fecha,
                                'subsanada_por' => $id_usuario,
                                'observaciones_subsanacion' => 'La demanda fue registrada como subsanada despues de ser inadmitida o rechazada por competencia'
                            );
                            $datos_titulo = array(
                                'COD_TITULO' => $id_titulo,
                                'COD_RESPUESTA' => DEMANDA_SUBSANADA //10-Demanda subsanada
                            );
                            if ($this->demanda_model->registrar_demandasubsanada($informacion_demandasubsanada) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                                $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL SUBSANAR DEMANDA");
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la demanda del titulo como subsanada.</div>');
                                redirect(base_url() . 'index.php/procesojudicial/Lista_Subsanar_Demanda');
                            } else {
                                $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            }
                        } else {
                            $datos_titulo = array(
                                'COD_TITULO' => $id_titulo,
                                'COD_RESPUESTA' => DEMANDA_NO_SUBSANADA //25-Demanda no subsanada
                            );
                            if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                                $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL SUBSANAR DEMANDA");
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la demanda del titulo como no subsanada.</div>');
                                redirect(base_url() . 'index.php/procesojudicial/Lista_Subsanar_Demanda');
                            } else {
                                $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            }
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Lista_DemandasApoderadoNoActuo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_DemandasApoderadoNoActuo') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(DEMANDA_NO_SUBSANADA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_DemandaRechazada';
                $this->data['titulo'] = 'Rechazar Demanda';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_DemandaRechazada() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_DemandaRechazada') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_DemandasApoderadoNoActuo');
                } else {
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => DEMANDA_RECHAZADA_COMPLETAMENTE
                    );
                    if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL RECHAZAR DEMANDA");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha Rechazado la Solicitud de Demanda.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_DemandasApoderadoNoActuo');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU006_Demanda Retirada
     * Lista_Demanda_Retirada
     * Se obtiene los titulos con estado demanda rechazada o demanda no subsanada
     */

    function Lista_Demanda_Retirada() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('demandaretirada/manage') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Demanda Retirada');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(DEMANDA_RECHAZADA, DEMANDA_RECHAZADA_COMPLETAMENTE, NULL, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Demanda_Retirada';
                $this->data['titulo'] = 'Retirar Demanda';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Demanda_Retirada() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Demanda_Retirada') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Demanda_Retirada');
                } else {
                    $fecha = $this->input->post('tb_fecha');
                    $cod_demanda = $this->demanda_model->get_obtenerdemanda($id_titulo, DEMANDA_REGISTRADA); //1- estado de demanda presentada en la tabla demanda
                    $id_demanda = $cod_demanda["COD_DEMANDA"];
                    $usuario = $this->ion_auth->user()->row();
                    $id_usuario = $usuario->IDUSUARIO;
                    $informacion_demandaretirada = array(//tabla demanda retirada
                        'cod_demanda' => $id_demanda,
                        'fecha_retiro' => $fecha,
                        'retirada_por' => $id_usuario,
                        'observaciones_retiro' => 'se realizo el retiro de la demanda despues de haber sido registrada la demanda como rechazada'
                    );
                    $datos_demanda = array(//tabla demanda
                        'COD_DEMANDA' => $id_demanda,
                        'COD_RESPUESTA' => DEMANDA_RETIRADA //2-Demanda retirada
                    );
                    $datos_titulo = array(//tabla titulo
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => DEMANDA_RETIRADA //11-Demanda retirada
                    );
                    if ($this->demanda_model->registrar_demandarechazada($informacion_demandaretirada) and $this->demanda_model->actualizar_demanda($datos_demanda) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL DEMANDA RETIRADA");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha retirado la demanda.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_Demanda_Retirada');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU007_Nueva Presentaci�n
     * Lista_Nueva_Demanda
     * Se obtiene los titulos con estado demanda retirada
     */

    function Lista_Nueva_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Nueva_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Presentacion de Nueva Demanda');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(DEMANDA_RETIRADA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Nueva_Demanda';
                $this->data['titulo'] = 'Presentar Demanda Nuevamente';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Nueva_Demanda() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Nueva_Demanda') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                $cod_abogado = $this->titulo_model->get_abogadoproceso($id_titulo);
                $id_abogado = $cod_abogado["COD_ABOGADO_ASIGNADO"]; 
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Presentar_Demanda');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('n_radicacion', 'Numero de Radicacion', 'required|max_length[23]');
                    $this->form_validation->set_rules('nom_juzgado', 'Nombre del Juzgado', 'required|max_length[30]');
                    $this->form_validation->set_rules('id_departamento', 'Departamento', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('id_ciudad', 'Ciudad', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('id_demandado', 'Identificacion del Demandado', 'required|max_length[15]');
                    $this->form_validation->set_rules('tpabogado', 'T.P del Abogado', 'required|max_length[15]');
                    $this->form_validation->set_rules('nombredemandado', 'Nombre del Demandado', 'required|max_length[30]');
                
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {   
                        $file = $this->do_multi_upload($id_titulo, 'SoporteDemanda');
                        $nombre_Soporte = $file[0]["upload_data"]["file_name"];
                        $informacion_soporte = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_Soporte,
                            'observaciones' => 'Soporte de la Demanda'
                        );
                        $informacion = array(
                            'cod_titulo' => $id_titulo,
                            'cod_ciudad' => $this->input->post('id_ciudad'),
                            'cod_demandado' => $this->input->post('id_demandado'),
                            'cod_estadodemanda' => DEMANDA_REGISTRADA, // 1-demanda presentada
                            'num_radi_proceso' => $this->input->post('n_radicacion'),
                            'cod_abogado' => $id_abogado,
                            'juzgado' => $this->input->post('nom_juzgado'),
                            'num_tp_abogado' => $this->input->post('tpabogado'),
                            'nombre_demandado' => $this->input->post('nombredemandado'),
                            'cod_departamento' => $this->input->post('id_departamento')
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => DEMANDA_PRESENTADA_OTRA_VEZ //5- demanda presentada
                        );

                        if ($this->titulo_model->adicionar_documentosoporte($informacion_soporte) and $this->demanda_model->adicionar_demanda($informacion) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL NUEVA PRESENTACION DEMANDA RECHAZADA");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la demanda del titulo.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Nueva_Demanda');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }  
                            
                    //carga de datos primera vez
                    $this->data['id_titulo'] = $id_titulo;
                    if ($this->input->post('id_ciudad') == '') {
                        $this->data['custom_error'] = '';
                    }
                    $this->data['class'] = "";
                    $this->data['departamento'] = $this->titulo_model->obtener_departamentos();
                    //$this->data['demanda_n'] = '1'; 
                    $this->data['demanda_n'] = 's';
                    $this->template->load($this->template_file, 'titulosjudiciales/presentardemanda_add', $this->data); //vista al prototipo 2 del caso de uso presentar demanda
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * No hay caso de uso asignado a esta funcion
     * Lista_SoportesLibrarMandamiento
     * paso anterior a cargar registro si demandado excepciona
     * carga titulos con estado librar mandamiento y demanda subsanada
     */

    function Lista_SoportesLibrarMandamiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_SoportesLibrarMandamiento') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Libra Mandamiento');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(LIBRAR_MANDAMIENTO_DE_DEMANDA, DEMANDA_SUBSANADA, NULL, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Carga_SoportesLibrarMandamiento';
                $this->data['titulo'] = 'Cargar soportes que Libró Mandamiento';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Carga_SoportesLibrarMandamiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Carga_SoportesLibrarMandamiento') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if (empty($_FILES['userfile']['name'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/soporteslibrarmandamiento_carga', $this->data); //vista para la carga de archivos
                } else {
                    if (!isset($file['error'])) {
                        $file = $this->do_multi_upload($id_titulo, 'SoporteLibrarMandamiento');
                    }
                    if (isset($file['error'])) {
                        $this->data['id_titulo'] = $id_titulo;
                        $this->template->set('title', 'Soportes Librar Mandamiento');
                        $this->data['title'] = 'CARGUE SOPORTES';
                        $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                        $this->data['class'] = 'error';
                        $this->data['message'] = $file['error'];
                        $this->data['error'] = true;
                        $this->data['file_error'] = $file;
                        $this->template->load($this->template_file, 'titulosjudiciales/soporteslibrarmandamiento_carga', $this->data);
                    } else {
                        $this->template->load($this->template_file, 'titulosjudiciales/soporteslibrarmandamiento_carga', $this->data);
                        $nombre_mandamiento = $file[0]["upload_data"]["file_name"]; //archivo 2
                        $usuario = $this->ion_auth->user()->row();
                        $id_usuario = $usuario->IDUSUARIO;
                        $fecha = $this->input->post('tb_fecha');
                        $informacion_soportemandamiento = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_mandamiento,
                            'observaciones' => 'Mandamiento cargado para librar mandamiento'
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => LIBRAR_MANDAMIENTO_APROBADO// cambio de estado a -26 librar mandamiento con soporte
                        );
                        if ($this->titulo_model->adicionar_documentosoporte($informacion_soportemandamiento) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL LIBRA MANDAMIENTO");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado los soportes de librar mandamiento.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_SoportesLibrarMandamiento');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU008_Registrar si demandado excepciona
     * Lista_Demandado_Excepciona
     * Se obtiene titulos con estado Librar mandamiento con soporte
     */

    function Lista_Demandado_Excepciona() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Demandado_Excepciona') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Demandado Excepciona');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(LIBRAR_MANDAMIENTO_APROBADO, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Demandado_Excepciona';
                $this->data['titulo'] = 'Verificar si el Demandado Excepciona';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Demandado_Excepciona() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Demandado_Excepciona') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Demandado_Excepciona');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/demandadoexcepciona_add', $this->data); //vista prototipo 2 caso de uso si el demandado excepciona
                    } else {
                        //llegado los datos entrada por segunda vez                       
                        if ($respuesta == "excepciona") {
                            $estado = DEMANDADO_EXCEPCIONA;
                            $observacion = "el titulo pasa de librar mandamiento a excepcionada por el demandado";
                        } else {
                            $estado = DEMANDADO_NO_EXCEPCIONA;
                            $observacion = "el titulo pasa de librar mandamiento a no excepcionada por el demandado";
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL REGISTRAR SI DEMANDADO EXCEPCIONA");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la respuesta del demandado.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Demandado_Excepciona');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU009_Verificar proferimiento del auto que ordena continuar
     * Lista_Verificar_Proferimiento
     * Se obtiene los titulos con estado al cual el demandado no exepciona
     */

    function Lista_Verificar_Proferimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Verificar_Proferimiento') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'T�tulos ejecutivos');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(DEMANDADO_NO_EXCEPCIONA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Carga_Verificar_Proferimiento';
                $this->data['titulo'] = 'Verificar proferimiento del auto que ordena continuar';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Carga_Verificar_Proferimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Carga_Verificar_Proferimiento') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'VerificarProferimiento');
                if (isset($file['error'])) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$file['error'].'</div>');
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/verificarproferimiento_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'auto que ordena que ordena continuar con la ejecucion de la demanda'
                    );
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => PROFERIMIENTO_VERIFICADO//-15 Proferimiento Verificado
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICAR PROFERIMIENTO DEL AUTO QUE ORDENA CONTINUAR");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado el proferimiento del Auto que ordena seguir con la ejecucion.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_Verificar_Proferimiento');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU010_Registrar pronunciamiento en excepci�n
     * Lista_Pronunciamiento_Excepcion
     * Se obtiene los titulos con estado al cual demandado exepciona
     */

    function Lista_Pronunciamiento_Excepcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Pronunciamiento_Excepcion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Descorrer traslado de las excepciones');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(DEMANDADO_EXCEPCIONA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Cargar_Pronunciamiento_Excepcion';
                $this->data['titulo'] = 'Descorrer Traslado de las Excepciones';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Cargar_Pronunciamiento_Excepcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Cargar_Pronunciamiento_Excepcion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if (empty($_FILES['userfile']['name'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/pronunciamientoexcepcion_carga', $this->data); //vista para la carga de archivos
                } else {
                    if (!isset($file['error'])) {
                        $file = $this->do_multi_upload($id_titulo, 'PronunciamientoExcepciones');
                    }
                    if (isset($file['error'])) {
                        $this->data['id_titulo'] = $id_titulo;
                        $this->template->set('title', 'Soportes Pronunciamiento en Excepciones');
                        $this->data['title'] = 'CARGUE SOPORTES';
                        $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                        $this->data['class'] = 'error';
                        $this->data['message'] = $file['error'];
                        $this->data['error'] = true;
                        $this->data['file_error'] = $file;
                        $this->template->load($this->template_file, 'titulosjudiciales/pronunciamientoexcepcion_carga', $this->data);
                    } else {
                        $nombre_excepcion = $file[0]["upload_data"]["file_name"];
                        $nombre_contestacion = $file[1]["upload_data"]["file_name"];
                        $informacion_soporteexcepcion = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_excepcion,
                            'observaciones' => 'excepcion digitalizada'
                        );
                        $informacion_soportecontestacion = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_contestacion,
                            'observaciones' => 'contestacion digitalizada'
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => PRONUNCIAMIENTO_EN_EXCEPCIONES// cambio de estado a -16 Pronunciamiento en Excepciones
                        );
                        if ($this->titulo_model->adicionar_documentosoporte($informacion_soporteexcepcion) and $this->titulo_model->adicionar_documentosoporte($informacion_soportecontestacion) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL DESCORRER TRASLADO DE LAS EXCEPCIONES");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado el tramite respecto las excepciones.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Pronunciamiento_Excepcion');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU011_Registrar si se decretaron pruebas
     * Lista_Pruebas_Decretadas
     * Se carga los titulos con estado pronunciamiento en excepcion
     */

    function Lista_Pruebas_Decretadas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Pruebas_Decretadas') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Verificar auto que cita audiencia');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(PRONUNCIAMIENTO_EN_EXCEPCIONES, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Pruebas_Decretadas';
                $this->data['titulo'] = 'Verificar auto que cita audiencia';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Pruebas_Decretadas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pruebasdecretadas/Agregar_Pruebas_Decretadas') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Pruebas_Decretadas');
                } else {
                    if (empty($_FILES['userfile']['name'])) {
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/pruebasdecretadas_add', $this->data); //vista prototipo 2 caso de uso pruebas decretadas
                    } else {
                        if (!isset($file['error'])) {
                            $file = $this->do_multi_upload($id_titulo, 'AutoqueCitaAudiencia');
                        }
                        if (isset($file['error'])) {
                            $this->data['id_titulo'] = $id_titulo;
                            $this->template->set('title', 'Soporte Auto que Cita Audiencia');
                            $this->data['title'] = 'CARGUE SOPORTES';
                            $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                            $this->data['class'] = 'error';
                            $this->data['message'] = $file['error'];
                            $this->data['error'] = true;
                            $this->data['file_error'] = $file;
                            $this->template->load($this->template_file, 'titulosjudiciales/pruebasdecretadas_add', $this->data);
                        } else {
                            $this->template->load($this->template_file, 'titulosjudiciales/pruebasdecretadas_add', $this->data);
                            $nombre_auto = $file[0]["upload_data"]["file_name"]; //archivo 2
                            $informacion_soporte = array(
                                'cod_titulo' => $id_titulo,
                                'nombre_documento' => $nombre_auto,
                                'observaciones' => 'Soporte Auto que Cita Audiencia'
                            );
                            $datos_titulo = array(
                                'COD_TITULO' => $id_titulo,
                                'COD_RESPUESTA' => DECRETARON_PRUEBAS // cambio de estado a -26 librar mandamiento con soporte
                            );
                            if ($this->titulo_model->adicionar_documentosoporte($informacion_soporte) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                                $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICAR AUTO QUE CITA AUDIENCIA");
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado Soporte Auto que Cita Audiencia</div>');
                                redirect(base_url() . 'index.php/procesojudicial/Lista_Pruebas_Decretadas');
                            } else {
                                $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            }
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/pruebasdecretadas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU012_Asistencia diligencia - Alegato de conclusi�n
     * Lista_Asistencia_Diligencia
     * Se carga titulos con estado no decretaron pruebas
     */

    function Lista_Asistencia_Diligencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Asistencia_Diligencia') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Asistencia Diligencia - Alegato de Conclusion');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(777, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Carga_Asistencia_Diligencia';
                $this->data['titulo'] = 'Asistir Diligencias y Alegato de Conclusion';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Carga_Asistencia_Diligencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Carga_Asistencia_Diligencia') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if (empty($_FILES['userfile']['name'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/asistenciadiligencia_carga', $this->data); //vista carga de archivos
                } else {
                    if (!isset($file['error'])) {
                        $file = $this->do_multi_upload($id_titulo, 'AsistenciaDiligencia');
                    }
                    if (isset($file['error'])) {
                        $this->data['id_titulo'] = $id_titulo;
                        $this->template->set('title', 'Soportes Asistencia Diligencia');
                        $this->data['title'] = 'CARGUE SOPORTES';
                        $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                        $this->data['class'] = 'error';
                        $this->data['message'] = $file['error'];
                        $this->data['error'] = true;
                        $this->data['file_error'] = $file;
                        $this->template->load($this->template_file, 'titulosjudiciales/asistenciadiligencia_carga', $this->data);
                    } else {
                        $nombre_actadiligencia = $file[0]["upload_data"]["file_name"];
                        $nombre_memorialalegato = $file[1]["upload_data"]["file_name"];
                        $informacion_soporteexcepcion = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_actadiligencia,
                            'observaciones' => 'Acta de Diligencia Digitalizada'
                        );
                        $informacion_soportecontestacion = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_memorialalegato,
                            'observaciones' => 'Memorial de Alegato Digitalizado'
                        );
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => TITULO_CON_ALEGATO_DE_CONCLUSION// cambio de estado a -19 Alegato de Conclusi�n -------- estado del titulo
                        );
                        if ($this->titulo_model->adicionar_documentosoporte($informacion_soporteexcepcion) and $this->titulo_model->adicionar_documentosoporte($informacion_soportecontestacion) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado el Alegato en Conclusi�n.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * RQ27_CU013_Verificar Sentencia
     * Lista_Verificar_Sentencia
     * Se carga titulos con estado No Decretaron Pruebas y asistencia diligencia
     */

    function Lista_Verificar_Sentencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Verificar_Sentencia') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Asistir audiencia de instrucción y fallo');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(DECRETARON_PRUEBAS, NO_DECRETARON_PRUEBAS, NULL, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Verificar_Sentencia';
                $this->data['titulo'] = 'Asistir audiencia de instrucción y fallo';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Verificar_Sentencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Verificar_Sentencia') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Verificar_Sentencia');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/verificarsentencia_add', $this->data); //vista prototipo 2 caso de uso verificar sentencia
                    } else {
                        //llegado los datos entrada por segunda vez 
                        $file = $this->do_multi_upload($id_titulo, 'Sentencias');
                        $nombre_Soporte = $file[0]["upload_data"]["file_name"];
                        $informacion_soporte = array(
                            'cod_titulo' => $id_titulo,
                            'nombre_documento' => $nombre_Soporte,
                            'observaciones' => 'Asistir audiencia de instrucción y fallo'
                        );
                        if ($respuesta == "sentencia") {
                            $estado = FAVORABLE_AL_SENA;
                            $observacion = "el titulo es Favorable para el SENA";
                            $sentencia = 's';
                        } else {
                            $estado = NO_FAVORABLE_AL_SENA;
                            $observacion = "el titulo no es Favorable para el SENA";
                            $sentencia = 'n';
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado,
                            'SENTENCIA_FAVO_SENA' => $sentencia
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo) and $this->titulo_model->adicionar_documentosoporte($informacion_soporte)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL ASISTIR AUDIENCIA DE INSTRUCCION Y FALLO");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha registrado la verificacion de la sentencia.</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_Verificar_Sentencia');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/pruebasdecretadas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Lista_Generar_Liquidacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Generar_Liquidacion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(FAVORABLE_AL_SENA, PROFERIMIENTO_VERIFICADO, NULL, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Generar_Liquidacion';
                $this->data['titulo'] = 'Registrar recibido de liquidación';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Generar_Liquidacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Generar_Liquidacion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_Generar_Liquidacion');
                } else {
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => LIQUIDACION_GENERADA //24- Liquidacion en Proceso
                    );
                    if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL REGISTRAR RECIBO DE LIQUIDACION");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha Registrado el recibido de la liquidación.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_Generar_Liquidacion');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Lista_Entregar_Liquidacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_Entregar_Liquidacion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Entregar Liquidacion');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(LIQUIDACION_GENERADA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_Entregar_Liquidacion';
                $this->data['titulo'] = 'Liquidación Radicada';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Entregar_Liquidacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_Entregar_Liquidacion') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                 $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'LiquidacionesEntregadas');
                if (isset($file['error'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/entregaliquidacion_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'Soporte de Entrega de la Liquidacion'
                    );
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => LIQUIDACION_ENTREGADA
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL LIQUIDACION RADICADA");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado el soporte de liquidacion al expediente.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_Entregar_Liquidacion');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
     function Lista_VerificarMedidasCautelares() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_VerificarMedidasCautelares') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Verificar Medidas Cautelares');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(MEDIDA_CAUTELAR_NO_EXISTE, LIQUIDACION_ENTREGADA, DEUDA_VIGENTE, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_VerificarMedidasCautelares';
                $this->data['titulo'] = 'Verificar si Existen Medidas Cautelares';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_VerificarMedidasCautelares() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_VerificarMedidasCautelares') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar medida cautelar.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_VerificarMedidasCautelares');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/verificarmc_add', $this->data); 
                    } else {
                        //llegado los datos entrada por segunda vez                       
                        if ($respuesta == "existe") {
                            $estado = MEDIDA_CAUTELAR_EXISTE;
                            $observacion = "Medidas cautelares en proceso judicial en marcha";
                        } else {
                            $estado = MEDIDA_CAUTELAR_NO_EXISTE;
                            $observacion = "Esperando Medidas Cautelares en Procesos Judiciales";
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICAR MEDIDAS CAUTELARES");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado la medida cautelar</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_VerificarMedidasCautelares');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
     function Lista_MedidasCautelares() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_MedidasCautelares') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Costas a cargo del SENA');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(MEDIDA_CAUTELAR_EXISTE, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_CargaAvaluo';
                $this->data['titulo'] = 'Avalúo bienes embargados o secuestrados';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_CargaAvaluo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_CargaAvaluo') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                 $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'Avaluos');
                if (isset($file['error'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/cargaravaluos_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'Soporte de Costas a cargo del SENA'
                    );
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => SOPORTE_AVALUOS
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL AVALUO BIENES EMBARGADOS O SECUESTRADOS");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado el soporte de Avalúo bienes embargados o secuestrados al expediente.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_MedidasCautelares');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
     function Lista_PagoRemateBienes() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_PagoRemateBienes') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Demandado Excepciona');
                $this->data['message'] = $this->session->flashdata('message');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(SOPORTE_AVALUOS, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_PagoRemateBienes';
                $this->data['titulo'] = 'Verificar Remate de Bienes';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_PagoRemateBienes() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_PagoRemateBienes') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_PagoRemateBienes');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/verifrematepagado_add', $this->data); //vista prototipo 2 caso de uso si el demandado excepciona
                    } else {
                        //llegado los datos entrada por segunda vez                       
                        if ($respuesta == "si_hay") {
                            $estado = DEUDA_PAGADA;
                            $observacion = "Se pago la Deuda con el remate del bien";
                        } else {
                            $estado = DEUDA_VIGENTE;
                            $observacion = "No se ha Pagado la totalidad de la deuda en el remate";
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICAR PAGO DE REMATE");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado el pago del Remate del Bien</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_PagoRemateBienes');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Lista_CargaFDP() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Cargue de Auto de Terminación de proceso');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos_dobleestado(DEUDA_PAGADA, ACUERDO_PAGO_CUMPLIDO, NULL, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_CargaFDP';
                $this->data['titulo'] = 'Cargue de Auto de Terminación de proceso';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_CargaFDP() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                 $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'FinDeProceso');
                if (isset($file['error'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/findeproceso_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'Soporte de Auto de Fin de Proceso'
                    );
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => SOPORTE_TERMINACION
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL AUTO DE FINALIZACION DE PROCESO");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado el soporte de Auto de Fin de Proceso al expediente.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_CargaFDP');

                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

      function Lista_CostasSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Costas a cargo del SENA');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(NO_FAVORABLE_AL_SENA, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_CostasSena';
                $this->data['titulo'] = 'Costas a Cargo del SENA';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_CostasSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                 $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'CostasSena');
                if (isset($file['error'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/cargarcostas_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'Soporte de Costas a cargo del SENA'
                    );
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => SOPORTE_COSTAS
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL COSTAS A CARGO DEL SENA");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado el soporte de Costas a cargo del SENA al expediente.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_CostasSena');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
     /*
     * ACUERDO DE PAGO
     */

    function Lista_PosiblesAcuerdoPago() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_ModificarTitulos') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES) || $this->verificar_rol(DIRECTOR_REGIONAL) || $this->verificar_rol(COORDINADOR_GRUPO_GC)) {
                $this->template->set('title', 'Realizar Acuerdo de Pago');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_acuerdodepago();
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Agregar_AcuerdoPago';
                $this->data['titulo'] = 'Realizar Acuerdo de Pago';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data); //vista de la lista de titulos con abogado asignado
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Agregar_AcuerdoPago() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Agregar_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                 $id_titulo = $this->input->post('cod_titulo');
                $file = $this->do_upload($id_titulo, 'AcuerdoPago');
                if (isset($file['error'])) {
                    $this->data['id_titulo'] = $id_titulo;
                    $this->template->load($this->template_file, 'titulosjudiciales/cargaracuerdopago_carga', $this->data); //vista para carga de documentos
                } else {
                    $nombre_archivo = $file['upload_data']['file_name'];
                    $informacion_soportestitulos = array(
                        'cod_titulo' => $id_titulo,
                        'nombre_documento' => $nombre_archivo,
                        'observaciones' => 'Soporte de Costas a cargo del SENA'
                    );
                    $cod_respuesta = $this->titulo_model->get_respuestatitulo($id_titulo);
                    $datos_titulo = array(
                        'COD_TITULO' => $id_titulo,
                        'COD_RESPUESTA' => ACUERDO_PAGO_GENERADO,
                        'ACUERDO_PAGO' => $cod_respuesta->COD_RESPUESTA
                    );
                    if ($this->titulo_model->adicionar_documentosoporte($informacion_soportestitulos) and $this->titulo_model->actualizar_titulo($datos_titulo)) {
                        $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL REALIZAR ACUERDO DE PAGO");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha cargado el soporte del acuerdo de pago al expediente.</div>');
                        redirect(base_url() . 'index.php/procesojudicial/Lista_PosiblesAcuerdoPago');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Lista_AcuerdoPagos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Lista_CargaFDP') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $this->template->set('title', 'Verificación de Acuerdo de Pago');
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->titulo_model->datatable_titulos(ACUERDO_PAGO_GENERADO, $id_usuario);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['ruta'] = 'index.php/procesojudicial/Verificar_AcuerdoPago';
                $this->data['titulo'] = 'Verificación de Acuerdo de Pago';
                $this->template->load($this->template_file, 'titulosjudiciales/gestiontitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Verificar_AcuerdoPago() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('procesojudicial/Verificar_AcuerdoPago') || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                $id_titulo = $this->input->post('cod_titulo');
                if ($id_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual presentar demanda.</div>');
                    redirect(base_url() . 'index.php/procesojudicial/Lista_PagoRemateBienes');
                } else {
                    $this->data['custom_error'] = '';
                    $respuesta = $this->input->post('opc_res');
                    if (empty($respuesta)) {
                        //carga de datos primera vez
                        $this->data['id_titulo'] = $id_titulo;
                        $this->data['class'] = "";
                        $this->template->load($this->template_file, 'titulosjudiciales/verifacuerdopago_add', $this->data); //vista prototipo 2 caso de uso si el demandado excepciona
                    } else {
                        //llegado los datos entrada por segunda vez                       
                        if ($respuesta == "si_hay") {
                            $estado = ACUERDO_PAGO_CUMPLIDO;
                            $observacion = "Se pago totalidad del acuerdo de pago";
                        } else {
                            $cod_respuesta = $this->titulo_model->get_respuestaacuerdo($id_titulo);
                            $estado = $cod_respuesta->ACUERDO_PAGO;
                            $observacion = "No se ha Pagado la totalidad del acuerdo de pago";
                        }
                        $datos_titulo = array(
                            'COD_TITULO' => $id_titulo,
                            'COD_RESPUESTA' => $estado
                        );
                        if ($this->titulo_model->actualizar_titulo($datos_titulo)) {
                            $this->Traza_PJ($datos_titulo['COD_RESPUESTA'], $datos_titulo['COD_TITULO'], $comentarios = "TITULO JUDICIAL VERIFICAR ACUERDO DE PAGO");
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado el pago del Remate del Bien</div>');
                            redirect(base_url() . 'index.php/procesojudicial/Lista_AcuerdoPagos');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    
    public function llena_ciudad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->verificar_rol(ABOGADO_PROCESOS_JUDICIALES)) {
                if ($this->input->post('id_departamento')) {
                    $departamento = $this->input->post('id_departamento');
                    $ciudad = $this->titulo_model->obtener_ciudad($departamento);
                    foreach ($ciudad as $fila) {
                        echo'<option value="' . $fila->CODMUNICIPIO . '">' . $fila->NOMBREMUNICIPIO . '</option>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/procesojudicial');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    private function do_multi_upload($cod_titulo, $carpeta) {
        $estructura = "./uploads/procesosjudiciales/" . $cod_titulo . "/" . $carpeta . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $config = array();
        $config['upload_path'] = $estructura;
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $files = $_FILES;
        $cpt = count($_FILES['userfile']['name']);

        for ($i = 0; $i < $cpt; $i++) {
            $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
            $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
            $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

            if (!$this->upload->do_upload()) {
                return $error = array('error' => $this->upload->display_errors());
            } else {
                $data[$i] = array('upload_data' => $this->upload->data());
            }
            //print_r($datos);
            //echo $data[0]["upload_data"]["file_name"];
        }
        return $data;
    }

    private function do_upload($cod_titulo, $carpeta) {
        $estructura = "./uploads/procesosjudiciales/" . $cod_titulo . "/" . $carpeta . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $config['upload_path'] = $estructura;
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }



    /**
    * Registra la traza para los eventos de insercion o actualizacion de datos de Proceo Judicial - Titulos 
    * @access public
    * @param string
    * @return string
    * @autor German E. Perez H 20141002
    */
    function Traza_PJ($cod_respuesta, $cod_titulo, $comentarios) {
        if (empty($cod_titulo)) {
            // Es la insercion inicial. Obtiene el ultimo codigo de titulo insertado
            $ultimo_cod_titulo = $this->titulo_model->get_ultimo_codigo_tit();
        } else {
            $ultimo_cod_titulo = $cod_titulo;
        }

        // Obtiene el codigo de gestion
        $cod_tipo_gestion = $this->titulo_model->get_tipo_gestion($ultimo_cod_titulo, $cod_respuesta);

        // Genera el rastro de la traza
        $usuario      = '';
        $cod_juridico = '';
        $cod_cartera_no_misional = '';
        $cod_devolucion = '';
        $cod_recepcion = '';
        trazarProcesoJuridico($cod_tipo_gestion, $cod_respuesta, $ultimo_cod_titulo, $cod_juridico, $cod_cartera_no_misional, $cod_devolucion, $cod_recepcion, $comentarios, $usuario);
                          
        
    }

}

/* End of file procesojudicial.php */
/* Location: ./system/application/controllers/titulo.php */    