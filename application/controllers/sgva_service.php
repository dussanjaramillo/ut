<?php
/**
 * Archivo para la administración de los metodos necesarios para procesar las peticiones realizadas desde Sgva a Cartera
 *
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/sgva_srvice.php
**/
 
if (!defined('BASEPATH'))
    exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');



class Sgva_service extends CI_Controller {

    private $nusoap_server;
    private $sesion;

    function __construct() {
        parent::__construct();
        $this->load->library("nu_soap");
        $this->ion = new Ion_auth_model();
        $this->sesion = new CI_Session();

        // Instanciamos la clase servidor de nusoap
        $this->nusoap_server = new nusoap_server();

        // Creamos el End Point, es decir, el lugar donde la petición cliente va a buscar la estructura del WSDL
        // aunque hay que recordar que nusoap genera dinámicamente dicha estructura XML
        $end_point = base_url() . 'index.php/sgva_service/index/wsdl';
        $ns = 'http://SgvaWDSL';
        //$ns = $end_point;
        // Indicamos cÃ³mo se debe formar el WSDL
        $this->nusoap_server->configureWSDL('SgvaWSDL', $ns);
        $this->nusoap_server->wsdl->schemaTargetNamespace = $ns;

        define('USUARIO', 'WsC4rt3r4');
        define('CONTRASENNA', 'S3n4C4rt3r4');

        // Indicamos cómo se debe formar el WSDL

        $this->nusoap_server->register(
                'Sgva_service..AcutalizarEmpresaCartera'
                , array('nit' => 'xsd:string', 'email' => 'xsd:string', 'telefono' => 'xsd:string', 'direccion' => 'xsd:string',
            'cuotaAprendiz' => 'int', 'resolucion' => 'xsd:string', 'plantaPersonal' => 'xsd:int', 'numEmpleados' => 'xsd:int',
            'usuario' => 'xsd:string', 'contrasenna' => 'xsd:string'
                )
                , array('return' => 'xsd:Array')
                , 'http://SgvaWDSL'
                , 'http://SgvaWDSL#AcutalizarEmpresaCartera'
                , 'rpc'
                , 'encoded'
                , "Actualiza los datos de una empresa en el sistema."
        );
//
//        
        $this->nusoap_server->wsdl->addComplexType(
                'StructOfReference', 'complexType', 'array', 'sequence', '', array(
            'PERIODO_PAGADO' => array('name' => 'PERIODO_PAGADO', 'type' => 'xsd:string'),
            'FECHA_PAGO' => array('name' => 'FECHA_PAGO', 'type' => 'xsd:string'),
            'FECHA_APLICACION' => array('name' => 'FECHA_APLICACION', 'type' => 'xsd:string'),
            'VALOR_PAGADO' => array('name' => 'VALOR_PAGADO', 'type' => 'xsd:string'),
            'TICKETID' => array('name' => 'TICKETID', 'type' => 'xsd:string')
                )
        );

        $this->nusoap_server->register(
                'Sgva_service..ConsultaMonetizacion'
                , array('empresa_nit' => 'xsd:int', 'periodo_inicial' => 'xsd:string', 'periodo_final' => 'xsd:string', 'usuario' => 'xsd:string',
            'contrasenna' => 'xsd:string'
                )
                , array('PAGO' => 'tns:StructOfReference')
                , 'http://SgvaWDSL'
                , 'http://SgvaWDSL#ConsultaMonetizacion'
                , 'rpc'
                , 'encoded'
                , "Permite consultar los pagos realizados por concepto de monetización para una empresa por periodo."
        );

    }


    function index() {
        ob_clean();
        $_SERVER['QUERY_STRING'] = '';
        if ($this->uri->segment(3) == 'wsdl') {
            $_SERVER['QUERY_STRING'] = 'wsdl';
        }
        $this->nusoap_server->service(trim(file_get_contents('php://input')));
    }

    /*     * ********** FUNCIONES CARTERA*********** */


    /* Función para consultar el pago realizado por concepto de monetización para un período */

    function ConsultaMonetizacion($nit, $per_inicial, $per_final, $identidad, $clave) {

        $obligatorios = array(
            'nit' => $nit,
            'periodo_inicial' => $per_inicial,
            'periodo_final' => $per_final,
            'usuario' => $identidad,
            'contrasenna' => $clave
        );
        $f_valida = Sgva_service::validacion($obligatorios);/*Verifica los campos obligatorios**/
        $validacion = $f_valida['validacion'];
        if ($validacion == false) :
            $login = Sgva_service::login($identidad, $clave);/*Verifica que el usuario y contraseña sean correctos*/

            if (count($login) == 0):
                $CI = & get_instance();
                $CI->load->model("Sgva_model");
                $model = new Sgva_model();
                // $periodo = $anno . '-' . $mes;
                $datos = array('nit' => $nit, 'periodo_inicial' => $per_inicial, 'periodo_final' => $per_final);
                $periodo_inicial = $per_inicial;
                $periodo_final = $per_final;
                $fecha_inicial = explode('-', $periodo_inicial);
                $fecha_final = explode('-', $periodo_final);
                $anno_inicial = $fecha_inicial[0];
                $mes_inicial = $fecha_inicial[1];
                $anno_final = $fecha_final[0];
                $mes_final = $fecha_final[1];
                //recorro los aÃ±os()
                $x = 0;
                $pago = array();
                $aportes = FALSE;
                //Recorre año a ño
                for ($anno = $anno_inicial; $anno <= $anno_final; $anno++):
                    //primer año;
                    if ($anno == $anno_inicial):
                        $resultado = array();
                        for ($mes = $mes_inicial + 0; $mes <= 12; $mes++):
                            if ($mes < 10):
                                $periodo = $anno . '-0' . $mes;
                            else:
                                $periodo = $anno . '-' . $mes;
                            endif;
                            $datos['periodo'] = $periodo;
                            $aportes = $model->ConsultaMonetizacion($datos);/**Consulta los pagos realizados para ese año*/
                            if ($aportes):
                                $pago = $aportes;
                          
                                $x++;
                            endif;

                        endfor;
                        //Años intermedios.
                    elseif ($anno != $anno_inicial && $anno != $anno_final):
                        //Otros Años 
                        for ($mes = 1; $mes <= 12; $mes++):
                            if ($mes < 10):
                                $periodo = $anno . '-' . $mes;
                            else:
                                $periodo = $anno . '-' . $mes;
                            endif;
                            $datos['periodo'] = $periodo;
                            $aportes = $model->ConsultaMonetizacion($datos);
                           
                            if ($aportes):
                                $pago =  array_merge($pago, $aportes);
                                $x++;
                            endif;
                        endfor;
                        
                            //Ultimo Año.
                    elseif ($anno == $anno_final):
                        for ($mes = 1; $mes <= $mes_final; $mes++):
                            if ($mes < 10):
                                $periodo = $anno . '-' . $mes;
                            else:
                                $periodo = $anno . '-' . $mes;
                            endif;

                            $datos['periodo'] = $periodo;
                            $aportes = $model->ConsultaMonetizacion($datos);
                            if ($aportes):
                                 $pago =  array_merge($pago, $aportes);
                                $x++;
                            endif;
                        endfor;
                    endif;
                endfor;
                if (count($pago) == 0):
                    $pago = array(0 => 'No hay pagos registrados');
                endif;
                
                return $pago;
            else:
                return $login;
            endif;
        else:

            $result = $f_valida['dato'];
            return $result;
        endif;
    }

    
    /* Function para Actualizar  empresa */

    function AcutalizarEmpresaCartera($nit, $email, $telefono, $direccion, $cuotaAprendiz, $resolucion, $plantaPersonal, $numEmpleados, $identidad, $clave) {
        //Campos Obligatorios;
        $obligatorios = array(
            'nit' => $nit,
            'email' => $email,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'usuario' => $identidad,
            'contrasenna' => $clave
        );

        $f_valida = Sgva_service::validacion($obligatorios);
        $validacion = $f_valida['validacion'];
        if ($validacion == false) ://Guarda los datos
            $login = Sgva_service::login($identidad, $clave);
            if (count($login) == 0):
                $datos = array('cuotaAprendiz' => $cuotaAprendiz, 'resolucion' => $resolucion, 'plantaPersonal' => $plantaPersonal, 'numEmpleados' => $numEmpleados);
                $empresa = array_merge($obligatorios, $datos);
//                $result=$empresa;
//                return $result; 
                $CI = & get_instance();
                $CI->load->model("Sgva_model");
                $model = new Sgva_model();
                $result = $model->ActualizarEmpresa($empresa);
            else:
                return $login;
            endif;
        else:
            $result = $f_valida['dato'];
        endif;

        return $result;
    }


    private function validacion($obligatorios) {
        $valida = array();
        $i = 0;
        $validacion = 0;
        $dato = 0;
        foreach ($obligatorios as $key => $val) :
            if (empty($val) || is_null($val)) :
                $dato = "El campo " . $key . " es obligatorio.";
                $valida[$i] = $dato;
                $validacion = true;
                $i++;
            endif;
        endforeach;
        $resultado = array('validacion' => $validacion, 'dato' => $valida);
        return $resultado;
    }

//
//    /* Función para validar el acceso del usuario .
//     * Siempre en todos las funciones se deben enviar estos  datos porque en cada función se valida el acceso.
//     */
//
    private function login($identidad, $clave) {
        $verf = array();
        if (!empty($identidad) and !empty($clave)) :
            if ($identidad != USUARIO || $clave != CONTRASENNA):

                if ($identidad != USUARIO):
                    $verf[0] = 'El usuario es incorrecto';
                endif;
                if ($clave != CONTRASENNA):
                    $verf[1] = 'La contraseña es incorrecta';
                endif;

                return $verf;
            else:

                return $verf;
            endif;
        endif;
    }

}
?>



