<?php
if (isset($message)){
    echo $message;
   }
   
$id_radio           = array('name'=>'id_radio','id'=>'id_radio','type'=>'hidden');
$tip_ges            = array('name'=>'tip_ges','id'=>'tip_ges','type'=>'hidden');
$expediente         = array('name'=>'expediente','id'=>'expediente','type'=>'hidden');
$valor_exp          = array('name'=>'valor_exp','id'=>'valor_exp','type'=>'hidden');
$button             = array('name'=>'salir','id'=>'salir','value'=>'salir','content'=>'<i class=""></i> Salir','class'=>'btn btn-success btn1');

?>
<div style="max-width: 1015px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <div align="center"><h3>Recibir Títulos en Físico </h3></div>
    
    <br>
        <table id="titulos">
            <thead>
           <tr>
              
                <th>No. Documento</th>
                <th>Razón Social</th>
                <th>N. Título</th> 
                <th>F. Radicado</th>
                <th>Num. Expediente</th>
                <th>Gestión a Realizar</th>
                <th>Recibir Doc. en Físico</th>
                
<!--                <th>Ver Titulo</th>-->
          </tr>
        </thead>
        <tbody></tbody>     
    </table>
    
</div>    
<?=form_input($id_radio)?><?=form_input($tip_ges)?><?=form_input($expediente)?><?=form_input($valor_exp)?>
<br>
<div align="center">
    <?= form_button($button)?>
    
</div>
<div id="mensaje" style="display: none;">Debe Seleccionar la Gestión del Título al Cuál va a Recibir</div>
<div id='dialog' style="display: none;">
    <center>
        <p>Se han Recibido Los Titulos en Físico?</p>
    </center>                
</div>
    <script type="text/javascript" language="javascript" charset="utf-8"> 
        var oTable;
        var gestion ='';
        var gestion2='';
      
    oTable = $('#titulos').dataTable( {        
        "sServerMethod": "POST", 
        "bJQueryUI":    true,"oLanguage": {
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
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 5,
        "sSearch": '',
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "viene", "value": '1' } );
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
               { "mDataProp": "NIT_EMPRESA","sClass": "center" },  
               { "mDataProp": "RAZON_SOCIAL","sClass": "center" },  
               { "mDataProp": "COD_RECEPCIONTITULO","sClass": "center" },  
               { "mDataProp": "FECHA_CARGA","sClass": "center" },
               { "mData": null,"sClass": "center",                    
                    "fnRender": function (oObj) {
                        gestion2 = '';                            
                        if(oObj.aData.NUM_PROCESO){                           
                         gestion2 = oObj.aData.NUM_PROCESO;
                       }
                       else{
                        gestion2 = "<input type='number' name='expediente' id='" + oObj.aData.COD_RECEPCIONTITULO + "' onchange='expediente(this.id,this.value)' >";
                    }
                        return gestion2;
                      }
                  }, 
               { "mData": null,"sClass": "center",                    
                    "fnRender": function (oObj) {
                        gestion = '';                            
                        if(oObj.aData.NUM_PROCESO){                            
                            gestion=oObj.aData.COMENTARIOS;                        
                    }
                    else{
                        gestion = "<input type='radio' name='gestion' id='" + oObj.aData.COD_RECEPCIONTITULO + "' value='REORGANIZACION' onclick='asignar(this.value,this.id)'> Reorganización<br>";
                        gestion += "<input type='radio' name='gestion' id='" + oObj.aData.COD_RECEPCIONTITULO + "' value='LIQUIDACION OBLIGATORIA' onclick='asignar(this.value,this.id)'> Liquidación Obli.<br>";                        
                    }
                        return gestion;
                      }
                  }, 
               { "mData": null,"sClass": "center",                    
                    "fnRender": function (oObj) {
                        gestion1 = '';                                 
//                       if(oObj.aData.NUM_PROCESO){                           
//                         gestion1 = "OK"; 
//                       }else{                            
                        gestion1 = "<a class='btn' id=\"" + oObj.aData.COD_RECEPCIONTITULO + "\" name=\"" + oObj.aData.COD_FISCALIZACION + "+" + oObj.aData.NIT_EMPRESA+ "+" + oObj.aData.COD_ESTADOPROCESO+ "+" + oObj.aData.COD_TIPO_RESPUESTA+ "\" onclick='enviar(this.name)'><i class=\"fa fa-pencil-square-o\" ></i></a>";
                   // }
                        return gestion1;
                      }
                  }                            
           ]
           
    } );
    function asignar(h,p){   
    $("#id_radio").val(p);
    $("#tip_ges").val(h); 
    }
    
    function expediente(id,valor){
        $("#expediente").val(id);
        $("#valor_exp").val(valor);    
    }
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >'+this+'</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    return false;
    }
    
    function enviar(name){    
        var name        = name.split('+');
        var cod_fis     = name[0];
        var nit_emp     = name[1]; 
        var estado      = name[2]; 
        var gestion     = name[3]; 
        var valor_exp   =$("#valor_exp").val();       
        var tip_ges     =$("#tip_ges").val();  

        var url = '<?php echo base_url('index.php/insolvencia/guardar_verificacion')?>';
        
        if (valor_exp == '' && estado != 14){
            $("#mensaje").dialog({width: 300, height: 100, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});    
        }else {
            $("#dialog").dialog({width: 400, height: 150, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});    
            $("#dialog").dialog({
            buttons: [{
                id: "si",
                text: "Si",
                class: "btn btn-success",
                click: function() {
                    redirect_by_post(url, {fiscalizacion:cod_fis,nit:nit_emp,gestion:gestion,num_proceso:valor_exp,tip_ges:tip_ges}, false);                        
                }
            },
            {
                id: "no",
                text: "No",
                class: "btn btn-success",
                click: function() {
                         $('#dialog').dialog('close');
                    }                    
                }]
            });                       
        }            
    }       
    
        
        $("#salir").confirm({
        title:"Confirmacion",
        text:"Esta Seguro de Salir?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/')?>";       
        },
        cancel: function(button) {            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
    

</script>