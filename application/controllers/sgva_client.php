<?php

/**
 * Archivo para la administración de los metodos necesarios para generar la comunicación entre Cartera y Sgva, contiene las peticiones que se
 * realizan desde Cartera a Sgva.
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/sgva_cliente.php

 */
if (!defined('BASEPATH'))
    exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class Sgva_client extends MY_Controller {

    private $soapclient;

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('sgva_model', '', TRUE);
        $this->load->file("application/libraries/nusoap/nusoap.php", false);
        $this->ion = new Ion_auth_model();

        ob_clean();

        //  $this->soapclient = new nusoap_client(base_url('index.php/sgva_service/index/wsdl'), 'wsdl');
        $this->soapclient = new nusoap_client('http://10.104.180.6/wscg/ws_sgva_cartera.asmx?WSDL', 'wsdl');
        $err = $this->soapclient->getError();
        if ($err) :
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        endif;
    }

    function index() {


//        $call = 'Sgva_service..AcutalizarEmpresa';
//        $parts = array('identidad' => 'victoria20ferreira@gmail.com', 'clave' => 'victoria20ferreira', 'NRO_IDENTIFICACION' => '830507');
//        $result = $this->soapclient->call($call, $parts);
//        $this->_manage_response($result, $this->soapclient->fault, $this->soapclient->getError());
    }

    function CambiarEstadoFiscalizador($id) {


        /* Cuando se inactive el usuario fiscalizador se envía un 0 */
        /* Cuando se active el usuario fiscalizador se envía un 1 */

        /* Consulto que el usuario sea un fiscalizador en cartera */
        $CI = & get_instance();
        $CI->load->model("Sgva_model");
        $model = new Sgva_model();

        $fiscalizador = $model->UsuarioFiscalizador($id);

        if ($fiscalizador):
            $data['estado'] = 0;
            if ($fiscalizador == 'S'):
                $data['estado'] = 1;
            endif;

            $parts = array('usuario_id' => $id,
                'estado' => $data['estado'],
                'CodAc' => '5i5t3m4_5Gv4C'
            );
            $call = 'Cambiar_Estado_Fiscalizador';
            $result = $this->soapclient->call($call, $parts);
            $result = $result['Cambiar_Estado_FiscalizadorResult'];
        else:
            $result = FALSE;
        endif;

        return $result;
    }

    /* Función que envia los datos de la empresa creada en Cartera para crearla en Sgva
     */

    function CrearEmpresa($datos_empresa) {

        $empresa = array(
            'empresa_nit' => $datos_empresa['CODEMPRESA'],
            'empresa_RSocial' => $datos_empresa['RAZON_SOCIAL'],
            'empresa_RepLegal' => $datos_empresa['REPRESENTANTE_LEGAL'],
            'empresa_email' => $datos_empresa['CORREOELECTRONICO'],
            'empresa_telefono' => $datos_empresa['TELEFONO_FIJO'],
            'empresa_direccion' => $datos_empresa['DIRECCION'],
            'empresa_ciudad' => $datos_empresa['COD_MUNICIPIO'],
            'empresa_dpto' => $datos_empresa['COD_DEPARTAMENTO'],
            'empresa_acteco' => $datos_empresa['CIIU'],
            'empresa_regional' => $datos_empresa['COD_REGIONAL'],
            'empresa_usuario_id' => '',
            'CodAc' => '5i5t3m4_5Gv4C');
        $parts = $empresa;
        $call = 'Crear_Empresa';
        $result = $this->soapclient->call($call, $parts);
        return $result['Crear_EmpresaResult'];
    }

    function AcutalizarEmpresa($datos_empresa) {
        $call = 'Actualizar_Campos_Empresa';
        //datos que recibe de cartera
        $empresa = array(
            'nit' => $datos_empresa['CODEMPRESA'],
            'rsocial' => $datos_empresa['RAZON_SOCIAL'],
            'repLegal' => $datos_empresa['REPRESENTANTE_LEGAL'],
            'Reg' => $datos_empresa['COD_REGIONAL'],
            'CodAc' => '5i5t3m4_5Gv4C');
        $result = $this->soapclient->call($call, $parts);

        return $result;
    }

    function CrearFiscalizador($data) {

        $res = 0;

        $parts = array('usuario_login' => $data['IDUSUARIO'],
            'usuario_nombres' => $data['NOMBRES'],
            'usuario_apellidos' => $data['APELLIDOS'],
            'usuario_centro' => '0',
            'usuario_regional' => $data['COD_REGIONAL'],
            'usuario_email' => $data['CORREO_PERSONAL'],
            'usuario_codigo_fiscalizador' => '',
            'usuario_celular' => $data['CELULAR'],
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $call = 'Crear_Fiscalizador';

        $result = $this->soapclient->call($call, $parts);

        return $result['Crear_FiscalizadorResult'];
    }

    /* Función que envia los datos cuando en Cartera se realizó  la Asginación Manualmente 
     * a una Fiscalizacion para empresas Nuevas */

    function AsignarFiscalizador($data) {
        //datos que recibe de cartera
        $datestring = "%d-%m-%Y %G:%i:%s";
        $fecha_actual = mdate($datestring);
        $parts = array('nit' => $data['NIT_EMPRESA'],
            'usuarioId' => $data['ASIGNADO_A'],
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $call = 'Asignar_fiscalizador_Empresa';
        $result = $this->soapclient->call($call, $parts);
        if ($result):
            $result = $result['Asignar_fiscalizador_EmpresaResult'];
        else:
            $result = 0;
        endif;
        return $result;
    }

    function ReasignarFiscalizador($asignado_a, $cod_asignacion) {

        /* Función que recibe los parametros cuando se realiza la reasignación de un fiscalizador a una empresa
         *  para que se realice la reasignación en Sgva */
        $CI = & get_instance();
        $CI->load->model("Sgva_model");
        $model = new Sgva_model();
        /* Consulta el nit de la asignación */
        $nit = $model->NitAsignacion($cod_asignacion);
        if ($nit):
            $parts = array('nit' => $nit['NIT_EMPRESA'],
                'usuarioId' => $asignado_a,
                'CodAc' => '5i5t3m4_5Gv4C'
            );
            $call = 'Asignar_fiscalizador_Empresa';
            $result = $this->soapclient->call($call, $parts);
            return $result['Asignar_fiscalizador_EmpresaResult'];
        else:
            return FALSE;
        endif;
    }

    /* Función que permite obtener el id del estado de cuenta para una empresa en Sgva */

    function ObtenerIdEstadoCuenta($nit) {

        $call = 'Obtener_ID_Estado_Cuenta';
        $parts = array(
            'Nit' => $nit,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);
        $resultado = $result['Obtener_ID_Estado_CuentaResult']['ObtenerIDEC']['Nro_Registros'];
        if ($result == 'Sin Registro'):
            $resultado = 0;
        else:
            if (isset($result['Obtener_ID_Estado_CuentaResult']['ObtenerIDEC']['id_estado']))
                $resultado = $result['Obtener_ID_Estado_CuentaResult']['ObtenerIDEC']['id_estado'];
        endif;
        return $resultado;
    }

    function sgva_cartera_query_2($id_estado) {

        /* Permite consumir la información básica del encabezado del Estado de Cuenta.* */
        $call = 'sgva_cartera_query_2';
        $parts = array(
            'id_estado' => $id_estado,
            'CodAc' => '5i5t3m4_5Gv4C'
        );

        $result = $this->soapclient->call($call, $parts);

        if (empty($result['sgva_cartera_query_2Result']['rEstCuenta'])):
            $result = null;
        else:
            $result = $result['sgva_cartera_query_2Result']['rEstCuenta'][0];
        endif;
        return $result;
    }

    function sgva_cartera_query_3($id_estado) {

        /** Permite consumir la información de las regulaciones que entran en el período de fiscalización.* */
        $call = 'sgva_cartera_query_3';
        $parts = array(
            'id_estado' => $id_estado,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);
        if (isset($result['sgva_cartera_query_3Result']['rEstCuenta2']['ControlR'])):
            $resultado = '';
        else:
            $resultado = array();

            if (array_key_exists('id_resolucion', $result['sgva_cartera_query_3Result']['rEstCuenta2'])):
                $resultado[0] = $result['sgva_cartera_query_3Result']['rEstCuenta2'];
            else:
                $resultado = $result['sgva_cartera_query_3Result']['rEstCuenta2'];
            endif;
        endif;
        return $resultado;
    }

    function sgva_cartera_query_6a($nit, $id_estado) {
//        $nit = 860047966;
//        $id_estado = 5021;
        /** Permite consumir la información de los contratos que entran dentro del período de fiscalización.  La columna de “Total Dias” 
         * deberá se calculada vía programación en la grilla de datos.   ** */
        $call = 'sgva_cartera_query_6a';
        $parts = array(
            'id_estado' => $id_estado,
            'nit' => $nit,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);
        if (isset($result['sgva_cartera_query_6aResult']['rEstCuenta3']['ControlR'])):

            $resultado = null;
        else:

            $resultado = array();
            if (array_key_exists('t_estudiante_id', $result['sgva_cartera_query_6aResult']['rEstCuenta3'])):
                $resultado[0] = $result['sgva_cartera_query_6aResult']['rEstCuenta3'];
            else:
                $resultado = $result['sgva_cartera_query_6aResult']['rEstCuenta3'];
            endif;
        endif;
        return $resultado;
    }

    function sgva_cartera_query_6b($nit, $id_estado) {
//        $nit = 860047966;
//        $id_estado = 5021;
        /**
          Permite consumir la información de los contratos que entran dentro del período de fiscalización. Si el campo “aprendiz_discapacidad” es igual
         *  a 1 deberá concatenar una letra D mayúscula entre pareéntesis luego del apellido. La columna de “Total Dias” deberá se calculada
         *  vía programación en la grilla de datos.
         * * */
        $call = 'sgva_cartera_query_6b';
        $parts = array(
            'id_estado' => $id_estado,
            'nit' => $nit,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);

        if (isset($result['sgva_cartera_query_6bResult']['rEstCuenta3']['ControlR'])):
            $resultado = null;
        else:
            $resultado = array();
            if (array_key_exists('id_resolucion', $result['sgva_cartera_query_6bResult']['rEstCuenta3'])):
                $resultado[0] = $result['sgva_cartera_query_6bResult']['rEstCuenta3'];
            else:
                $resultado = $result['sgva_cartera_query_6bResult']['rEstCuenta3'];
            endif;
        endif;
//        echo "<pre>";
//        print_r($result);
//        echo "</pre>";
//        die();
        return $resultado;
    }

    function sgva_cartera_query_7($id_estado) {
        /** Permite consumir la información de los pagos de monetización realizados en el período de fiscalización. */
        $call = 'sgva_cartera_query_7';

        $parts = array(
            'id_estado' => $id_estado,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);
//        

        if (isset($result['sgva_cartera_query_7Result']['rEstCuenta5']['ControlR'])):
            $resultado = null;
        else:
            $resultado = array();
            if (array_key_exists('periodo_pago', $result['sgva_cartera_query_7Result']['rEstCuenta5'])):
                $resultado[0] = $result['sgva_cartera_query_7Result']['rEstCuenta5'];
            else:
                $resultado = $result['sgva_cartera_query_7Result']['rEstCuenta5'];
            endif;
//
        endif;

        return $resultado;
    }

    function sgva_cartera_query_9($id_estado, $year) {

        $call = 'sgva_cartera_query_9';
        $parts = array(
            'ano' => $year,
            'id_estado' => $id_estado,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);


        if (isset($result['sgva_cartera_query_9Result']['rEstCuenta6']['ControlR'])):
            $resultado = null;
        else:
            $resultado = array();
            if (array_key_exists('periodo_pago', $result['sgva_cartera_query_9Result']['rEstCuenta6'])):
                $resultado[0] = $result['sgva_cartera_query_9Result']['rEstCuenta6'];
            else:
                $resultado = $result['sgva_cartera_query_9Result']['rEstCuenta6'];
            endif;
//
        endif;

        return $resultado;
    }

    function CreaEmpresaSgva() {
        /* Función que permite crear empresas en el sgva cuando se crean al subir pila */
        $CI = & get_instance();
        $CI->load->model("Sgva_model");
        $model = new Sgva_model();
        $empresas = $model->EmpresasCartera();
        if ($empresas):
            foreach ($empresas as $datos_empresa):
              
                $empresa = array(
                    'empresa_nit' => $datos_empresa['NIT_EMPRESA'],
                    'empresa_RSocial' => $datos_empresa['NOMBRE_EMPRESA'],
                    'empresa_RepLegal' => $datos_empresa[''],
                    'empresa_email' => $datos_empresa['EMAIL'],
                    'empresa_telefono' => $datos_empresa['TELEFONO'],
                    'empresa_direccion' => $datos_empresa['DIRECCION'],
                    'empresa_ciudad' => $datos_empresa['COD_MUNICIPIO'],
                    'empresa_dpto' => $datos_empresa['COD_DEPARTAMENTO'],
                    'empresa_acteco' => $datos_empresa['CIIU'],
                    'empresa_regional' => $datos_empresa['COD_REGIONAL'],
                    'empresa_usuario_id' => ''
                );
            endforeach;

        else:
        endif;
        return TRUE;
    }

    function Confirmar_Pago($nit, $id_estado) {
        /* Función que permite informar al Sgva que un Estado de Cuenta ha sido cobrado y pagado. */
        $call = 'Confirmar_Pago';
        $parts = array(
            'nit' => $nit,
            'EstadoId' => $id_estado,
            'CodAc' => '5i5t3m4_5Gv4C'
        );
        $result = $this->soapclient->call($call, $parts);
        return $result['Confirmar_PagoResult'];
    }

    private function _manage_response($result, $is_fault, $is_error) {
        // Fallas
        if ($is_fault) {
            echo '<h2>Falla:</h2><pre>';
            print_r($result);
            echo '</pre>';
            echo '<h2>Request</h2><pre>' . htmlspecialchars($this->soapclient->request, ENT_QUOTES) . '</pre>';
            echo '<h2>Response</h2><pre>' . htmlspecialchars($this->soapclient->response, ENT_QUOTES) . '</pre>';
            echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->soapclient->debug_str, ENT_QUOTES) . '</pre>';
        } else {
            // Errores
            if ($is_error) {
                // Imprimir los detalles del error
                echo '<h2>Error:</h2><pre>' . $is_error . '</pre>';
                echo '<h2>Request</h2><pre>' . htmlspecialchars($this->soapclient->request, ENT_QUOTES) . '</pre>';
                echo '<h2>Response</h2><pre>' . htmlspecialchars($this->soapclient->response, ENT_QUOTES) . '</pre>';
                echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->soapclient->debug_str, ENT_QUOTES) . '</pre>';
            } else {
                // ¡Que felicidad, desplegamos el resultado!
                ob_clean();
                header("Content-Type:text/xml");
                echo "<pre>";
                print_r($result);
                echo "</pre>";
                //echo base64_decode($result);
            }
        }
        return;
    }

// end function
}
