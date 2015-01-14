<?php

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : todo el proceso de medidas cautelares embargo y bienes
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
class Mcinvestigacion extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation','tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper','template_helper'));
        $this->load->model('titulo_model', '', TRUE);
        $this->load->model('Mcinvestigacion_model', '', TRUE);
        $this->load->model('numeros_letras_model');
        $this->load->library('tcpdf/tcpdf.php');

        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/tinymce/tinymce.jquery.min.js',
            'js/ajaxfileupload.js',
            'js/validCampoFranz.js',
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
        );
//SECUENCIA
//AUTO
        define("AUTO1", "Resoluci&oacute;n Decretando Medidas Cautelares"); //AUTOS
        define("INICIO_PROCESO", "204");
        define("INICIO_SECRETARIO", "276");
        define("INICIO_COORDINADOR", "277");
        define("SUBIR_ARCHIVO", "619");
        define("DEVOLUCION", "278");
        define("ENVIAR_MODIFICACIONES", "1111111");
//OFICIO
//Oficio Orden de Investigacion y Envargo de Dinero
        define("OFICIO", "Oficio de investigación de bienes");
        define("ARCHIVO_APROBADO", "279");
        define("OFICIO_SECRETARIO", "280");
        define("OFICIO_COORDINADOR", "281");
        define("OFICIO_SUBIR_ARCHIVO", "620");
        define("OFICIO_DEVOLUCION", "282");
        define("OFICIO_ENVIAR_MODIFICACIONES", "1111112");
//Oficio Orden de Investigacion y Envargo de Dinero // en caso de busqueda de mas dineros 
        define("OFICIO2", "(Reiteraci&oacute;n) Oficio de Orden de Investigaci&oacute;n y Embargo de Dineros");
        define("ARCHIVO_APROBADO2", "1126");
        define("OFICIO_SECRETARIO2", "1127");
        define("OFICIO_COORDINADOR2", "1128");
        define("OFICIO_SUBIR_ARCHIVO2", "1130");
        define("OFICIO_DEVOLUCION2", "1129");
        define("OFICIO_ENVIAR_MODIFICACIONES2", "1111110");
//OTRO
        define("RESPUESTA1", "Respuesta de Oficios Recibidos");
        define("RESPUESTA2", "Respuesta Entidad Bancaria");
        define("RESPUESTA3", "RESPUESTA");
        define("RESPUESTA", "RESPUESTA");
        define("OFICIO_APROBADO", "283");
        define("APRUEBA_DINEROS_BANCOS", "284");
        define("APRUEBA_DINEROS_BANCOS_CONFIRMAR", "285");


        define("REMANENTES1", "16");
//auto de levantamiento de medidas cautelarias
        define("AUTO2", "Auto de Levantamiento de Embargo");
        define("AUTO_LEVANTAMIENTO", "290");
        define("AUTO_INICIO_SECRETARIO", "291");
        define("AUTO_INICIO_COORDINADOR", "292");
        define("AUTO_SUBIR_ARCHIVO", "621");
        define("AUTO_DEVOLUCION", "293");
        define("AUTO_ENVIAR_MODIFICACIONES", "1111113");
//OFICIO
        define("AUTO_ARCHIVO_APROBADO", "287"); // omitir dependiendo la desuda y 
        define("FRACCIONAMIENTO", "Oficio de Fraccionamiento de Titulos");
        define("GENERAR_FRACCIONAMIENTO", "294");
        define("FRACCIONAMIENTO_INICIO_SECRETARIO", "295");
        define("FRACCIONAMIENTO_INICIO_COORDINADOR", "296");
        define("FRACCIONAMIENTO_SUBIR_ARCHIVO", "622");
        define("FRACCIONAMIENTO_DEVOLUCION", "297");
        define("FRACCIONAMIENTO_ENVIAR_MODIFICACIONES", "1111114");

//Generar oficio bienes
        define("OFICIO1", "(Reiteraci&oacute;n) Generar Oficio Bienes");
        define("OFICIO_BIENES", "286");
        define("FRACCIONAMIENTO_ARCHIVO_APROBADO", "298");
        define("OFICIO_BIENES_INICIO_SECRETARIO", "311");
        define("OFICIO_BIENES_INICIO_COORDINADOR", "312");
        define("OFICIO_BIENES_SUBIR_ARCHIVO", "624");
        define("OFICIO_BIENES_DEVOLUCION", "313");
        define("OFICIO_BIENES_ENVIAR_MODIFICACIONES", "1111115");


        define("APROVACION_BIENES_GENERALES", "625");
//permisos
        define("ABOGADO", "43"); // id de la tabla uduario_gurpos para saber el secretario
        define("SECRETARIO", "41"); // id de la tabla uduario_gurpos para saber el secretario
        define("COORDINADOR", "42"); // id de la tabla uduario_gurpos para saber el secretario
//MENSAJES
        define("ERROR", "ERROR DE BASES DE DATOS");
        define("REMANENTES", "REMANENTES");
        define("PRELACION", "Embargo Efectivo");
//
//RUTAS
        define("RUTA_INI", "./uploads/MC");
        define("RUTA_DES", "uploads/MC/COD_");
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
//        echo "<pre>";
//        print_r($this->data['user']);
//RECORTES
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");



//bienes y servicio
//muebles
        define("MUEBLES_NUM", "6"); //AUTOS
        define("MUEBLES", "Auto Diligencia de Secuestro (Mueble)"); //AUTOS
        define("DILIGENCIA_GENERAL", "Auto Diligencia de Secuestro"); //AUTOS
        define("MUEBLES_INICIO", "318");
        define("MUEBLES_SECRETARIO", "364");
        define("MUEBLES_COORDINARO", "365");
        define("MUEBLES_SUBIR_ARCHIVO", "627");
        define("MUEBLES_DEVOLUCION", "367");
        define("MUEBLES_ENVIAR_MODIFICACIONES", "1111116");

//comisionar
        define("MUEBLE_COMISIONAR1", "663");

        define("MUEBLES_COM_SECUESTRO_NUM", "20");
        define("MUEBLES_COM_SECUESTRO", "Auto de Comisi&oacute;n Generado (Muebles)");
        define("MUEBLES_COM_SECUESTRO_INICIO", "664");
        define("MUEBLES_COM_SECUESTRO_SECRETARIO", "337");
        define("MUEBLES_COM_SECUESTRO_COORDINARO", "338");
        define("MUEBLES_COM_SECUESTRO_SUBIR_ARCHIVO", "340");
        define("MUEBLES_COM_SECUESTRO_DEVOLUCION", "339");
        define("MUEBLES_COM_SECUESTRO_ENVIAR_MODIFICACIONES", "1111117");

        define("MUEBLES_DES_SECUESTRO_NUM", "21");
        define("MUEBLES_DES_SECUESTRO", "Proyectar Despacho Comisorio (Mueble)");
        define("MUEBLES_DES_SECUESTRO_INICIO", "665");
        define("MUEBLES_DES_SECUESTRO_SECRETARIO", "341");
        define("MUEBLES_DES_SECUESTRO_COORDINARO", "342");
        define("MUEBLES_DES_SECUESTRO_SUBIR_ARCHIVO", "344");
        define("MUEBLES_DES_SECUESTRO_DEVOLUCION", "343");
        define("MUEBLES_DES_SECUESTRO_ENVIAR_MODIFICACIONES", "1111118");

        define("RTA_MUEBLE_COMISIONAR1", "668");

        define("MUEBLES_RESPUESTA_NUM", "35");
        define("MUEBLES_RESPUESTA", "Solicitar Documento (Mueble)");
        define("MUEBLES_RESPUESTA_INICIO", "666");
        define("MUEBLES_RESPUESTA_SECRETARIO", "353");
        define("MUEBLES_RESPUESTA_COORDINARO", "667");
        define("MUEBLES_RESPUESTA_SUBIR_ARCHIVO", "354");
        define("MUEBLES_RESPUESTA_DEVOLUCION", "355");
        define("MUEBLES_RESPUESTA_ENVIAR_MODIFICACIONES", "1111119");

        define("MUEBLES_RPESENTA_NUM", "31");
        define("MUEBLES_RPESENTA", "Subir Documento Auto de Comisi&oacute;n (Muebles)");
        define("MUEBLES_RPESENTA_SUBIR_ARCHIVO", "669");

        define("MUEBLES_COMISORIO_NUM", "36");
        define("MUEBLES_COMISORIO", "Auto Incorporando Despacho Comisorio (Muebles)");
        define("MUEBLES_COMISORIO_INICIO", "670");
        define("MUEBLES_COMISORIO_SECRETARIO", "349");
        define("MUEBLES_COMISORIO_COORDINARO", "350");
        define("MUEBLES_COMISORIO_SUBIR_ARCHIVO", "352");
        define("MUEBLES_COMISORIO_DEVOLUCION", "351");
        define("MUEBLES_COMISORIO_ENVIAR_MODIFICACIONES", "1111120");

        define("MUEBLES_FECHA_NUM", "37");
        define("MUEBLES_FECHA", "Proyectar Comunicaci&oacute;n al Deudor Incorporando Diligencia al Expediente y Fijar Fecha y Hora de Secuestro (Mueble)");
        define("MUEBLES_FECHA_INICIO", "671");
        define("MUEBLES_FECHA_SECRETARIO", "357");
        define("MUEBLES_FECHA_COORDINARO", "672");
        define("MUEBLES_FECHA_SUBIR_ARCHIVO", "358");
        define("MUEBLES_FECHA_DEVOLUCION", "359");
        define("MUEBLES_FECHA_ENVIAR_MODIFICACIONES", "1111121");

        define("MUEBLES_DILIGENCIA_NUM", "41");
        define("MUEBLES_DILIGENCIA", "Auto de Fecha y Hora de Diligencia de Secuestro (Mueble)");
        define("MUEBLES_DILIGENCIA_INICIO", "673");
        define("MUEBLES_DILIGENCIA_SECRETARIO", "953");
        define("MUEBLES_DILIGENCIA_COORDINARO", "954");
        define("MUEBLES_DILIGENCIA_SUBIR_ARCHIVO", "956");
        define("MUEBLES_DILIGENCIA_DEVOLUCION", "955");
        define("MUEBLES_DILIGENCIA_ENVIAR_MODIFICACIONES", "1111141");

        define("MUEBLES_ORDEN_NUM", "48");
        define("MUEBLES_ORDEN", "Auto Ordenando Levantamiento de Medidas (Mueble)");
        define("MUEBLES_ORDEN_INICIO", "996");
        define("MUEBLES_ORDEN_SECRETARIO", "992");
        define("MUEBLES_ORDEN_COORDINARO", "993");
        define("MUEBLES_ORDEN_SUBIR_ARCHIVO", "995");
        define("MUEBLES_ORDEN_DEVOLUCION", "994");
        define("MUEBLES_ORDEN_ENVIAR_MODIFICACIONES", "1111148");


        define("MUEBLES_LEVANTAR_ACTA_NUM", "42");
        define("MUEBLES_LEVANTAR_ACTA", "Subir Documento Acta en Diligencia de Secuestro (Mueble)");
        define("MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO", "957");
        define("MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2", "999");

        define("MUEBLES_DOCUMENTO_ACTA_NUM", "49");
        define("MUEBLES_DOCUMENTO_ACTA", "Subir Documento Avaluo Dentro de la Diligencia de Secuestro  Aprobado y Firmado");
        define("MUEBLES_DOCUMENTO_ACTA_SUBIR_ARCHIVO", "997");

        define("MUEBLE_OPOSICION", "960");
        define("OPOSICION", "OPOSICI&Oacute;N");

        define("MUEBLE_FAVORABLE2", "998");

// SIN OPOSICION 
        define("VIKY_OPOSICION", "617");
// Envio Avaluo
        define("VIKY_AVALUO", "1011");

        define("MUEBLES_PROYECTAR_AUTO_NUM", "43");
        define("MUEBLES_PROYECTAR_AUTO", "Proyectar Auto de Apertura de Pruebas (Mueble)");
        define("MUEBLES_PROYECTAR_AUTO_INICIO", "972");
        define("MUEBLES_PROYECTAR_AUTO_SECRETARIO", "961");
        define("MUEBLES_PROYECTAR_AUTO_COORDINARO", "965");
        define("MUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO", "964");
        define("MUEBLES_PROYECTAR_AUTO_DEVOLUCION", "963");
        define("MUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES", "1111143");

        define("MUEBLES_PROYECTAR_RESPUESTA_NUM", "44");
        define("MUEBLES_PROYECTAR_RESPUESTA", "Proyectar Respuesta al Auto de Oposici&oacute;n a la Diligencia del Secuestro Muebles");
        define("MUEBLES_PROYECTAR_RESPUESTA_INICIO", "973");
        define("MUEBLES_PROYECTAR_RESPUESTA_SECRETARIO", "966");
        define("MUEBLES_PROYECTAR_RESPUESTA_COORDINARO", "967");
        define("MUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO", "969");
        define("MUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION", "968");
        define("MUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES", "1111144");


        define("MUEBLE_FAVORABLE", "971");
        define("FAVORABLE", "FAVORABLE AL DEUDOR");

        define("VIKY_FAVORABLE", "378");
//&oacute;Insistencia Perseguir Derechos?
        define("MUEBLE_INSISTIR", "974");
        define("INSISTIR", "¿Insistencia Perseguir Derechos?");

// EXISTE OPOSICION AL SECUESTRO
//Inmuebles

        define("INMUEBLES_NUM", "7");
        define("INMUEBLES", "Oficio de Solicitud de Registro de Embargo (Inmueble)");
        define("INMUEBLES_INICIO", "647");
        define("INMUEBLES_SECRETARIO", "320"); // estaba en 320 pero el cliente dijo que solo lo queria en 2 pasos
        define("INMUEBLES_COORDINARO", "321"); ///////////////////////////////////////////////////////
        define("INMUEBLES_SUBIR_ARCHIVO", "649");
        define("INMUEBLES_DEVOLUCION", "322");
        define("INMUEBLES_ENVIAR_MODIFICACIONES", "1111123");

        define("PRESETO", "SE PRESENTO");
        define("INMUEBLES_OK", "675");
        define("INMUEBLE_EMBARGO", "111112");
        define("AUTO_FECHA_HORA", "300");

        define("INMUEBLES_EMBARGO_NUM", "12");
        define("INMUEBLES_EMBARGO", "Oficio Informando Embargo de Remanentes o de Prelaci&oacute;n de Cr&eacute;ditos (Inmueble)");
        define("INMUEBLES_EMBARGO_INICIO", "676");
        define("INMUEBLES_EMBARGO_SECRETARIO", "324");
        define("INMUEBLES_EMBARGO_COORDINARO", "325");
        define("INMUEBLES_EMBARGO_SUBIR_ARCHIVO", "325");
        define("INMUEBLES_EMBARGO_DEVOLUCION", "326");
        define("INMUEBLES_EMBARGO_ENVIAR_MODIFICACIONES", "1111124");

        define("INMUEBLES_SECUESTRO_NUM", "16");
        define("INMUEBLES_SECUESTRO", "Auto de Diligencia de Secuestro (Inmueble)");
        define("INMUEBLES_SECUESTRO_INICIO", "678");
        define("INMUEBLES_SECUESTRO_SECRETARIO", "333");
        define("INMUEBLES_SECUESTRO_COORDINARO", "334");
        define("INMUEBLES_SECUESTRO_SUBIR_ARCHIVO", "336");
        define("INMUEBLES_SECUESTRO_DEVOLUCION", "335");
        define("INMUEBLES_SECUESTRO_ENVIAR_MODIFICACIONES", "1111126");

        define("INMUEBLES_FECHA_NUM", "15");
        define("INMUEBLES_FECHA", "Auto de Fecha y Hora de Diligencia de Secuestro (Inmueble)");
        define("INMUEBLES_FECHA_INICIO", "677");
        define("INMUEBLES_FECHA_SECRETARIO", "360");
        define("INMUEBLES_FECHA_COORDINARO", "361");
        define("INMUEBLES_FECHA_SUBIR_ARCHIVO", "363");
        define("INMUEBLES_FECHA_DEVOLUCION", "362");
        define("INMUEBLES_FECHA_ENVIAR_MODIFICACIONES", "1111125");


        define("INMUEBLES_LEVANTAR_ACTA_NUM", "45");
        define("INMUEBLES_LEVANTAR_ACTA", "Subir Documento Acta en Diligencia de secuestro (Inmueble)");
        define("INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO", "978");




///////////////////////////////////////////////////////////////////////////////////
        define("INMUEBLE_OPOSICION", "979");

        define("INMUEBLES_PROYECTAR_AUTO_NUM", "46");
        define("INMUEBLES_PROYECTAR_AUTO", "Proyectar Auto de Apertura de Pruebas (Inmueble)");
        define("INMUEBLES_PROYECTAR_AUTO_INICIO", "980");
        define("INMUEBLES_PROYECTAR_AUTO_SECRETARIO", "981");
        define("INMUEBLES_PROYECTAR_AUTO_COORDINARO", "982");
        define("INMUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO", "984");
        define("INMUEBLES_PROYECTAR_AUTO_DEVOLUCION", "983");
        define("INMUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES", "1111146");

        define("INMUEBLES_PROYECTAR_RESPUESTA_NUM", "47");
        define("INMUEBLES_PROYECTAR_RESPUESTA", "Proyectar Respuesta al Auto de Oposici&oacute;n a la Diligencia del Secuestro");
        define("INMUEBLES_PROYECTAR_RESPUESTA_INICIO", "985");
        define("INMUEBLES_PROYECTAR_RESPUESTA_SECRETARIO", "986");
        define("INMUEBLES_PROYECTAR_RESPUESTA_COORDINARO", "987");
        define("INMUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO", "989");
        define("INMUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION", "988");
        define("INMUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES", "1111147");


        define("INMUEBLE_FAVORABLE", "1003");
//&oacute;Insistencia Persegir Derechos?
        define("INMUEBLE_INSISTIR", "1002");
        define("INMUEBLE_FAVORABLE2", "1001");
        define("FAVORABLE_SENA", "FAVORABLE AL SENA");

        define("INMUEBLES_DOCUMENTO_ACTA_NUM", "50");
        define("INMUEBLES_DOCUMENTO_ACTA", "Subir Documento Avaluo Dentro de la Diligencia de Secuestro  Aprobado y Firmado");
        define("INMUEBLES_DOCUMENTO_ACTA_SUBIR_ARCHIVO", "1000");

        define("INMUEBLES_ORDEN_NUM", "51");
        define("INMUEBLES_ORDEN", "Auto Ordenando Levantamiento de Medidas (Inmueble)");
        define("INMUEBLES_ORDEN_INICIO", "1004");
        define("INMUEBLES_ORDEN_SECRETARIO", "1005");
        define("INMUEBLES_ORDEN_COORDINARO", "1006");
        define("INMUEBLES_ORDEN_SUBIR_ARCHIVO", "1008");
        define("INMUEBLES_ORDEN_DEVOLUCION", "1007");
        define("INMUEBLES_ORDEN_ENVIAR_MODIFICACIONES", "1111151");

        define("INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2", "1009");

//////////////////////////////////////////////////////////////////////////////////
//        Pendiente  no c porque sale 
        define("INMUEBLES_AUTO_SECUESTRO_NUM", "17");
        define("INMUEBLES_AUTO_SECUESTRO", "Auto Diligencia secuestro (Inmueble)");
        define("INMUEBLES_AUTO_SECUESTRO_INICIO", "300");
        define("INMUEBLES_AUTO_SECUESTRO_SECRETARIO", "301");
        define("INMUEBLES_AUTO_SECUESTRO_COORDINARO", "302");
        define("INMUEBLES_AUTO_SECUESTRO_SUBIR_ARCHIVO", "303");
        define("INMUEBLES_AUTO_SECUESTRO_DEVOLUCION", "304");
        define("INMUEBLES_AUTO_SECUESTRO_ENVIAR_MODIFICACIONES", "1111127");

//COMISIONAR
        define("INMUEBLE_COMISIONAR1", "679");

        define("INMUEBLES_COMISION_SECUESTRO_NUM", "18");
        define("INMUEBLES_COMISION_SECUESTRO", "Auto de Comisi&oacute;n (Inmueble)");
        define("INMUEBLES_COMISION_SECUESTRO_INICIO", "684");
        define("INMUEBLES_COMISION_SECUESTRO_SECRETARIO", "680");
        define("INMUEBLES_COMISION_SECUESTRO_COORDINARO", "681");
        define("INMUEBLES_COMISION_SECUESTRO_SUBIR_ARCHIVO", "683");
        define("INMUEBLES_COMISION_SECUESTRO_DEVOLUCION", "682");
        define("INMUEBLES_COMISION_SECUESTRO_ENVIAR_MODIFICACIONES", "1111128");

        define("INMUEBLES_DESP_COM_SECUESTRO_NUM", "19");
        define("INMUEBLES_DESP_COM_SECUESTRO", "Proyectar Despacho Comisorio (Inmueble)");
        define("INMUEBLES_DESP_COM_SECUESTRO_INICIO", "689");
        define("INMUEBLES_DESP_COM_SECUESTRO_SECRETARIO", "685");
        define("INMUEBLES_DESP_COM_SECUESTRO_COORDINARO", "686");
        define("INMUEBLES_DESP_COM_SECUESTRO_SUBIR_ARCHIVO", "688");
        define("INMUEBLES_DESP_COM_SECUESTRO_DEVOLUCION", "687");
        define("INMUEBLES_DESP_COM_SECUESTRO_ENVIAR_MODIFICACIONES", "1111129");


        define("INMUEBLES_DOCUMENTO_SECUESTRO_NUM", "25");
        define("INMUEBLES_DOCUMENTO_SECUESTRO", "Solicitud de Informe Comisorio (Inmueble)");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_INICIO", "694");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_SECRETARIO", "690");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_COORDINARO", "695");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_SUBIR_ARCHIVO", "691");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_DEVOLUCION", "692");
        define("INMUEBLES_DOCUMENTO_SECUESTRO_ENVIAR_MODIFICACIONES", "1111130");

        define("INMUEBLES_RPESENTA_NUM", "31");
        define("INMUEBLES_RPESENTA", "Subir Documento Inmuebles Auto de Comisi&oacute;n Aprobado y Firmado");
        define("INMUEBLES_RPESENTA_SUBIR_ARCHIVO", "696");

        define("INMUEBLES_COMISORIO_SECUESTRO_NUM", "32");
        define("INMUEBLES_COMISORIO_SECUESTRO", "Auto Incorporando Despacho Comisorio (Inmueble)");
        define("INMUEBLES_COMISORIO_SECUESTRO_INICIO", "701");
        define("INMUEBLES_COMISORIO_SECUESTRO_SECRETARIO", "697");
        define("INMUEBLES_COMISORIO_SECUESTRO_COORDINARO", "698");
        define("INMUEBLES_COMISORIO_SECUESTRO_SUBIR_ARCHIVO", "700");
        define("INMUEBLES_COMISORIO_SECUESTRO_DEVOLUCION", "699");
        define("INMUEBLES_COMISORIO_SECUESTRO_ENVIAR_MODIFICACIONES", "1111131");


//COMISIONAR
        define("VEHICULO_COMISIONAR1", "1057");
        define("COMISIONAR", "COMISIONAR");

//NO COMISIONAR
        define("NO_INMUEBLE_COMISIONAR1", "309");
        define("NO_VEHICULO_COMISIONAR1", "310");
        define("NO_MUEBLE_COMISIONAR1", "311");

//RESPUESTA DOCUMENTO COMISION
        define("RTA_INMUEBLE_COMISIONAR1", "324");
        define("RTA_VEHICULO_COMISIONAR1", "844");

//VEHICULOS
        define("VEHICULO_NUM", "8");
        define("VEHICULO", "Oficio de Solicitud de Registro de Embargo (Veh&iacute;culo)");
        define("VEHICULO_INICIO", "705");
        define("VEHICULO_SECRETARIO", "702");
        define("VEHICULO_COORDINARO", "703");
        define("VEHICULO_SUBIR_ARCHIVO", "706");
        define("VEHICULO_DEVOLUCION", "704");
        define("VEHICULO_ENVIAR_MODIFICACIONES", "1111132");

//        define("PRESETO", "SE PRESENTO");
        define("VEHICULO_OK", "1034");
        define("VEHICULO_EMBARGO2", "111113");

//        define("AUTO_FECHA_HORA", "300");

        define("VEHICULO_EMBARGO_NUM", "13");
        define("VEHICULO_EMBARGO", "Oficio Solicitando Aprehensi&oacute;n (Veh&iacute;culo)");
        define("VEHICULO_EMBARGO_INICIO", "707");
        define("VEHICULO_EMBARGO_SECRETARIO", "329");
        define("VEHICULO_EMBARGO_COORDINARO", "330");
        define("VEHICULO_EMBARGO_SUBIR_ARCHIVO", "332");
        define("VEHICULO_EMBARGO_DEVOLUCION", "331");
        define("VEHICULO_EMBARGO_ENVIAR_MODIFICACIONES", "1111133");

        define("VEHICULO_EMBARGO_ENTREGA_NUM", "58");
        define("VEHICULO_EMBARGO_ENTREGA", "Oficio Aprehensi&oacute;n Entregado por la Policia(Veh&iacute;culo)");
        define("VEHICULO_EMBARGO_ENTREGA_SUBIR_ARCHIVO", "1369");

        define("VEHICULO_SECUESTRO_NUM", "14");
        define("VEHICULO_SECUESTRO", "Auto de Diligencia de Secuestro (Veh&iacute;culo)");
        define("VEHICULO_SECUESTRO_INICIO", "712");
        define("VEHICULO_SECUESTRO_SECRETARIO", "708");
        define("VEHICULO_SECUESTRO_COORDINARO", "709");
        define("VEHICULO_SECUESTRO_SUBIR_ARCHIVO", "711");
        define("VEHICULO_SECUESTRO_DEVOLUCION", "710");
        define("VEHICULO_SECUESTRO_ENVIAR_MODIFICACIONES", "1111134");

        define("VEHICULO_COMISION_NUM", "22");
        define("VEHICULO_COMISION", "Auto de Comisi&oacute;n (Veh&iacute;culo)");
        define("VEHICULO_COMISION_INICIO", "717");
        define("VEHICULO_COMISION_SECRETARIO", "713");
        define("VEHICULO_COMISION_COORDINARO", "714");
        define("VEHICULO_COMISION_SUBIR_ARCHIVO", "716");
        define("VEHICULO_COMISION_DEVOLUCION", "715");
        define("VEHICULO_COMISION_ENVIAR_MODIFICACIONES", "1111135");

        define("VEHICULO_DESPACHO_NUM", "23");
        define("VEHICULO_DESPACHO", "Solicitud de Informe Comisorio (Veh&iacute;culo)");
        define("VEHICULO_DESPACHO_INICIO", "722");
        define("VEHICULO_DESPACHO_SECRETARIO", "718");
        define("VEHICULO_DESPACHO_COORDINARO", "723");
        define("VEHICULO_DESPACHO_SUBIR_ARCHIVO", "719");
        define("VEHICULO_DESPACHO_DEVOLUCION", "720");
        define("VEHICULO_DESPACHO_ENVIAR_MODIFICACIONES", "1111136");

        define("VEHICULO_RPESENTA_NUM", "26");
        define("VEHICULO_RPESENTA", "Subir Documento Auto de Comisi&oacute;n Aprobado y Firmado (Veh&iacute;culo)");
        define("VEHICULO_RPESENTA_SUBIR_ARCHIVO", "724");

        define("VEHICULO_INCORPORANDO_NUM", "27");
        define("VEHICULO_INCORPORANDO", "Despacho Comisorio (Veh&iacute;culo)");
        define("VEHICULO_INCORPORANDO_INICIO", "729");
        define("VEHICULO_INCORPORANDO_SECRETARIO", "725");
        define("VEHICULO_INCORPORANDO_COORDINARO", "726");
        define("VEHICULO_INCORPORANDO_SUBIR_ARCHIVO", "728");
        define("VEHICULO_INCORPORANDO_DEVOLUCION", "727");
        define("VEHICULO_INCORPORANDO_ENVIAR_MODIFICACIONES", "1111137");

        define("VEHICULO_FECHA_NUM", "28");
        define("VEHICULO_FECHA", "Auto de Fecha y Hora de Diligencia de Secuestro (Veh&iacute;culo)");
        define("VEHICULO_FECHA_INICIO", "734");
        define("VEHICULO_FECHA_SECRETARIO", "730");
        define("VEHICULO_FECHA_COORDINARO", "731");
        define("VEHICULO_FECHA_SUBIR_ARCHIVO", "733");
        define("VEHICULO_FECHA_DEVOLUCION", "732");
        define("VEHICULO_FECHA_ENVIAR_MODIFICACIONES", "1111138");

        define("VEHICULO_DILIGENCIA_NUM", "29");
        define("VEHICULO_DILIGENCIA", "Acta en Diligencia de Secuestro (Veh&iacute;culo)");
        define("VEHICULO_DILIGENCIA_INICIO", "739");
        define("VEHICULO_DILIGENCIA_SECRETARIO", "735");
        define("VEHICULO_DILIGENCIA_COORDINARO", "736");
        define("VEHICULO_DILIGENCIA_SUBIR_ARCHIVO", "737");
        define("VEHICULO_DILIGENCIA_DEVOLUCION", "738");
        define("VEHICULO_DILIGENCIA_ENVIAR_MODIFICACIONES", "1111139");

        define("VEHICULO_OPOSICION_NUM", "30");
        define("VEHICULO_OPOSICION", "Respuesta al Auto de Oposici&oacute;n a la Diligencia de Secuestro (Veh&iacute;culo)");
        define("VEHICULO_OPOSICION_INICIO", "740");
        define("VEHICULO_OPOSICION_SECRETARIO", "372");
        define("VEHICULO_OPOSICION_COORDINARO", "373");
        define("VEHICULO_OPOSICION_SUBIR_ARCHIVO", "375");
        define("VEHICULO_OPOSICION_DEVOLUCION", "374");
        define("VEHICULO_OPOSICION_ENVIAR_MODIFICACIONES", "1111140");

////////////////////////////////////////////////////////////////////////////////////////////
        define("VEHICULO_FAVORABLE2", "1045");
        define("VEHICULO_FAVORABLE", "1046");

        define("VEHICULO_EMBARGO_OFICIO_NUM", "52");
        define("VEHICULO_EMBARGO_OFICIO", "Oficio Informando Embargo de Remanentes o de Prelaci&oacute;n de Cr&eacute;ditos (Veh&iacute;culo)");
        define("VEHICULO_EMBARGO_OFICIO_INICIO", "1040");
        define("VEHICULO_EMBARGO_OFICIO_SECRETARIO", "1041");
        define("VEHICULO_EMBARGO_OFICIO_COORDINARO", "1042");
        define("VEHICULO_EMBARGO_OFICIO_SUBIR_ARCHIVO", "1042");
        define("VEHICULO_EMBARGO_OFICIO_DEVOLUCION", "1043");
        define("VEHICULO_EMBARGO_OFICIO_ENVIAR_MODIFICACIONES", "1111152");

        define("VEHICULO_OPOSICION2", "1044");
        define("VEHICULO_INSISTIR", "1047");


        define("VEHICULO_ORDEN_NUM", "53");
        define("VEHICULO_ORDEN", "Auto Ordenando Levantamiento de Medidas (Veh&iacute;culo)");
        define("VEHICULO_ORDEN_INICIO", "1048");
        define("VEHICULO_ORDEN_SECRETARIO", "1049");
        define("VEHICULO_ORDEN_COORDINARO", "1050");
        define("VEHICULO_ORDEN_SUBIR_ARCHIVO", "1052");
        define("VEHICULO_ORDEN_DEVOLUCION", "1051");
        define("VEHICULO_ORDEN_ENVIAR_MODIFICACIONES", "1111153");

        define("VEHICULO_DOCUMENTO_ACTA_NUM", "54");
        define("VEHICULO_DOCUMENTO_ACTA", "Subir Documento Avaluo Dentro de la Diligencia de Secuestro  Aprobado y Firmado");
        define("VEHICULO_DOCUMENTO_ACTA_SUBIR_ARCHIVO", "1053");


        define("VEHICULO_PROYECTAR_AUTO_NUM", "56");
        define("VEHICULO_PROYECTAR_AUTO", "Proyectar Auto de Apertura de Pruebas (Veh&iacute;culo)");
        define("VEHICULO_PROYECTAR_AUTO_INICIO", "1141");
        define("VEHICULO_PROYECTAR_AUTO_SECRETARIO", "1142");
        define("VEHICULO_PROYECTAR_AUTO_COORDINARO", "1143");
        define("VEHICULO_PROYECTAR_AUTO_SUBIR_ARCHIVO", "1145");
        define("VEHICULO_PROYECTAR_AUTO_DEVOLUCION", "1144");
        define("VEHICULO_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES", "1111156");

        define("DOCUMENTO_EFECTIVO_VEHICULO", "1366");
        define("VEHICULO_RECEPCION", "Recepción documento Embargo de vehiculo (Veh&iacute;culo)");

        define("VEHICULO_RECEPCION_DOCUMENTO", "57");

        define("DILIGENCIA_SECUESTRO", VEHICULO_SECUESTRO_INICIO . "_" . INMUEBLES_SECUESTRO_INICIO . "_" . MUEBLES_INICIO);
        define("DILIGENCIA_SECUESTRO_SECRETARIO", VEHICULO_SECUESTRO_SECRETARIO . "_" . INMUEBLES_SECUESTRO_SECRETARIO . "_" . MUEBLES_SECRETARIO);
        define("DILIGENCIA_SECUESTRO_COORDINADOR", VEHICULO_SECUESTRO_COORDINARO . "_" . INMUEBLES_SECUESTRO_COORDINARO . "_" . MUEBLES_COORDINARO);
        define("DILIGENCIA_SECUESTRO_DEVOLUCION", VEHICULO_SECUESTRO_DEVOLUCION . "_" . INMUEBLES_SECUESTRO_DEVOLUCION . "_" . MUEBLES_DEVOLUCION);
        define("DILIGENCIA_SECUESTRO_ENVIAR_MODIFICACIONES", VEHICULO_SECUESTRO_ENVIAR_MODIFICACIONES . "_" . INMUEBLES_SECUESTRO_ENVIAR_MODIFICACIONES . "_" . MUEBLES_ENVIAR_MODIFICACIONES);
        define("DILIGENCIA_SECUESTRO_SUBIR_ARCHIVO", VEHICULO_SECUESTRO_SUBIR_ARCHIVO . "_" . INMUEBLES_SECUESTRO_SUBIR_ARCHIVO . "_" . MUEBLES_SUBIR_ARCHIVO);

////////////////////////////////////////////////////////////////////////////////////////////
//        define("VEHICULO_OPOSICION", "189");
        $sesion = $this->session->userdata;

        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);
     
    }

    function index() {
        $this->Listar_Menu();
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : el sistema sabe cual persmiso esta asignado y lo envia a su pantalla respectiva
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function Listar_Menu() {
        $this->activo();
//template data
        $this->template->set('title', 'Medidas Cautelares');
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['admin'] = $this->ion_auth->is_admin();
//        $this->data['permiso'] = $this->Mcinvestigacion_model->permiso();
//        if (count($this->data['permiso']) > 1) {
//            $this->general($this->data['permiso']);
//        } else if ($this->data['permiso'][0]['IDGRUPO'] == ABOGADO) {
//            $this->abogado();
//        } else if ($this->data['permiso'][0]['IDGRUPO'] == SECRETARIO) {
//            $this->secretarios();
//        } else if ($this->data['permiso'][0]['IDGRUPO'] == COORDINADOR) {
//            $this->coordinador();
//        }
        $this->abogado();
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : confirma si el cliente esta activo en la pagina
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function activo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mcinvestigacion/index') || TRUE) {
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta &oacute;rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : si el cliente tiene mas de 2 grupos le sale la vista de los grupos asignados
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function general($post) {
        $this->activo();
        $this->data['post'] = $post;
        $this->template->load($this->template_file, 'proyectardocumentomc/view', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : vista de toda la informacion del abogado 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function abogado() {
        $this->activo();
        $this->data['post'] = $this->input->post();
//       print_r($this->data['post']);
        $this->data['informacion'] = "1";
        $this->data['titu'] = " Abogado";
        $this->Mcinvestigacion_model->set_array_num(
                array(INICIO_PROCESO, DEVOLUCION, ARCHIVO_APROBADO2, OFICIO_SUBIR_ARCHIVO2, OFICIO_DEVOLUCION2,
                    ARCHIVO_APROBADO, OFICIO_DEVOLUCION, OFICIO_APROBADO, APRUEBA_DINEROS_BANCOS, APRUEBA_DINEROS_BANCOS_CONFIRMAR,
                    AUTO_LEVANTAMIENTO, AUTO_DEVOLUCION, AUTO_ARCHIVO_APROBADO,
                    GENERAR_FRACCIONAMIENTO, FRACCIONAMIENTO_DEVOLUCION, FRACCIONAMIENTO_ARCHIVO_APROBADO,
                    OFICIO_BIENES, OFICIO_BIENES_DEVOLUCION, APROVACION_BIENES_GENERALES, SUBIR_ARCHIVO,
                    OFICIO_SUBIR_ARCHIVO, AUTO_SUBIR_ARCHIVO, FRACCIONAMIENTO_SUBIR_ARCHIVO, OFICIO_BIENES_SUBIR_ARCHIVO));
        $this->db->where(' (PC.ABOGADO=' . ID_USER . ' OR PC.ABOGADO is NULL)  AND 1=', '1', FALSE);
        $id = $this->data['post']['cod_coactivo'];
        //  echo $id; die();
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO(NULL, NULL, $id);
        $this->template->load($this->template_file, 'proyectardocumentomc/proyectardocumento', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : vista de toda la informacion del secretario 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function secretarios() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['informacion'] = "2";
        $this->data['titu'] = " Secretario";
        $this->Mcinvestigacion_model->set_array_num(
                array(INICIO_SECRETARIO, OFICIO_SECRETARIO2,
                    OFICIO_SECRETARIO, AUTO_INICIO_SECRETARIO,
                    FRACCIONAMIENTO_INICIO_SECRETARIO,
                    OFICIO_BIENES_INICIO_SECRETARIO, APROVACION_BIENES_GENERALES));
        $this->db->where(' (MC_MEDIDASCAUTELARES.REVISADO_POR=' . ID_USER . ' AND MC_MEDIDASCAUTELARES.REVISADO_POR=' . ID_SECRETARIO . ') and 1=', 1, false);
        $id = $this->data['post']['cod_coactivo'];
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO(null, null, $id);
        $this->template->load($this->template_file, 'proyectardocumentomc/proyectardocumento_secretario', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : vista de toda la informacion del coordinador 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function coordinador() {
        $this->activo();
        if (ID_USER == ID_COORDINADOR) {
            $this->data['post'] = $this->input->post();
            $this->data['informacion'] = "3";
            $this->data['titu'] = " Funcionario Ejecutor";
            $this->Mcinvestigacion_model->set_array_num(
                    array(//informacion del abogado pero el ejecutor no puede realizar los cambios de estado
                        INICIO_PROCESO, DEVOLUCION, ARCHIVO_APROBADO2, OFICIO_SUBIR_ARCHIVO2, OFICIO_DEVOLUCION2,
                        ARCHIVO_APROBADO, OFICIO_DEVOLUCION, OFICIO_APROBADO, APRUEBA_DINEROS_BANCOS, APRUEBA_DINEROS_BANCOS_CONFIRMAR,
                        AUTO_LEVANTAMIENTO, AUTO_DEVOLUCION, AUTO_ARCHIVO_APROBADO,
                        GENERAR_FRACCIONAMIENTO, FRACCIONAMIENTO_DEVOLUCION, FRACCIONAMIENTO_ARCHIVO_APROBADO,
                        OFICIO_BIENES, OFICIO_BIENES_DEVOLUCION, APROVACION_BIENES_GENERALES, SUBIR_ARCHIVO,
                        OFICIO_SUBIR_ARCHIVO, AUTO_SUBIR_ARCHIVO, FRACCIONAMIENTO_SUBIR_ARCHIVO, OFICIO_BIENES_SUBIR_ARCHIVO,
                        //informacion del secretario pero el ejecutor no puede realizar los cambios de estado
                        INICIO_SECRETARIO, OFICIO_SECRETARIO2,
                        OFICIO_SECRETARIO, AUTO_INICIO_SECRETARIO,
                        FRACCIONAMIENTO_INICIO_SECRETARIO,
                        OFICIO_BIENES_INICIO_SECRETARIO, APROVACION_BIENES_GENERALES,
                        // informacion del ejecutor la cual puede realizar los cambios
                        INICIO_COORDINADOR,
                        OFICIO_COORDINADOR, OFICIO_COORDINADOR2,
                        AUTO_INICIO_COORDINADOR,
                        FRACCIONAMIENTO_INICIO_COORDINADOR,
                        OFICIO_BIENES_INICIO_COORDINADOR, APROVACION_BIENES_GENERALES));
            $id = $this->data['post']['cod_coactivo'];
            $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO(null, null, $this->data['post']['cod_coactivo']);
            $this->template->load($this->template_file, 'proyectardocumentomc/proyectardocumento_coordinador', $this->data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
            redirect(base_url() . 'index.php/bandejaunificada/index');
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : confirma si hay documentos para mostrar al usuario
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function gestion() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
//        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id'], $this->data['post']['tipo_doc']);
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id'], $this->data['post']['tipo_doc']);
        $this->data['traza'] = $this->Mcinvestigacion_model->MC_TRAZABILIDAD($this->data);
        $this->data['municipio'] = $this->Mcinvestigacion_model->municipio2($this->data['consulta'][0]['COD_MUNICIPIO']);
        $this->data['traza'] = $this->Mcinvestigacion_model->MC_TRAZABILIDAD($this->data);
        if (empty($this->data['consulta'][0]['RUTA_DOCUMENTO_GEN'])):
            $this->data['consulta'][0]['RUTA_DOCUMENTO_GEN'] = "no_existe.txt";
        endif;

        $this->data['nom_documento'] = $this->data['consulta'][0]['RUTA_DOCUMENTO_GEN'];
        if (file_exists(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta'][0]['RUTA_DOCUMENTO_GEN'])) {
            $name_file = trim(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta'][0]['RUTA_DOCUMENTO_GEN']);
            $text_file = fopen($name_file, "r");
            $contet_file = fread($text_file, filesize($name_file));
            $this->data['documento'] = $contet_file;
//            $this->data['documento'] = "";
        } else {
            $this->data['documento'] = "";
        }
        $this->load->view('proyectardocumentomc/gestion', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : pasa de numeros a letras resive 2 parametros 
//1- valor 
//2- si es pesos o dolares 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function valorEnLetras($valor, $tipo) {
        $info = $this->numeros_letras_model->ValorEnLetras($valor, $tipo);
        return $info;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : informacion del secretario
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function secretario() {
        $this->activo();
        $info = $this->Mcinvestigacion_model->secretario();
        return $info;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function guardar_Mc_medidas_cautelarias() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->guardar_archivo();
        if ($this->data['post']['nit'] != '3eeee3eeeee3') {
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id']);
            if (empty($this->data['post']['cod_siguiente_prelacion']))
                $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_cautelarias($this->data);
            else {
                $this->data['bloqueo'] = 0;
                $info1 = "";
                if (DILIGENCIA_SECUESTRO_SECRETARIO == $this->data['post']['cod_siguiente_prelacion'] ||
                        DILIGENCIA_SECUESTRO_ENVIAR_MODIFICACIONES == $this->data['post']['cod_siguiente_prelacion']) {
                    $info1 = (DILIGENCIA_SECUESTRO_SECRETARIO == $this->data['post']['cod_siguiente_prelacion']) ? explode("_", DILIGENCIA_SECUESTRO_SECRETARIO) : $info1;
                    $info1 = (DILIGENCIA_SECUESTRO_ENVIAR_MODIFICACIONES == $this->data['post']['cod_siguiente_prelacion']) ? explode("_", DILIGENCIA_SECUESTRO_ENVIAR_MODIFICACIONES) : $info1;
//                print_r($info);
                    $this->data['bloqueo'] = 3;
                    $this->data['post']['cod_siguiente_prelacion'] = $info1[0];
                    $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                    $this->data['bloqueo'] = 2;
                    $this->data['post']['cod_siguiente_prelacion'] = $info1[1];
                    $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                    $this->data['bloqueo'] = 1;
                    $this->data['post']['cod_siguiente_prelacion'] = $info1[2];
                    $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                } else {
                    $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                }
            }
        } else {
            $info = $this->Mcinvestigacion_model->guardar_datos_temporales($this->data);
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : actualizacion de los codigos siguientes
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function update_Mc_medidas_cautelarias() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->guardar_archivo();
        $this->data['user'] = $this->ion_auth->user()->row();
        if (empty($this->data['post']['cod_siguiente_prelacion']))
            $this->Mcinvestigacion_model->update_Mc_medidas_cautelarias($this->data);
        else {
            $this->data['bloqueo'] = 0;
            $info1 = "";
            if (DILIGENCIA_SECUESTRO_COORDINADOR == $this->data['post']['cod_siguiente_prelacion'] || DILIGENCIA_SECUESTRO_SUBIR_ARCHIVO == $this->data['post']['cod_siguiente_prelacion']) {
                $info1 = (DILIGENCIA_SECUESTRO_COORDINADOR == $this->data['post']['cod_siguiente_prelacion']) ? explode("_", DILIGENCIA_SECUESTRO_COORDINADOR) : $info1;
                $info1 = (DILIGENCIA_SECUESTRO_SUBIR_ARCHIVO == $this->data['post']['cod_siguiente_prelacion']) ? explode("_", DILIGENCIA_SECUESTRO_SUBIR_ARCHIVO) : $info1;
                $this->data['bloqueo'] = 3;
                $this->data['post']['cod_siguiente_prelacion'] = $info1[0];
                $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                $this->data['bloqueo'] = 2;
                $this->data['post']['cod_siguiente_prelacion'] = $info1[1];
                $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
                $this->data['bloqueo'] = 1;
                $this->data['post']['cod_siguiente_prelacion'] = $info1[2];
                $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
            } else
                $info = $this->Mcinvestigacion_model->guardar_Mc_medidas_Prelacion($this->data);
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : guarda archivos txt
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function guardar_archivo() {
        $this->activo();
        $post = $this->input->post();
        $this->data['post'] = $post;
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $post['id'])) {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        }
        $ar = fopen(RUTA_DES . $post['id'] . "/" . $post['nombre'] . ".txt", "w+") or die();
        fputs($ar, $post['informacion']);
        fclose($ar);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : confirma si los docuemtos existan para ser mostrados y posteriormente ser descargados 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function documento() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['consulta2'] = $this->Mcinvestigacion_model->documento($this->data);
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id'], $this->data['post']['tipo_doc']);
        $this->data['municipio'] = $this->Mcinvestigacion_model->municipio2($this->data['consulta'][0]['COD_MUNICIPIO']);
        $this->data['traza'] = $this->Mcinvestigacion_model->MC_TRAZABILIDAD($this->data);
        if (empty($this->data['consulta2'][0]['RUTA_DOCUMENTO_MC'])):
            $this->data['consulta2'][0]['RUTA_DOCUMENTO_MC'] = "no_existe.txt";
        endif;
        $this->data['nom_documento'] = $this->data['consulta2'][0]['RUTA_DOCUMENTO_MC'];
        if (file_exists(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta2'][0]['RUTA_DOCUMENTO_MC'])) {
            $name_file = trim(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta2'][0]['RUTA_DOCUMENTO_MC']);
            $text_file = fopen($name_file, "r");
            $contet_file = fread($text_file, filesize($name_file));
            $this->data['documento'] = $contet_file;
        } else {
            $this->data['documento'] = "";
        }
        $this->load->view('proyectardocumentomc/documento', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se ven todos los documentos que el cliente tiene para todo el proceso de medidas cautelares
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function view_documentos() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['id_consulta'] = $this->Mcinvestigacion_model->view_medida_cautelar($this->data);
        $this->load->view('proyectardocumentomc/view_documentos', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se ven todos los documentos que el cliente tiene para todo el proceso de medidas cautelares
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function documentos_vistas($id) {
        $informacion = $this->Mcinvestigacion_model->view_documentos($id);
        return $informacion;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : resive nombre de los archivos txt para mostrarlo al cliente 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function abrir_txt() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $name_file = "./" . $this->data['post']['ruta'];
        $text_file = fopen($name_file, "r");
        $contet_file = fread($text_file, filesize($name_file));
        echo $contet_file;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : envia el documento para subirlo y guarda en base de datos en medidasprelacion
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : subir documentos
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    private function do_upload() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $config['upload_path'] = RUTA_DES . $this->data['post']['id'] . "/";
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '20480';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $this->load->library('tcpdf/tcpdf.php');

        if (!$this->upload->do_upload()) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    function subir_doc() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['documentos'] = $this->Mcinvestigacion_model->select_documentos_mc($this->data['post']['id'], $this->data['post']['tipo']);
        $this->load->view('proyectardocumentomc/subir_doc', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : mostrar si el cliente tiene documentos de los bancos para subir con el fin de poder hacerle el cruce correspondiente
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function respuesta() {
        $this->activo();
        $this->data['post'] = $this->input->post();
//        print_r($this->data['post']);
        $this->data['total'] = $this->Mcinvestigacion_model->count_documentos_bancarios($this->data['post']['id']);
        $this->data['documentos'] = $this->Mcinvestigacion_model->select_documentos_bancarios($this->data['post']['id']);
        $this->load->view('proyectardocumentomc/respuesta', $this->data);
    }

    function informacion_banco() {
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->delete_documentos_bancarios($this->data['post']['dato']);
        $documentos = $this->Mcinvestigacion_model->select_documentos_bancarios($this->data['post']['id']);
        echo $documentos;
    }

    function informacion_doc_mc() {
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->delete_documentos_mc($this->data['post']['dato']);
        $documentos = $this->Mcinvestigacion_model->select_documentos_mc($this->data['post']['id'], $this->data['post']['tipo']);
        echo $documentos;
    }

    function bancarios() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id']);
        $this->data['bancos'] = $this->Mcinvestigacion_model->MC_EMBARGOBIENES($this->data);
        $this->data['info_bancos'] = $this->Mcinvestigacion_model->bancos($this->data);
        $this->load->view('proyectardocumentomc/bancarios', $this->data);
    }

    function subir_documentos_nuevos() {
        $this->data['post'] = $this->input->post();
        $this->activo();
        $this->Mcinvestigacion_model->subir_documentos_nuevos($this->data);
    }

    function confirmar_pago() {
        $this->activo();
        $this->data['post'] = $this->input->post();
//        print_r($this->data['post']);
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id']);
        $this->data['bancos'] = $this->Mcinvestigacion_model->MC_EMBARGOBIENES($this->data);
        $this->load->view('proyectardocumentomc/confirmar_pago', $this->data);
    }

    function confirmar_valores() {
        $this->activo();
        $this->data['post'] = $this->input->post();
//        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO($this->data['post']['id']);
//        $this->data['bancos'] = $this->Mcinvestigacion_model->MC_EMBARGOBIENES($this->data);
        $this->load->view('proyectardocumentomc/confirmar_valores', $this->data);
    }

    function bloquear_vehiculos() {
        $this->data['post'] = $this->input->post();
        $this->data['documentos'] = $this->Mcinvestigacion_model->bloquear_vehiculos($this->data['post']);
    }

    function presenta() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['documentos'] = $this->Mcinvestigacion_model->select_documentos_mc($this->data['post']['id'], VEHICULO_RECEPCION_DOCUMENTO);
        $this->load->view('proyectardocumentomc/presenta', $this->data);
    }

    function comision() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->load->view('proyectardocumentomc/comision', $this->data);
    }

    function oposicion() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->load->view('proyectardocumentomc/oposicion', $this->data);
    }

    function favorable() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->load->view('proyectardocumentomc/favorable', $this->data);
    }

    function favorable2() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->load->view('proyectardocumentomc/favorable2', $this->data);
    }

    function insistir() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->load->view('proyectardocumentomc/insistir', $this->data);
    }

    function guardar_trazabilidad() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        if (empty($this->data['post']['cod_siguiente_prelacion'])) {
            $this->Mcinvestigacion_model->guardar_trazabilidad($this->data);
        } else {
            $this->data['bloqueo'] = 0;
            if (DILIGENCIA_SECUESTRO_DEVOLUCION == $this->data['post']['devol']) {
                $info1 = explode("_", DILIGENCIA_SECUESTRO_DEVOLUCION);
//                print_r($info);
                $this->data['bloqueo'] = 3;
                $this->data['post']['devol'] = $info1[0];
                $info = $this->Mcinvestigacion_model->guardar_trazabilidad_prelacion($this->data);
                $this->data['bloqueo'] = 2;
                $this->data['post']['devol'] = $info1[1];
                $info = $this->Mcinvestigacion_model->guardar_trazabilidad_prelacion($this->data);
                $this->data['bloqueo'] = 1;
                $this->data['post']['devol'] = $info1[2];
                $info = $this->Mcinvestigacion_model->guardar_trazabilidad_prelacion($this->data);
            } else
                $this->Mcinvestigacion_model->guardar_trazabilidad_prelacion($this->data);
        }
    }

    function upload() {
        $id = $_REQUEST['id'];
        if (!file_exists(RUTA_DES . $id)) {
            if (!mkdir(RUTA_DES . $id, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $output_dir = RUTA_DES . $id . "/";
        if (isset($_FILES["myfile"])) {
            $ret = array();

            $error = $_FILES["myfile"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
            if (!is_array($_FILES["myfile"]["name"])) { //single file
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $fileName);
                $ret[] = $fileName;
            } else {  //Multiple files, file[]
                $fileCount = count($_FILES["myfile"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $fileName);
                    $ret[] = $fileName;
                }
            }
            $this->Mcinvestigacion_model->guardar_documentos_bancarios($id, $fileName);
            echo json_encode($fileName);
        }
    }

    function detele() {
        $this->data['post'] = $this->input->post();
        $id = $this->data['post']['id'];
        $archivo = $this->data['post']['archivo'];

        $output_dir = RUTA_DES . $id . "/";
        if (isset($archivo)) {
            $fileName = $archivo;
            $filePath = $output_dir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->Mcinvestigacion_model->eliminar_documentos_bancarios($id, $fileName);
            echo "Deleted File " . $fileName . "<br>";
        }
    }

    function documentos_bancarios() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->documentos_bancarios($this->data);
    }

    function resumen_documentos_bancarios() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->resumen_documentos_bancarios($this->data);
    }

    public function redirigir() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->documentos_bancarios($this->data);
        header('Location:' . base_url('index.php/mcinvestigacion/abogado'));
    }

    function prelacion() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->Mcinvestigacion_model->COBROPERSUASIVO_PRELACION($this->data['post']['id']);
        $this->data['permiso'] = $this->Mcinvestigacion_model->permiso();
        $this->load->view('proyectardocumentomc/prelacion', $this->data);
    }

    function prelacion_detalle() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['info_bancos'] = $this->Mcinvestigacion_model->bancos($this->data);
        $this->data['prioridad2'] = $this->Mcinvestigacion_model->informacion_prelacion($this->data['post']);
//        echo $this->data['prioridad'];
        $this->data['tipo_prioridad'] = $this->Mcinvestigacion_model->MC_TIPO_PRIORIDAD($this->data);
        $this->load->view('proyectardocumentomc/prelacion_detalle', $this->data);
    }

    function accion_de() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->accion_de($this->data);
    }

    function avance() {
        $this->activo();
        $this->data['post'] = $this->input->post();
//        $this->Mcinvestigacion_model->ofice($this->data);
        $this->Mcinvestigacion_model->avance($this->data);
    }

    function bloqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('proyectardocumentomc/bloquear', $this->data);
    }

    function bloqueo_por_time() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('proyectardocumentomc/bloqueo_por_time', $this->data);
    }

    function reiniciar_proceso() {
        $this->data['post'] = $this->input->post();
        $this->Mcinvestigacion_model->reiniciar_proceso($this->data);
    }

    function subir_documento_doc1() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['file'] = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->Mcinvestigacion_model->subir_documento_doc1($this->data);
        header('Location:' . base_url('index.php/mcinvestigacion/abogado'));
    }

    public function doUploadFile() {
        $this->data['post'] = $this->input->get();
        $post = $this->data['post'];
        $nombre_carpeta = RUTA_INI;
        $nombre_subcarpeta = RUTA_DES . $post['id'] . '/';

        if (!is_dir($nombre_carpeta)) {
            @mkdir($nombre_carpeta, 0777);
        } else {
            
        }
        if (!is_dir($nombre_subcarpeta)) {
            @mkdir($nombre_subcarpeta, 0777);
        } else {
            
        }
        $status = '';
        $message = '';
        $background = '';
        $file_element_name = 'userFile';

        if ($status != 'error') {
            $config['upload_path'] = RUTA_DES . $post['id'] . '/';
            $config['allowed_types'] = 'pdf';
//            $config['allowed_types'] = 'png|jpg|gif|pdf';
            $config['max_size'] = '10000';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name)) {
                return false;
            } else {
                $data = $this->upload->data();
            }
            @unlink($_FILES[$file_element_name]);
        }
        $this->Mcinvestigacion_model->guardar_documentos_bancarios($post['id'], $data['file_name']);
        $tabla = $this->Mcinvestigacion_model->select_documentos_bancarios($post['id']);
//        $tabla= "";
        $tabla = base64_encode($tabla);
        $json_encode = json_encode(array('message' => $message, 'status' => $status, 'background' => $background, 'tabla' => $tabla));
        echo $json_encode;
    }

    public function doUploadFile2() {
        $this->data['post'] = $this->input->get();
        $post = $this->data['post'];
        $nombre_carpeta = RUTA_INI;
        $nombre_subcarpeta = RUTA_DES . $post['id'] . '/';

        if (!is_dir($nombre_carpeta)) {
            @mkdir($nombre_carpeta, 0777);
        } else {
            
        }
        if (!is_dir($nombre_subcarpeta)) {
            @mkdir($nombre_subcarpeta, 0777);
        } else {
            
        }
        $status = '';
        $message = '';
        $background = '';
        $file_element_name = 'userFile';

        if ($status != 'error') {
            $config['upload_path'] = RUTA_DES . $post['id'] . '/';
            $config['allowed_types'] = 'pdf';
//            $config['allowed_types'] = 'png|jpg|gif|pdf';
            $config['max_size'] = '10000';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name)) {
                return false;
            } else {
                $data = $this->upload->data();
            }
            @unlink($_FILES[$file_element_name]);
        }
        $this->Mcinvestigacion_model->subir_documento_doc2($this->data, $data['file_name']);
//        $this->Mcinvestigacion_model->guardar_documentos_bancarios($post['id'], $data['file_name']);
        $tabla = $this->Mcinvestigacion_model->select_documentos_mc($post['id'], $post['tipo']);
//        $tabla= "";
        $tabla = base64_encode($tabla);
        $json_encode = json_encode(array('message' => $message, 'status' => $status, 'background' => $background, 'tabla' => $tabla));
        echo $json_encode;
    }

    function imprimir_pdf() {
        $post = $this->input->post();
        $html = $post['infor_pdf'];
        $nombre_pdf = '';
        $titulo = $this->input->post('titulo_doc');
        $tipo = $this->input->post('tipo_documento');
        $data[0] =$tipo;
        $data[1] =  $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

}
