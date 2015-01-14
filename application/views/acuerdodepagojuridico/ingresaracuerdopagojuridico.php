<div id="totalgarantias" class='modal hide fade modal-body' align="center"></div>
<div align="center" style='background-color: #FC7323; border-radius: 12px 12px 12px 12px'><h3 style="color : white;">FACILIDAD PARA EL PAGO DE OBLIGACIONES</h3></div>
<div align='center'>    <br><?= $nombreusuario ?>
    <br><?= $cargousuario ?><br></div>
<br>
<table id="estilotabla">
    <thead>
        <tr>
            <th>No Identificaón</th>
            <th>Ejecutado</th>
            <th>No Liquidación</th>
            <th>Autorización</th>
            <th>Garantía</th>
            <th>Crear Acuerdo</th>
            <th>Máxima Cuotas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($acuerdos as $ac) { ?>
            <tr><td><?= $ac['CODEMPRESA'] ?></td>
                <td><?= $ac['RAZON_SOCIAL'] ?></td>
                <td><?= $ac['NUM_LIQUIDACION'] ?></td>
                <?php if($ac['EXCEPCION'] == "" && $ac['EXC'] == "" or $ac['EXCEPCION'] == 2 && $ac['EXC'] == ""){ ?>
                <td style="cursor: pointer" class="autorizacion" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" resolucion="<?= $ac['NUMERO_RESOLUCION'] ?>" excepcion="<?= $ac['EXCEPCION'] ?>" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>">
                    <i class="fa fa-circle-o">&nbsp;Autorizacion</i>        
                </td>
                <?php }else if($ac['EXCEPCION'] == 1 && $ac['EXC'] == ''){?>
                    <td> En espera de Autorización</td>
                <?php }else if($ac['EXCEPCION'] == 1 && $ac['EXC'] == 3){ ?>
                    <td> Solicitud Autorizada</td>
                <?php }else if($ac['EXCEPCION'] == 1 && $ac['EXC'] == 4){ ?>
                <td style="cursor: pointer" class="autorizacion" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" resolucion="<?= $ac['NUMERO_RESOLUCION'] ?>" excepcion="<?= $ac['EXCEPCION'] ?>"  exc="<?= $ac['EXC']  ?>" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>">
                    <i class="fa fa-circle-o">&nbsp;Solicitud Rechazada <br> ¿Solicitar Nuevamente Autorización?</i>        
                </td>
                <?php } ?>
                <td>
                    <button type="button" nit="<?= $ac['CODEMPRESA'] ?>" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" resolucion="<?= $ac['NUMERO_RESOLUCION'] ?>" liquidacion="<?= $ac['NUM_LIQUIDACION'] ?>" excepcion="<?= $ac['EXCEPCION'] ?>" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>" class="creagarantia btn btn-success"><i class="fa fa-circle-o">&nbsp;Crear Garantia</i> </button> <?php
//                        } ?>
                    <?php if (!empty($ac['CODIGO'])): ?>    
                        <button type="button" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" resolucion="<?= $ac['NUMERO_RESOLUCION'] ?>" documento="<?= $ac['CODEMPRESA'] ?>" liquidacion="<?= $ac['NUM_LIQUIDACION'] ?>" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>" class="garantias btn btn-success" razon="<?= $ac['RAZON_SOCIAL'] ?>"><i class="fa fa-folder-open-o">Garantia</i></button>
                    <?php endif; ?>    
                </td>
                <td>
                <?php if (!empty($ac['CODIGO']) or $ac['EXC'] == 3) { ?>
                    <button fiscalizacion="<?= $ac['COD_FISCALIZACION'] ?>" intereses="<?= $ac['VALOR_INTERESES'] ?>" abono="<?= $ac['ABONO_INICIAL'] ?>" plazo="<?= $ac['CUOTAS'] ?>" type="button" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" resolucion="<?= $ac['NUMERO_RESOLUCION'] ?>" nit="<?= $ac['CODEMPRESA'] ?>" liquidacion="<?= $ac['NUM_LIQUIDACION'] ?>" proceso="<?= $ac['NUMERO_PROCESO'] ?>" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>" class="acuerdo btn btn-success"><i class="fa fa-folder-o">Acuerdo</i></button></td>
            <?PHP } ?>
                <td>
                    <?php if (!empty($ac['CODIGO']) or $ac['EXC'] == 3) { ?>
                    <?php if(!empty($ac['CUOTAS']) || ( empty($ac['CUOTAS']) and empty($ac['ESPERA'])) ){?>
                    <button type="button" plazo="<?= $ac['CUOTAS'] ?>" class="cuotas btn btn-success" acuerdo="<?= $ac['COD_ACUERDOJURIDICO'] ?>" valorafinanciar="<?= $ac['VALOR_A_CALCULAR'] ?>" >Cuotas</button>
                    <?php }else if(empty($ac['CUOTAS']) && !empty($ac['ESPERA']) ){
                       echo "En espera de autorización";
                    }?>
                    <?PHP } ?>
                </td>
            </tr>
<?php } ?>
    </tbody>
</table>
<div id="solicitudautorizacion" class="modal hide fade modal-body">
    <table>
        <tr><td>Enviar solicitud autorizacion</td>
            <td><select id="envioautorizacion" style="width: 70px"><option value="1">SI</option><option value="2">NO</option></select></td></tr>
        <tr><td><button type="button" id="guardar" class="btn btn-success">Guardar</button></td></tr>
    </table>
</div>

<div id="avisoautorizacion" class="modal hide fade modal-body" align="center"><h4>SOLICITUD EN ESPERA DE AUTORIZACION</h4></div> 
<form method="post" id="ingresaracuerdo">
    <input type="hidden" id="intereses" name="intereses">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="abono" name="abono">
    <input type="hidden" id="liquidacion" name="liquidacion">
    <input type="hidden" id="acuerdo" name="acuerdo">
    <input type="hidden" id="resolucion" name="resolucion">
    <input type="hidden" id="juridico" name="juridico" value="2">
    <input type="hidden" id="valorafinanciar" name="valorafinanciar" >
    <input type="hidden" id="plazoautorizado" name="plazoautorizado" >
    <input type="hidden" id="numeroproceso" name="numeroproceso" >
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">
</form>
<div id="loadgarantia"></div>
<div id="consultacuotas"></div>
<div id="consultacuotasaplazadas"></div>
<script>
    $('#consultacuotas').hide();
//--------------------------------------------------------------------------------------------    
//Calcular Cuotas    
//--------------------------------------------------------------------------------------------
    $('.cuotas').click(function(){
        $('#consultacuotas *').remove();
        $('#consultacuotasaplazadas *').remove();
        var plazo = $(this).attr('plazo');
        var acuerdo = $(this).attr('acuerdo');
        
        if(plazo == ''){
        
        
        var acuerdo = $(this).attr('acuerdo');
        var valorafinanciar = $(this).attr('valorafinanciar');
        var url = "<?= base_url('index.php/acuerdodepago/calcularcuota') ?>";
        $.post(url,{acuerdo : acuerdo , valorafinanciar : valorafinanciar},function(data){
            $('#consultacuotas').dialog({
                autoOpen : true,
                width : 400,
                modal : true
            });
            var table = "<table align='center'>";
            table += "<tr><td align='center'><b>Plazo Máximo en Meses</b></td></tr>"
            $.each(data,function(key,val){
                table += "<tr><td align='center' id='plazo'>"+val.PLAZOMESES+"</td></tr>"
            });
            table += "<tr><td style='color : red' align='center'><b>Desea enviar autorizar para el  aumento <br>de plazo en meses</b></td></tr>";
            table += "<tr><td><textarea style='width: 315px; height: 57px;' id='observacion'></textarea></td></tr>";        
            table += "<tr><td align='center'><button acuerdo='"+acuerdo+"' id='envio' type='button' class='btn btn-success'>Enviar autorizar</button></td></tr>";
            
            table += "<table>";
            $('#consultacuotas').append(table);
            
            $('#envio').on('click',function(){
                if($('#error').length != 0){
                    $('#error').remove();
                }
                if($('#observacion').val() != ''){
                $(this).remove();
                var acuerdo = $(this).attr('acuerdo');
                var observacion = $('#observacion').val();
                var plazo = $('#plazo').text();
                var url = "<?= base_url('index.php/acuerdodepago/solicitudautorizacion') ?>";
                $.post(url,{acuerdo : acuerdo, plazo : plazo,observacion : observacion},function(data){
                    $('#consultacuotas').dialog('close');
//                    location.reload(); 
                });
                }else{
                    if($('#error').length == 0){
                    var error = "<tr id='error'><td align='center' style='color : red'><b>Por favor Ingresar la Observación</b></td></tr>"
                    $('#consultacuotas').append(error);
                    }
                }
            });
        });
        }else{
            if($('#maximoautorizado').length == 0){
            var table = "<table id='maximoautorizado'>";
            table += "<tr><td>Plazo máximo autorizado</td><td align='center'><b>"+plazo+"</b></td><tr>";
            table += "<tr><td colspan='2' style='color : green; cursor :pointer' id='observacionautorizacioncuota' acuerdo='"+acuerdo+"'><b>Observaciones</b></td><tr>";
            table += "</table>";
            
            $('#consultacuotasaplazadas').append(table);
            
             $('#observacionautorizacioncuota').on('click',function(){
                var acuerdo = $(this).attr('acuerdo');
                var url = "<?= base_url('index.php/acuerdodepago/observacionautorizaicon') ?>";
                if($('#textareaobservacion').length == 0){
                $.post(url,{acuerdo : acuerdo},function(data){
                    var fila = "<tr id='textareaobservacion'><td colspan='2'><textarea disabled='disabled' style='width: 554px; height: 223px;'>"+data+"</textarea></td></tr>"
                    $('#maximoautorizado').append(fila);
                });
                }
            });
            $('#consultacuotasaplazadas').dialog({
                width : 600,
                autoOpen : true,
                title : 'plazo máximo autorizado',
                modal : true,
                buttons : [{
                        id : 'aceptar',
                        text : 'Aceptar',
                        class : 'btn btn-success',
                        click : function(){
                            $('#consultacuotasaplazadas').dialog('close');
                        }
                }]
            });
            }
        }
    });
//--------------------------------------------------------------------------------------------
//                              Garantias
//--------------------------------------------------------------------------------------------  
    $('#preloadminicancelar').hide();
    $('.creagarantia').click(function() {
      
       
       var acuerdo = $(this).attr('acuerdo');
       var resolucion = $(this).attr('resolucion');
       var liquidacion = $(this).attr('liquidacion');
       var nit = $(this).attr('nit');
       
       var url = "<?= base_url('index.php/acuerdodepagojuridico/garantiajuridica') ?>";
       
       $('#loadgarantia').load(url,{acuerdo : acuerdo},function(){
                $('#nitt').text(nit);
                $('#resoluciont').text(resolucion);
                $('#liquidaciont').text(liquidacion);
               
                       console.log(liquidacion);
                    $('#ingresodegarantia').css({
                        'width': '1200px',
                        "position": "fixed",
                        "height": 'auto',
                        "left": "50%",
                        "margin-left": "-500px"
                    });
                    $('#ingresodegarantia').modal('show');
                    $('#ingresodegarantia').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
       });
       

       

    });

    
    
//--------------------------------------------------------------------------------------------
//                              Creacion Acuerdo
//--------------------------------------------------------------------------------------------

    $('.acuerdo').click(function() {
        var intereses = $(this).attr('intereses');
        var abono = $(this).attr('abono');
        var acuerdo = $(this).attr('acuerdo');
        var proceso = $(this).attr('proceso');
        var liquidacion = $(this).attr('liquidacion');
        var nit = $(this).attr('nit');
        var resolucion = $(this).attr('resolucion');
        var valorafinanciar = $(this).attr('valorafinanciar');
        var plazo = $(this).attr('plazo');
        var fiscalizacion = $(this).attr('fiscalizacion');
        
        $('#fiscalizacion').val(fiscalizacion);
        $('#intereses').val(intereses);
        $('#abono').val(abono);
        $('#numeroproceso').val(proceso);
        $('#plazoautorizado').val(plazo);
        $('#acuerdo').val(acuerdo);
        $('#resolucion').val(resolucion);
        $('#liquidacion').val(liquidacion);
        $('#valorafinanciar').val(valorafinanciar);
        $('#nit').val(nit);
        $("#ingresaracuerdo").attr('action', "<?= base_url('index.php/acuerdodepago/acuerdo') ?>");
        $("#ingresaracuerdo").submit();

    });


//---------------------------------------------------------------------------------------------
//                                       garantias
//------------------------------------------------------------------------------------------
    $('#totalgarantias').hide();
    $('.garantias').click(function() {
        $('#totalgarantias *').remove();
        var liquidacion = $(this).attr('liquidacion');
        var documento = $(this).attr('documento');
        var razon = $(this).attr('razon');
        var url = "<?= base_url('index.php/acuerdodepagojuridico/consultagarantiasacuerdojuridico') ?>";

        $.post(url, {liquidacion: liquidacion, documento: documento}, function(data) {
            var tabla = "<table class='table table-striped'>";
            for (var nit in data) {
                tabla += "<tr><td align='center' style='color: white; background-color : green;'><b>" + nit + "</b></td><td colspan='12' align='center' style='color: white; background-color : green;'><b>"+razon+"</b><td></tr>";
                for (var garantia in data[nit]) {
                    tabla += "<tr><td style='color : green'><b>Garantia</b></td><td style='width : 180px'>" + garantia + "</td></tr>";
                    for (var avaluo in data[nit][garantia]) {
                        tabla += "<tr><td style='width : 100px'><b>VALOR AVALUÓ POR PERITO</b></td><td style='width : 180px'>" + avaluo + "</td>";
                        for (var comercial in data[nit][garantia][avaluo]) {
                            tabla += "<td><b> VALOR COMERCIAL </b></td><td style='width : 180px'>" + comercial + "</td>";
//                           
                            $.each(data[nit][garantia][avaluo][comercial], function(key, val) {
                                tabla += "<td style='width : 180px'><b>" + key + "</b></td><td style='width : 180px'>" + val + "</td>";
                            });
                        }
                    }
                }
                tabla += "</tr>";
                tabla += "</tr>";
            }
            tabla += "</table>";

            $('#totalgarantias').append(tabla);

            $('#totalgarantias').css({
                'width': '700px',
                "position": "fixed",
                "height": 'auto',
                "top": "10%",
                "left": "60%",
                "margin-left": "-500px"
            });
            $('#totalgarantias').modal('show');
        });

    });


//-----------------------------------------------------------------------------------------------    
    $('#solicitudautorizacion').hide();
    $('#guardar').click(function() {
   
    var autorizar = $('#envioautorizacion').val();
    
    if(autorizar == 1){
        $(this).attr('disabled',true);
       var fila = $(this).attr('fila');
       console.log(fila);
       $('#estilotabla').dataTable().fnDeleteRow(fila); 
      
        var acuerdo = $(this).attr('acuerdo');
        var url = "<?= base_url('index.php/acuerdodepagojuridico/notificacionautorizacion') ?>";
        $.post(url, {acuerdo: acuerdo, autorizar: autorizar},function(data){
            $('#guardar').attr('disabled',false);
            $('#solicitudautorizacion').modal('hide');
            
        });
    }else{
        $('#solicitudautorizacion').modal('hide');
    }  
    });
    $('.autorizacion').click(function() {
        
            var tabla = $(this).parents('tr:last');
            var posicion = $('#estilotabla').dataTable().fnGetPosition(this);
            var columnas = $('#estilotabla').dataTable().fnGetData( posicion[0] );
        var excepcion = $(this).attr('excepcion');
        var exc = $(this).attr('exc');
        
        console.log(excepcion+"***"+exc);

        if (excepcion != 1 || exc == 4)
        {
            var acuerdo = $(this).attr('acuerdo');
            $('#guardar').attr('acuerdo', acuerdo);
            $('#guardar').attr('fila', posicion[0]);
            $('#solicitudautorizacion').css({
                'width': '250px',
                "position": "fixed",
                "height": 'auto',
                "top": "40%",
                "left": "80%",
                "margin-left": "-500px"
            });
            $('#solicitudautorizacion').modal('show');
        }
        else if(excepcion != 1 && exc == '')
        {
            $('#avisoautorizacion').css({
                'width': '350px',
                "position": "fixed",
                "height": 'auto',
                "top": "40%",
                "left": "80%",
                "margin-left": "-500px"
            });
            $('#avisoautorizacion').modal('show');
        }
    });
    $('#estilotabla').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
            "fnInfoCallback": null,
            }
    });
</script>    