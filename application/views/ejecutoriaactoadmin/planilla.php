<!-- Responsable: Leonardo Molina-->
<?php
if (!isset($resmulta->NUMERO_RESOLUCION)) {

    if (isset($gestiones->COD_RESPUESTA)) {
        if ($gestiones->COD_RESPUESTA) {
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El documento ya esta impreso. ' . $gestiones->NOMBRE_GESTION . '</div>';
        }
    }
//var_dump($registros->NUMERO_RESOLUCION);
    if (!isset($registros->NUMERO_RESOLUCION)) {
        echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Existe una inconsistencia en la base de datos.</div>';
        die;
    }
    $nresol = array('name' => 'nresol', 'id' => 'nresol', 'type' => 'hidden', 'value' => $registros->NUMERO_RESOLUCION);
    $cod_estado = array('name' => 'cod_estado', 'id' => 'cod_estado', 'type' => 'hidden', 'value' => $registros->COD_ESTADO);
    $citacion = array('name' => 'citacion ', 'id' => 'citacion', 'type' => 'hidden', 'value' => $registros->CGESTION); //echo $registros->CITACION;
    $cfisca = array('name' => 'cfisca', 'id' => 'cfisca', 'type' => 'hidden', 'value' => $registros->COD_CPTO_FISCALIZACION); //echo $registros->NOMBRE_CONCEPTO;
    $nrecur = array('name' => 'nrecur', 'id' => 'nrecur', 'type' => 'hidden', 'value' => $registros->NUM_RECURSO);
    $button = array('name' => 'ejecutoriar', 'id' => 'ejecutoriar', 'value' => 'Cargar Ejecutoria', 'content' => "<i class='btn-save'></i>", 'class' => 'btn btn-primary');
    $codfisc = array('name' => 'codfisc', 'id' => 'codfisc', 'type' => 'hidden', 'value' => $registros->COD_FISCALIZACION);
    $rgestion = array('name' => 'rgestion', 'id' => 'rgestion', 'type' => 'hidden');
    $rgestion2 = array('name' => 'rgestion2', 'id' => 'rgestion2', 'type' => 'hidden');
    $gnit = array('name' => 'gnit', 'id' => 'gnit', 'type' => 'hidden', 'value' => $registros->CODEMPRESA);
    $nit = $registros->CODEMPRESA;
    $nempresa = $registros->NOMBRE_EMPRESA;
    $fecha_citacion = $registros->FECHA_ENVIO_CITACION;
    $fres = $registros->FECHA_ACTUAL;
    $numres = $registros->NUMERO_RESOLUCION;
} else {
    $nresol = array('name' => 'nresol', 'id' => 'nresol', 'type' => 'hidden', 'value' => $resmulta->NUMERO_RESOLUCION);
    $cod_estado = array('name' => 'cod_estado', 'id' => 'cod_estado', 'type' => 'hidden', 'value' => $registros->COD_ESTADO);
    $cfisca = array('name' => 'cfisca', 'id' => 'cfisca', 'type' => 'hidden', 'value' => $resmulta->COD_CPTO_FISCALIZACION); //echo $registros->NOMBRE_CONCEPTO;
    $codfisc = array('name' => 'codfisc', 'id' => 'codfisc', 'type' => 'hidden', 'value' => $resmulta->COD_FISCALIZACION);
    $gnit = array('name' => 'gnit', 'id' => 'gnit', 'type' => 'hidden', 'value' => $resmulta->CODEMPRESA);
    $rgestion = array('name' => 'rgestion', 'id' => 'rgestion', 'type' => 'hidden');
    $rgestion2 = array('name' => 'rgestion2', 'id' => 'rgestion2', 'type' => 'hidden');
    $button = array('name' => 'ejecutoriar', 'id' => 'ejecutoriar', 'value' => 'Cargar Ejecutoria', 'content' => "<i class='btn-save'></i>", 'class' => 'btn btn-primary');
}
echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Resolución: ' . $registros->CITACION . '</div>';
;
?>
<?= form_open_multipart(base_url('index.php/ejecutoriaactoadmin/ejecutoriar/')); ?>




<textarea name="informacion" id="informacion" style="width:100%; height: 300px"></textarea>

<div class="modal-footer">
    <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar2">Cancelar</a>
    <a href="#" class="btn btn-primary" id="generarPdf"><li class="fa fa-download"></li> Descargar Documento</a>
    <?php
//        echo $gestiones->COD_RESPUESTA;
    if (isset($gestiones->COD_RESPUESTA)) {
        if ($gestiones->COD_RESPUESTA >= '120' && $gestiones->COD_RESPUESTA <= '126') {
            ?>
            <a href="#" id="Gestionar" class="btn btn-primary" data-toggle="modal" data-target="#modal2" data-keyboard="false" data-backdrop="static" title="Gestionar"><li class="fa fa-share"></li> Ejecutoriar Resolución</a>
        <?php
        }
        if ($gestiones->COD_RESPUESTA >= '877') {
            ?>
            <a href="#" id="Gestionar" class="btn btn-primary" data-toggle="modal" data-target="#modal2" data-keyboard="false" data-backdrop="static" title="Gestionar"><li class="fa fa-share"></li> Ejecutoriar Multa</a>     

            <?php
        }
    }
    ?>
    <!--resolucion: --><?= form_input($nresol) ?>
    <!--resolucion: --><?= form_input($cod_estado) ?>
    <!--conceptofis:--><?= form_input($cfisca) ?>
    <!--CodFisca:-->   <?= form_input($codfisc) ?>
    <!--Nit Empresa--> <?= form_input($gnit) ?>
    <!--Gestionrespuesta--> <?= form_input($rgestion2) ?>
    <!--RespuestaGestion--> <?= form_input($rgestion) ?>
    <?php
    if (isset($registros->CGESTION)) {

        /* <!--RECURSO:--> */ if (isset($nrecur))
            echo form_input($nrecur);
        /* <!--citacion:--> */ if (isset($citacion))
            echo form_input($citacion);
    }
    ?>
    <div id="resultado"></div>
</div>


<!-- Modal External-->
<div class="modal hide fade in" id="modal2" style="display: none; width: 40%; margin-left: -20%; margin-top: 5%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Subir archivo Ejecutoria</h4>
            </div>
            <div class="modal-body2" >
                <div align="center">
                    <div class="input-append" id="arch0">
                        <div>
                            <input type="file" name="archivo0" class="btn btn-primary file_uploader">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <?= form_submit($button); ?>
<?= form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- 6.1 Constancia de ejecutoria sin recurso para contratos de aprendizaje-->
<div id="c61" style="display: none; ">
    <h2 style="text-align: center;">CONSTANCIA&nbsp; DE EJECUTORIA</h2>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>RAZON SOCIAL: <?= $nempresa; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. <strong><?= $numres; ?></strong> de <strong><?= $fres; ?>,</strong>fue notificada personalmente el&nbsp; d&iacute;a<strong>15/01/2013</strong><strong>, </strong>&nbsp;conforme lo establecido en el art&iacute;culo 67 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, sin que se hubieren interpuesto los recursos a que ten&iacute;a lugar, en el t&eacute;rmino previsto para el efecto, se declara ejecutoriada la mencionada providencia el d&iacute;a&nbsp; <strong>.</strong></p>
    <p>&nbsp;</p>
    <p>Dado en&nbsp; Bogotá.,&nbsp; a los <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p>&nbsp;</p>
    <p style="text-align: right;">Nombre del Coordinador (a)</p>
    <p style="text-align: right;">Coordinador /a Relaciones Corporativas e Internacionales</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Elaborado por: WALKIN MEZA</p>
    <p>Revisado por: NOMBRE DEL COORDINADOR</p>
    <p>&nbsp;</p>
</div>

<div id="c62" style="display: none; ">
    <!-- 6.2 Constancia de ejecutoria con recurso para contratos de aprendizaje-->
    <h2 style="text-align: center;">CONSTANCIA&nbsp; DE EJECUTORIA</h2>
    <p>&nbsp;</p>
    <p>&nbsp;&nbsp;</p>
    <p>RAZON SOCIAL: <?= $nempresa; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. <strong><?= $numres; ?></strong> de <strong><?= $fres; ?>,</strong>fue notificada personalmente el&nbsp; d&iacute;a<a name="fecha_notificacion"></a><strong>15/01/2013</strong><strong>, </strong>&nbsp;se interpusieron los recursos dentro del t&eacute;rmino establecido por la ley; los cuales fueron resueltos por las personas competentes para tal caso, el recurso de reposici&oacute;n mediante resoluci&oacute;n______ de fecha _____ el cual fue notificado personalmente el d&iacute;a ________ y el de apelaci&oacute;n mediante resoluci&oacute;n______ de fecha _______ fue notificado personalmente el d&iacute;a ______, seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 74 del c&oacute;digo Contencioso Administrativo, se declara ejecutoriada la mencionada providencia el d&iacute;a&nbsp; <strong>.</strong></p>
    <p>&nbsp;</p>
    <p>Dado en&nbsp; Bogotá.,&nbsp; a los <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p>Nombre del Coordinador (a)</p>
    <p>Coordinador /a Relaciones Corporativas e Internacionales</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Elaborado por: WALKIN MEZA</p>
    <p>Revisado por: NOMBRE DEL COORDINADOR</p>
</div>

<!-- 6.3 Constancia de ejecutoria con recurso por aviso para contratos de aprendizaje-->
<div id="c63" style="display: none; ">

    <h2 style="text-align: center;">CONSTANCIA&nbsp; DE EJECUTORIA</h2>
    <p>&nbsp;</p>
    <p>&nbsp;&nbsp;</p>
    <p>RAZON SOCIAL: <?= $nempresa; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. <strong><?= $numres; ?></strong> de <strong><?= $fres; ?>,</strong>fue notificada mediante <strong>AVISO</strong>publicado el xxxxxx y desfijado el <strong>&nbsp;xxxxxxxx </strong>conforme lo establecido en el art&iacute;culo <strong>76</strong> del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, sin que se hubieren interpuesto los recursos a que ten&iacute;a lugar, en el t&eacute;rmino previsto para el efecto, se declara ejecutoriada la mencionada providencia el <strong>XXXXX </strong></p>
    <p>&nbsp;</p>
    <p>Dado en&nbsp; YOPAL.,&nbsp; el d&iacute;a<strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p>Nombre del Coordinador (a)</p>
    <p>Coordinador /a Relaciones Corporativas e Internacionales</p>

</div>

<div id="c64" style="display: none; ">
    <!-- 6.4 Constancia de ejecutoria sin recurso por aviso para contratos de aprendizaje-->

    <h2 style="text-align: center;">CONSTANCIA&nbsp; DE EJECUTORIA</h2>
    <p>&nbsp;</p>
    <p>&nbsp;&nbsp;</p>
    <p>RAZON SOCIAL: <a name="rsocial"></a><?= $nempresa; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><a name="nit"></a><?= $nit; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. <a name="nresolucion"></a><strong><?= $numres; ?></strong> de <a name="fecha_resolucion"></a><strong><?= $fres; ?>,</strong>fue notificada mediante <strong>AVISO</strong>publicado el <a name="fecha_fijacion"></a>xxxxxx y desfijado el <a name="fecha_desfijacion"></a><strong>&nbsp;xxxxxxxx </strong>conforme lo establecido en el art&iacute;culo <strong>76</strong> del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo<strong>, </strong>&nbsp;se interpusieron los recursos dentro del t&eacute;rmino establecido por la ley; los cuales fueron resueltos por las personas competentes para tal caso, el recurso de reposici&oacute;n mediante resoluci&oacute;n______ de fecha _____ el cual fue notificado personalmente el d&iacute;a ________ y el de apelaci&oacute;n mediante resoluci&oacute;n______ de fecha _______ fue notificado personalmente el d&iacute;a ______, seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 74 del c&oacute;digo Contencioso Administrativo, se declara ejecutoriada la mencionada providencia el d&iacute;a&nbsp; <a name="fecha_ejecutoria"></a><strong>.</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p>Dado en&nbsp; <a name="ciudad"></a>Bogotá.,&nbsp; el d&iacute;a<strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p>&nbsp;</p>
    <p><a name="coordinador"></a>Nombre del Coordinador (a)</p>
    <p>Coordinador /a Relaciones Corporativas e Internacionales</p>
    <p>Elaborado por: <a name="elaborado"></a>WALKIN MEZA</p>
    <p>Revisado por: <a name="revisado"></a>NOMBRE DEL COORDINADOR</p>

</div>

<div id="c65" style="display: none; ">

    <!--6.5 Constancia de ejecutoria para aportes y FIC-->


    <p style="text-align: center;"><strong>REPUBLICA DE COLOMBIA</strong></p>
    <p style="text-align: center;"><strong>MINISTERIO DEL TRABAJO</strong></p>
    <p style="text-align: center;"><strong>SERVICIO NACIONAL DE APRENDIZAJE SENA</strong></p>
    <p style="text-align: center;"><strong>REGIONAL DISTRITO CAPITAL</strong></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <h2>AUTO DE EJECUTORIA</h2>
    <p><strong>&nbsp;</strong></p>
    <p><strong>EMPRESA: <?= $nempresa; ?></strong></p>
    <p>&nbsp;</p>
    <p><strong>NIT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $nit; ?></strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. <strong><?= $numres; ?> de fecha <?= $fres; ?>, </strong>notificada personalmente el <strong>18 de octubre de&nbsp; 2012, </strong>conforme lo establecido en el art&iacute;culo 67 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, el 01 de noviembre de 2012 interpusieron el recurso de apelaci&oacute;n, el cual se resolvi&oacute; mediante Resoluci&oacute;n No. <strong>00124 del 23 de enero de 2013</strong>, por parte de los funcionarios competentes para conocerlo, fue notificada personalmente el <strong>08 de febrero de 2013</strong>, de conformidad con el articulo 87 del mismo c&oacute;digo se declara ejecutoriada la mencionada providencia el&nbsp; <strong>11 de Febrero de 2013.</strong></p>
    <p>Dado en&nbsp; Bogot&aacute;, D.C.,&nbsp; el&nbsp; <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
    <p style="text-align: center;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CARLOS EDUARDO HERNANDEZ</strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
    <p style="text-align: right;">Coordinador Grupo de Relaciones</p>
    <p style="text-align: right;">Corporativas E Internacionales&nbsp;</p>

</div>

<div id="c66" style="display: none; ">
    <!-- 6.6 Constancia de ejecutoria para aportes y FIC por aviso sin recurso-->

    <h2 style="text-align: center;">AUTO DE EJECUTORIA</h2>
    <p>&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
    <p>RAZON SOCIAL: &laquo;<?= $nempresa; ?>&raquo;</p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?></strong><strong>&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. &laquo;<?= $numres; ?>&raquo; del &laquo;<?= $fres; ?>&raquo; fue notificada personalmente.&nbsp; No se interpusieron los recursos a que ten&iacute;a lugar, en el t&eacute;rmino previsto para el efecto, seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 50 del c&oacute;digo Contencioso Administrativo. Se declara ejecutoriada la mencionada providencia el d&iacute;a &laquo;FECHADEEJECUTORIA&raquo;</p>
    <p>&nbsp;</p>
    <p><strong>En caso de ser notificado por edicto se debe colocar el siguiente p&aacute;rrafo;</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p>Por cuanto la Resoluci&oacute;n No. &laquo;NODERES&raquo; del &laquo;FECHA&raquo; fue notificada por edicto fijado el dia ________ y desfijado _______.&nbsp; No se interpusieron los recursos a que ten&iacute;a lugar, en el t&eacute;rmino previsto para el efecto, seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 45 del c&oacute;digo Contencioso Administrativo. Se declara ejecutoriada la mencionada providencia el d&iacute;a &laquo;FECHADEEJECUTORIA&raquo;</p>
    <p>&nbsp;</p>
    <p>Dado en&nbsp; Bogot&aacute;, D.C.,&nbsp; a los&nbsp; <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p style="text-align: center;">Nombre del Coordinador (a)</p>
    <p style="text-align: center;">Coordinadora Relaciones Corporativas e Internacionales</p>

</div>

<div id="c67" style="display: none; ">
    <!-- 6.7 Constancia de ejecutoria para aportes y FIC por aviso con recurso-->

    <h2 style="text-align: center;">AUTO DE EJECUTORIA</h2>
    <p>&nbsp;</p>
    <p>&nbsp;&nbsp;</p>
    <p>RAZON SOCIAL: &laquo;<?= $nempresa; ?>&raquo;</p>
    <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?></strong><strong>&nbsp;&nbsp; </strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Por cuanto la Resoluci&oacute;n No. &laquo;<?= $numres; ?>&raquo; del &laquo;<?= $fres; ?>&raquo; fue notificada personalmente.&nbsp; El d&iacute;a ______ se interpusieron los recursos dentro del t&eacute;rmino establecido por la ley; los cuales fueron resueltos por las personas competentes para tal caso, el recurso de reposici&oacute;n mediante resoluci&oacute;n______ de fecha _____ el cual fue notificado personalmente el d&iacute;a ________ y el de apelaci&oacute;n mediante resoluci&oacute;n______ de fecha _______ fue notificado personalmente el d&iacute;a ______, seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 50 del c&oacute;digo Contencioso Administrativo. Se declara ejecutoriada la mencionada providencia el d&iacute;a &laquo;FECHADEEJECUTORIA&raquo;</p>
    <p>&nbsp;</p>
    <p><strong>En caso de ser notificado por edicto se debe colocar el siguiente p&aacute;rrafo;</strong></p>
    <p>Por cuanto la Resoluci&oacute;n No. &laquo;NODERES&raquo; del &laquo;FECHA&raquo; fue notificada mediante edicto fijado el dia ________ y desfijado _______ .&nbsp; El d&iacute;a ______ se interpusieron los recursos dentro del t&eacute;rmino establecido por la ley; los cuales fueron resueltos por las personas competentes para tal caso, el recurso de reposici&oacute;n mediante resoluci&oacute;n______ de fecha _____ el cual fue notificado personalmente el d&iacute;a ________ y el de apelaci&oacute;n mediante resoluci&oacute;n______ de fecha _______ fue notificado personalmente el d&iacute;a ______,&nbsp; seg&uacute;n lo se&ntilde;alado en el art&iacute;culo 50 del c&oacute;digo Contencioso Administrativo. Se declara ejecutoriada la mencionada providencia el d&iacute;a &laquo;FECHADEEJECUTORIA&raquo;</p>
    <p>&nbsp;</p>
    <p>Dado en&nbsp; _____________,&nbsp; a los&nbsp; <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p style="text-align: center;">Nombre del Coordinador (a)</p>
    <p style="text-align: center;">Coordinadora Relaciones Corporativas e Internacionales</p>

</div>

<div id="c665" style="display: none; ">
    <center>CONSTANCIA  DE EJECUTORIA</center><br>
    <p>RAZON SOCIAL: &laquo;<?= $nempresa; ?>&raquo;</p>                                                                   
        <p><strong>NIT:&nbsp;&nbsp; </strong><strong><?= $nit; ?></strong><strong>&nbsp;&nbsp; </strong></p>
        Por cuanto la Resoluci&oacute;n No. &laquo;<?= $numres; ?>&raquo; del &laquo;<?= $fres; ?>&raquo;, fue notificada mediante AVISO publicado el <?= $fecha_citacion; ?> y desfijado el  <?= $fecha_citacion; ?> conforme lo establecido en el artículo 76 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo,  se interpusieron los recursos dentro del término establecido por la ley; los cuales fueron resueltos por las personas competentes para tal caso, el recurso de reposición mediante resolución______ de fecha _____ el cual fue notificado personalmente el día ________ y el de apelación mediante Resoluci&oacute;n No. &laquo;<?= $numres; ?>&raquo; del &laquo;<?= $fres; ?>&raquo; fue notificado personalmente el día  <?= $fecha_citacion; ?>, según lo señalado en el artículo 74 del código Contencioso Administrativo, se declara ejecutoriada la mencionada providencia el día.
        <p>Dado en&nbsp; _____________,&nbsp; a los&nbsp; <strong> <?= $dia ?>/<?= $mes ?>/<?= $ano ?>.</strong></p>
        Nombre del Coordinador (a)<br>
        Coordinador /a Relaciones Corporativas e Internacionales
<!--    <p>
        Elaborado por: WALKIN MEZA<br>
        Revisado por: NOMBRE DEL COORDINADOR-->
</div>


<div id="c68" style="display: none; ">

    <!-- 6.8 Carta de cobro persuasivo -->

    <p>Se&ntilde;or</p>
    <p>NOMBRE</p>
    <p>CARGO (si aplica)</p>
    <p>RAZ&Oacute;N SOCIAL (si aplica)</p>
    <p>NIT/ C&eacute;dula</p>
    <p>&nbsp;</p>
    <p>Respetado se&ntilde;or:</p>
    <p>Le informamos que contamos con un t&iacute;tulo ejecutivo en firme (especificar el acto que lo profiri&oacute;- acto administrativo o liquidaci&oacute;n de la deuda) en donde consta que el aportante (especificar nombre del aportante) le adeuda al subsistema XXXX de la Protecci&oacute;n Social, aportes por un valor de XXXX m&aacute;s los intereses que se generen hasta la fecha del pago. Este valor corresponde a los periodos XXXX a XXXX.</p>
    <p>Es por esto que le recordamos que debe realizar el pago inmediatamente a trav&eacute;s de la Planilla</p>
    <p>Lo invitamos a cancelar y as&iacute; evitar el inicio del (Cobro administrativo Coactivo o judicial) correspondiente, el cual le generar&iacute;a un costo adicional al final del proceso as&iacute; como la pr&aacute;ctica de medidas cautelares como el embargo, secuestro y remate de los bienes o activos patrimoniales.</p>
    <p>Cordial Saludo,</p>
    <p>&nbsp;</p>
    <p>Integrada de Liquidaci&oacute;n de Aportes PILA.</p>
    <p>&nbsp;</p>
    <p>Nombre</p>
    <p>Indicar Administradora</p>
    <p>Direcci&oacute;n</p>
    <p>Tel&eacute;fono</p>
</div>

<!-- Plantilla constancia ejecutoria Multas-->
<div id="c70" style="display: none; ">
    <p style="text-align: center;">MINISTERIO DE LA PROTECCION SOCIAL</p>
    <p style="text-align: center;">DIRECCION TERRITORIAL DE CUNDINAMARCA</p>
    <p style="text-align: center;">GRUPO PREVENCION, INSPECCION, VIGILANCIA Y CONTROL</p>
    <p style="text-align: center;">Carrera 7 No. 32-63 PISO 2 Bogot&aacute;, D.C. &ndash;Colombia</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p style="text-align: center;"><strong><em>LA AUXILIAR ADMINISTRATIVA DE LA COORDINACION DEL GRUPO DE </em></strong></p>
    <p style="text-align: center;"><strong><em>PREVENCION, INSPECCION, VIGILANCIA Y CONTROL DE LA</em></strong></p>
    <p style="text-align: center;"><strong><em>DIRECCION TERRITORIAL DE CUNDINAMARCA</em></strong></p>
    <p><em>&nbsp;</em></p>
    <p style="text-align: center;"><strong><em>HACE CONSTAR</em></strong></p>
    <p><em>&nbsp;</em></p>
    <p style="text-align: justify;"><em>En este <strong>INFORME SECRETARIAL</strong>: Que de conformidad del Articulo 62 y 63 del Decreto 01 de 1984, queda debidamente <strong>EJECUTORIADA</strong> la Resoluci&oacute;n No.XXX del XXXXXX, Expedida por la Coordinaci&oacute;n del Grupo de Promoci&oacute;n, Inspecci&oacute;n, Vigilancia y Control de la Direcci&oacute;n Territorial de Cundinamarca por medio de la cual se <strong>SANCIONA</strong> a la empresa <strong>XXXXXXXXXX</strong>, por cuanto se dio el tr&aacute;mite correspondiente y no se interpusieron recurso de ley.</em></p>
    <p><em>&nbsp;</em></p>
    <p><em>Dada en Bogot&aacute;. D. C, hoy XXXXXXXXXX</em></p>
    <p><em>&nbsp;</em></p>
    <p><em>&nbsp;</em></p>
    <p><em>&nbsp;</em></p>
    <p><strong><em>NIRSA ELDA GAMEZ</em></strong></p>
    <p><strong><em>A. Administrativo</em></strong></p>
</div>

<div id="pdf_resp"></div>

<script type="text/javascript">
    $(function() {

        if ($('#cfisca').val() == "1" || $('#cfisca').val() == "2") {//fic

            if ($('#nrecur').val()) {
                alert("con recurso");
            }
            if (!$('#nrecur').val()) {
                alert("sin recurso");
            }

            if (($('#citacion').val() == "33" || $('#citacion').val() == "411" || $('#citacion').val() == "415") && $('#nrecur').val()) {//con recurso, personal, correo fisico
                $("#c65").css("display", "block");
                myDivObj = document.getElementById("c65");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c65").css("display", "none");
                $("#rgestion").val("131");
                $("#rgestion2").val("124");
            }
            if (($('#citacion').val() == "33" || $('#citacion').val() == "411" || $('#citacion').val() == "415") && !$('#nrecur').val()) {//SIN recurso, personal, correo fisico
                $("#c665").css("display", "block");
                myDivObj = document.getElementById("c665");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c665").css("display", "none");
                $("#rgestion").val("131");
                $("#rgestion2").val("124");
            }

            if (($('#citacion').val() == "35" || $('#citacion').val() == "311" || $('#citacion').val() == "415") && !$('#nrecur').val()) {// sin recurso y aviso
                $("#c66").css("display", "block");
                myDivObj = document.getElementById("c66");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c66").css("display", "none");
                $("#rgestion").val("132");
                $("#rgestion2").val("125");
            }
            if (($('#citacion').val() == "35" || $('#citacion').val() == "311" || $('#citacion').val() == "415") && $('#nrecur').val()) {// con recurso, aviso
                $("#c67").css("display", "block");
                myDivObj = document.getElementById("c67");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c67").css("display", "none");
                $("#rgestion").val("133");
                $("#rgestion2").val("126");
            }
        }
        if ($('#cfisca').val() == "3") {//contratos de aprendizaje
            if ($('#citacion').val() == "33" && $('#nrecur').val()) {//con recurso, personal, correo fisico
                $("#c61").css("display", "block");
                myDivObj = document.getElementById("c61");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c61").css("display", "none");
                $("#rgestion").val("127");
                $("#rgestion2").val("120");
            }

            if ($('#citacion').val() == "33" && !$('#nrecur').val()) {//sin recurso, personal, correo fisico

                $("#c62").css("display", "block");
                myDivObj = document.getElementById("c62");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c62").css("display", "none");
                $("#rgestion").val("128");
                $("#rgestion2").val("121");
            }
            if ($('#citacion').val() == "35" && $('#nrecur').val()) {// con recurso, aviso
                $("#c63").css("display", "block");
                myDivObj = document.getElementById("c63");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c63").css("display", "none");
                $("#rgestion").val("129");
                $("#rgestion2").val("122");
            }
            if ($('#citacion').val() == "35" && !$('#nrecur').val()) {// sin recurso y aviso
                $("#c64").css("display", "block");
                myDivObj = document.getElementById("c64");
                $("#informacion").val(myDivObj.innerHTML);
                $("#c64").css("display", "none");
                $("#rgestion").val("130");
                $("#rgestion2").val("123");
            }
        }

        if ($('#cfisca').val() == "5") {// Multas del ministerio
            $("#c70").css("display", "block");
            myDivObj = document.getElementById("c70");
            $("#informacion").val(myDivObj.innerHTML);
            $("#c70").css("display", "none");
            $("#rgestion").val("878");
            $("#rgestion2").val("877");
        }
    });

</script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });

    $('.modal').on('hidden', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
//        $('#modal2').modal('hide');
    });

    $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();

    });
//    $('#cancelar2').on('click', function() {
//        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
////        $('#modal2').modal('hide').removeData();
//        
//    });

    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }

    $('#generarPdf').on('click', function() {
        var informacion = tinymce.get('informacion').getContent();
        var cod_prc = $('#cfisca').val();
        var cfisc = $('#codfisc').val();
        var nresol = $('#nresol').val();
        var cod_estado = $('#cod_estado').val();
        var gnit = $('#gnit').val();
        var rges2 = $('#rgestion2').val();
        var nombre = "Ejec" + cfisc + "_" + nresol;
        var url = "<?= base_url() ?>index.php/ejecutoriaactoadmin/pdf";
        redirect_by_post(url, {
            informacion: informacion,
            nombre: nombre,
            cfisc: cfisc,
            gnit: gnit,
            rges2: rges2,
            cod_estado: cod_estado,
            nresol: nresol,
            cod_prc: cod_prc
        }, true);
        location.href = "<?= base_url('index.php/ejecutoriaactoadmin') ?>";
    });


</script>

