<?php

/**
 * Verificarpagos (class MY_Controller) :)
 *
 * Verificaci�n de pagos
 *
 * Permite verificar los pagos recibidos de una deuda en proceso juridico, y envia el correo de notificaci�n al abogado asignado.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Verificarpagos
 */
class Verificarpagos extends MY_Controller {

    var $verificarpagos;

    function __construct() {

        parent::__construct();
        $this->load->model('verificarpagos_model');
        $this->load->helper("traza_fecha_helper");
        $this->verificarpagos = new Verificarpagos_model();
    }

    function index() {
        $nombre_fichero = realpath("./uploads/proceso_validacion_pago.txt");
        $fichero_texto = fopen($nombre_fichero, "r") or exit();
        if (filesize($nombre_fichero) > 0)
            $fecha = fread($fichero_texto, filesize($nombre_fichero));
        else
            $fecha = NULL;
        fclose($fichero_texto);
        if (!is_null($fecha))
            $this->verificarpagos->set_fecha($fecha);
        $pagos = $this->verificarpagos->get_pagos();
        if (!empty($pagos)) :
            $vencidos = $pagados = array();
            if (is_null($fecha))
                $fecha = date("Y-m-d", strtotime("" . date("Y-m-d") . "-5 year"));
            switch (ENVIRONMENT) :
                case 'development':
                    echo "<p>" . $fecha . "</p>"; //exit;
                    //echo "<pre>";print_r($pagos);exit("</pre>");
                    break;
                case 'testing':
                    break;
                case 'production':
                    break;
                default:
                    exit('El ambiente de la aplicación no está configurado correctamente.');
            endswitch;
            foreach ($pagos as $pago) :
                switch (ENVIRONMENT) :
                    case 'development':
                        echo "<pre>";
                        print_r($pago);
                        echo "</pre>" . $pago['DIAS_MORA'];
                        break;
                    case 'testing':
                        break;
                    case 'production':
                        break;
                    default:
                        exit('El ambiente de la aplicación no está configurado correctamente.');
                endswitch;
                if ($pago['DIAS_MORA'] > 0 and $pago['VALOR_DEUDA_ACTUAL'] > 0) :
                    $vencidos[] = $pago;
                else :
                    $pagados[] = $pago;
                endif;
            endforeach;
        endif;
        if (!empty($pagados)) :
            flush();
            ob_flush();
            $this->envia_correos($pagados);
        endif;
        $fecha = date("Y-m-d");
        $fichero_texto = fopen($nombre_fichero, "w+") or exit();
        $write = fputs($fichero_texto, $fecha);
        fclose($fichero_texto);
    }

    private function envia_correos($pagos) {
        if (!empty($pagos)) :
            $data = $cod_expedientes = array();
            foreach ($pagos as $pago) :
                $mensaje = 'Señor(a) ' . $pago['NOMBRES'];
                $mensaje .= "<br><br>";
                $mensaje .= "Se ha registrado el siguiente pago de la empresa y/o persona: " . $pago['EJECUTADO'];
                $mensaje .= "<br><br><strong>Datos del proceso:</strong><br><br>";
                $mensaje .= "- Proceso: " . $pago['COD_PROCESO_COACTIVO'];
                $mensaje .= "<br>- Número de título: " . $pago['NUM_LIQUIDACION'];
                $mensaje .= "<br>- Fecha del proceso: " . $pago['FECHA_PROCESO'];
                $mensaje .= "<br>- Concepto: " . $pago['CONCEPTO'];
                $mensaje .= "<br>- Identificación del tercero: " . $pago['IDENTIFICACION'];
                $mensaje .= "<br>- Razón social: " . $pago['EJECUTADO'];
                $mensaje .= "<br>- Dirección: " . $pago['DIRECCION'];
                $mensaje .= "<br>- Teléfono(s): " . $pago['TELEFONO'];
                $mensaje .= "<br><br><strong>Datos del pago:</strong><br><br>";
                $mensaje .= "<br>- Fecha de pago: " . $pago['FECHA_PAGO'];
                $mensaje .= "<br>- Fecha de Aplicación: " . $pago['FECHA_APLICACION'];
                $mensaje .= "<br>- Valor pagado: $ " . number_format($pago['VALOR_PAGADO'], 0, ",", ".");
                $mensaje .= "<br>- Distribución capital: $ " . number_format($pago['DISTRIBUCION_CAPITAL'], 0, ",", ".");
                $mensaje .= "<br>- Distribución intereses corrientes: $ " . number_format($pago['DISTRIBUCION_INTERES'], 0, ",", ".");
                $mensaje .= "<br>- Distribución intereses de mora: $ " . number_format($pago['DISTRIBUCION_INTERES_MORA'], 0, ",", ".");
                $x = 1;
                $enviado = false;
                while ($enviado == false and $x <= 3) :
                    $enviado = enviarcorreosena($pago['EMAIL'], $mensaje, "Registro de pago, obligación No. " . $pago['COD_PROCESO_COACTIVO'], "", "", "");
                    $x++;
                endwhile;
                if (!in_array($pago['NO_EXPEDIENTE'], $cod_expedientes[$pago['COD_PROCESO_COACTIVO']])) :
                    $cod_expedientes[$pago['COD_PROCESO_COACTIVO']][] = $pago['NO_EXPEDIENTE'];
                endif;
            endforeach;
            foreach ($cod_expedientes as $cpc => $cod) :
                $this->verificarpagos->SetProceso($cpc);
                $auto = $this->crearAutosCierre();
                foreach ($cod as $cod_exp) :
                    $data[] = array("COD_RECEPCIONTITULO" => $cod_exp, "CERRADO" => 1, "NUM_AUTOGENERADO" => $auto);
                endforeach;
                if (!empty($data)) :
                    $this->verificarpagos->ActualizarTitulos($data);
                endif;
            endforeach;
            switch (ENVIRONMENT) :
                case 'development':
                case 'testing':
                    echo $mensaje;
                    break;
                case 'production':
                    break;
                default:
                    exit('El ambiente de la aplicación no está configurado correctamente.');
            endswitch;
        endif;
    }

    public function crearAutosCierre($codfiscalizacion = FALSE, $titulos = array()) {
        //print_r($titulos);die();
        if ($codfiscalizacion === FALSE) :
            $procesos = $this->verificarpagos->obtenerFiscalizacion();
            if (!empty($procesos)) :
                $cod_gestioncobro = trazarProcesoJuridico(436, 1132, "", $procesos['COD_PROCESO_COACTIVO'], "", "", "", "Auto se genera porque deuda esta en cero.", $procesos['IDENTIFICACION'], TRUE);
                $data = array("COD_TIPO_AUTO" => "1", "COD_TIPO_PROCESO" => "1", "COD_PROCESO_COACTIVO" => $procesos['COD_PROCESO_COACTIVO'], "COD_ESTADOAUTO" => "7",
                    "CREADO_POR" => "2", "ASIGNADO_A" => $procesos['ABOGADO'], "COD_GESTIONCOBRO" => $cod_gestioncobro
                );
                $auto = $this->verificarpagos->crearAuto($data);
                return $auto;
            endif;
        else :
            if ($this->ion_auth->logged_in()) :
              //  echo 1; die();
           
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/index') || $this->ion_auth->in_menu('verificarpagos/crearAutosCierre') ) :
                     //   echo 2; die();
                    if (empty($titulos)) :
                        return false;
                    else :
                        $proceso = $this->verificarpagos->obtenerFiscalizacion($codfiscalizacion);
                        if (!empty($proceso)) :
                            $cod_gestioncobro = trazarProcesoJuridico(436, 1132, "", $proceso['COD_PROCESO_COACTIVO'], "", "", "");
                            $data = array("COD_TIPO_AUTO" => "1", "COD_TIPO_PROCESO" => "1", "COD_PROCESO_COACTIVO" => $proceso['COD_PROCESO_COACTIVO'], "COD_ESTADOAUTO" => "7",
                                "CREADO_POR" => $proceso['ABOGADO'], "ASIGNADO_A" => $proceso['ABOGADO'], "COD_GESTIONCOBRO" => $cod_gestioncobro
                            );
                            $auto = $this->verificarpagos->crearAuto($data);
                            foreach ($titulos as $titulo) :
                                $dato[] = array("COD_RECEPCIONTITULO" => $titulo, "CERRADO" => 1, "NUM_AUTOGENERADO" => $auto);
                            endforeach;
                            $dato = array('TITULOS' => $dato);
                            $data = array_merge($dato, $data);

                            if (!empty($data)) :
                                $this->verificarpagos->ActualizarTitulos($data);
                            endif;
                            return $auto;
                        endif;
                    endif;
                else :
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                    redirect(base_url() . 'index.php/inicio');
                endif;
            else :
                redirect(base_url() . 'index.php/auth/login');
            endif;
        endif;
    }

}

/* End of file verificarpagos.php */
/* Location: ./system/application/controllers/verificarpagos.php */