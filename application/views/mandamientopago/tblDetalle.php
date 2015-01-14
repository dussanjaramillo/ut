<?php  
$i=1;
$permisoUser = 0;
if (count(@$registros->result_array) > 0) {
foreach ($registros->result_array as $data) { 
    ?>

<tr class="odd">
    <td class="item" align="center">
        <?= "<b>".$data['COD_MANDAMIENTOPAGO']."</b>" ?>
    </td>
    <td id="borrar" class="center">
        <button class="delete" title="Eliminar" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>" fiscalizacion="<?= $data['COD_FISCALIZACION'] ?>"><i class="fa fa-trash-o"></i></button>
    </td>
    <td id="editar" class="center">
        <button class="edit" title="Editar" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>"><i class="fa fa-edit"></i></button>
    </td>
    <td id="view" class="center">
        <a href="#" class="btn btn-small view" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static" clave="<?= $data['COD_FISCALIZACION'] ?>" plantilla="<?= $data['PLANTILLA'] ?>"><i class="fa fa-eye"></i></a>
    </td>
    <td id="imprimir" class="center">
        <a href="#" class="btn btn-small print" title="Imprimir" data-toggle="modal" data-target="#pdf" data-keyboard="false" data-backdrop="static" clave="<?= $data['COD_FISCALIZACION'] ?>" plantilla="<?= $data['PLANTILLA'] ?>"><i class="fa fa-print"></i></a>
    </td>
    <td id="subir" class="center">
        <a href="<?= base_url()?>index.php/mandamientopago/up"  class="btn btn-small up" title="Subir" data-toggle="modal" data-target="#upload" data-keyboard="false" data-backdrop="static" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>"><i class="fa fa-arrow-up"></i></a>
    </td>
    <td id="gestion" class="center">
        <button class="gestion" title="Gestion" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>" estado_gestion="<?= $data['ESTADO'] ?>" fiscalizacion="<?= $data['COD_FISCALIZACION'] ?>" gestion_cobro="<?= $data['COD_GESTIONCOBRO']?>"><i class="fa fa-gears"></i></button>
    </td> 
    <td class="item" align="center">
        Acuerdo de Pago <?php //$data['COD_MANDAMIENTOPAGO'] ?>
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
        <?= $data["FECHA_MANDAMIENTO"] ?>
    </td>
    <td class="item" align="center">
        <button class="viewGestion" title="Ver Gestion" data-toggle="modal" data-target="#viewGestion" clave="<?= $data['COD_MANDAMIENTOPAGO'] ?>" estado_gestion="<?= $data['ESTADO'] ?>" ><i class="fa fa-camera-retro fa-lg"></i></button>
    </td>
<!--    <td class="item" align="center">
        <?php 
        if ($data["APROBADO"] == '0'){
            echo $data["APROBADO"] = 'ELABORADO';
        }else {
            echo $data["APROBADO"];
        }
        ?>
    </td>-->
</tr>
<?php
$i++;
    }
    echo "<tr><td><input type='hidden' value='$i' name='cont' id='cont'></td</tr>";   

    foreach ($permiso as $user) {
        $var = $user['IDGRUPO'];
        $permisoUser = $var.'-'.$permisoUser;
    }
    $permisoUser = substr($permisoUser,0,-2);
    echo "<tr><td>
         <input type='hidden' value='".$empresa['perfiles']."' name='perfil' id='perfil'>
         </td></tr>
         <td>
         <input type='hidden' value='".$user['IDUSUARIO']."' name='iduser' id='iduser'>
         </td>
         <td>
         <input type='hidden' value='".$empresa['nit_empresa']."' name='nit' id='nit'>
         </td>";
} else { ?>
    <tr class="odd">
        <td class="center" colspan="11">
            <font color="red"><b>NO SE ENCONTRARON DATOS</b></font>
        </td>
    </tr>
<?php
} ?>

<script>
$(document).ready(function() {
    var perfil = $('#perfil').val();    
             /////////Validacion Botones segun perfil////////////////////
             if (perfil == "42"){
                var band = 1;
                }else if (perfil == "43" && band != 1){
                      $('.up').attr('disabled','disabled');
                      $('.up').prop('title', 'No Permitido');
                    var band = 2;
                }else if (perfil == "41" && band != 2){
                    var band = 3;
                    $('.delete').attr('disabled','disabled');
                    $('.delete').prop('title', 'No Permitido');
                    $('.up').prop('title', 'No Permitido');
                    $('.up').attr('disabled','disabled');
                }


    $('.edit').click(function(){
       var clave = $(this).attr('clave');
       $('#clave').val(clave);
       $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edit')?>");
       $('#frmTmp').submit();
    });
    
    $('.delete').click(function(){
       var clave = $(this).attr('clave');
       var fiscalizacion = $(this).attr('fiscalizacion');
       var nit = $('#nit').val();
       $('#clave').val(clave);       
       $('#fiscalizacion').val(fiscalizacion);
       $('#nit_empresa').val(nit);
       $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/delete')?>");
       if(confirm("¿Esta seguro de Eliminar el mandamiento de pago ?") == true) {
         $('#frmTmp').submit();
       }
    });
    
    
    
    $('.print').click(function(){
       var clave = $(this).attr('clave');
       var plantilla = $(this).attr('plantilla');
       $('#clave').val(clave);
       $('#plantilla').val(plantilla);
    });
    
    $('.view').click(function(){
       var clave = $(this).attr('clave');
       var plantilla = $(this).attr('plantilla');
       $('#clave').val(clave);
       $('#plantilla').val(plantilla);
    });
    
    $('.viewGestion').click(function(){
       var clave = $(this).attr('clave');
       var estado_gestion = $(this).attr('estado_gestion');
       $('#clave').val(clave);
       $('#estado_gestion').val(estado_gestion);
    });    
    
    $('.gestion').click(function(){
       var gestion = $(this).attr('estado_gestion');
       var clave = $(this).attr('clave');
       var fiscalizacion = $(this).attr('fiscalizacion');
       var gestion_cobro = $(this).attr('gestion_cobro');       
       $('#estado_gestion').val(gestion);
       $('#clave').val(clave);
       $('#fiscalizacion').val(fiscalizacion);
       $('#gestion_cobro').val(gestion_cobro);
       
       if (gestion == '205' || gestion == '0' || gestion == '207' || gestion == '206'){   
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edit')?>");
          $('#frmTmp').submit();//Editar Mandamiento de Pago
       }else if (gestion == '208'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacion')?>");
          $('#frmTmp').submit();//Se genera citacion de notificacion personal
       }else if (gestion == '220' || gestion == '221'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCit')?>");
          $('#frmTmp').submit();//se verifican los estados de la citacion
       }else if (gestion == '222'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/correo')?>");
          $('#frmTmp').submit();//se notifica por correo el mandamiento de pago
       }else if (gestion == '223' || gestion == '219'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/acta')?>");
          $('#frmTmp').submit();//se genera acta de notificacion personal
       }else if (gestion == '210' || gestion == '211'){
          $(".ajax_load").show("slow")
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActa')?>");
          $('#frmTmp').submit();// se verifican los estados del acta
       }else if (gestion == '216' || gestion == '217'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCor')?>");
          $('#frmTmp').submit();//se verifian los estado de notificacion por correo 
       }else if (gestion == '218'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/pagina')?>");
          $('#frmTmp').submit();//se notifica mandamiento por pagina web
       }else if (gestion == '213' || gestion == '226' || gestion == '227'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/pago')?>");
          $('#frmTmp').submit();//se verifica el pago, si no hay pago se validan las excepciones, si no hay 
          //excepcion sigue adelante el proceso
       }else if (gestion == '224' || gestion == '225'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editPag')?>");
          $('#frmTmp').submit();//se verifican los estado de notificacion por pagina
       }else if (gestion == '235' || gestion == '259' || gestion == '256'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/addRes')?>");
          $('#frmTmp').submit();//Crear Resolución ordenando seguir adelante     
       }else if (gestion == '237'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacionex')?>");
          $('#frmTmp').submit();// si se presentan excepciones, se genera la citacion de notificacion para la excepcion
       }else if (gestion == '236'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/medidas')?>");
          $('#frmTmp').submit();// si las excepciones quedan en estado PROCEDEN se inicia el proceso de verificar medidas cautelares
       }else if (gestion == '238' || gestion == '239'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCitex')?>");
          $('#frmTmp').submit();// se verifican los estado de la citacion de notificacion para la excepcion
       }else if (gestion == '241' || gestion == '245' || gestion == '232' || gestion == '233'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/actaExc')?>");
          $('#frmTmp').submit();// se crea acta personal de notificacion de la excepcion
       }else if (gestion == '659' || gestion == '660'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActaExc')?>");
          $('#frmTmp').submit();// se verifican los estados de acta personal de notificacion de la excepcion
       }else if (gestion == '240'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/correoex')?>");
          $('#frmTmp').submit();// se genera correo de notificacion de excepcion
       }else if (gestion == '242' || gestion == '243'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCorreoex')?>");
          $('#frmTmp').submit();//se verifican los estado de correo de notificacion de excpcion
       }else if (gestion == '244'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/paginaExc')?>");
          $('#frmTmp').submit();//se genera notificacion por pagina web de notificacion de excepcion
       }else if (gestion == '230' || gestion == '231'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edPaginaExc')?>");
          $('#frmTmp').submit();//se verifica los estados de notificacion por pagina de excepcion
       }else if (gestion == '661' || gestion == '662' || gestion == '232' || gestion == '233' ){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/recurso')?>");
          $('#frmTmp').submit();//si hay excepcion y se genero acta, se solicita presentar recursos
          // si los presenta se resuelve recurso.. si no sigue adelante el proceso
       }else if (gestion == '258' || gestion == '257'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacionRec')?>");
          $('#frmTmp').submit();// se genera citacion de notificacion personal que resuelve recurso
       }else if (gestion == '260' || gestion == '261'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCitacionRec')?>");
          $('#frmTmp').submit();//se verifica los estado de la citacion de notificacion personal que resuelve recurso
       }else if (gestion == '263' || gestion == '271' || gestion == '743' || gestion == '744'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/actaRec')?>");
          $('#frmTmp').submit();//se genera acta de notificacion personal que resuelve recurso
       }else if (gestion == '272' || gestion == '273'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActaRec')?>");
          $('#frmTmp').submit();//se verifican estados acta de notificacion personal que resuelve recurso
       }else if (gestion == '262'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/correoRec')?>");
          $('#frmTmp').submit();// se genera correo de notificacion personal que resuelve recurso
       }else if (gestion == '268' || gestion == '269'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCorreorec')?>");
          $('#frmTmp').submit();// se validan estados de correo por notificacion personal que resuelve recurso
       }else if (gestion == '270'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/paginaRec')?>");
          $('#frmTmp').submit();//se genera notificacion pagian web de notificacion personal que resuelve recurso
       }else if (gestion == '741' || gestion == '742'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edPaginaRec')?>");
          $('#frmTmp').submit();// se validan estado de notificacion pagina web de notificacion personal que resuelve recurso
       }else if (gestion == '250'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/levanta')?>");
          $('#frmTmp').submit();// se genera el proceso de levantar medidas cautelares
       }else if (gestion == '251'){
          alert ("No existen obligaciones pendientes");
       }else if (gestion == '252' || gestion == '253'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editLevanta')?>");
          $('#frmTmp').submit();// se verifican los estado de el proceso de levantar medidas cautelares
       }else if (gestion == '255'){
           alert ('Levantamiento de Medidas Cautelares Aprobado y Firmado, Mandamiento de pago N° '+clave);
       }else if (gestion == '254'){
           alert ('Resolución Ordenando Seguir Adelante Rechazada, Mandamiento de pago N° '+clave);
       }else if (gestion == '246' || gestion == '247'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editRes')?>");
          $('#frmTmp').submit();// se verifican los estado de el proceso de levantar medidas cautelares
       }else if (gestion == '248'){
           alert ('Resolución Ordenando Seguir Adelante Aprobado y Firmado, Mandamiento de pago N° '+clave);
       }else if (gestion == '249'){
           alert ('Resolución Ordenando Seguir Adelante Rechazada, Mandamiento de pago N° '+clave);
       }else if (gestion == '274'){
           alert ('Acta de Notificación Personal que Resuelve el Recurso Rechazada '+clave);
       }else if (gestion == '275'){
           alert ('Acta de Notificación Personal que Resuelve el Recurso Aprobado y Firmado '+clave);
       }
    });
    
});   
</script>
