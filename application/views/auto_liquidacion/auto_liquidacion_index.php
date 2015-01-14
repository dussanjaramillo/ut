<?php

if (isset($message)){
    echo $message;
   }

if ($perfil == 41){
    $perfiles = 'Secretario';
    $icono = "fa-user";
}else if ($perfil == 42){
    $perfiles = 'Coordinador';
    $icono = "fa-users";
}else if ($perfil == 43){
    $perfiles = 'Abogado';
    $icono = "fa-male";
}   
   
   
?>
<br>
    <h1>Liquidación de Crédito</h1>
<br><br>
<div style="display:none" id="alert" class="alert alert-danger">
    
</div>
<br><br>
<div class="alert alert-info">
<h4 align="left">
        <i class="fa <?php echo @$icono; ?> "> <?php echo @$perfiles ?></i> 
</h4>
</div>    
<div class="modal hide fade in" id="viewDocumento" style="display: none; width: 60%; margin-left: -30%;">
  <div class="viewDocumento-dialog">
    <div class="viewDocumento-content">
      <div class="viewDocumento-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div id="subtitle" name="subtitle"></div>
      </div>
      <div class="viewDocumento-body conn">
        Cargando datos...
      </div>
      <div align="right" class="viewDocumento-footer">
          <a href="#" class="btn btn-success" data-dismiss="modal"><i class="fa fa-trash-o"></i> Cerrar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<table id="tablaq">
    <thead>      
       <tr>
        <th>Identificaci&oacute;n</th>
        <th>Ejecutado</th>
        <th>Num. Proceso</th>                
        <th>Ver Estado</th>
        <th>Tipo Auto</th>
        <th>Documentos</th>
        <th>Seleccionar</th>
      </tr>
    </thead>
    <tbody>
            <?php   
            if (@$registros != ''){
            foreach (@$registros->result_array as $value) {                
                ?>
        <tr>
            <td align="center"><?= $value['CODEMPRESA'] ?></td>
            <td align="center"><?= $value['NOMBRE_EMPRESA'] ?></td>
            <td align="center"><?= $value['COD_FISCALIZACION'] ?></td>            
            <td align="center"><?= $value['NOMBRE_GESTION'] ?></td>
            <td align="center"><?= $value['DESCRIPCION_AUTO'] ?></td>
            <td align="center">
                <button class="viewDocumento" title="Ver Documentos" data-toggle="modal" data-target="#viewDocumento" clave="<?= $value['NUM_AUTOGENERADO'] ?>" fiscalizacion="<?= $value['COD_FISCALIZACION'] ?>" gestion="<?= $value['COD_RESPUESTA'] ?>" nit="<?= $value['CODEMPRESA'] ?>" ><i class="fa fa-folder-open"></i></button>
            </td>
            <td align="center">
                <input type="radio" id="rdbNit" name='rdbNit' gestion="<?= $value['COD_RESPUESTA'] ?>" clave="<?= $value['NUM_AUTOGENERADO'] ?>" nit="<?= $value['CODEMPRESA'] ?>" class="opcion" razon="<?= $value['NOMBRE_EMPRESA'] ?>" fiscalizacion="<?= $value['COD_FISCALIZACION'] ?>" tipo_auto="<?= $value['COD_TIPO_AUTO'] ?>">
            </td>
        </tr>
                <?php                
            }            
          }
            ?>
    </tbody>     
</table>
<br>

<div align="center" id="error"></div>
<br>
<div id='dialog' style="display: none;">
    <center>
        <p>Se Objeta Liquidación?</p>
    </center>  
</div>
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>

<form id="frmTmp" method="POST">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion">
    <input type="hidden" id="nombre" name="nombre">
    <input type="hidden" id="clave" name="clave">
    <input type="hidden" id="gestion" name="gestion">
    <input type="hidden" id="tipo_auto" name="tipo_auto">
    <input type="hidden" id="perfil" name="perfil" value="<?php echo $perfil ?>">     
</form>

<script type="text/javascript" language="javascript" charset="utf-8"> 
    $('#tablaq').dataTable({
        "bJQueryUI": true,
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
        }
   });
   
    
    $('.opcion').click(function(){
        var nit = $(this).attr('nit');
        var fisca = $(this).attr('fiscalizacion');
        var name = $(this).attr('razon');
        var clave = $(this).attr('clave');
        var gestion = $(this).attr('gestion');        
        var tipo_auto = $(this).attr('tipo_auto');        
        $('#nit').val(nit);
        $('#cod_fiscalizacion').val(fisca);
        $('#nombre').val(name);
        $('#clave').val(clave);
        $('#gestion').val(gestion);    
        $('#tipo_auto').val(tipo_auto);
        //alert (gestion+'***'+tipo_auto);
        switch (tipo_auto){
            case '3':
                if (gestion == '1132' || gestion == '1133' || gestion == '1134' || gestion == '1135' || gestion == '1136' || gestion == '1137'){   
                $(".ajax_load").show("slow");
                $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editAuto')?>");
                $('#frmTmp').submit();
                }else if (gestion == '1138' || gestion == '1160'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/correo')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '450' || gestion == '451' || gestion == '1153' || gestion == '1152'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editCorreo')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '452'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/pagina')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '453' || gestion == '1155' || gestion == '1154'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editPagina')?>");
                    $('#frmTmp').submit();
                }else if(gestion == '454' || gestion == '800'){  
                    var url         = "<?=base_url()?>index.php/auto_liquidacion/guardar_objecion";
                    var url1         = "<?=base_url()?>index.php/auto_liquidacion/identBien";
                    $("#dialog").dialog({width: 300, height: 150, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});    
                    $("#dialog").dialog({
                    buttons: [{
                        id: "si",
                        text: "Si",
                        class: "btn btn-success",
                        click: function() {
                            $(".ajax_load").show("slow");
                            $.post( url, { nit:nit,cod_fiscalizacion:fisca,nombre:name,
                            clave:clave,gestion:gestion } ); 
                            $("#frmTmp").attr("action", url);
                            $('#frmTmp').submit();
                        }
                    },
                    {
                        id: "no",
                        text: "No",
                        class: "btn btn-success",
                        click: function() {
                            $(".ajax_load").show("slow");
                            $.post( url1, { nit:nit,cod_fiscalizacion:fisca,nombre:name,
                            clave:clave,gestion:gestion } );
                            $("#frmTmp").attr("action", url1);
                            $('#frmTmp').submit();    
                        }                                            
                        }]
                    });
                }if (gestion == '840'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/addAutoObj')?>");
                    $('#frmTmp').submit();
                }else 
               break;
            case '24':
                if (gestion == '1132' || gestion == '1133' || gestion == '1134' || gestion == '1135' || gestion == '1136' || gestion == '1137'){   
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editAutoObj')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '1138' || gestion == '1160'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/correoObjec')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '450' || gestion == '451' || gestion == '1153' || gestion == '1152'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editCorreoObjec')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '452'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/paginaObjec')?>");
                    $('#frmTmp').submit();
                }else if (gestion == '453' || gestion == '1155' || gestion == '1154'){
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/auto_liquidacion/editPaginaObjec')?>");
                    $('#frmTmp').submit();
                }else if(gestion == '454' || gestion == '800'){                      
                    $('#alert').show();
                    $('#alert').html("<b>Identificar Tipo de Bien Embargado</b>");                    
                }
                break;
        }            
    });       
        
     $('.viewDocumento').click(function(){
            var clave = $(this).attr('clave');
            var fisca = $(this).attr('fiscalizacion');            
            var nit = $(this).attr('nit');
            var gestion = $(this).attr('gestion');
            $('#clave').val(clave);
            $('#cod_fiscalizacion').val(fisca);            
            $('#nit').val(nit);            
            $('#gestion').val(gestion);   
    });
    
     $(function() {
        $('#viewDocumento').on('show',function() {
            var clave = $('#clave').val();
            var fisca = $('#cod_fiscalizacion').val();
            var gestion = $('#gestion').val();
            //alert (clave+'***'+fisca+'***'+gestion);
            if (clave!="") {
                $(".ajax_load").show("slow");
                $.ajax({
                    type: "POST",
                    url: "documentos",
                    data: { clave: clave, fisca:fisca, gestion:gestion },
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.conn').html("No hay Datos");
            }
        });
     })
 

    
</script>