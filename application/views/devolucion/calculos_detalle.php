<?php
$i=0;
?>
<?php if($tipo=="porcentaje_calculos"){?>
<?php  foreach ($planilla->result_array as $data) { ?>
<?= $i++;?>
<tr class="odd">
    <td class="item">
        <?= $data['COD_PLANILLAUNICA'] ?>
    </td>
    <td class="item">
        <?= $data['PERIDO_PAGO'] ?>
    </td>
    <td class="item">
       <?= $data['PRIMER_APELLIDO'] ?> <?= $data['SEGUN_APELLIDO'] ?> <?= $data['PRIMER_NOMBRE'] ?> <?= $data['SEGUN_NOMBRE'] ?>
    </td>
    <td class="item">
        <?= $cuadro;?>
    </td>
    <td class="item">
        <?= $data['APORTE_OBLIG'] ?>
    </td>
    <td class="item">
       <?= $data['APORTE_OBLIG']*0.02 ?>
    </td>
    <td class="item">
        <?= ($data['APORTE_OBLIG']*0.02)*($cuadro/100) ?>
    </td>
    <td class="item">
        <?= (($data['APORTE_OBLIG']*0.02)*($cuadro/100))*0.02 ?>
    </td>
    <td class="item">
        <?= ($data['APORTE_OBLIG']*0.02)-((($data['APORTE_OBLIG']*0.02)*($cuadro/100))*0.02)?>
        
    </td>
    
    
<!--    <td class="center">
        <a href="<?= base_url()?>index.php/multasministerio/detalle/<?= $data['COD_MULTAMINISTERIO'] ?>" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a>
    </td>-->
</tr>
    <?php } ?>
<tr><td><input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?= $i?>"></td></tr>
<?php }?>
<?php if($tipo=="ibc_calculos"){?>
<?php  foreach ($planilla->result_array as $data) { ?>
<?= $i++;?>
<tr class="odd">
    <td class="item">
        <?= $data['COD_PLANILLAUNICA'] ?>
    </td>
    <td class="item">
        <?= $data['PERIDO_PAGO'] ?>
    </td>
    <td class="item">
       <?= $data['PRIMER_APELLIDO'] ?> <?= $data['SEGUN_APELLIDO'] ?> <?= $data['PRIMER_NOMBRE'] ?> <?= $data['SEGUN_NOMBRE'] ?>
    </td>
    <td class="item">
        <?= $cuadro;?>
    </td>
    <td class="item">
        <?= $ibc*0.02 ?>
    </td>
    <td class="item">
       
    </td>
    <td class="item">
        <?= $ibc ?>
    </td>
    <td class="item">
        <?= $ibc*($cuadro/100) ?>
    </td>
    <td class="item">
        
        
    </td>
    
    
<!--    <td class="center">
        <a href="<?= base_url()?>index.php/multasministerio/detalle/<?= $data['COD_MULTAMINISTERIO'] ?>" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a>
    </td>-->
</tr>
    <?php } ?>
<tr><td><input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?= $i?>"></td></tr>
<?php }?>
<script type="text/javascript" language="javascript" charset="utf-8">
    $(function () {
        var valor = $("#cantidad_registros").val();
        
        if(valor!=0){
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
   
    });
//    $('#adicionar_calculos').click(function(){ 
//        
//        var can_reg = $("#cantidad_registros").val();
//         var x = new Array(can_reg);
//  for (var i = 0; i < can_reg; i++) {
//    x[i] = new Array(20);
//  }
//  for(var i=0 ; i<can_reg;i++){
//        x[i][0] = $("#planilla"+i).val();
//        x[i][1] = $("#periodo_pago"+i).val();
//  }
//        
////        if(tipo_cal=="ibc_calculos"){
////            if(nit_cal&&des_cal&&has_cal&&cua_cal&&ibcval_cal){
////        $('#dblData').load(url, {nit_cal: nit_cal,tipo_cal : tipo_cal,cua_cal : cua_cal,des_cal : des_cal,has_cal : has_cal,ibcval_cal : ibcval_cal},function(data){
////           
////            });
////        }
////        else{
////            alert("No se han ingresado los datos requeridos")
////        }
////        }
////        else{
////            if(nit_cal&&des_cal&&has_cal&&cua_cal&&tipo_cal){
////        $('#dblData').load(url, {nit_cal: nit_cal,tipo_cal : tipo_cal,cua_cal : cua_cal,des_cal : des_cal,has_cal : has_cal},function(data){
////           
////            });
////        }
////        else{
////            alert("No se han ingresado los datos requeridos")
////        }
////        }
//        
//    });

</script>
