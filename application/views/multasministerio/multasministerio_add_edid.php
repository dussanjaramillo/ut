<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
// Responsable: Leonardo Molina
$timestamp = now();
$timezone = 'UM5';
$daylight_saving = FALSE;
$datestring = "%d/%m/%Y";
$now = gmt_to_local($timestamp, $timezone, $daylight_saving);

$attributes = array('class' => 'mycustomclass','style' => 'color: #000; font-weight:bold;'); 
$nit        = array('name'=> 'nit','id'=>'nit','value'=>$registros->CODEMPRESA,'type'=>'hidden');
$rsocial    = array('name'=> 'rsocial','id'=>'rsocial','value'=>$registros->NOMBRE_EMPRESA,'maxlength'=>'128','disabled'=>'disabled');
$nradicado  = array('name'=> 'nradicado','id'=>'nradicado','value'=>$registros->NRO_RADICADO,'maxlength'=>'12','required'=>'required','autofocus'=>"true",'onchange'=>"numerico('nradicado')");
$nis        = array('name'=> 'nis','id'=>'nis','value'=>$registros->NIS,'maxlength'=>'10','required'=>'required','onchange'=>"numerico('nis')");
$nresol     = array('name'=> 'nresol','id'=>'nresol','value'=>$registros->NRO_RESOLUCION,'maxlength'=>'15','required'=>'required','readonly'=>'readonly');
$valor      = array('name'=> 'valor','id'=>'valor','value'=>$registros->VALOR,'maxlength'=>'15','required'=>'required','readonly'=>'readonly');
$button     = array('name'=> 'button','id'=>'submit-button','value'=>'Guardar','type'=>'submit','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success btn1');
$fecha      = array('name'=> 'fecha1','id'=>'fecha1','value'=>mdate($datestring, $now),'readOnly'=>'true');
$pinicial   = array('name'=> 'pinicial','readonly'=>'readonly','id'=>'pinicial','required'=>'true','value'=>$registros->PERIODO_INICIAL2);
//$pfinal     = array('name'=> 'pfinal','id'=>'pfinal','required'=>'true','value'=>set_value('pfinal'));
$fejecutoria= array('name'=> 'fejecutoria','readonly'=>'readonly','id'=>'fejecutoria','required'=>'true','value'=>$registros->FECHA_EJECUTORIA2);
$chk        = array('name'=> 'exig','id'=> 'exig','value'=> '0');
$apel       = array('name'=> 'apel','id'=> 'apel','value'=> '0');
$exig       = array('name'=> 'exig1','id'=> 'exig1','type'=> 'hidden', 'value'=>set_value('exig1'));
$file       = array('name'=> 'userfile','id' => 'archivos','class' => 'validate[required]');
$fisc       = array('name'=> 'fisc','id' => 'fisc','type'=>'hidden','value'=>$registros2->COD_FISCALIZACION);
?>
    
<h1>Multas Ministerio</h1><br><br>


<div class="center-form-large" >
    <?= form_open_multipart(base_url('index.php/multasministerio/add_edid'),array('class'=>'validar_form'));?>
    <?php if(isset($custom_error)) echo $custom_error;
    if (isset($message)) echo $message;
?>
    <table>
        <tr>
            <td><?= form_label('Nit', 'nit',$attributes);?><?= form_label($registros->CODEMPRESA);?></td><?=  form_input($nit)?>
            <td><?= form_label('Razón social', 'rsocial',$attributes);?><?= form_label($registros->NOMBRE_EMPRESA);?></td>
            <td></td>
            <td><?= form_label('Fecha','fecha1',$attributes);?><?= form_input($fecha);?></td>
        </tr>
        
        <tr>
            <td style="width: 200px;"><?= form_label('Número de Radicado<span class="required">*</span>', 'nradicado',$attributes);?></td>
            <td style="width: 300px;"><?= form_input($nradicado);?><?=form_error('nradicado','<div>','</div>');?></td>
            <td style="width: 200px;"><?= form_label('NIS<span class="required">*</span>', 'nis',$attributes);?></td>
            <td style="width: 300px;"><?= form_input($nis);?><?=form_error('nis','<div>','</div>');?></td>
        </tr>
        <tr>
            <td><?= form_label('Fecha Vigencia', 'pinicial',$attributes);?></td>
            <td><?= form_input($pinicial);?></td>
<!--            <td><?= form_label('Período Final', 'pfinal',$attributes);?></td>
            <td><?= form_input($pfinal);?></td>-->
        </tr>
        <tr>
            <td><?= form_label('Número Resolución<span class="required">*</span>', 'nresol',$attributes);?>
                
            </td>
            <td><?= form_input($nresol);?><?=form_error('nresol','<div>','</div>');?>
                <button href='#' id="btn_resol" data-toggle="modal" data-target="#modal3" data-keyboard="false" data-backdrop="static" title="Respuesta Auto" class="btn btn-default" style="position: relative;float: left;">Generar Resolución...</button></td>
            <td><?= form_label('Fecha ejecutoria', 'fejecutoria',$attributes);?></td>
            <td><?= form_input($fejecutoria);?></td>
            
        </tr>
        <tr>
            <td><?= form_label('Asignar Responsable<span class="required">*</span>', 'respons',$attributes);?></td>
            <td><select class="form-control" name="respons" id="respons">
                    <option>Seleccione un usuario</option>
                <?php foreach($grupos as $row){echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?></select></td>
            <td><?= form_label('Valor<span class="required">*</span>', 'valor',$attributes);?></td>
            <td><?= form_input($valor);?><?=form_error('valor','<div>','</div>');?></td>
        </tr>
        <tr>
            <td>
                <?= form_checkbox($apel);?> Revocatoria
                
            </td>
            <td>
                <div id="exigibilidad">
                    <?= form_checkbox($chk);?> Exigibildad del título
                </div>
                <div id="subirFile" style="display: none;">
                    <span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span>
                    <div class="input-append" id="arch0">
                        <div><input type="file" name="archivo0" class="btn btn-primary file_uploader"></div>
                    </div>
                    <div id="file_source"></div>
                </div>
            </td>
            <td><?= form_input($fisc); ?></td>
            <td><?= form_button($button);?><a href="<?=  base_url('index.php/multasministerio');?>" class="btn btn-default">Volver</a></td>
        </tr>
    </table>
    <div class="file-upload">
    <div id="apelacion"></div>
</div>
   
    
</div>  
 
<!-- Modal External-->

  <div class="modal hide fade in" id="modal2" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Observaciones</h4>
        </div>
          
        <div class="modal-body">
            <h4>Genere las observaciones correspondientes para realizar la devolución de documentos.</h4>
            <div align="center">
                <textarea  id="observaciones" name="observaciones" style="width: 80%; height: 150px;" autofocus="true"></textarea>
                <table>
                    <tr>
                        <td>Número Radicado On-Base:</td>
                        <td><input type="text" name="ncomunicacion" id="ncomunicacion" /></td>
                    </tr>
                </table>
                 
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <a href="#" class="btn btn-primary mce-text" id="continuarObser">Continuar</a>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
 <?= form_close();?>
<!-- Modal External-->

  <div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Exigibilidad del Título</h4>
        </div>
          <?= form_open();?>
        <div class="modal-body">
            <p style="font: oblique bold 110% italic;  border-style:solid; border-width:1px; border-color:black;">De acuerdo a la circular conjunta No 00000031, Las providencias por las cuales se impongan multas dentro de las investigaciones administrativas laborales deberan contener, como minimo, lo siguiente:</p>
            <br>
            <table>
                <tr>
                    <td style="width: 20px;"><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox','onchange'=>'contarChecks(this.form)'));?></td><td>La individualizacion de la persona natural o juridica a sancionar. El nombre exacto de la persona natural o juridica sancionada, ciudad, direccion, inromacion que se tomara principalmente de certificado de exitencia y representacion legal de la Camara de Comercio o documento que haga sus veces.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox1','onChange'=>'contarChecks(this.form)'));?></td><td>Número de identificación tributaria (NIT) o cedula de ciudadania para las personas naturales.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox2','onChange'=>'contarChecks(this.form)'));?></td><td>El nombre del representante legal de las emrpesas o entidades.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox3','onChange'=>'contarChecks(this.form)'));?></td><td>Para el caso de los establecimientos de comercio el sancionado sera el propietario, debidadmente identificado con su cedula de ciudadania.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox4','onChange'=>'contarChecks(this.form)'));?></td><td>Analisis de los hechos y pruebas con base en los cuales se impone la sancion.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox5','onChange'=>'contarChecks(this.form)'));?></td><td>Las normas infringidas y los hechos probados.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox6','onChange'=>'contarChecks(this.form)'));?></td><td>Indica el valor exacto de la multa impuesta, en salarios minimos y en pesos.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox7','onChange'=>'contarChecks(this.form)'));?></td><td>Advertir al sancionado que, en caso de no realizar la consignacion del valor de la multa en el termino de (15) dias habilies posteriores a la ejecutoria de la resolucion que impone la multa, se cobraran intereses moratorios a la tasa legalmente prevista y se procedera al cobro de la misma.</td>
                </tr>
            </table>
            <br>
            <p style="font: oblique bold 110% italic;  border-style:solid; border-width:1px; border-color:black;">Los actos administrativos expedidso por el ministerio de trabajo a traves de las Direcciones Territoriales, que contengan una multa a favor del SENA, deben dser remitidos a la respectiva regional del sena dentro del mes siguiente en que dichos actos queden debidamente ajecutoriados, anexando los siguientes documentos:</p>
            <br>
            <table>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox8','onChange'=>'contarChecks(this.form)'));?></td><td>Primera copia autentica, que preste merito ejecutivo, del acto administrativo complejo.</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox9','onChange'=>'contarChecks(this.form)'));?></td><td>Copia de las notificaciones surtidas en el tramite de dichos actos administrativos (personal, por aviso, por publicacion, en la pagina electronica, y por inscripcion).</td>
                </tr>
                <tr>
                    <td><?= form_checkbox(array('name'=>'orderBox[]','id'=>'orderBox10','onChange'=>'contarChecks(this.form)'));?></td><td>Constancia de la ejecutoria y de la firmeza del acto administrativo.</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal" id="devolver">Devolver documento</a>
            <a href="#" class="btn btn-primary mce-text" id="continuar">Continuar</a>
        </div>
          <?= form_close();?>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php 
  $mform = array('name'=> 'mform','id' => 'mform','class' => 'form-horizontal', 'role' => 'form');
  $mnres = array('name'=> 'mnres','id' => 'mnres','class' => 'validate[required]','maxlength'=>'30');
  $mcreg = array('name'=> 'mcreg','id' => 'mcreg','class' => 'validate[required]');
  $mvalo = array('name'=> 'mvalo','id' => 'mvalo','class' => 'validate[required]','maxlength'=>'15','onkeypress' => 'return soloNumeros(event)');
  $mnit  = array('name'=> 'mnit' ,'id' => 'mnit' ,'value' => $registros->CODEMPRESA,'type'  => 'hidden');
  $mnom  = array('name'=> 'mnom' ,'id' => 'mnom' ,'value' => $registros->NOMBRE_EMPRESA,'type'  => 'hidden');
  
  ?>
  <div class="modal hide fade in" id="modal3" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Generar Resolución</h4>
        </div>
        <?= form_open(base_url().'index.php/multasministerio/addMultasResolucion',$mform);?>
        <div class="modal-body">
            <div id="mensaje"></div>
            <table>
                <tr>
                    <td>Numero Resolucion</td><td><?= form_input($mnres);?></td>
                </tr>
                <tr>
                    <td>Código Regional</td>
                    <td><select class="form-control" name="mcreg" id="mcreg">
                    <option>Seleccione regional</option>
                <?php foreach($regional as $row){echo '<option value="'.$row->COD_REGIONAL.'">'.$row->NOMBRE_REGIONAL.'</option>';}?></select></td>
                </tr>
                <tr>
                    <td>Valor</td><td><?= form_input($mvalo);?></td>
                </tr>
                <tr>
                    <td>Adjuntar</td><td><input type="file" name="archivo0" id="archivoRes" class="btn btn-primary"></td>
                </tr>
                </tr>
            </table>
        </div>
          <?= form_input($mnit);?>
          <?= form_input($mnom);?>
          
          <div id="respuesta"></div>
          
        <div class="modal-footer">
            <span id="loader" style="display: none; float: right; position: relative"><img src="<?=  base_url()?>/img/27.gif" width="40px" height="40px" /></span>
            <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
            <button href="#" type="button" class="btn btn-primary mce-text" id="continuarObser" onclick="guardar()">Continuar</button>
        </div>
          <?= form_close();?>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  
<!--
cargar:
resulucion
resolucion principal
resolucion de apelacion
las notificaciones fecha
el auto de ejecutoria fecha

todo esto en la resolucion

averiguar: fecha ejecutoria ministerio
el ministerio es el que hace ejecutoria de la multa.

Fechas de notificacion, vienen con la resolucion.-->

<script type="text/javascript" language="javascript" charset="utf-8">
    
    $('#mnres').change(function() {
        var url = "<?php echo base_url('index.php/resolucion/confirmar_resolucion') ?>";
        var id = $('#mnres').val();
        $(".preload, .load").show();
        $.post(url, {id: id})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    if (msg == ""){
                        $('#boton_finalizar').show();
                    }else {
                        $('#boton_finalizar').hide();
                        alert("Este número de Resolición ya fue usado en la fecha "+msg.FECHA_CREACION);
                        $('#mnres').val('');
                    }
//                    alert("Los datos fueron guardados con exito");
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
    });
    
    function numerico(campo){
        if(isNaN($('#'+campo).val())){
        $('#'+campo).val('');
        alert('Campo Numerico')
        }
    }
    $(function() {
        
        if($('#nresol').val()){
            $("#btn_resol").attr('disabled',true);
        }
        
        $(".validar_form").submit( function(){
            var select = $("#respons").val();
            if(select == "Seleccione un usuario"){
                alert("Debe seleccionar un Responsable")
                return false;
            }else return true;
        });
        
        $("#fejecutoria").datepicker();
        $("#pinicial").datepicker();
//        $('#pfinal').datepicker({
//            onSelect: function(dateText, inst) {
//            var lockDate = new Date($('#pinicial').datepicker('getDate'));
//            //lockDate.setDate(lockDate.getDate() + 1);
//            $('#pfinal').datepicker('option', 'minDate', lockDate);
//            }
//        });

        $(".btn1").attr('disabled','disabled');
        
        $('#cancelar').on('click', function() {
            $('#mnres').val('');
            $('#mvalo').val('');
            document.getElementById('mensaje').innerHTML = '';
            var select = $('#mcreg');
            select.val($('option:first', select).val());
        });
        $('.close').on('click', function() {
            $('#mnres').val('');
            $('#mvalo').val('');
            document.getElementById('mensaje').innerHTML = '';
            var select = $('#mcreg');
            select.val($('option:first', select).val());
        });
        $("#continuar").addClass("disabled");
        });
        $('#exig').change(function(){
            if($(this).is(":checked")) {
                $('#modal').modal('show');
//                $('#modal').st
                $('#exig').val('1');
              }else{
                  $('#exig').val('0');
              }
        });
        
        $('#devolver').click(function(){
            $("#exig").removeAttr("checked");
            $("#exig").val("0");
            $('#modal2').modal('show');
        });
        $("#continuarObser").click(function(){
            
            if($.trim($('#observaciones').val()) == ''){
                alert("El campo observaciones es Obligatorio en este caso.");
                
            }else if($.trim($('#ncomunicacion').val()) == ''){
                alert("El campo Número comunicacion es Obligatorio en este caso.");
            } else{
                $(".btn1").removeAttr("disabled");
                $("#exig").attr("disabled");
                $('#modal2').modal('hide');
            }
            
            
        });
      function contarChecks(f) {
        resu = 0;
        for (var i = 0, total = f.elements.length; i < total; i ++)
        if (f.elements[i].type == "checkbox" && f.elements[i].checked) resu ++;
        if(resu==11){//11
            $("#continuar").removeClass("disabled"); 
            $('#continuar').bind('click', function(e){
                e.preventDefault();
                $('#modal').modal('hide');
                $(".btn1").removeAttr("disabled");
                $("#exig").val("1");
                $("#subirFile").css("display", "block");
                $("#exigibilidad").css("display", "none");
                
            });
        }
        else{
            $("#exig").val("0");
            $("#continuar").addClass("disabled");
            $('#continuar').unbind('click');
            $("#subirFile").css("display", "none");
            $("#exigibilidad").css("display", "block");
        }
    }
    
    jQuery(function ($) {
        $.datepicker.regional['es'] = {
            changeYear: true,
            changeMonth: true,
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S '],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: '',
            maxDate: '+0m +0w'
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    
    // funcion para subir varios archivos al servidor
    (function() {
        jQuery("#addImg").on('click',addSource);
    })();

    function addSource(){
        var numItems = jQuery('.file_uploader').length+1;
        var template =  '<div  class="field" id="arch'+numItems+'">'+
                            '<div class="input-append">'+
                                '<div >'+
                                    '<input type="file" name="archivo'+numItems+'" id="archivo'+numItems+'"class="btn btn-primary file_uploader">'+
                                    ' <button type="button" class="close" id="'+numItems+'" onclick="deleteFile(this.id)">&times;</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }
    
    $('#apel').change(function(){
            if($(this).is(":checked")) {
                addApelacion();
              }else{
                $("#arch12").remove();
              }
        });
        
    function addApelacion(){
        if($('#exig').is(":checked")) {
            //$('#condiciones:checked').val();
            var numItems = jQuery('.file_uploader').length;
            var template = '<div class="file-upload" id="arch12" ><p style="color: #000; font-weight:bold;">Seleccione el archivo de Apelación</p><input type="file" name="archivo1" id="archivo1" class="btn file-upload file_uploader" data-classInput="input-small"></div>';
            jQuery(template).appendTo('#apelacion');
        }else{
            alert("Para adjuntar una apelación, la multa debe ser exigible.");
            $("#apel").removeAttr("checked");
        }
    }
    
    function deleteFile(elemento){
        $("#arch"+elemento).fadeOut("slow",function(){
            $("#arch"+elemento).remove();
        });
    
    }
      function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 0))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }
    function guardar() {
        var formData = new FormData($("#mform" )[0]);
        var select   = $("#mcreg").val();
        
        if($('#mnres').val()==''){
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-error">\n\
<button type="button" class="close" data-dismiss="alert">&times;\n\
</button>El campo Resolución es obligatorio.\n\
</div>';
           
        }else
        if(select == "Seleccione regional"){
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-error">\n\
<button type="button" class="close" data-dismiss="alert">&times;\n\
</button>El campo código regional es obligatorio.\n\
</div>';
        }else
        if($('#mvalo').val()==''){
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-error">\n\
<button type="button" class="close" data-dismiss="alert">&times;\n\
</button>El campo valor es obligatorio.\n\
</div>';
        }else
        if($('#archivoRes').val()==''){
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-error">\n\
<button type="button" class="close" data-dismiss="alert">&times;\n\
</button>Adjuntar el archivo es obligatorio.\n\
</div>';
        }else{
            $("#loader").show();
            var url = "<?php echo base_url() ?>index.php/multasministerio/addMultasResolucion"; // El script a dónde se realizará la petición.
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#loader").hide();
                    $("#respuesta").html(data);
                    $("#nresol").val($('#mnres').val());
                    $("#valor").val($('#mvalo').val());
                    $("#btn_resol").attr('disabled',true);
                    $('#modal3').modal('hide').removeData();
                    var data2=data.replace(' ','');
                    $('#fisc').val(data2);
//                    alert($('#fisc').val());
                }
            });
        }
    }
    $(".preload, .load").hide();
</script>


