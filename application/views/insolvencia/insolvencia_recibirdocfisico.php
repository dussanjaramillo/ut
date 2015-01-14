<?php
if (isset($message)){
    echo $message;
   }   
$numerodoc_recdoc   = array('name'=>'numerodoc_recdoc','class'=>'search-query','id'=>'numerodoc_recdoc','value'=>$rec_doc->NITEMPRESA,'readonly'=>'true');
$razonsoc_recdoc    = array('name'=>'razonsoc_recdoc','class'=>'search-query input-xxlarge','id'=>'razonsoc_recdoc','value'=>$rec_doc->RAZON_SOCIAL,'readonly'=>'true');
$docufisi_recdoc    = array('name'=>'docufisi_recdoc','class'=>'search-query input-xxlarge','id'=>'docufisi_recdoc','value'=>$rec_doc->DOCUMENTOS_FISICOS_RECIBIDOS,'type'=>'hidden');
$codtitul_recdoc    = array('name'=>'codtitul_recdoc','class'=>'search-query','id'=>'codtitul_recdoc','type'=>'hidden');
$codregin_recdoc    = array('name'=>'regimenin_titulos','class'=>'search-query','id'=>'regimenin_titulos','type'=>'hidden');
$parambusq_titulos  = array('name'=>'parambusq_titulos','class'=>'search-query','id'=>'parambusq_titulos','type'=>'hidden');
$sel_titulos        = array('name'=>'sel_titulos','class'=>'search-query','id'=>'sel_titulos','type'=>'hidden');
$opcion             = array('name'=>'opcion','id'=>'opcion','value'=>'si','type'=>'radio','required'=>'true','class'=>'seleccion');
$opcion1            = array('name'=>'opcion','id'=>'opcion1','value'=>'no','type'=>'radio','required'=>'true','class'=>'seleccion');
$opcion2            = array('name'=>'opcion2','id'=>'opcion2','value'=>'reorga','type'=>'radio','required'=>'true');
$opcion3            = array('name'=>'opcion2','id'=>'opcion3','value'=>'liqui','type'=>'radio','required'=>'true');
$opcion4            = array('name'=>'opcion4','id'=>'opcion4','value'=>'si_tit','type'=>'radio','required'=>'true','class'=>'titu');
$opcion5            = array('name'=>'opcion4','id'=>'opcion5','value'=>'no_tit','type'=>'radio','required'=>'true','class'=>'titu');
$button2            = array('name'=>'aceptar_titulos1','id'=>'aceptar_titulos1','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button3            = array('name'=>'asigabo_titulos1','id'=>'asigabo_titulos1','type'=>'submit','value'=>'asignar','content'=>'<i class=""></i> Continuar','class'=>'btn btn-success btn1','disabled'=>'true');
?>
<div style="max-width: 915px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <div align="center" style="background-color: #FC7323; color: #ffffff;"><h3>Revisión Existencia de Títulos Recibidos</h3></div>
    <?= form_open(base_url('index.php/insolvencia/cobro_coactivo'))?>
    <table>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numerodoc_recdoc)?></td>
            <td>Razón Social</td><td><?= form_input($razonsoc_recdoc)?></td>
            <?= form_input($codtitul_recdoc)?><?= form_input($sel_titulos)?><?= form_input($codregin_recdoc)?><?= form_input($docufisi_recdoc)?><?= form_input($parambusq_titulos)?>
        </tr>
        <tr>            
            
            
        </tr>
        <tr id="fisicos">
            <td>RECIBIDOS EN FISICO</td>
            <td id="opcion_td"><?=form_checkbox($opcion);?> Si</td>
            <td id="opcion1_td"><?=form_checkbox($opcion1);?> No</td>
        </tr>
    </table>
    <table  id="caminos" style="display: none">
        <tr>                
            <td ><?= form_checkbox($opcion2);?> Reorganización</td>
            <td><?= form_checkbox($opcion3);?> Liquidación Obligatoria</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td>EXISTEN TITULOS</td>
            <td id="opcion_td"><?=form_checkbox($opcion4);?> Si</td>
            <td id="opcion1_td"><?=form_checkbox($opcion5);?> No</td>
        </tr>
    </table>
    
    
</div>    
<br>
<table id="titulos">
    <thead>
           <tr>
               <th>Seleccion</th>
                <th>No. Documento</th>
                <th>R. Social</th>
                <th>N. Título</th>
                <th>N. Radicado</th>
                <th>F. Radicado</th>
                <th>NIS</th>
                <th>Usuario</th>
                <th>Comentarios</th>
                <th>Ver Titulo</th>
          </tr>
        </thead>
        <tbody></tbody>     
    </table>
<br>
<div align="center">
    <?= form_button($button2)?>
    <?= form_button($button3)?>
   
</div>
<div id="mensaje" style="display: none;">Debe de Hacer la Búsqueda por un Solo Parámetro</div>
<?=form_close()?>
    <script type="text/javascript" language="javascript" charset="utf-8"> 
        function habilitar(id){
                
                if(id) {
                    $("#asigabo_titulos1").attr("disabled",false);
                    $("#sel_titulos").val(id);
                    
                    }
                  else{
                       $("#asigabo_titulos1").attr("disabled",true);

                  }
        }
       var oTable;
        $('.seleccion').change(function(){
            
            if($(this).is(":checked")) {
                var h=$("input[name='opcion']:checked").val();
                if(h=="si"){
                $("#asigabo_titulos1").attr("disabled",false);
                $("#caminos").css('display','block');
            }
            else{
                $("#asigabo_titulos1").attr("disabled",true);
                $("#caminos").css('display','none');
            }
                             }
             });
             
             
             $('.titu').change(function(){            
            if($(this).is(":checked")) {
                var h=$("input[name='opcion4']:checked").val();
                
                if(h=="si_tit"){
                    $("#titulos").css('display','block');
                    var url = "<?= base_url('index.php/insolvencia/detalle_recdoc')?>";
                    num_doc=$("#numerodoc_recdoc").val();
                    para   ='NIT_EMPRESA';
                    $("#parambusq_titulos").val('NIT_EMPRESA')

                    oTable.fnDraw();    
            }
            else{
                $("#titulos").css('display','none');
                $("#asigabo_titulos1").attr("disabled",false);
            }
                             }
             });
     
         $(function () {
          if($("#docufisi_recdoc").val()=="S") {
              $("#opcion").attr('checked','checked');
              $("input:radio[name=opcion]").attr('disabled','true');
                $("#caminos").css('display','block');
          } 
          else{
              $("#opcion1").attr('checked','checked');
              
          }
         });
        
        $("#aceptar_titulos").confirm({
        title:"Confirmacion",
        text:"Â¿Esta seguro de cancelar?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
       
  
    
   
    var gestion ='';
    var gestion2='';
      
    oTable = $('#titulos').dataTable( {
        
        "sServerMethod": "POST", 
        "bJQueryUI":    true,
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 5    ,
        "sSearch": '',
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "para", "value": $('#parambusq_titulos').val() },{ "name": "para1", "value": $('#parambusq_titulos1').val() },{ "name": "num_doc", "value": $("#numerodoc_recdoc").val() },{ "name": "raz_soc", "value": $("#razonsoc_titulos").val() },{ "name": "num_exp", "value": $("#numeroexp_titulos").val() },{ "name": "fec_ini", "value": $("#fechaini_titulos").val() },{ "name": "fec_fin", "value": $("#fechafin_titulos").val() } );
        },
        "autoWidth": true,
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?=base_url()?>index.php/insolvencia/getData",          
        "aoColumns": [  
    
    //Link Gestion
                 { "sClass": "center", sWidth: '12%',"mData": null,
                    "bSearchable": true,
                    "bSortable": false,
                    "fnRender": function (oObj) {
                        gestion = '';
                        
//                        if(oObj.aData.NIT_EMPRESA){
//                            
//                            
//                        }
//                        else
//                            $("#asigabo_titulos").attr("disabled",true);
                            
                        
                        gestion = '<input type="radio" name="sel" value="'+oObj.aData.COD_TITULO+'" class="selec_titu" required="true" onclick="habilitar(this.value)">  ';
                        
                        return gestion;
                      }
                  },
    
               { "mDataProp": "NIT_EMPRESA" },  
               { "mDataProp": "RAZON_SOCIAL" },  
               { "mDataProp": "COD_TITULO" },  
               { "mDataProp": "NUM_RADICADO" },
               { "mDataProp": "FECHA_RADICADO" },
               { "mDataProp": "NIS" },
               { "mDataProp": "COD_USUARIO" },
               { "mDataProp": "COMENTARIOS" },
               { "mDataProp": "COD_USUARIO" }
            
//               
               
           ]

    } );

</script>