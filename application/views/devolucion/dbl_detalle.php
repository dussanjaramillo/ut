<?php 
if (isset($message)){
    echo $message;
   }
   $i=0;
?>
<?php  foreach ($buscar->result_array as $data) { ?>
<?php $i++;?>

<tr class="odd">
    <td class="item">
        <input type="radio" name="sel" id="sel"  value="<?= $data['COD_DEVOLUCION'] ?>" class="seleccion">      
    </td>
    <td class="item">
        <?= $data['NIT'] ?>
    </td>
    <td class="item">
        <?= $data['RAZON_SOCIAL'] ?>
    </td>
    <td class="item">
        <?= $data['NRO_RADICACION'] ?>
    </td>
    <td class="item">
        <?= $data['FECHA_RADICACION'] ?>
    </td>
    <td class="item">
        <?= $data['NRO_PLANILLA'] ?>
    </td>
    <td class="item">
        <?= $data["VALOR_DEVOLUCION"] ?>
    </td>
    <td class="item">
        <?= $data["MOTIVO_DEVOLUCION"] ?>
    </td>
    <td class="item">
        <?= $data["NOMBRE_CONCEPTO"] ?>
    </td>
    <td class="item">
        <?= $data["INFORMANTE"] ?>
    </td>
   <td class="item">
        <?= $data["CARGO"] ?>
    </td>
  <!--   <td class="item">
        <?= $data["PERIODO"] ?>
    </td>
    <td class="item">
        <?= $data["FECHA_REGISTRO"] ?>
    </td>
    <td class="item">
        <?= $data["VALOR_PAGADO"] ?>
    </td>
    <td class="item">
        <?= $data["FECHA_REGISTRO"] ?>
    </td>
    <td class="item">
        <?= $data["ENVIADO_CONTABILIDAD"] ?>
    </td>-->
    
<!--    <td class="center">
        <a href="<?= base_url()?>index.php/multasministerio/detalle/<?= $data['COD_MULTAMINISTERIO'] ?>" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a>
    </td>-->
</tr>

    <?php } ?>
<script type="text/javascript" language="javascript" charset="utf-8">
     $('.seleccion').change(function(){
            if($(this).is(":checked")) {
                var h=$("input[name='sel']:checked").val();
                $("#Gestionar").removeAttr('href');
                $('#ver_resul').attr("disabled", false);
                $('#aprobar_resul').attr("disabled", false);
                
                $("#Gestionar").attr('href','<?= base_url()?>index.php/devolucion/devolucion_detalle/'+$("input[name='sel']:checked").val());
                $("#aprobar_devo").attr('href','<?= base_url()?>index.php/devolucion/aprobar_devolucion/'+$("input[name='sel']:checked").val());
              }
              else{
                    $('#ver_resul').attr("disabled", true);
                    $('#aprobar_resul').attr("disabled", true);
                  
              }
        });

</script>