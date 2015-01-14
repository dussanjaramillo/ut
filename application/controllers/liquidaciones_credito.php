<?php
/**
 * Archivo para ala administración de los metodos necesarios para las liquidaciones en el proceso coactivo
 *
 * @packageCartera
 * @subpackage Controllers
 * @author jdussan
 * @location./application/controllers/liquidaciones_credito.php
 * @last-modified 07/11/2014
 */
class Liquidaciones_credito extends MY_Controller
{

    public $TIPONOTIFICACIONPERSONAL = '1';//PERSONAL
    public $TIPONOTIFICACIONCORREO = '2';//CORREO
    public $TIPONOTIFICACIONDIARIO = '3';//AVISO PUBLICADO EN DIARIO
    public $TIPONOTIFICACIONPAGINA = '4';//PAGINA WEB DEL SENA
    public $TIPONOTIFICACIONACTA = '5';//ACTA
    public $TIPONOTIFICACIONMEDIDA = '6';//MEDIDA CAUTELAR
    public $TIPONOTIFICACIONADELANTE = '7';//SEGUIR ADELANTE
    public $TIPONOTIFICACIONAVISO = '8';//AVISO
    public $TIPONOTIFICACIONEDICTO = '9';//EDICTO
    public $ESTADONOTIFICACIONGENERADA = '1';
    public $ESTADONOTIFICACIONPREAPROBADA = '2';
    public $ESTADONOTIFICACIONAPROBADA = '3';
    public $ESTADONOTIFICACIONRECHAZADA = '4';
    public $ESTADONOTIFICACIONRECIBIDA = '5';
    public $ESTADONOTIFICACIONDEVUELTA = '6';
    public $ESTADONOTIFICACIONPREGENERADA = '7';
    public $NOTIFICACION_GENERADA = '1';
    public $NOTIFICACION_APROBADA = '2';
    public $NOTIFICACION_NOAPROBADA = '3';
    public $NOTIFICACION_REVISADA = '4';
    public $NOTIFICACION_ENVIADO = '5';
    public $NOTIFICACION_ENTREGADO = '6';
    public $NOTIFICACION_DEVUELTO = '7';
    public $TIPOAUTO_LIQUIDACION = '3';//AUTO DE LIQUIDACION DE CREDITO
    public $TIPOAUTO_OBJECION = '24';//AUTO DE LIQUIDACION DE CREDITO
    public $TIPOAUTO_APROBACION = '25';//AUTO DE LIQUIDACION DE CREDITO
    public $PROCESO = '14';//Liquidación de Crédito Coactivo

    function __construct()
    {
        parent::__construct();
        $this -> load -> library('form_validation', 'tcpdf/tcpdf.php');
        $this -> load -> helper(array('form', 'url', 'codegen_helper', 'date', 'template', 'liquidaciones','traza_fecha_helper'));
        $this -> load -> model('liquidaciones_credito_model','', TRUE);
        $this->load->model('auto_liquidacion_model','',TRUE);
        $this->load->model('plantillas_model');
        $this->data['javascripts'] = array('js/tinymce/tinymce.jquery.min.js', 'js/jquery.dataTables.min.js'/*, 'js/jquery.dataTables.defaults.js'*/);
        $this->data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
        $this->data['user'] = $this -> ion_auth -> user() -> row();
        define("ID_USER", $this -> data['user'] -> IDUSUARIO);
        define("PERFIL", @$this -> data['permiso'][0]['IDGRUPO']);
        define ("REGIONAL",@$this -> data['permiso'][0]['COD_REGIONAL']);
        $sesion = $this -> session -> userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);
    }

    public function index()
    {
        $this -> manage();
    }

    //BASE PARA CONSULTA
    public function manage()
    {
        try {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                $codigoProceso = (int)$this -> security -> xss_clean($this -> input -> post('cod_coactivo_liquidacion'));
                if ($codigoProceso <= 0):
                    throw new Exception('<strong><i>No cuenta con datos necesarias para el calculo de liquidaciones de crédito</i></strong>');
                else:
                    $codigoRespuesta = (int)$this -> liquidaciones_credito_model -> consultarCodRespuestaProceso($codigoProceso);
                    $proceso = $this -> liquidaciones_credito_model -> consultarProceso($codigoProceso, $codigoRespuesta);
                    $usuario = (int)$proceso[0]['ABOGADO'];
                    $abogado = $this -> liquidaciones_credito_model -> getInfoUsuario($usuario);
                    $this -> data['codigoExpediente'] = $codigoProceso;
                    $this -> data['proceso'] = $proceso;
                    $this -> data['abogado'] = $abogado;
                    $this -> data['titulo'] = 'Liquidaciones de Crédito';
                    $this -> template->load($this->template_file, 'liquidaciones_credito/liquidaciones_base',$this-> data);
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        } catch (Exception $e) {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES PARA LIQUIDACIÓN
    public function formularioExpediente()
    /**
    * Función que muestra el formulario para consultar los expedientes activos y a los cuales puede acceder el usuario
    * @return array $data;
    */
    {
        try{
            if ($this -> ion_auth -> logged_in ()): //verificación de acceso autorizado
                if ($this -> ion_auth -> is_admin ()): //verificación de perfil
                    $this -> template -> set ('title', 'Consultar Procesos');
                    $this -> template -> load ($this -> template_file, 'liquidaciones_credito/consultar_expediente', $this -> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        } catch (Exception $e) {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR INFORMACIÓN DE EXPEDIENTE CONSULTADO Y FORMULARIO DE COSTES
    function consultarExpediente()
    /**
    * Función que muestra la información del expediente y liquidación consultado por el usuario
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/consultarExpediente')): //verificación de perfil
                    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); //configuración de mensajes de error
                    $this->form_validation->set_rules('expediente', 'Expediente', 'required|min_length[1]|max_length[15]'); // regla de validación para expediente
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;

                    $expediente = $this -> input -> post('expediente');
                    //$liquidacion = $this -> liquidaciones_model -> consultarLiquidacionCredito($expediente); //capturado desde la tabla
                    //######### DATA PARA BUGER ########
                    // $liquidacion = array('nit' => '800987654', 'razonSocial' => 'INDUSTRIAS SABOR S.A', 'concepto' => 'APORTES PARAFISCALES', 'instancia' => 'Mandamiento de Pago Generado', 'representanteLegal' => 'JUAN GONZALEZ', 'telefono' => '4654321', 'estado' => 'Elaborar Liquidación de Crédito', 'fechaInicio' => '2013/01/01', 'fechaFin' => '2014/01/01', 'totalCapital' => '20000000', 'totalInteres' => '3400000');
                    //######### DATA PARA BUGER ########
                    $this -> data['liquidacion'] = $liquidacion;
                    $this -> template -> set('title', 'Generar Liquidación de Crédito');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/liquidar_expediente',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR LIQUIDACIÓN DE CRÉDITO GENERADA
    function generarLiquidacion()
    /**
    * Función que muestra la liquidación generada de las liquidaciones administrativas asociadas al proceso y por los costos del mismo
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/generarLiquidacion')): //verificación de perfil
                    $proceso = unserialize($this -> security -> xss_clean($this -> input -> post('liquidacion')));
                    $fecha_liquidacion = (string)$this -> security -> xss_clean($this -> input -> post ('fechaLiquidacion'));
                    $codigoExpediente = (int)$this -> security -> xss_clean($this -> input -> post('proceso'));
                    $honorarios = (int)$this -> security -> xss_clean($this -> input -> post('honorarios'));
                    $transporte = (int)$this -> security -> xss_clean($this -> input -> post('transporte'));
                    $this -> data['proceso'] = $proceso;
                    $this -> data['codigoExpediente'] = $codigoExpediente;
                    $this -> data['fechaLiquidacion'] = $fecha_liquidacion;
                    $this -> data['honorarios'] = $honorarios;
                    $this -> data['transporte'] = $transporte;
                    $this -> template -> set('title', 'Generar Liquidación de Crédito');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/generar_liquidacion',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES PARA GENERAR NOTIFICACIONES POR ESCRITO
    function formularioExpedienteNotificacion()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les puede generar una notificación por escrito
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteNotificacion')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Expedientes para Notificación');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_expediente_notificacion',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR INFORMACIÓN DE EXPEDIENTE CONSULTADO Y PLANTILLA DE NOTIFICACIÓN
    function consultarExpedienteNotificacion()
    /**
    * Función que muestra la información del expediente y liquidación consultado por el usuario para una notificación por escrito
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/consultarExpedienteNotificacion')): //verificación de perfil
                    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); //configuración de mensajes de error
                    $this->form_validation->set_rules('expediente', 'Expediente', 'required|min_length[1]|max_length[15]'); // regla de validación para expediente
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    //######### DATA PARA BUGER ########
                    $liquidacion = array('nit' => '800987654', 'razonSocial' => 'INDUSTRIAS SABOR S.A', 'concepto' => 'APORTES PARAFISCALES', 'instancia' => 'Mandamiento de Pago Generado', 'representanteLegal' => 'JUAN GONZALEZ', 'telefono' => '4654321', 'estado' => 'Liquidación de crédito generada');

                    $datos = array('ciudad' => 'Bogotá', 'fecha' => $fecha_actual, 'representanteLegal' => 'JUAN GONZALEZ', 'direccion' => 'Calle 45 # 34-25', 'ciudadEmpresa' => 'Bogotá', 'proceso' => '1101123', 'cedula' => '80564332');

                    //cargue de template
                    $template = template_tags('uploads/plantillas/rq_019_cu_002.txt', $datos);
                    $this -> data['plantilla'] = $template;
                    //######### DATA PARA BUGER ########

                    $this -> data['liquidacion'] = $liquidacion;
                    $this -> template -> set('title', 'Generar Notificación Liquidación de Crédito');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_expediente',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //GRABAR NOTIFICACIÓN GENERADA
    function grabarExpedienteNotificacion()
    /**
    * Función que almacena la notificación por escrito de un expediente
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/grabarExpedienteNotificacion')): //verificación de perfil
                    $this -> template -> set('title', 'Generar Notificación Liquidación de Crédito');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_expediente_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES QUE TIENEN NOTIFICACIONES
    function formularioVerifica()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioVerifica')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Notificaciones');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_notificacion',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA VALIDAR NOTIFICACIONES
    function Verificar()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación vía Web
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/Verificar')): //verificación de perfil
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    $this -> template -> set('title', 'Cargar Soportes Notificación');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //CARGA EL ARCHIVO DE SOPORTE DE NOTIFICACIÓN
    function cargarSoporte()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/cargarSoporte')): //verificación de perfil
                    $this -> template -> set('title', 'Cargar Soportes Notificación');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES PARA GENERAR NOTIFICACIONES WEB
    function formularioExpedienteWeb()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les puede generar una notificación web
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteWeb')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Expedientes para Notificación Web');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_expediente_web',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR INFORMACIÓN DE EXPEDIENTE CONSULTADO Y PLANTILLA DE NOTIFICACIÓN WEB
    function consultarExpedienteWeb()
    /**
    * Función que muestra la información del expediente y liquidación consultado por el usuario para una notificación Web
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/consultarExpedienteWeb')): //verificación de perfil
                    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); //configuración de mensajes de error
                    $this->form_validation->set_rules('expediente', 'Expediente', 'required|min_length[1]|max_length[15]'); // regla de validación para expediente
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    //######### DATA PARA BUGER ########
                    $liquidacion = array('nit' => '800987654', 'razonSocial' => 'INDUSTRIAS SABOR S.A', 'concepto' => 'APORTES PARAFISCALES', 'instancia' => 'Mandamiento de Pago Generado', 'representanteLegal' => 'JUAN GONZALEZ', 'telefono' => '4654321', 'estado' => 'Liquidación de crédito generada');

                    $datos = array('ciudad' => 'Bogotá', 'fecha' => $fecha_actual, 'representanteLegal' => 'JUAN GONZALEZ', 'direccion' => 'Calle 45 # 34-25', 'ciudadEmpresa' => 'Bogotá', 'proceso' => '1101123', 'cedula' => '80564332');

                    //cargue de template
                    $template = template_tags('uploads/plantillas/rq_019_cu_004.txt', $datos);
                    $this -> data['plantilla'] = $template;
                    //######### DATA PARA BUGER ########

                    $this -> data['liquidacion'] = $liquidacion;
                    $this -> template -> set('title', 'Generar Notificación Web');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_web',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //GRABAR NOTIFICACIÓN WEB GENERADA
    function grabarNotificacionWeb()
    /**
    * Función que almacena la notificación Web de un expediente
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/grabarNotificacionWeb')): //verificación de perfil
                    $this -> template -> set('title', 'Generar Notificación Web Liquidación de Crédito');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_web_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES QUE TIENEN NOTIFICACIONES WEB
    function formularioVerificaWeb()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación Web
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioVerificaWeb')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Notificaciones Web');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_notificacion_web',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA VALIDAR NOTIFICACIONES WEB
    function VerificarWeb()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación vía Web
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/VerificarWeb')): //verificación de perfil
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    $this -> template -> set('title', 'Cargar Soportes Notificación Web');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar_web',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //CARGA EL ARCHIVO DE SOPORTE DE NOTIFICACIÓN WEB
    function cargarSoporteWeb()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteCarteleras')): //verificación de perfil
                    $this -> template -> set('title', 'Cargar Soportes Notificación Web');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar_webs_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES PARA GENERAR NOTIFICACIONES CARTELERAS
    function formularioExpedienteCarteleras()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les puede generar una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteCarteleras')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Expedientes para Notificación Carteleras');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_expediente_cartelera',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR INFORMACIÓN DE EXPEDIENTE CONSULTADO Y PLANTILLA DE NOTIFICACIÓN CARTELERAS
    function consultarExpedienteCarteleras()
    /**
    * Función que muestra la información del expediente y liquidación consultado por el usuario para una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/consultarExpedienteCarteleras')): //verificación de perfil
                    $this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); //configuración de mensajes de error
                    $this->form_validation->set_rules('expediente', 'Expediente', 'required|min_length[1]|max_length[15]'); // regla de validación para expediente
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    //######### DATA PARA BUGER ########
                    $liquidacion = array('nit' => '800987654', 'razonSocial' => 'INDUSTRIAS SABOR S.A', 'concepto' => 'APORTES PARAFISCALES', 'instancia' => 'Mandamiento de Pago Generado', 'representanteLegal' => 'JUAN GONZALEZ', 'telefono' => '4654321', 'estado' => 'Liquidación de crédito generada');

                    $datos = array('ciudad' => 'Bogotá', 'fecha' => $fecha_actual, 'representanteLegal' => 'JUAN GONZALEZ', 'direccion' => 'Calle 45 # 34-25', 'ciudadEmpresa' => 'Bogotá', 'proceso' => '1101123', 'cedula' => '80564332');

                    //cargue de template
                    $template = template_tags('uploads/plantillas/rq_019_cu_005.txt', $datos);
                    $this -> data['plantilla'] = $template;
                    //######### DATA PARA BUGER ########

                    $this -> data['liquidacion'] = $liquidacion;
                    $this -> template -> set('title', 'Generar Notificación por Cartelera');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_cartelera',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //GRABAR NOTIFICACIÓN PARA CARTELERAS GENERADA
    function grabarNotificacionCarteleras()
    /**
    * Función que almacena la notificación Web de un expediente
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/grabarNotificacionCarteleras')): //verificación de perfil
                    $this -> template -> set('title', 'Generar Notificación Liquidación de Crédito por Carteleras');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/notificar_carteleras_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en Notificaciones en Liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA CONSULTAR EXPEDIENTES QUE TIENEN NOTIFICACIONES POR CARTELERA
    function formularioVerificaCarteleras()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteCarteleras')): //verificación de perfil
                    $this -> template -> set('title', 'Consultar Notificaciones de Carteleras');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/consultar_notificacion_cartelera',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //MOSTRAR FORMULARIO PARA VALIDAR NOTIFICACIONES POR CARTELERA
    function VerificarCarteleras()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteCarteleras')): //verificación de perfil
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;
                    $this -> template -> set('title', 'Cargar Soporte Notificaciones de Carteleras');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar_cartelera',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }

    //CARGA EL ARCHIVO DE SOPORTE DE NOTIFICACIÓN POR CARTELERA
    function cargarSoporteCartelera()
    /**
    * Función que muestra el formulario para consultar los expedientes activos a los cuales se les generó una notificación para carteleras
    * @return array $data;
    */
    {
        try
        {
            if ($this->ion_auth->logged_in()): //verificación de acceso autorizado
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpedienteCarteleras')): //verificación de perfil
                    $this -> template -> set('title', 'Cargar Soporte Notificaciones de Carteleras');
                    $this -> template->load($this->template_file, 'liquidaciones_credito/verificar_carteleras_exito',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Su sesión ha terminado.</div>');
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Consultar Expedientes';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() .'</div>';
            $this -> template -> set('title', 'Errores en liquidaciones de Crédito');
            $this -> template -> load($this->template_file, 'liquidaciones_credito/liquidaciones_error',$this-> data);
        }
    }


    //MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
    function getFormSoporteLiquidacion()
    {
        try
        {
            if ($this->ion_auth->logged_in()):
                if ($this->ion_auth->is_admin() /*|| $this->ion_auth->in_menu('fiscalizacion/fiscalizacion')*/):
                    //template data
                    $this -> template->set('title', 'Legalización de Liquidación');
                    $this -> data['message']=$this->session->flashdata('message');

                    //recepción variables
                    //$codigo_fiscalizacion = $this -> input -> post('codigoFiscalizacion'); //capturado desde el caso de uso anterior
                    //----------Deshabilitar una vez conectemos los modulos
                    if($this -> uri -> segment (3) === FALSE):
                        $codigo_fiscalizacion = 1;
                    else:
                        $codigo_fiscalizacion = $this -> uri -> segment (3);
                    endif;

                    $empresa = $this -> liquidaciones_model -> getCabecerasSoportesLiquidacion($codigo_fiscalizacion);
                    if ($empresa == NULL):
                        throw new Exception('El codigo de Fiscalización no existe. <strong><em>Código Fiscalización : ' . $codigo_fiscalizacion . '</em></strong>');
                    else:
                        $this -> data['empresa'] =  $empresa;
                        $this -> data['codigoFiscalizacion'] = $codigo_fiscalizacion;

                        //consultar fecha actual para fecha default del cargue de archivo
                        $datestring = "%d/%m/%Y";
                        $fecha_actual = mdate($datestring);
                        $this -> data['fecha'] = $fecha_actual;
                    endif;

                    $this -> template->load($this->template_file, 'liquidaciones/legalizacionliquidacion_form',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Legalización de Liquidación';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() .'</div>';
            $this -> template -> load($this->template_file, 'liquidaciones/liquidaciones_error',$this-> data);
        }
    }

    //GRABAR SOPORTE DE LIQUIDACIÓN Y CARGUE DE ARCHIVO
    function loadSoporteLiquidacion()
    {
        try
        {
            if ($this->ion_auth->logged_in()):
                if ($this->ion_auth->is_admin() /*|| $this->ion_auth->in_menu('fiscalizacion/fiscalizacion')*/):
                    //template data
                    $this -> template->set('title', 'Legalización de Liquidación');
                    $this -> data['message']=$this->session->flashdata('message');

                    //Captura de datos para la carga
                    $concepto = $this->input->post('codigoConcepto');
                    $liquidacion = $this->input->post('numeroLiquidacion');
                    $nis = $this->input->post('nis');
                    $nis = mb_strtoupper($nis); //uppercase para el NIS
                    $fecha = $this->input->post('fechaRadicado');
                    $radicado = $this->input->post('numeroRadicado');
                    $codigoFiscalizacion = $this->input->post('codigoFiscalizacion');

                    //Captura nombre legalizador
                    $usuario = $this->ion_auth->user()->row();

                    $nombres = $usuario -> NOMBRES;
                    $apellidos  = $usuario -> APELLIDOS ;
                    $fiscalizador = $nombres .  " " . $apellidos;

                    if ($concepto == "" || $liquidacion == "" || $nis == "" || $fecha == "" || $radicado == "" || $codigoFiscalizacion ==""):
                        //Excepción para datos faltantes
                        throw new Exception('No se han recibido los datos suficientes para proceder con su solicitud.');
                    else:
                        //Cargar archivo en servidor
                        $resultado = $this->do_upload($codigoFiscalizacion);
                        if ($resultado != NULL):
                            $archivo = $resultado['upload_data']['file_name'];
                            //insertar datos en la tabla soporte_legalizacion
                            $carga = $this -> liquidaciones_model -> loadSoportesLiquidacion($liquidacion, $nis, $fecha, $radicado, $archivo, $fiscalizador);
                            if ($carga != NULL):
                                //Cargar vista de respuesta
                                $this -> data['liquidacion'] = $liquidacion;
                                $this -> data['respuesta'] = $carga;
                                $this -> template->load($this->template_file, 'liquidaciones/legalizacionliquidacion_exito',$this-> data);
                            else:
                                //Excepción para errores de base de datos
                                throw new Exception('Ha ocurrido un problema guardando los datos en la base de datos.');
                            endif;
                        else:
                            //Excepción para errores de carga
                            throw new Exception('Ha ocurrido un problema cargando el archivo en el servidor.');
                        endif;
                    endif;
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Legalización de Liquidación';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() .'</div>';
            $this -> template -> load($this->template_file, 'liquidaciones/liquidaciones_base',$this-> data);
        }

    }

    //MOSTRAR FORMULARIO  PARA SOPORTE DE LIQUIDACIÓN
    function comprobante()
    {
        try
        {
            if ($this->ion_auth->logged_in()):
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/comprobante')):
                    //template data
                    $this -> template->set('title', 'Comprobante de Liquidación');
                    $this -> data['message']=$this->session->flashdata('message');

                    //recepción variables
                    if ($this->uri->segment(3) === FALSE):

                        throw new Exception('<strong>No cuenta con los suficientes parametros para generar un comprobante</strong>.');

                    else:

                        $codigoMulta = $this->uri->segment(3);

                    endif;

                    //consultar información de la liquidación
                    $liquidacion = $this -> liquidaciones_model -> getLiquidacionMultaMinisterio($codigoMulta);
                    $this -> data['liquidacion'] = $liquidacion;

                    //consultar fecha actual para fecha default de la liquidación
                    $datestring = "%d/%m/%Y";
                    $fecha_actual = mdate($datestring);
                    $this -> data['fecha'] = $fecha_actual;

                    $this -> template->load($this->template_file, 'liquidaciones/comprobante',$this-> data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    redirect(site_url().'/inicio');
                endif;
            else:
                redirect(site_url().'/auth/login');
            endif;
        }
        catch (Exception $e)
        {
            $this -> data['titulo'] = 'Legalización de Liquidación';
            $this -> data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() .'</div>';
            $this -> template -> load($this->template_file, 'liquidaciones/liquidaciones_error',$this-> data);
        }
    }

     //FUNCIÓN CARGUE DE ARCHIVO EN SERVER
     private function do_upload($codigoFiscalizacion)
     {
            //Asignación carpeta para codigo fiscalización. Esta carpeta es unica para que ubiquen el archivo
            $nombre_carpeta = './uploads/fiscalizaciones/'.$codigoFiscalizacion.'/liquidaciones/';
            //Verifica la creación de la carpeta
            if (!is_dir($nombre_carpeta)):
                @mkdir($nombre_carpeta, 0755);
            endif;

            $config['upload_path'] = $nombre_carpeta;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '2048';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()):
                return $error = array('error' => $this->upload->display_errors());
            else :
                return $data = array('upload_data' => $this->upload->data());
            endif;
    }
    /** Metodos Sergio */
        function addAuto() {
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                //$cod_coactivo = '46';
                $cod_coactivo = $this->input->post('cod_coactivo');
                //$nit_empresa = '800800800';//$this->input->post('nit');//'800800800';
                $nit_empresa = $this->input->post('nit');//'800800800';
                $this->data['tipo'] = 'autoliquidacion';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Auto de Liquidación de Credito</h2>";
                $this->data['instancia'] = 'Auto de Liquidación de Crédito';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/autoliquidacion/';
                $this->data['plantilla'] = "auto_liquidacion.txt";
                $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $cod_coactivo;
                $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
                $this->data['filas2'] = template_tags($urlplantilla2,$consec);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);

                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_add', $this->data);
            }else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }
    }

    function addAutoObj() {
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $nit_empresa = $this->input->post('nit');
                @$this->data['auto_num'] = $this->input->post('clave');
                @$gestion =  $this->input->post('gestion');
                $this->data['tipo'] = 'objecion';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Auto que resuelve Objeción</h2>";
                $this->data['instancia'] = 'Auto que resuelve Objeción';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/objecion/';
                $this->data['plantilla'] = "auto_objecion.txt";
                $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $cod_coactivo;
                $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
                $this->data['filas2'] = template_tags($urlplantilla2,$consec);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                @$this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_add', $this->data);
            }else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }
    }

    function addAutoLiq() {
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $nit_empresa = $this->input->post('nit');
                @$this->data['auto_num'] = $this->input->post('clave');
                @$gestion =  $this->input->post('gestion');
                $this->data['tipo'] = 'aprobacion';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Auto que Aprueba la Liquidación del Crédito</h2>";
                $this->data['instancia'] = 'Auto que resuelve Objeción';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/aprobacion/';
                $this->data['plantilla'] = "auto_objecion.txt";
                $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $cod_coactivo;
                $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
                $this->data['filas2'] = template_tags($urlplantilla2,$consec);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                @$this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_add', $this->data);
            }else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }
    }

    function autos_info() {
        $this->data['post']=$this->input->post();
        $this->data['perfil'] = PERFIL;
        if ($this->ion_auth->logged_in()){
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                    $this->template->set('title', 'AUTOS');
                    $cod_coactivo = $this->input->post('cod_coactivo');
                    $this->data['registros'] = $this->auto_liquidacion_model->liquidacionesView($cod_coactivo);

                        @$this->data['nit']              = $this->data['registros'][0]['CODEMPRESA'];
                        @$this->data['cod_coactivo']     = $this->data['registros'][0]['COD_FISCALIZACION'];
                        @$this->data['razon']            = $this->data['registros'][0]['NOMBRE_EMPRESA'];
                        @$this->data['cod_respuesta']    = $this->data['registros'][0]['COD_RESPUESTA'];
                        @$this->data['num_auto']         = $this->data['registros'][0]['NUM_AUTOGENERADO'];
                        @$this->data['regional']         = $this->data['registros'][0]['COD_REGIONAL'];
                        @$this->data['tipo_auto']        = $this->data['registros'][0]['COD_TIPO_AUTO'];
                        @$this->data['procesopj']        = $this->data['registros'][0]['PROCESOPJ'];
                    $this->data['message']=$this->session->flashdata('message');
                    $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_index',$this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }

        } else
    {
            redirect(base_url().'index.php/auth/login');
    }
    }


         function documentos() {
         $ID = $this->input->post('clave');
         $idgestion = $this->input->post('gestion');
         $fiscalizacion = $this->input->post('fisca');
         $this->data['fiscalizacion'] = $fiscalizacion;
         if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                @$this->data['documentos'] = $this->auto_liquidacion_model->getDocumentos($ID);
                @$this->data['autos'] = $this->auto_liquidacion_model->getAuto($ID);
                $this->load->view('liquidaciones_credito/auto_liquidacion_documentos',$this->data);
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio/nits');
            }
        }else {
            redirect(base_url() . 'index.php/auth/login');
        }
     }

     function correo() {
         if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');
                $perfil = PERFIL;
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'correo';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificación de Auto de Liquidación de Crédito</h2>";
                $this->data['instancia'] = 'Notificación por Correo';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/correo/';
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');
                $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_correo', $this->data);
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/bandejaunificada/procesos');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }

    function correoObjec() {
         if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');
                $perfil = PERFIL;
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'correoObjec';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto que resuelve Objeciones</h2>";
                $this->data['instancia'] = 'Notificación por Correo';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/correoObjec/';
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');
                $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_correo', $this->data);
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/bandejaunificada/procesos');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }

    private function do_uploadAuto($cProceso, $cTipo) {
        $this->load->library('upload');
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }
        $cFile = $cFile.$cTipo."/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }

        $config['upload_path'] = $cFile;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filecolilla')) {
            return $error = array('error' => $this->upload->display_errors());
        }else {
            return $data = array('upload_data' => $this->upload->data());
        }


     }

    private function do_uploadDoc($cProceso, $cTipo1, $cTipo2) {
        $this->load->library('upload');
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }

        if (@$_FILES['filecolilla']['name'] != '')
        {
            $cFile1 = $cFile.$cTipo1."/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1,0777,true);
            }

            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('filecolilla'))
            {

                $data1 = $this->upload->data();
            }
            else
            {
                echo $this->upload->display_errors();
            }
        }

        if (@$_FILES['doccolilla']['name'] != '')
        {
            $cFile2 = $cFile.$cTipo2."/";
            if (!is_dir($cFile2)) {
                mkdir($cFile2,0777,true);
            }
            $config['upload_path'] = $cFile2;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('doccolilla'))
            {
                $data2 = $this->upload->data();
            }
            else
            {
                echo $this->upload->display_errors();
            }

        }
        @$dataArray = array ($data1,$data2);
        return $dataArray;
    }//fin do_uploadDoc

    private function do_uploadImg($cProceso, $cTipo1) {
        $this->load->library('upload');
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
                    mkdir($cFile,0777,true);
        }
        if (@$_FILES['filecolilla']['name'] != '')
        {
            $cFile1 = $cFile.$cTipo1."/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1,0777,true);
            }

            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('filecolilla')) {
                return $error = array('error' => $this->upload->display_errors());
            }else {
                return $data = array('upload_data' => $this->upload->data());
            }
            @$dataArray = array ($data);
            return $dataArray;
        }
    }


     function editAuto(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $this->data['gestion'] = $gestion =  $this->input->post('gestion');
        $perfil = PERFIL;
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nit'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Auto de Liquidación de Credito</h2>";
        $this->data['instancia'] = 'Notificación por Correo';
        $this->data['tipo'] = "autoliquidacion";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/autoliquidacion/';
         if ($this->ion_auth->logged_in()){
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                 @$this->data['result'] = $this->auto_liquidacion_model->getAuto($ID);
                 $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                 $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);

                 if (@$this->data['result']->NOMBRE_DOC_GENERADO != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/autoliquidacion";
                        $cFile .= "/".$this->data['result']->NOMBRE_DOC_GENERADO;
                        $this->data['plantilla'] = read_template($cFile);
                    }

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit', $this->data);
             }else{
                 redirect(base_url().'index.php/bandejaunificada/procesos');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
     }

     function editAutoObj(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $this->data['gestion'] = $gestion =  $this->input->post('gestion');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nit'] = $nit;
        $this->data['titulo'] = "<h2>Notificar Auto de Objeción</h2>";
        $this->data['instancia'] = 'Notificación por Correo';
        $this->data['tipo'] = "objecion";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/objecion/';
         if ($this->ion_auth->logged_in()){
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                 @$this->data['result'] = $this->auto_liquidacion_model->getAuto($ID);
                 $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                 $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);
                 $regional = REGIONAL;
                 if (@$this->data['result']->NOMBRE_DOC_GENERADO != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/objecion";
                        $cFile .= "/".$this->data['result']->NOMBRE_DOC_GENERADO;
                        $this->data['plantilla'] = read_template($cFile);
                    }

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit', $this->data);
             }else{
                 redirect(base_url().'index.php/bandejaunificada/procesos');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
     }

      function editAutoLiq(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $this->data['gestion'] = $gestion =  $this->input->post('gestion');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nit'] = $nit;
        $this->data['titulo'] = "<h2>Auto que Aprueba la Liquidación del Crédito</h2>";
        $this->data['instancia'] = 'Auto que aprueba Liquidacion';
        $this->data['tipo'] = "aprobacion";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/aprobacion/';
         if ($this->ion_auth->logged_in()){
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                 @$this->data['result'] = $this->auto_liquidacion_model->getAuto($ID);
                 $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                 $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);
                 $regional = REGIONAL;
                 if (@$this->data['result']->NOMBRE_DOC_GENERADO != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/aprobacion";
                        $cFile .= "/".$this->data['result']->NOMBRE_DOC_GENERADO;
                        $this->data['plantilla'] = read_template($cFile);
                    }

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit', $this->data);
             }else{
                 redirect(base_url().'index.php/bandejaunificada/procesos');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
     }


     function editCorreo(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nitempresa'] = $nit;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Notificación de Auto de Liquidación de Crédito</h2>";
        $this->data['instancia'] = "Notificación de Auto de Liquidación de Crédito por Correo";
        $this->data['tipo'] = "correo";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/correo/';
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'ACTIVO');
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');
                $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);

                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/correo";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');
                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit_correo', $this->data);
            }else{
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }

     function editCorreoObjec(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');
        $perfil = PERFIL;
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Notificar Auto que resuelve Objeciones</h2>";
        $this->data['instancia'] = "Notificación de Auto de Objeción por Correo";
        $this->data['tipo'] = "correoObjec";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/correoObjec/';
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'ACTIVO');
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');
                $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/correoObjec";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit_correo', $this->data);
            }else{
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }

    function editPagina(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');
        $perfil = PERFIL;
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['tipo'] = 'pagina';
        $this->data['titulo'] = "<h2>Notificar Auto de Liquidación por Página Web</h2>";
        $this->data['instancia'] = 'Notificación por Página Web';
        $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/pagina/';
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/pagina/';
        $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'ACTIVO');
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/pagina";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit_pagina', $this->data);
            }else{
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }

    function editPaginaObjec(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');
        $perfil = PERFIL;
        $cod_coactivo = $this->input->post('cod_coactivo');
        $this->data['cod_coactivo'] = $cod_coactivo;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['tipo'] = 'paginaObjec';
        $this->data['titulo'] = "<h2>Notificar Auto de Objeción por Página Web</h2>";
        $this->data['instancia'] = 'Notificación por Página Web';
        $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/paginaObjec/';
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$cod_coactivo.'/pdf/paginaObjec/';
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'ACTIVO');
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/paginaObjec";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');

                 $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_edit_pagina', $this->data);
            }else{
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }

    function guardar_auto() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                $this->data['custom_error'] = '';
        $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');

                if ($this->form_validation->run() == false){
                    redirect(base_url() . 'index.php/liquidaciones_credito/addAuto');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                }else {
                    $cod_coactivo  = $this->input->post('cod_coactivo');
                    $nit_empresa    = $this->input->post('nitempresa');
                    $tipo           = $this->input->post('tipo');
                    $auto_num       = $this->input->post('auto_num');
                    if ($tipo == 'autoliquidacion'){
                        $tipo_auto = $this->TIPOAUTO_LIQUIDACION;
                    }elseif ($tipo == 'objecion'){
                        $tipo_auto = $this->TIPOAUTO_OBJECION;
                    }elseif ($tipo == 'aprobacion'){
                        $tipo_auto = $this->TIPOAUTO_APROBACION;
                    }

                    $estado = 1473;
                    $mensaje = 'Auto Generado y se Asigna a Secretario';
                    $tipogestion = 723;

                    $cRuta = "./uploads/liquidacion/".$cod_coactivo."/".$tipo."/";
                        if (!is_dir($cRuta)) {
                            mkdir($cRuta,0777,true);
                        }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));

                    $gestion = trazarProcesoJuridico($tipogestion,$estado,'',$cod_coactivo,'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                    $data = array(
                                'COD_TIPO_AUTO'         => $tipo_auto,
                                'COD_PROCESO_COACTIVO'  => $cod_coactivo,
                                'COD_ESTADOAUTO'        => $this->ESTADONOTIFICACIONGENERADA,
                                'FECHA_CREACION_AUTO'   => date("d/m/Y"),
                                'FECHA_CREACION_AUTO'   => date("d/m/Y"),
                                'CREADO_POR'            => ID_USER,
                                'COD_TIPO_PROCESO'      => $this->PROCESO,
                                'ASIGNADO_A'            => ID_SECRETARIO,
                                'COMENTARIOS'           => $this->input->post('comentarios'),
                                'NOMBRE_DOC_GENERADO'   => $sFile,
                                'COD_GESTIONCOBRO'      => $gestion
                    );
                    $insert = $this->auto_liquidacion_model->guardarAuto('AUTOSJURIDICOS', $data);
                    if ($insert == TRUE){
                         if ($tipo == 'objecion'){
                             $estadoNew = '1132';
                             $mensajeNew = 'Auto Pre-Generado';
                             $tipogestionNew = '436';
                             $gestionNew = $gestion = trazarProcesoJuridico($tipogestionNew,$estadoNew,'',$cod_coactivo,'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                             $dataNew = array(
                                'COD_GESTIONCOBRO'      => $gestionNew,
                                'FECHA_GESTION'         => date("d/m/Y"),
                            );
                            $insertNew = $this->auto_liquidacion_model->guardarObjecion($dataNew,$auto_num);
                        }
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha creado correctamente.</div>');
                    redirect(base_url().'index.php/bandejaunificada/procesos');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha podido crear.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }

                }

            }else {
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }

    }

    function guardar_notif() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                $this->data['custom_error'] = '';
        $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');

                if ($this->form_validation->run() == false){
                    redirect(base_url() . 'index.php/bandejaunificada/procesos');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                }else {

                    $cod_coactivo  = $this->input->post('cod_coactivo');
                    $cod_estado     = $this->ESTADONOTIFICACIONGENERADA;
                    $nit_empresa    = $this->input->post('cod_nit');
                    $tipo           = $this->input->post('tipo');
                    if ($tipo == 'correo'|| $tipo == 'correoObjec'){
                        $respuesta = 450;
                        $mensaje = 'Notificación de Auto de Liquidación de Crédito por Correo Generada';
                        $tipogestion = 185;
                        $tipo_notificacion = $this->TIPONOTIFICACIONCORREO;
                    }else if ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                        $respuesta = 453;
                        $tipogestion = 186;
                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Generada';
                        $tipo_notificacion = $this->TIPONOTIFICACIONPAGINA;
                    }

                    $cRuta = "./uploads/liquidacion/".$cod_coactivo."/".$tipo."/";
                        if (!is_dir($cRuta)) {
                            mkdir($cRuta,0777,true);
                        }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $gestion = trazarProcesoJuridico($tipogestion,$respuesta,'',$cod_coactivo,'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                    //var_dump($gestion);die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $tipo_notificacion,
                            'FECHA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $cod_estado,
                            'NOMBRE_DOC_CARGADO' => ' ',
                            'OBSERVACIONES' => $this->input->post('comentarios'),
                            'ESTADO_NOTIFICACION'=>'ACTIVO',
                            'NUM_AUTOGENERADO' => $this->input->post('num_auto'),
                            'PLANTILLA' => $sFile
                        );
                        $dataAuto = array(
                            'COD_GESTIONCOBRO' => $gestion,
                            'FECHA_GESTION' => date("d/m/Y"),
                            //'ASIGNADO_A' => $asignado[0],
                        );
                    $insert = $this->auto_liquidacion_model->guardarNotificacion($data,$dataAuto);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación no se ha podido crear.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }
                }
            }else{
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
    }

    function guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso , $usuario, $cod_coactivo) {
            $CI = & get_instance();
            $CI->load->model("Expediente_model");
            $model = new Expediente_model();
            $radicado = FALSE;
            $fecha_radicado = FALSE;
            $expediente = $model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, $usuario, $cod_coactivo); //Guarda en la tabla expediente
            return $expediente;
     }

    function guardar_objecion() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
    {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                    $cod_coactivo  = $this->input->post('cod_coactivo');
                    $nit_empresa   = $this->input->post('nit');
                    $auto_num      = $this->input->post('clave');
                    $gestion       = $this->input->post('gestion');
                    $tipo          = $this->input->post('tipo');

                    if ($tipo == 's'):
                        $estado = 840;
                        $mensaje = 'Objeción Presentada';
                        $tipogestion = 298;
//                        $docFile = $this->do_uploadAuto($cod_coactivo,'objecion');
//                        var_dump($docFile);die;
//                        $nombre = $docFile['upload_data']['file_name'];
//                        $ruta = 'uploads/liquidacion/'.$cod_coactivo.'/pdf/objecion/';
//                        $tipo_expediente = 2;
//                        $subproceso = 'Objecion';
//                        $expediente = $this->guarda_expediente($tipogestion,$nombre,$ruta,$tipo_expediente,$subproceso,ID_USER,$cod_coactivo);
                        else:
                            $estado = 1471;
                            $mensaje = 'Objeción Presentada';
                            $tipogestion = 298;
                    endif;

                    $gestion = trazarProcesoJuridico($tipogestion,$estado,'',$cod_coactivo,'', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');
                    $data = array(
                                'COD_GESTIONCOBRO'      => $gestion
                    );
                    $insert = $this->auto_liquidacion_model->guardarObjecion($data,$auto_num);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }
            }else {
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }

    }

    function modificarNotific(){
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info'))
            {
                $this->data['custom_error'] = '';
        $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');


                if ($this->form_validation->run() == false){
                    redirect(base_url() . 'index.php/liquidaciones_credito/editCorreo');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                }else {
                    $asignado       = $asignado = explode("-",$this->input->post('asignado'));
                    $cod_coactivo  = $this->input->post('cod_coactivo');
                    $nit_empresa    = $this->input->post('cod_nit');
                    $iduser         = $this->input->post('iduser');
                    $aprobado       = $this->input->post('aprobado');
                    $revisado       = $this->input->post('revisado');
                    $ID             = $this->input->post('notifica');
                    $auto           = $this->input->post('num_auto');
                    $tipo           = $this->input->post('tipo');
                    $estado         = $this->input->post('id_estado');
                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                        $tipoNotif = $this->TIPONOTIFICACIONCORREO;
                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                        $tipoNotif = $this->TIPONOTIFICACIONPAGINA;
                    }

                    $plantilla = $this->auto_liquidacion_model->getNotifica($auto,$tipoNotif,'ACTIVO');
                     if ($plantilla->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$cod_coactivo."/".$tipo;
                        $cFile .= "/".$plantilla->PLANTILLA;
                     if (is_file($cFile))
                        $cMandamiento = read_template($cFile);
                     }else {
                        $cMandamiento = "";
                     }

                      if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                      }else{
                          $bGenera = TRUE;
                      }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/liquidacion/".$cod_coactivo."/".$tipo;
                            $cRuta .= "/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $plantilla->PLANTILLA;
                        }
                        //echo $estado.$tipo;die;
                        switch ($estado){
                                case $this->NOTIFICACION_GENERADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 451;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Generada';
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 453;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Generada';
                                    }
                                    $asignado = $this->input->post('iduser');
                                    $estado_notificacion = 'ACTIVO';
                                    break;
                                case $this->NOTIFICACION_ENVIADO:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 451;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Generada';
                                    }
                                    $asignado = $this->input->post('iduser');
                                    $estado_notificacion = 'ACTIVO';
                                    break;
                                case $this->NOTIFICACION_APROBADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 1152;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Aprobada';
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 1154;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Realizada';
                                    }
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;
                                case $this->NOTIFICACION_NOAPROBADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 1153;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo No Aprobada';
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 1155;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web No Realizada';
                                    }
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;
                                case $this->NOTIFICACION_REVISADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 450;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Generada';
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 453;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Generada';
                                    }
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;
                                case $this->NOTIFICACION_ENTREGADO:
                                    //echo $estado.$tipo;die;
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 800;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Recibida';
                                        $file = $this->do_uploadDoc($cod_coactivo,'colilla',$tipo);
                                        $documento = $file[1]['file_name'];
                                        $colilla   = $file[0]['file_name'];

                                        $nombre = $file[1]['file_name'];
                                        $ruta = 'uploads/mandamientos/'.$cod_coactivo.'/pdf/'.$tipo;
                                        $tipo_expediente = 7;
                                        $subproceso = 'Modificar Notificacion';
                                        $expediente = $this->guarda_expediente($respuesta,$nombre,$ruta,$tipo_expediente,$subproceso,ID_USER,$cod_coactivo);
                                        //echo $respuesta;die;
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 454;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Publicada';
                                        $file = $this->do_uploadImg($cod_coactivo,$tipo);
                                        $documento = $file['upload_data']['file_name'];
                                        $colilla   = '';
                                    }
                                    $asignado = $this->input->post('iduser');
                                    $estado_notificacion = 'ACTIVO';
                                    break;
                                case $this->NOTIFICACION_DEVUELTO:
                                    //echo $estado.$tipo;die;
                                    $file = $this->do_uploadDoc($cod_coactivo,'colilla',$tipo);
                                    $documento = $file[1]['file_name'];
                                    $colilla   = $file[0]['file_name'];
                                    //echo $this->input->post('motivo');//die;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/'.$cod_coactivo.'/pdf/'.$tipo;
                                    $tipo_expediente = 7;
                                    $subproceso = 'Modificar Notificacion';
                                    $motivo = $this->input->post('motivo');
                                    //echo $motivo;die;
                                    if ($this->input->post('motivo') == 4){
                                        $estado_notificacion = 'INACTIVO';
                                            if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                                $respuesta = 1160;
                                                $tipogesti = 185;
                                                $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Devuelta por Dirección Errada';
                                            }
                                        $asignado = $this->input->post('iduser');
                                    }else {
                                        $estado_notificacion = 'ACTIVO';
                                            if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                                $respuesta = 452;
                                                $tipogesti = 185;
                                                $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Devuelta';
                                            }
                                        $asignado = $this->input->post('iduser');
                                    }
                                    $expediente = $this->guarda_expediente($respuesta,$nombre,$ruta,$tipo_expediente,$subproceso,ID_USER,$cod_coactivo);
                                    break;
                            }
                            //echo $respuesta;die;
                            if (@$documento == ''){
                                @$documento = ' ';
                            }
                            if (@$colilla == ''){
                                @$$colilla = ' ';
                            }

                            $gestion = trazarProcesoJuridico($tipogesti,$respuesta,'',$cod_coactivo,'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                            $data = array(
                                'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                                'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                                'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                                'COD_ESTADO'=> $estado,
                                'DOC_COLILLA' => 'uploads/liquidacion/'.$cod_coactivo.'/pdf/colilla/',
                                'DOC_FIRMADO' => 'uploads/liquidacion/'.$cod_coactivo.'/pdf/'.$tipo.'/',
                                'NOMBRE_DOC_CARGADO' => @$documento,
                                'NOMBRE_COL_CARGADO' => @$colilla,
                                'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                                'OBSERVACIONES' => $this->input->post('comentarios'),
                                'PLANTILLA' => $cPlantilla,
                                'ESTADO_NOTIFICACION'=>$estado_notificacion,
                            );
                            $dataAuto = array(
                                'COD_GESTIONCOBRO' => $gestion,
                                'FECHA_GESTION' => date("d/m/Y"),
                                //'ASIGNADO_A' => $asignado,
                            );
                            $insert = $this->auto_liquidacion_model->modificarNotific($data,$dataAuto,$auto,$ID);

                            if ($insert == TRUE){
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                                redirect(base_url().'index.php/bandejaunificada/procesos');
                            }else {
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación no se ha podido modificar.</div>');
                                redirect(base_url().'index.php/bandejaunificada/procesos');
                            }

                }

            }else {
                redirect(base_url().'index.php/bandejaunificada/procesos');
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }
    }

    function pagina() {
         if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');
                $perfil = PERFIL;
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'pagina';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Liquidación por Página Web</h2>";
                $this->data['instancia'] = 'Notificación por Página Web';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/pagina/';
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');
                $this->data['gestiones'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_pagina', $this->data);
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/liquidaciones_credito/auto_liquidacion_correo');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }

    function paginaObjec() {
         if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');
                $perfil = PERFIL;
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'paginaObjec';
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Objeción por Página Web</h2>";
                $this->data['instancia'] = 'Notificación por Página Web';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$cod_coactivo.'/paginaObjec/';
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');

                $this->template->load($this->template_file, 'liquidaciones_credito/auto_liquidacion_pagina', $this->data);
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/liquidaciones_credito/auto_liquidacion_correo');
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }

    function pdf(){
        if ($this->ion_auth->logged_in())
        {
                $this->load->library('form_validation');
                $this->load->library("tcpdf/tcpdf");
                $html = $this->input->post('liquidacion');
                $html=  str_replace("'", "\"", $html);
                //echo $html;
                ob_clean();
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('dejavusans', '', 8, '', true);
                $pdf->SetTitle('Liquidacion');
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output('auto_liquidacion.pdf', 'I');
        //$this->template->load($this->template_file, 'mandamientopago/mandamientopago_pdf', $this->data);
    } else
        {
            redirect(base_url().'index.php/auth/login');
    }
    }//Fin PDF

    function tareasAuto(){
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
    {
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones_credito/formularioExpediente') || $this->ion_auth->in_menu('liquidaciones_credito/autos_info')){

                $this->data['custom_error'] = '';
        $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');

                if ($this->form_validation->run() == false){
                    redirect(base_url() . 'index.php/liquidaciones_credito/editAuto');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                }else {
                    $asignado       = $asignado = explode("-",$this->input->post('asignado'));
                    $cod_coactivo  = $this->input->post('cod_coactivo');
                    $nit_empresa    = $this->input->post('cod_nit');
                    $iduser         = $this->input->post('id_user');
                    $aprobado       = $this->input->post('aprobado');
                    $revisado       = $this->input->post('revisado');
                    $tipo           = $this->input->post('tipo');
                    $perfil         = PERFIL;
                    $ID             =  $this->input->post('num_auto');
                    $plantilla      = $this->auto_liquidacion_model->getAuto($ID);
                    $gestion        = $this->input->post('gestion');

                    if ($plantilla->NOMBRE_DOC_GENERADO != "") {
                            $cFile  = "./uploads/liquidacion/".$cod_coactivo."/".$tipo;
                            $cFile .= "/".$plantilla->NOMBRE_DOC_GENERADO;
                            if (is_file($cFile)){
                               $cMandamiento = read_template($cFile);
                            }
                    }else {
                        $cMandamiento = "";
                    }

                        if (trim($this->input->post('notificacion')) == trim($cMandamiento)) {
                            $bGenera = FALSE;
                        }else{
                            $bGenera = TRUE;
                        }

                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta  = "./uploads/liquidacion/".$cod_coactivo."/".$tipo."/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $plantilla->NOMBRE_DOC_GENERADO;
                        }


                      if ($gestion == 1473):
                          if ($revisado == 1):
                                $estado = 1474;
                                $mensaje = 'Auto se Pre-Aprueba y Asigna a  Ejecutor';
                                $tipogestion = 723;
                                $estado_auto = $this->ESTADONOTIFICACIONPREAPROBADA;
                              elseif ($revisado == 2):
                                $estado = 1475;
                                $mensaje = 'Auto se Devuelve al Abogado Asignado con Comentarios';
                                $tipogestion = 723;
                                $estado_auto = $this->ESTADONOTIFICACIONRECHAZADA;
                          endif;
                      endif;
                      if ($gestion == 1475):
                          $estado = 1473;
                          $mensaje = 'Auto Generado y se Asigna a Secretario';
                          $tipogestion = 723;
                          $estado_auto = $this->ESTADONOTIFICACIONGENERADA;
                      endif;
                      if ($gestion == 1474):
                          if ($aprobado == 3):
                                $estado = 1475;
                                $mensaje = 'Auto se Devuelve al Abogado Asignado con Comentarios';
                                $tipogestion = 723;
                                $estado_auto = $this->ESTADONOTIFICACIONRECHAZADA;
                              elseif ($aprobado == 1):
                                $estado = 1476;
                                $mensaje = 'Auto se Aprueba y Asigna a Abogado para Subir Archivo';
                                $tipogestion = 723;
                                $estado_auto = $this->ESTADONOTIFICACIONAPROBADA;
                          endif;
                      endif;
                      if ($gestion == 1476):
                        $estado = 1477;
                        $mensaje = 'Abogado Subió el Archivo Firmado';
                        $tipogestion = 723;
                        $estado_auto = $this->ESTADONOTIFICACIONRECIBIDA;
                        $docFile = $this->do_uploadAuto($cod_coactivo,$tipo);
                        $nombre = $docFile['upload_data']['file_name'];
                        $ruta = 'uploads/liquidacion/'.$cod_coactivo.'/pdf/'.$tipo;
                        $tipo_expediente = 2;
                        $subproceso = 'Tareas Auto';
                        $expediente = $this->guarda_expediente($tipogestion,$nombre,$ruta,$tipo_expediente,$subproceso,ID_USER,$cod_coactivo);
                        if ($tipo == 'aprobacion'):
                            $consulta = $this->auto_liquidacion_model->actualizaAvaluo($cod_coactivo,$estado);
                        endif;
                      endif;


                    $gestion = trazarProcesoJuridico($tipogestion,$estado,'',$cod_coactivo,'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                    $data = array(
                                'COD_ESTADOAUTO'        => $estado_auto,
                                'FECHA_GESTION'         => date("d/m/Y"),
                                //'ASIGNADO_A'            => $asignado[0],
                                'REVISADO'              => $revisado,
                                'APROBADO'              => $aprobado,
                                'COMENTARIOS'           => $this->input->post('comentarios'),
                                'NOMBRE_DOC_GENERADO'   => $cPlantilla,
                                'COD_GESTIONCOBRO'      => $gestion,
                                'NOMBRE_DOC_FIRMADO'    => $docFile['upload_data']['file_name'],
                                'FECHA_DOC_FIRMADO'     => date("d/m/Y")
                    );
                    $insert = $this->auto_liquidacion_model->modificarAuto('AUTOSJURIDICOS',$data,$ID);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha podido modificar.</div>');
                        redirect(base_url().'index.php/bandejaunificada/procesos');
                    }

                }
          }else{

          }
        }else{

        }
    }

       function temp(){
        $temporal = $this->input->post('temporal');
        $proceso = $this->input->post('cod_coactivo');
        $tipo = $this->input->post('tipo');

        if ($this->ion_auth->logged_in())
        {
                $cRuta = "./uploads/liquidacion/temporal/".$proceso."/".$tipo."/";
                    if (is_dir($cRuta)) {
                        $handle = opendir($cRuta);
                        while ($file = readdir($handle)) {
                         if (is_file($cRuta.$file)) {
                            unlink($cRuta.$file);
                          }
                        }
                        mkdir($cRuta,0777,true);
                        $sFile = create_template($cRuta, $this->input->post('temporal'));
                    }

                    if (!is_dir($cRuta)) {
                        mkdir($cRuta,0777,true);
                        $sFile = create_template($cRuta, $this->input->post('temporal'));
                    }

                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'.ucfirst($tipo).' Creado Correctamente.</div>');
        redirect(base_url().'index.php/bandejaunificada/procesos');
        }
   }//Fin temp

}
/* End of file liquidaciones.php */
