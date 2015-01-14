<?php
$nit_calculos       =   array('name'=>'nit','class'=>'search-query','id'=>'nit','required');
$desde_calculos     =   array('name'=>'desde_calculos','id'=>'desde_calculos','required','class'=>'input-small search-query','onkeypress'=>'return prueba(event)');
$hasta_calculos     =   array('name'=>'hasta_calculos','id'=>'hasta_calculos','required','class'=>'input-small search-query','onkeypress'=>'return prueba(event)');
$porcentaje_calculos=   array('name'=>'calculos','id'=>'calculos','value'=>'porcentaje_calculos','type'=>'radio','class'=>'habilitar','required');
$ibc_calculos       =   array('name'=>'calculos','id'=>'calculos','value'=>'ibc_calculos','type'=>'radio','class'=>'habilitar','required');
$salario_calculos   =   array('name'=>'calculos','class'=>'search-query','id'=>'calculos','value'=>'salario_calculos','type'=>'radio','class'=>'habilitar','required');
$cuadro_calculos    =   array('name'=>'cuadro_calculos','id'=>'cuadro_calculos','class'=>'input-mini search-query');
$ibcvalor_calculos  =   array('name'=>'ibcvalor_calculos','id'=>'ibcvalor_calculos','class'=>'input-mini search-query','disabled'=>'true');
$button             =   array('name'=>'cargar_calculos','id'=>'cargar_calculos','content'=>'<i class=""></i> Cargar','class'=>'btn btn-success btn1');
$button1            =   array('name'=>'generardevolucion_calculos','id'=>'generardevolucion_calculos','type'=>'submit','content'=>'<i class=""></i> Generar Solicitud Devolucion','class'=>'btn btn-success btn1');
$button2            =   array('name'=>'aceptar_calculos','id'=>'aceptar_calculos','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button3            =   array('name'=>'exportar_calculos','id'=>'exportar_calculos','content'=>'<i class=""></i> Exportar','class'=>'btn btn-success btn1');
$button4            =   array('name'=>'generaracta_calculos','id'=>'generaracta_calculos','value'=>'acta','type'=>'submit','content'=>'<i class=""></i> Generar Acta Contabilidad','class'=>'btn btn-success btn1');
$button5            =   array('name'=>'generaractafis_calculos','id'=>'generaractafis_calculos','content'=>'<i class=""></i> Generar Acta Fiscalizacion','class'=>'btn btn-success btn1');
$attributes             = array('name'=>'form','id'=>'form');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;padding-top: 30px;">
    <div><?php
if (isset($message)){
    echo $message;
   }
   ?>
    </div>
<?= form_open(base_url('index.php/devolucion/manage'))?>
    <table>
        <tr>
            <td><?=form_label('Nit','nit_calculos')?></td><td><?=form_input($nit_calculos)?></td>
        </tr>
        <tr>
            <td><?=form_label('Periodo','periodo_calculos')?></td>
        </tr>
    </table>
    <table>
        <tr>
            <td><?= form_label('Desde','desde_calculos')?></td><td><?=form_input($desde_calculos)?></td>
            <td><?= form_label('Hasta','hasta_calculos')?></td><td><?=form_input($hasta_calculos)?></td>
        </tr>
    </table>
    <?=form_label('Tipo Calculo','tipo_calculos')?>
    <table cellpadding="15%">
        <tr>
            <td><?= form_label('Porcentaje','porcentaje_calculos')?></td><td><?= form_checkbox($porcentaje_calculos);?></td><td><?= form_input($cuadro_calculos);?></td>
        </tr>
        <tr>
            <td><?= form_label('IBC','ibc_calculos')?></td><td><?= form_checkbox($ibc_calculos);?></td><td><?= form_input($ibcvalor_calculos);?></td>
        </tr>
        <tr>
            <td><?= form_label('Salario Integral','salario_calculos')?></td><td><?= form_checkbox($salario_calculos);?></td>
        </tr>

    </table>
    <table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button)?></td><td><?= form_button($button1)?></td><td><td><?= form_button($button4);?></td></td>
        </tr>
    </table>
</div>
<?= form_close()?>
<?= form_open(base_url('index.php/devolucion/exportar_excel'),$attributes)?>
<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
<div id="realizarcalculotabla">
    <div id="exportar1">
        <table id="realizar_cal">
            
            <thead>
           <tr>
            <th >Planilla</th>
            <th>Periodo</th>
            <th>Nombre</th>
            <th>Porcentaje</th>
           <th>IBC Errado</th>
             <th>Pago Errado</th>
            <th>IBC Real</th>
            <th>Pago Correcto</th>
            <th>Diferencia</th>
          </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
    <table align="center" cellpadding="15%">
    <tr>
        <td><a href="<?=base_url('index.php/')?>"><?= form_button($button2);?></a></td>
        <td><?= form_button($button3);?></td>
        
        <td><?= form_button($button5);?></td>
    </tr>
</table>
    <?= form_close()?>
</div>
<div class="modal hide fade in" id="modal-acta" style="display: none; width: 60%; margin-left: -30%;">
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

  <div id="mensaje" style="display: none;">No se han ingresado los datos requeridos</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
	$("#exportar_calculos").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#realizar_cal").eq(0).clone()).html());
		$("#form").submit();
        });
        
        $('#desde_calculos').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"              
        });
        $('#hasta_calculos').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
        });
});
var oTable;
$(function () {
    $('#generardevolucion_calculos').attr("disabled", true);
    $('#aceptar_calculos').attr("disabled", true);
    $('#exportar_calculos').attr("disabled", true);
    $('#generaracta_calculos').attr("disabled", true);
    $('#generaractafis_calculos').attr("disabled", true);
    $("#desde_calculos").datepicker();
    $('#hasta_calculos').datepicker({
            onSelect: function(dateText, inst) {
            var lockDate = new Date($('#desde_calculos').datepicker('getDate'));
            //lockDate.setDate(lockDate.getDate() + 1);
            $('#hasta_calculos').datepicker('option', 'minDate', lockDate);
            }
        });
    });

//    $('#realizar_cal').dataTable( {
//         "bJQueryUI": true,
//            "bProcessing": true,
//            "sPaginationType": "full_numbers",
//            "bPaginate": false,
//            "bScrollCollapse": true
//    });
    $('.habilitar').click(function(){
        var tipo_cal= $("#calculos:checked").val();

        if(tipo_cal=="ibc_calculos"){
            $('#ibcvalor_calculos').attr("disabled", false);
        }
        else{
            $('#ibcvalor_calculos').attr("disabled", true);
        }
    });    

    $('#cargar_calculos').click(function(){        
        var nit_cal = $("#nit").val();
        var des_cal = $("#desde_calculos").val();
        var has_cal = $("#hasta_calculos").val();
        var cua_cal = $("#cuadro_calculos").val();
        var tipo_cal= $("#calculos:checked").val();
        var ibcval_cal= $("#ibcvalor_calculos").val();        
        var url = "<?= base_url('index.php/devolucion/calculos')?>";
        if( tipo_cal == "ibc_calculos" ){                    
            if(nit_cal && des_cal && has_cal && cua_cal && ibcval_cal){
                oTable.fnDraw();
            }else{
                $("#mensaje").dialog({width: 100, height: 100, show: "scale", hide: "scale", resizable: "false", position: "center", modal: "true"});
            }
        }else{
            if(nit_cal != false && des_cal && has_cal && cua_cal != false){
                oTable.fnDraw();
            }else{
                $("#mensaje").dialog({width: 300, height: 100, show: "scale", hide: "scale", resizable: "false", position: "center", modal: "true"});
            }
        }
    });
    var gestion ='';
    var gestion2='';
oTable = $('#realizar_cal').dataTable( {        
        "sServerMethod": "POST", 
        "bJQueryUI":    true,
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
            "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "iDisplayStart" : 0,
        "iDisplayLength" : 10    ,
        "sSearch": '',
        "fnServerParams": function ( aoData ) {
            aoData.push( 
            { "name":"nit_cal", "value":$("#nit").val()},
            { "name":"tipo_cal","value":$("#calculos:checked").val()},
            { "name":"cua_cal", "value":$("#cuadro_calculos").val()},
            { "name":"des_cal", "value":$("#desde_calculos").val()},
            { "name":"has_cal", "value":$("#hasta_calculos").val()});
        },
        "autoWidth": true,
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,        
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?=base_url()?>index.php/devolucion/getData1",          
        "aoColumns": [  
               {   "sClass": "center",
                   "mDataProp": "COD_PLANILLAUNICA" },  
               {   "sClass": "center", 
                   "mDataProp": "PERIDO_PAGO" },  
               {   "sClass": "center",
                   "mDataProp": "PRIMER_APELLIDO" }, 
               {   "sClass": "center",
                   "mData": null, "fnRender": function (oObj) {
                       if(oObj.aData.COD_PLANILLAUNICA){
                            $('#aceptar_calculos').attr("disabled", false);
                            $('#exportar_calculos').attr("disabled", false);
                            $('#generaracta_calculos').attr("disabled", false);
                            $('#generaractafis_calculos').attr("disabled", false);
                            $('#generardevolucion_calculos').attr("disabled", false);
                            var can_reg = $("#cantidad_registros").val();
                            $("#canti_regis").val(can_reg);                           
                       }
                       else{
                            $('#aceptar_calculos').attr("disabled", true);
                            $('#exportar_calculos').attr("disabled", true);
                            $('#generaracta_calculos').attr("disabled", true);
                            $('#generaractafis_calculos').attr("disabled", true);
                            $('#adicionar_calculos').attr("disabled", true);
                            $('#generardevolucion_calculos').attr("disabled", true);
                           
                       }
                        if($("#calculos:checked").val()=="porcentaje_calculos")            
                        gestion = $("#cuadro_calculos").val();
                    else
                       gestion = $("#cuadro_calculos").val();                         
                        return gestion;
                      }}, 
               {   "sClass": "center",
                   "mData": null,"fnRender": function (oObj1) {
                        if($("#calculos:checked").val() == "porcentaje_calculos")
                            gestion1 = oObj1.aData.APORTE_OBLIG*0.02;
                        else
                            gestion1 = $("#ibcvalor_calculos").val()*0.02;                        
                        return gestion1;
                      }},
               {   "sClass": "center",
                   "mData": null,"fnRender": function (oObj2) {
                        if($("#calculos:checked").val() == "porcentaje_calculos")
                            gestion2 = (oObj2.aData.APORTE_OBLIG*0.02)*($("#cuadro_calculos").val()/100);
                        else
                            gestion2 = "";                        
                        return gestion2;
                      }},
               {   "sClass": "center",
                   "mData": null,"fnRender": function (oObj3) {
                        if($("#calculos:checked").val() == "porcentaje_calculos")
                            gestion3 = ((oObj3.aData.APORTE_OBLIG*0.02)*($("#cuadro_calculos").val()/100))*0.02;
                        else
                            gestion3 = $("#ibcvalor_calculos").val();                        
                        return gestion3;
                      }},
               {   "sClass": "center",
                   "mData": null,"fnRender": function (oObj4) {
                        if($("#calculos:checked").val() == "porcentaje_calculos")
                            gestion4 = (oObj4.aData.APORTE_OBLIG*0.02)-((oObj4.aData.APORTE_OBLIG*0.02)*($("#cuadro_calculos").val()/100))*0.02;
                        else
                           gestion4 = $("#ibcvalor_calculos").val()*($("#cuadro_calculos").val()/100);                        
                        return gestion4;
                      }},
               {   "sClass": "center",
                   "mData": null,"fnRender": function (oObj5) {
                        if($("#calculos:checked").val() == "porcentaje_calculos")
                            gestion5 = (oObj5.aData.APORTE_OBLIG*0.02)*($("#cuadro_calculos").val()/100)-(oObj5.aData.APORTE_OBLIG*0.02)-((oObj5.aData.APORTE_OBLIG*0.02)*($("#cuadro_calculos").val()/100))*0.02;
                        else
                            gestion5 = $("#ibcvalor_calculos").val()*($("#cuadro_calculos").val()/100);                                                    
                        return gestion5;
                      }} 
                  
           ]

    } );     

</script>
