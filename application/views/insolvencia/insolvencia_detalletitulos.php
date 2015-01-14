<?php 
if (isset($message)){
    echo $message;
   }
   $i=0;
?>
<?php  foreach ($consultar_titulos->result_array as $data) { ?>
<?php $i++;?>
<?php
$datos = explode("/", $data["RUTA_DOC_ACERCAMIENTO"]);
?>
<tr class="odd">
    <td class="item">
        <input type="radio" name="sel" id="sel"  value="<?= $data['COD_TITULO'] ?>" class="selec_titu" required="true">  
    </td>
    <td class="item">
        <?= $data['NIT_EMPRESA'] ?>
    </td>
    <td class="item">
        <?= $data['RAZON_SOCIAL'] ?>
    </td>
    <td class="item">
        <?= $data['COD_TITULO'] ?>
    </td>
    <td class="item">
        <?= $data['NUM_RADICADO'] ?>
    </td>
    <td class="item">
        <?= $data["FECHA_RADICADO"] ?>
    </td>
    <td class="item">
        <?= $data["NIS"] ?>
    </td>
    <td class="item">
        <?= $data["COD_USUARIO"] ?>
    </td>
    <td class="item">
        <?= $data["COMENTARIOS"] ?><input type="hidden" name="fisicos_recibidos" id="fisicos_recibidos" value="<?= $data["DOCUMENTOS_FISICOS_RECIBIDOS"] ?>"> 
       
    </td>
    
   <td class="item">
<!--       <a href="<?= base_url().'uploads/AcercamientoPersuasivo/Req_Generado/'.$datos[3]?>" target="blank"><?= $datos[3] ?></a>-->
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
    <td><input type="hidden" name="expediente" id="expediente" value="<?= $data['NIT_EMPRESA'] ?>"></td>
    <td><input type="hidden" name="regimen_insolvenci" id="regimen_insolvenci" value="<?= $data['COD_REGIMENINSOLVENCIA'] ?>"></td>
</tr>


    <?php } ?>
<tr>
    <td><input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?= $i?>"></td>
    
    <td></td>
</tr>
<script type="text/javascript" language="javascript" charset="utf-8">
      $(function () {
          if($("#cantidad_registros").val()>0){
              $("#asigabo_titulos").attr("disabled",false);
              $("#regimenin_titulos").val($("#regimen_insolvenci").val());
          }
          else
              $("#asigabo_titulos").attr("disabled",true);
               });
//            $can_reg=$("#cantidad_registros").val();
//            opcion_check    = '<input id="opcion" class="seleccion" type="radio" required="true" value="si" name="opcion" checked="checked"> Si'; 
//            opcion          = '<input id="opcion" class="seleccion" type="radio" required="true" value="si" name="opcion"> Si'; 
//            
//            opcion1_check   = '<input id="opcion1" class="seleccion" type="radio" required="true" value="no" name="opcion" checked="checked"> No'; 
//            opcion1         = '<input id="opcion1" class="seleccion" type="radio" required="true" value="no" name="opcion"> No'; 
//            
//            $("#opcion_td").html('');
//            $("#opcion1_td").html('');
//            
//            if($can_reg>0) {
//                
//                $("#fisicos").css('display','block');
//                if($("#fisicos_recibidos").val()=="S"){ 
//                    
//                    $("#asigabo_titulos").attr("disabled",false);
//                    
//                    $("#opcion_td").html(opcion_check);
//                    $("#opcion1_td").html(opcion1);
//                 
//                    $("input:radio[name=opcion]").attr('disabled','true');
//                    $("#caminos").css('display','block');
//                }
//            else{
//                $("#opcion_td").html(opcion);
//                $("#opcion1_td").html(opcion1_check);
//                   
//                    $("#asigabo_titulos").attr("disabled",true);
//                    $("#caminos").css('display','none');
//           }
//            }
//            else{
//                $("#fisicos").css('display','none');
//            }
//            
//          }
//          );
//  
  $('.selec_titu').change(function(){
            
            if($(this).is(":checked")) {
                var h=$("input[name='sel']:checked").val();
                
                $("#codtitul_recdoc").val(h);
                $("#asigabo_titulos1").attr('disabled',false);
                }
             });
//     
     
</script>
    

