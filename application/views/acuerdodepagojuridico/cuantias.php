<input type="hidden" id="salario" value="<?= $salariominimo ?>">
<br>
<div>
    <h3>CUOTA INICIAL</h3>
    <table align='left' id="aviso" align="center" style="width: 450px; height: 100px;  border-style: ridge; box-shadow: 6px 6px 6px #444444;">
        <tr>
            <td style="width: 200px"><b>Salario Mínimo</b></td>
            <td style="width: 200px" align='right'><h4><?php echo "$ ".$salariominimo ?></h4></td>
        </tr>
        <tr>
            <td style="width: 200px"><b>Porcentaje Cuota Inicial</b></td>
            <td align="right" style="width: 200px">
                <input type="text" id="cinicial" name="cinicial" style="width: 30px"
                           <?php if(!empty($cuotainicial)){
                               echo "value='".$cuotainicial[0]['PORCENTAJE']."'";
                           } ?>
                >%
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right" style="width: 300px"><button type="button" id="guardarporcentaje" name="guardarporcentaje" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button></td>
        </tr>        
    </table>    
</div><br><br><br>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div><br><br>
<!--<div> 
    <h3>PARA INGRESAR ACUERDO DE PAGO</h3>
    <table style="width: 500px">
        <tr>
            <td>La Garantia debe ser:</td>
            <?php ?>
            <td align="right">
                <?php 
                $ultimagarantia = $ultimagarantia[0]['COD_ADMINGARANTIA'];
                ?>
                <select id="garantia" name="garantia">
                    <?php foreach($admingarantia as $garantia){ ?>
                    <option <?php if($garantia['COD_ADMINGARANTIA'] == $ultimagarantia){  echo "selected='selected'";} ?> value="<?= $garantia['COD_ADMINGARANTIA'] ?>"><?= $garantia['NOMBRE_ADMINGARANTIA'] ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2"><button type="button" id="guardargarantia" class="btn btn-success">Guardar</button></td>
        </tr>
    </table>
</div>    -->
<br><br><br>
<div style="display:none" id="error" class="alert alert-danger"></div>
<br>
<div><H3>CUANTIAS</h3></div>
<table id='table' align="center" style="width: 4000px; height: 100px;  border-style: ridge;box-shadow: 6px 6px 6px #444444;">
    <tr align="center">
        <td style="width: 300px"><b>Desde</b></td>
        <?php 
        if ($tablacuantias->num_rows == 0){
            $valorh = array('name'=>'valorh','id'=>'valorh','class'=>'cuantia number','type'=>'text','value'=>'','style'=>'width: 100px');
        }else{    
            $valorh = array('name'=>'valorh','id'=>'valorh','class'=>'cuantia number','type'=>'text','readonly'=>'true','value'=>$maxCuantia[0]['MAXIMO'],'style'=>'width: 100px');
        }
        ?>        
        <td><?= form_input($valorh) ?></td>
        <td style="width: 300px"><b>Hasta</b></td>
        <td ><input type="text" name="valord" id="valord" style="width: 100px" class="cuantia number"></td>        
<!--        <td style="width: 600px"><b>Porcentaje</b></td>
        <td><input type="text" name="porcentaje" id="porcentaje" style="width: 100px" class="cuantia"></td>-->
    
        <td style="width: 300px"><b>N. Cuotas</b></td>
        <td style="width: 300px"><input type="text" name="ncuotas" id="ncuotas" style="width: 100px" class="cuantia"></td>
    </tr>
    <tr>
        <td colspan="8"><div id="aviso" align='center'></div></td>
    </tr>
    <tr>
        <td colspan="10" align='center'><button id="guardar" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i>   Guardar</button></td>
    </tr>
</table>
<br>
<br>
<table id="estilotabla">
    <thead align='center'>  
    <th><b>Desde</b></th>
    <th><b>Hasta</b></th>    
<!--    <th><b>Porcentaje</b></th>-->
    <th><b>No. Cuotas</b></th>    
    <th><b>Eliminar</b></th>    
    </thead>
            <tbody align='center'>
                
        <?php foreach ($tablacuantias->result_array as $tabla) {?>
                <tr>
            <td align='center'><?= $tabla['DESDE'] ?></td>
            <td align='center'><?= $tabla['HASTA'] ?></td>
<!--            <td align='center'><?= $tabla['PORCENTAJE'] ?></td>-->
            <td align='center'><?= $tabla['PLAZOMESES'] ?></td>
            <td><button id="eliminar" class="eliminar btn btn-danger" name="eliminar" mayora='<?= $tabla['HASTA'] ?>'
                         menora='<?= $tabla['DESDE'] ?>' plazo='<?= $tabla['PLAZOMESES'] ?>' codigo='<?= $tabla['COD_CUANTIA'] ?>' ><i class="fa fa-minus-square"></i> Eliminar</button></td>
                </tr>
            <?php }  ?>
            </tbody>
</table>
<div id="detalleeliminar" class="eliminacion" style="display: none" align='center'>
    <b>Esta seguro de eliminar la informacion seleccionada?</b> 
</div>
<script>
//------------------------------------------------------------------------------
//   GUARDA GARANTIA ADMINISTRATIVA PARA ACUERDO DE PAGO    
//------------------------------------------------------------------------------    
    
    $('#guardargarantia').click(function(){
        var garantia = $('#garantia').val();
        var url = "<?= base_url('index.php/acuerdodepago/guardaadmingarantia') ?>";
        $.post(url,{garantia : garantia},function(){
            
        });
        
    });
    
    
//  ----------------------------------------------------------------------------
//  Guarda porcentaje cuota inicial  
//  ----------------------------------------------------------------------------  
$('#guardarporcentaje').click(function(){
    $(".ajax_load").show("slow");
    var porcentaje = $('#cinicial').val();
    var url = "<?= base_url('index.php/acuerdodepago/guardarporcentajecuotainicial') ?>";
    if($('#avisoporcentaje').length != 0 ) $('#avisoporcentaje').remove();
    
    if(porcentaje != ""){
    $.post(url,{porcentaje : porcentaje},function(){
    $(".ajax_load").hide();    
    $("#error").show();   
    $("#error").html('Porcentaje Guardado');   
    });
    }else{
        $(".ajax_load").hide();    
        $("#error").show();   
        $("#error").html('Por Favor ingresar Porcentaje para la Cuota Inicial'); 
    }
    
});    
    
    var oTable=  $('#estilotabla').dataTable({
         "bJQueryUI": true,
         "bFilter": false
     });
    
    //  $('.number').number(true,0);    
    $('#guardar').click(function(){ 
    $(".ajax_load").show("slow");       
    var mayora      =  $('#valord').val();          
    var menora      =  $('#valorh').val() ;
    //var porcentaje  = $('#porcentaje').val();
    var plazo       = $('#ncuotas').val();
        if(mayora == "" || menora == "" || plazo == ""){
            $('#error').show();               
            $('#error').html('Por Favor Ingresar los Datos que faltan');   
            $(".ajax_load").hide();
        }else if (parseInt(mayora)<parseInt(menora)){               
            $('#error').show();               
            $('#error').html('El Valor debe ser mayor al campo "Menor Igual a"');   
            $('#valord').val('');
            $('#valord').focus();
            $(".ajax_load").hide();                 
        }else{ 
         $('#error').hide();            
         $('#estilotabla').dataTable().fnClearTable();
            var url = "<?= base_url('index.php/acuerdodepago/tablacuantias') ?>";
            var tipo = "A";            
            $('#valord').val('');
            $('#ncuotas').val('');
            $.post(url, {tipo : tipo, mayora : mayora, menora : menora, //porcentaje : porcentaje,
              plazo : plazo},function(data){
               $('#error').show();               
               $('#error').html('Cuantia guardada Correctamente'); 
               window.location = '<?= base_url('index.php/acuerdodepago/cuantias') ?>';
               $.each(data, function(key, val) {
                      $('#estilotabla').dataTable().fnAddData([
                          val.COD_CUANTIA,
                          val.HASTA,
                          val.DESDE,
                          //val.PORCENTAJE,
                          val.PLAZOMESES,
                          '<button id="eliminar1" class="eliminar btn btn-danger" mayora="' + val.HASTA + '\
                          " name="eliminar" menora="' + val.DESDE + '\
                          " plazo="' + val.PLAZOMESES + '" ><i class="fa fa-minus-square"></i> Eliminar</button>'                          
                      ]);
                  });                  
                  $(".ajax_load").hide();   
              });
            }
        });
    
     $('.eliminar').on("click",function(){
             var mayora = $(this).attr('mayora');
             var menora = $(this).attr('menora');
             var porcentaje = $(this).attr('porcentaje');
             var plazo = $(this).attr('plazo');
             var id = $(this).attr('codigo');


                $('#valord').val(mayora);
                $('#valorh').val(menora);
                $('#porcentaje').val(porcentaje);
                $('#ncuotas').val(plazo);

                var confirmar = $(this);
                $('#detalleeliminar').dialog({
                    width: 400,
                    height: 200,
                    modal: true,                    
                    buttons: [{
                            id: 'confirmar',
                            text: 'Confirmar',
                            class: 'btn btn-success',
                            click: function() {
                                var url = "<?= base_url('index.php/acuerdodepago/eliminarcuantias') ?>";
                                $.post(url, {id : id});
                                confirmar.parents('tr:last').remove();
                                $('#detalleeliminar').dialog('close');
                                $('#error').show();               
                                $('#error').html('Cuantia eliminada Correctamente');                                                                                                
                            }
                        },
                        {
                            id: 'cancelar',
                            text: 'Cancelar',
                            class: 'btn btn-warning',
                            click: function() {
                                $('#detalleeliminar').dialog('close');
                            }
                        }]                           
                });   
                $('#valord').val('');
                $('#ncuotas').val('');                                               
            });
            
    $( "#valord" ).blur(function() {
        var menor = $('#valorh').val();
        var mayor = $('#valord').val();
        if (parseInt(mayor)<parseInt(menor)){
            alert ('El Valor debe ser mayor al campo "Menor Igual a"');            
            $('#valord').val('');
            $('#valord').focus();
        }        
    });
    
    $('.cuantia').change(function(){
            if (isNaN($(this).val())) {
                    $(this).val('');
                }
        });
</script>
