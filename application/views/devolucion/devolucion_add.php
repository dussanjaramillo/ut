<?php 
if (isset($message)){
    echo $message;
   }
   if($viene==1){
       $nit_val             = $mostrarplanilla1->N_INDENT_APORTANTE;
       $razonso_val         = $mostrarplanilla1->NOM_APORTANTE;
       $direccion_val       = $mostrarplanilla1->DIREC_CORRESPONDENCIA;
       $periodo_val         = $mostrarplanilla1->PERIDO_PAGO;
       $fecha_val           = $mostrarplanilla1->FECHA__PAGO;
       $numeroplanilla_val  = $mostrarplanilla1->N_RADICACION;
       $valorpagado_val     = $mostrarplanilla1->APORTE_OBLIG;
       $periodo_val         = $mostrarplanilla1->PERIDO_PAGO;
       $fecha_val           = date("d/m/Y",  strtotime($fecha_val));
   }
   else{
       $nit_val             = $empresa->CODEMPRESA;
       $razonso_val         = $empresa->NOMBRE_EMPRESA;
       $direccion_val       = $empresa->DIRECCION;
       $fecha_val           = "";
       $numeroplanilla_val  = "";
       $valorpagado_val     = ""; 
       $periodo_val         = "";
   }
?>
<?php
$buttonmodal        = array('name'=> 'button','id' => 'submit-button','value' => 'Confirmar','type' => 'submit','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');
$nit                = array('name'=> 'nit1','id'=>'nit1','maxlength'=>'12','required'=>'required','placeholder' => '89422345','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$nit_val,'class'=>'search-query');
$razon              = array('name'=> 'razon1','class'=>'search-query','id'=>'razon1','maxlength'=>'30','required'=>'required','placeholder' => 'Comida Nachos','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$razonso_val);
$dire               = array('name'=> 'dir1','class'=>'search-query','id'=>'dir1','maxlength'=>'30','required'=>'required','placeholder' => 'cr 45 #49-39','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$direccion_val);
$num_rad            = array('name'=> 'num_rad1','class'=>'search-query','id'=>'num_rad1','maxlength'=>'12','required'=>'required','onkeyup'=>'contarChecks(this.form)');
$fec_rad            = array('name'=> 'fec_rad1','class'=>'search-query','id'=>'fec_rad1','maxlength'=>'50','required'=>'required','type' => 'text','onchange'=>'contarChecks(this.form)','onkeypress'=>'return prueba(event)');
$num_pla            = array('name'=> 'num_pla1','class'=>'search-query','id'=>'num_pla1','maxlength'=>'50','required'=>'required','readonly'=>'true','onkeyup'=>'contarChecks(this.form)','value'=>$numeroplanilla_val);
$val_dev            = array('name'=> 'val_dev1','class'=>'search-query','id'=>'val_dev1','maxlength'=>'50','required'=>'required','onkeyup'=>'contarChecks(this.form)');
$moti               = array('opcion_selec' => '--Seleccione--' , 'opcion1'  => 'opcion1','opcion2'    => 'opcion2','opcion3' => 'opcion3');
$conce              = array('opcion_selec' => '--Seleccione--' , 'opcion1'  => 'Aportes','opcion2'=> 'FIC','opcion3' => 'Monetización','opcion3' => 'Otros');
$infor              = array('name'=> 'infor1','class'=>'search-query','id'=>'infor1','maxlength'=>'12','required'=>'required','onkeyup'=>'contarChecks(this.form)');
$tikcet             = array('name'=> 'tikcet1','class'=>'search-query','id'=>'tikcet1','maxlength'=>'12','required'=>'required','onkeyup'=>'contarChecks(this.form)','class'=>'input-small');
$cargo              = array('opcion_selec' => '--Seleccione--' , 'opcion1'  => 'opcion1','opcion2'=> 'opcion2','opcion3' => 'opcion3');
$periodo            = array('name'=> 'periodo1','class'=>'search-query','id'=>'periodo1','maxlength'=>'12','required'=>'required','placeholder' => 'MARZO 2010','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$periodo_val);
$fecha              = array('name'=> 'fecha1','class'=>'search-query','id'=>'fecha1','maxlength'=>'12','required'=>'required','placeholder' => '03/04/2010','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$fecha_val);
$numero_planilla    = array('name'=> 'numero_plani1','class'=>'search-query','id'=>'numero_plani1','maxlength'=>'12','required'=>'required','placeholder' => '234534','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$numeroplanilla_val);
$valor_pagado       = array('name'=> 'valor_pagado1','class'=>'search-query','id'=>'valor_pagado1','maxlength'=>'12','required'=>'required','placeholder' => '$300.000','style'=>'background-color: gray;filter: alpha(opacity=50);opacity: .5;color: black;','readonly'=>'true','value'=>$valorpagado_val);
$button             = array('name'=> 'Adicionar1','id'=>'Adicionar1','value'=>'Adicionar','type'=>'submit','content'=>'<i class=""></i> Adicionar','class'=>'btn btn-success btn1');
$button1            = array('name'=> 'Revertir1','id'=>'Revertir1','value'=>'Revertir','content'=>'<i class=""></i> Revertir','class'=>'btn btn-success btn1');
$button2            = array('name'=> 'Consultar_Empleados1','id'=>'Consultar_Empleados1','content'=>'<i class=""></i> Consultar Empleados','class'=>'btn btn-success btn1');
$button3            = array('name'=> 'Adicionar_Comunicacion1','id'=>'Adicionar_Comunicacion1','content'=>'<i class=""></i> Adicionar Comunicación','class'=>'btn btn-success btn1','onclick'=>'habilitarpes()');
$button4            = array('name'=> 'Enviar_Correo1','id'=>'Enviar_Correo1','value'=>'Enviar Correo','content'=>'<i class=""></i> Enviar Correo','class'=>'btn btn-success btn1');
$buttons            = array('name'=> 'traer_planilla1','id'=>'traer_planilla1','value'=>'','content'=>'<i class=""></i> ...','class'=>'btn btn-success btn1');
$funcion_dropdown   = 'onChange="contarChecks(this.form);"';
$sesion             = array('name'=>'sesion','id'=>'sesion','type'=>'hidden','value'=>$this->session->userdata('NRO_RADICACION'));
//pestaña devolucion y correspondencia
$corres             = array('opcion_selec' => '--Seleccione--' , 'opcion1'  => 'Enviado','opcion2'    => 'No Enviado');
$numero_radicacion  = array('name'=> 'numero_radicacion','class'=>'search-query','id'=>'numero_radicacion','maxlength'=>'12','required'=>'required','readonly'=>'true','value'=>$this->session->userdata('NRO_RADICACION'));
$fecha_radicacion   = array('name'=> 'fecha_radicacion','class'=>'search-query','id'=>'fecha_radicacion','maxlength'=>'50','required'=>'required','type' => 'text','readonly'=>'true','value'=>$this->session->userdata('FECHA_RADICACION'));
$remitente          = array('name'=> 'remitente','class'=>'search-query','id'=>'remitente','maxlength'=>'12','required'=>'required','value'=>$this->session->userdata('INFORMANTE'),'readonly'=>'true');
$cargodev           = array('name'=> 'cargodev','class'=>'search-query','id'=>'cargodev','maxlength'=>'12','required'=>'required','value'=>$this->session->userdata('CARGO'),'readonly'=>'true');
//$observaciones    = array('name'=> 'obser_devycorre','id'=>'obser_devycorre','maxlength'=>'12','required'=>'required','type'=>'textarea','style'=>'witdh:50px;height=50px;');
$button5            = array('name'=> 'Aceptar','id'=>'Aceptar','value'=>'Aceptar','type'=>'submit','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button6            = array('name'=> 'Cancelar','id'=>'Cancelar','value'=>'Cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
$button7            = array('name'=> 'Enviar_correo','id'=>'Enviar_correo','value'=>'Enviar Correo','content'=>'<i class=""></i> Enviar Correo','class'=>'btn btn-success btn1');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Solicitud devolucion</a></li>
  <li><a href="#profile" id="dev">Devolucion y Correspondencia</a></li>
<!--  <li><a href="#messages" data-toggle="tab">Messages</a></li>
  <li><a href="#settings" data-toggle="tab">Settings</a></li>-->
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="home">
        <?= form_open('devolucion/adicionar');?>
        <table>
            <tr>
                <td><?=form_label('Nit','nit');?></td><td><?=form_input($nit);?></td>
                <td><?=form_label('Razon Social','rsocial');?></td><td><?=form_input($razon);?></td>
                <td><?=form_label('Direccion','direccion');?></td><td><?=form_input($dire);?></td>
        </tr>
        <tr>
               <td><?=form_label('Numero de Planilla','num_plan');?></td><td><?=form_input($num_pla);?></td><td><a href='<?= base_url().'index.php/devolucion/mostrar_planilla/'.$nit_val?>' id="Gestionar" data-toggle="modal" data-target="#modal_traer" data-keyboard="false" data-backdrop="static" title="Gestionar"><?= form_button($buttons);?></a></td>
        </tr>
        <tr colspan="3">
            <td><?=form_label('Numero de Radicación','num_rad');?></td><td><?=form_input($num_rad);?></td>
        </tr>
        <tr>
            <td><?=form_label('Fecha de Radicacion','fec_rad');?></td><td><?=form_input($fec_rad);?></td>
        </tr>
        <tr>
            <td><?=form_label('Valor Devolucion','val_dev');?></td><td><?=form_input($val_dev);?></td>
        </tr>
        <tr>
            <td><?=form_label('Motivo','moti');?></td>
            <td>
                <select name="moti1" id="moti1" onchange="contarChecks()">
            <option>--Seleccione--</option>
                <?php foreach($motivo_devol as $row){
                    echo '<option value="'.$row->COD_MOT_DEVOLUCION_DINERO.'">'.$row->NOMBRE_MOTIVO.'</option>';}?>
        </select>
            </td>
        </tr>
        <tr>
            <td><?=form_label('Concepto','conce');?></td>
            <td><select name="concep1" id="concep1" onchange="contarChecks()">
            <option>--Seleccione--</option>
                <?php foreach($concepto_devol as $row){
                    echo '<option value="'.$row->COD_CONCEPTO.'">'.$row->NOMBRE_CONCEPTO.'</option>';}?>
        </select></td>
            <td><?=form_label('Tikcet Id','tikcet');?></td><td><?=form_input($tikcet);?></td>
        </tr>
        <tr>
            <td><?=form_label('Informante','infor');?></td><td><select name="infor" id="infor" onchange="contarChecks()">
            <option>--Seleccione--</option>
                <?php foreach($usuarios as $row){
                    echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBREUSUARIO.'</option>';}?>
        </select></td>
        </tr>
          <tr>
              <td><?=form_label('Cargo','carg');?></td><td><select name="cargos1" id="cargos1" onchange="contarChecks()" >
            <option>--Seleccione--</option>
                <?php foreach($cargos as $row){
                    echo '<option value="'.$row->NOMBRECARGO.'">'.$row->NOMBRECARGO.'</option>';}?>
        </select></td>
        </tr>
        <tr>
            <td><?=form_label('Periodo','peri');?></td><td><?=form_input($periodo);?></td>
        </tr>
        <tr>
            <td><?=form_label('Fecha','fech');?></td><td><?=form_input($fecha);?></td>
        </tr>
        <tr>
            <td><?=form_label('Numero Planilla','num_plani');?></td><td><?=form_input($numero_planilla);?></td>
        </tr>
        <tr>
            <td><?=form_label('Valor Pagado','Valor_pag');?></td><td><?=form_input($valor_pagado);?></td>
        </tr>
<!--        <tr>
            <td><?=form_label('Valor Conciliado','valor_con');?></td><td><?=form_input($valor_conci);?></td>
        </tr>-->
        <?=form_input($sesion);?>
        </table>  
        <table  cellpadding="15%">
            <tr>
                <td><?= form_button($button);?></td>
                <td><a href="index"><?= form_button($button1);?></a></td>
                <td><a href="index.php/ecollect/add/"><?= form_button($button2);?></a></td>
                <td><?= form_button($button3);?></td>
                <td><a href='<?= base_url().'index.php/devolucion/formcorreo/'.$nit_val?>' id="Gestionar" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static" title="Gestionar"><?= form_button($button4);?></a></td>
            </tr>
            
        </table>
        <?= form_close();?>
    </div>
    <div class="tab-pane" id="profile">
        <?= form_open('devolucion/adicionar1');?>
        <table cellpadding="15%">
            <tr>
                <td><?=form_label('Correspondencia','correspon');?></td><td><?=form_dropdown('corres', $corres, 'opcion_selec');?></td>
            </tr>
            <tr>
                <td><?=form_label('Numero de Radicación','numero_radicacion');?></td><td><?=form_input($numero_radicacion);?></td>
            </tr>
            <tr>
                <td><?=form_label('Fecha de Radicación','fecha_radicacion');?></td><td><?=form_input($fecha_radicacion);?></td>
            </tr>
            <tr>
                <td><?=form_label('Enviado por','remitente');?></td><td><?=form_input($remitente);?></td>
            </tr>
            <tr>
                <td><?=form_label('Cargo','cargo');?></td><td><?=form_input($cargodev);?></td>
            </tr>
            <tr>
                <td><?=form_label('Observaciones','observaciones');?></td><td><textarea name="content" id="targetTextArea" style="width:500px; height: 300px"></textarea></td>
            </tr>
        </table>
        <table  cellpadding="15%" align="center">
            <tr>
                <td><?= form_button($button5);?></td>
                <td><?= form_button($button6);?></td>
                <td><a href='<?= base_url('index.php/devolucion/formcorreo')?>' id="Gestionar" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static" title="Gestionar"><?= form_button($button7);?></a></td>
            </tr>
            
        </table>
        <?= form_close();?>
        
    </div>
    
<!--  <div class="tab-pane" id="messages">...</div>
  <div class="tab-pane" id="settings">...</div>-->
</div>

</div>

  <div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Mensaje Nuevo</h4>
        </div>
        <div class="modal-body" align="center">
         <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />
        </div>
<!--          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <?=form_button($buttonmodal);?>
        </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  <div class="modal hide fade in" id="modal_traer" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccionar Planilla</h4>
        </div>
        <div class="modal-body" align="center">
         <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />
        </div>
<!--          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            
        </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
<script type="text/javascript" language="javascript" charset="utf-8">
    function prueba(e){
            tecla = (document.all) ? e.keyCode : e.which; 
            if (tecla==8) return true; // backspace
            if (tecla==32) return true; // espacio
            if (e.ctrlKey && tecla==86) { return true;} //Ctrl v
            if (e.ctrlKey && tecla==67) { return true;} //Ctrl c
            if (e.ctrlKey && tecla==88) { return true;} //Ctrl x
 
            patron = /[a-zA-Z]/; //patron
 
            te = String.fromCharCode(tecla); 
            return patron.test(te); // prueba de patron
    }
    $('.close').on('click', function() {
        $('.modal-body').html('<img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />');
        $('#modal').modal('hide').removeData();
    });
$(function () {
        if($( "#numero_radicacion" ).val()){
            $("#Adicionar1").attr("disabled", true);
            $("#Consultar_Empleados1").attr("disabled", false);
            $("#Adicionar_Comunicacion1").attr("disabled", false);
            $("#Enviar_Correo1").attr("disabled", false);
        }
        else{
            $("#Adicionar1").attr("disabled", true);
            $("#Consultar_Empleados1").attr("disabled", true);
            $("#Adicionar_Comunicacion1").attr("disabled", true);
            $("#Enviar_Correo1").attr("disabled", true);
        }
        $("#fec_rad1").datepicker({maxDate: '+0m +0w'});
        $("#fecha_radicacion").datepicker();
        
        $("#Cancelar").confirm({
        title:"Confirmacion",
        text:"¿Esta seguro de cancelar?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/devolucion/')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
    $("#Revertir1").confirm({
        title:"Confirmacion",
        text:"¿Esta seguro de cancelar?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/devolucion/realizar_calculos')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
        //$("#Cancelar").confirm();
        //$("#dev").removeAttr('data-toggle','tab');
    });
    function habilitarpes(){
        $("#dev").attr('data-toggle','tab');
        
    }
function contarChecks() {
        var nit     = $( "#nit1" ).val();
        var razon   = $( "#razon1" ).val();
        var dir     = $( "#dir1" ).val();
        var fec_rad = $( "#fec_rad1" ).val();
        var num_pla = $( "#num_pla1" ).val();
        var val_dev = $( "#val_dev1" ).val();
        var moti    = $( "#moti1" ).val();
        var concep  = $( "#concep1" ).val();
        var infor   = $( "#infor" ).val();
        var carg    = $('select[name=cargos1]').val();
        var periodo = $( "#periodo1" ).val();
        var fecha   = $( "#fecha1" ).val();
        var numero_plani = $( "#numero_plani1" ).val();
        var valor_pagado = $( "#valor_pagado1" ).val();
        var num_rad     = $( "#num_rad1" ).val();
        var valor_conci = $( "#valor_conci1" ).val();
        var tikcet  =   $("#tikcet1").val();
        //alert(infor);
        
        if( num_rad&&fec_rad&&num_pla&&val_dev&&tikcet&&infor!="--Seleccione--"&&moti!="--Seleccione--"&&concep!="--Seleccione--"&&carg!="--Seleccione--"){
          //  alert(infor);
            $("#Adicionar1").attr("disabled", false);
            $("#Consultar_Empleados1").attr("disabled", false);
            $("#Adicionar_Comunicacion1").attr("disabled", false);
            $("#Enviar_Correo1").attr("disabled", false);
            
        }
        else{
            $("#Adicionar1").attr("disabled", true);
            $("#Consultar_Empleados1").attr("disabled", true);
            $("#Adicionar_Comunicacion1").attr("disabled", true);
            $("#Enviar_Correo1").attr("disabled", true);
        }
    }
    jQuery(function ($) {
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi�', 'Juv', 'Vie', 'S�b'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S�'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    
    tinymce.init({
    
    selector: "textarea",
    theme:    "modern",
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
     // para reemplazar el contenido desde un jscript
     //tinyMCE.activeEditor.setContent("pepe");
     

</script>

