<?php
/***
 * mandamientopago_pdf -- Vista para visualizar los documentos generados en PDF.
 *
 * @author		Human Team Technology QA
 * @author		Nicolas Gonzalez R. - nigondo@gmail.com
 * @version		1.1
 * @since		Enero de 2014
 */
    ob_start();    
    $this->load->library("tcpdf/tcpdf");
    try
    {        
        mandamientopago::pdf();
    }
    catch(TCPDF_exception $e) {
        echo $e;
        exit;
    }
?>
