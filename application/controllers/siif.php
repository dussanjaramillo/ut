<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. 
 */

class Siif extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('siif_model', '', TRUE);
    }

    function generar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('siif/generar')) {
                $message = $this->session->flashdata('message');
                if ($message)
                    $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $message . '</div>';
                $this->template->set('title', 'Informe SIIF');
                $cuentas = $this->siif_model->cuentassiif();
                $x = 0;
                foreach ($cuentas->result_array as $value) {
                    $opciones[$x]['cuenta'] = $value['CUENTA'];
                    $opciones[$x]['descripcion'] = $value['NOMBREBANCO'] . " - " . $value['DESCRIPCION'];
                    $x++;
                }
                $this->data['cuentas'] = $opciones;
                $this->template->load($this->template_file, 'siif/generar_siif', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function exportar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('siif/generar')) {
                $conceptos = array();
                $codigos = array();
                $path = "uploads/siif/";
                $ahora = date('Ymdhis');
                mkdir($path . $ahora, 0777);
                $fecha = $this->input->post('fecha');
                $responsable = strtoupper($this->input->post('responsable'));
                $cuenta = $this->input->post('cuenta');
                $documento = $this->input->post('documento');
                $reciprocas = $this->input->post('reciprocas');

                $conceptosxcuenta = $this->siif_model->conceptosxcuenta($cuenta);

                if (sizeof($conceptosxcuenta->result_array()) > 0) {
                    $x = 0;
                    foreach ($conceptosxcuenta->result_array() as $value) {
                        $conceptos[$x]['concepto'] = $value['COD_CONCEPTO_RECAUDO'];
                        $conceptos[$x]['procedencia'] = $value['PROCEDENCIA'];
                        $x++;
                    }
                    $pagos = $this->siif_model->traerpagos($fecha, $conceptos, $reciprocas);
                    $lista = $pagos->result_array();
                    if (sizeof($lista) > 0) {
                        $codsiif = $this->siif_model->codsiif();
                        foreach ($codsiif->result_array() as $cod) {
                            $codigos[$cod['COD_CONCEPTO_RECAUDO']]['PCI'] = ($cod['PCI']) ? $cod['PCI'] : '';
                            $codigos[$cod['COD_CONCEPTO_RECAUDO']]['CODIGO'] = ($cod['CODIGO_SIIF']) ? $cod['CODIGO_SIIF'] : '';
                            $codigos[$cod['COD_CONCEPTO_RECAUDO']]['DESCRIPCION'] = ($cod['DESCRIPCION_SIIF']) ? $cod['DESCRIPCION_SIIF'] : '';
                            $pci = explode('-', $codigos[$cod['COD_CONCEPTO_RECAUDO']]['PCI']);
                            $size = sizeof($pci) - 1;
                            unset($pci[$size]);
                            $codigos[$cod['COD_CONCEPTO_RECAUDO']]['ORIGEN'] = implode('-', $pci);
                        }

                        $maestro = '';
                        $detalle = '';
                        $count = 0;
                        for ($x = 1; $x <= count($lista); $x++) {
                            for ($reg = 1; $reg <= 200; $reg++) {
                                if ($x <= count($lista)) {
                                    $j = ($x - 1);
                                    $value = $lista[$j];
                                    $date = new DateTime($value['FECHA_PAGO']);

                                    $maestro .= $reg . '|';
                                    $maestro .= $codigos[$value['COD_SUBCONCEPTO']]['PCI'] . '|';
                                    $maestro .= $documento . '|CNT|';
                                    $maestro .= $codigos[$value['COD_SUBCONCEPTO']]['ORIGEN'] . '|';
                                    $maestro .= substr($fecha, -4) . '|';
                                    $maestro .= $date->format('Y-m-d') . '|';
                                    $maestro .= $this->siif_model->tipoidentificacion($value['NITEMPRESA']) . '|';
                                    $maestro .= $value['NITEMPRESA'] . '|';
                                    $maestro .= '||';
                                    $maestro .= $value['VALOR_PAGADO'] . '|';
                                    $maestro .= '02|01|8|';
                                    $maestro .= $cuenta . '|';
                                    $maestro .= $date->format('Y-m-d') . '|';
                                    $maestro .= '11|';
                                    $maestro .= $responsable . '|';
                                    $maestro .= 'GRUPO DE RECAUDO Y CARTERA|';
                                    $maestro .= $codigos[$value['COD_SUBCONCEPTO']]['DESCRIPCION'] . '|';
                                    $maestro .= '|';
                                    $maestro .= "\r\n";

                                    $detalle .= $reg . '|' . $reg . '|';
                                    $detalle .= '|';
                                    $detalle .= trim($codigos[$value['COD_SUBCONCEPTO']]['CODIGO']) . '|';
                                    $detalle .= '|';
                                    $detalle .= $value['VALOR_PAGADO'] . '|';
                                    $detalle .= "\r\n";

                                    if ($reg != 200)
                                        $x++;
                                }
                            }
                            $count++;
                            $ar = fopen($path . $ahora . "/MSIIF-" . trim($cuenta) . "-" . str_replace("/", "", $fecha) . "-" . $count . ".TXT", "x") or die("Problemas en la creacion");
                            fputs($ar, $maestro);
                            fclose($ar);

                            $ar = fopen($path . $ahora . "/DSIIF" . trim($cuenta) . "-" . str_replace("/", "", $fecha) . "-" . $count . ".TXT", "x") or die("Problemas en la creacion");
                            fputs($ar, $detalle);
                            fclose($ar);

                            $maestro = '';
                            $detalle = '';
                        }

                        $archivo = trim($cuenta) . "-" . str_replace("/", "", $fecha) . "-" . $ahora;
                        $command = 'zip ' . $path . $archivo . " " . $path . $ahora . "/*";
                        $cmd = exec($command);
                        rmdir($path . $ahora);

                        ob_clean();

                        header('Content-Description: Descargar Archivo');
                        header("Content-type: application/octet-stream");
                        header("Content-Length: " . filesize($path . $archivo . ".zip"));
                        header('Content-Disposition: attachment;filename="' . $archivo . '.zip"');
                        header("Content-Type: application/force-download");
                        header("Content-Transfer-Encoding: binary");
                        readfile($path . $archivo . ".zip");
                        die();
                    }else {
                        $this->session->set_flashdata('message', 'No existen pagos con los datos suministrados');
                        redirect('siif/generar/');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Esta cuenta no tiene asociado ningun concepto');
                    redirect('siif/generar/');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function cuentasbancarias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('siif/cuentasbancarias')) {
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->data['cuentas'] = $this->siif_model->cuentassiif();
                $this->template->load($this->template_file, 'siif/cuentasbancarias', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function adicionarcuenta() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('siif/cuentasbancarias')) {
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->load->library('form_validation');
                $this->data['bancos'] = $this->siif_model->bancos();
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('cuenta', 'Número de Cuenta', 'required|trim|xss_clean|max_length[30]');
                $this->form_validation->set_rules('banco', 'Entidad Bancaria', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $data = array(
                        'NUMERO_CUENTA' => set_value('cuenta'),
                        'IDBANCO' => set_value('banco'),
                        'DESCRIPCION' => set_value('descripcion'),
                        'ACTIVO' => '1',
                    );

                    try {
                        $add = $this->siif_model->adicionacuenta($data);
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La aplicación se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/siif/cuentasbancarias');
                    } catch (Exception $ex) {
                        $this->data['custom_error'] = '<div class="form_error"><p>Ocurrio un error.</p><p>' . $ex . '</p></div>';
                    }
                }
                $this->template->load($this->template_file, 'siif/cuenta_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function conceptos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('siif/cuentasbancarias')) {
                $id = $this->uri->segment(3);
                $this->data['conceptos'] = $this->siif_model->conceptos();
                $this->data['conceptosxcuenta'] = $this->siif_model->traeconceptoscuenta($id);

                $this->load->view('siif/conceptos_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function siifTerceros() {
        $path = "uploads/siif/";
        $fecha = date('Ymd');
        $archivo = 'SIIFTerceros-20141021';
        ob_clean();
        header('Content-Description: Descargar Archivo');
        header("Content-type: application/octet-stream");
        header("Content-Length: " . filesize($path . $archivo . ".zip"));
        header('Content-Disposition: attachment;filename="' . $archivo . '.zip"');
        header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        readfile($path . $archivo . ".zip");
        die();
    }

}
