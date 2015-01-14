<?php 
if (count($registros->result_array) > 0) {
foreach ($registros->result_array as $data) { ?>
<tr class="odd">
<!--    <td class="center">
        <button class="edit" title="Editar" nit="<?= $data['NIT_EMPRESA'] ?>" gestion_cobro="<?= $data['COD_GESTIONCOBRO']?>" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>"><i class="fa fa-edit"></i></button>
    </td>-->
    <td class="center">
        <i title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"  class="view fa fa-eye" clave="<?= $data['COD_FISCALIZACION'] ?>" plantilla="<?= $data['PLANTILLA'] ?>" gestion="<?= $data['ESTADO'] ?>" style="cursor : pointer"></i>
    </td>
    <td id="imprimir" class="center">
        <i class="print fa fa-print" title="Imprimir" data-toggle="modal" data-target="#pdf" data-keyboard="false" data-backdrop="static" clave="<?= $data['COD_FISCALIZACION'] ?>" gestion="<?= $data['ESTADO'] ?>" plantilla="<?= $data['PLANTILLA'] ?>" style="cursor : pointer"></i>
    </td>
    <td class="center">
        <i id="subir" fiscalizacion="<?= $data['COD_FISCALIZACION'] ?>"aprobado="<?= $data['APROBADO'] ?>" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>" nit="<?= $data['NIT_EMPRESA'] ?>" class="up fa fa-arrow-up" style="cursor : pointer"></i>
    </td>
    <td class="item" align="center">
        Resoluci&oacute;n <?php //$data['COD_MANDAMIENTOPAGO'] ?>
    </td>
    <td class="item" align="right">
        <?= $data['COD_FISCALIZACION'] ?>
    </td>
    <td class="item" align="center">
        <?= $data['CREADO'] ?>
    </td>
    <td class="item" align="center">
        <?= $data['ASIGNADO'] ?>
    </td>
    <td class="item" align="center">
        <?= $data["FECHA_MANDAMIENTO_ADELANTE"] ?>
    </td>
    <td>
        <button class="viewGestion" title="Ver Gestion" data-toggle="modal" data-target="#viewGestion" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>" estado_gestion="<?= $data['ESTADO'] ?>" ><i class="fa fa-camera-retro fa-lg"></i></button>
    </td>
<!--    <td class="item">
        <?= $data["ESTADO"] ?>
    </td>-->
</tr>
<?php }
} else { ?>
    <tr class="odd">
        <td class="center" colspan="11">
            <font color="red"><b>NO SE ENCONTRARON DATOS</b></font>
        </td>
    </tr>
<?php
}
?>

<script>
    $('.edit').click(function(){
       var clave = $(this).attr('clave');
       var nit = $(this).attr('nit');
       $('#clave').val(clave);
       $('#nit').val(nit);
       $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editRes')?>");
       $('#frmTmp').submit();
    });
    
    $('.view').click(function(){
       var clave = $(this).attr('clave');
       var plantilla = $(this).attr('plantilla');
       var gestion = $(this).attr('gestion');
       $('#clave').val(clave);
       $('#plantilla').val(plantilla);
       $('#gestion').val(gestion);
       
    });
    
    $('.viewGestion').click(function(){
       var clave = $(this).attr('clave');
       var estado_gestion = $(this).attr('estado_gestion');
       $('#clave').val(clave);
       $('#estado_gestion').val(estado_gestion);
    });
    
    $('.up').click(function(){
       var clave = $(this).attr('clave'); 
       var nit = $(this).attr('nit');        
       var nit = $(this).attr('nit');        
       var fiscalizacion = $(this).attr('fiscalizacion');    
       var perfil = $('#perfil').val();
       var aprobado = $(this).attr('aprobado');       
       if (perfil == 41){
           alert("No tiene permisos para ingresar");
            $('.up').prop("disabled",true);
            $('.up').prop('title', 'No Permitido'); 
       }else if (aprobado != 'REVISADO' && perfil == 43){
            alert("El coordinador no ha revisado el mandamiento");
            $('.up').prop("disabled",true);
            $('.up').prop('title', 'No Permitido');           
        }else {
            $(".ajax_load").show("slow");
            var url = "<?= base_url('index.php/mandamientopago/upRes') ?>";
            $('#upload').load(url,{clave : clave, nit : nit, fiscalizacion:fiscalizacion},function(data){               
               $(".ajax_load").hide("slow");
               $('#upload').modal('show');
               $('#proceso').val(clave);                              
            });   
        }
            
    });
    
            $('#pdf').on('show', function() {
            var clave = $('#clave').val();
            var plantilla = $('#plantilla').val();
            var gestion = $('#gestion').val();
            $('#subtitle').html("<h6>C&oacute;digo Fiscalizaci&oacute;n:  "+clave+"</h6>");
            if (plantilla!="") {
                $(".ajax_load").show("slow");
                $.ajax({                    
                    type: "POST",
                    url: "loadRes",
                    data: { plantilla: plantilla, clave:clave, gestion:gestion },
                    success: function(data){
                        $('#mandamiento').val(data);
                        $('.pdf-conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.pdf-conn').html("No hay Datos");
            }
            //$('.conn').html("PDF para el ID "+clave+" "+plantilla+"<br><br><br>");
        });
        
       $('#btnpdf').click(function() {
        $(".ajax_load").show("slow");
        $('#frmTmp').attr("action", "pdf");
        $('#frmTmp').submit();
        $('#frmTmp').attr("action", "add");
        });
</script>