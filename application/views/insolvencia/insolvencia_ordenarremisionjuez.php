<?php
if (isset($message)){
    echo $message;
   }
   
$id_radio           = array('name'=>'id_radio','id'=>'id_radio','type'=>'hidden');
$tip_ges            = array('name'=>'tip_ges','id'=>'tip_ges','type'=>'hidden');
$button             = array('name'=>'salir','id'=>'salir','value'=>'salir','content'=>'<i class=""></i> Salir','class'=>'btn btn-success btn1');
?>
<div style="max-width: 1015px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <div align="center" style="background-color: #FC7323; color: #ffffff;"><h3>Ordenar Remisión</h3></div>
    
    <br>
        <table id="titulos">
            <thead>
           <tr>
              
                <th>No. Documento</th>
                <th>Razón Social</th>
                <th>N. Titulo</th> 
                <th>F. Radicado</th>
                <th>Obervaciones</th>
                <th>Gestión</th>
                <th>Remitir</th>
                
<!--                <th>Ver Titulo</th>-->
          </tr>
        </thead>
        <tbody></tbody>     
    </table>
    
</div>    
<?=form_input($id_radio)?><?=form_input($tip_ges)?>
<br>
<div align="center">
    <?= form_button($button)?>
    
</div>
<div id="mensaje" style="display: none;">Debe de Hacer la Búsqueda por un Solo Parámetro</div>

    <script type="text/javascript" language="javascript" charset="utf-8"> 
        var oTable;
        var gestion ='';
        var gestion2='';
      
    oTable = $('#titulos').dataTable( {
        
        "sServerMethod": "POST", 
        "bJQueryUI":    true,
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
        },
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 5,
        "sSearch": '',
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "viene", "value": '2' } );
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
               { "mDataProp": "COD_RECEPCION_TITULO","sClass": "center" },  
               { "mDataProp": "FECHA_RECEPCION","sClass": "center" },
               { "mDataProp": "OBSERVACIONES","sClass": "center" },
               { "mDataProp": "COMENTARIOS","sClass": "center" },
               { "mData": null,"sClass": "center",
                    
                    "fnRender": function (oObj) {
                        gestion1 = '';
                        
                       
                            
                        //gestion1 = "<a class='btn' href='<?=base_url()?>index.php/insolvencia/guardartitfisi/" + oObj.aData.COD_TITULO + "/" + $("#gestion").val() + "' id=\"Gestionar\"><i class=\"fa fa-pencil-square-o\"></i></a>";
                        gestion1 = "<a class='btn' id=\"" + oObj.aData.COD_RECEPCION_TITULO + "\" onclick='enviar(this.id)'><i class=\"fa fa-pencil-square-o\" ></i></a>";
                    
                        return gestion1;
                      }
                  }                            
           ]
    } );
    function asignar(h,p){
   // alert("prueba"+h+" prueba 1"+p);
    $("#id_radio").val(p);
    $("#tip_ges").val(h);
    
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
    function enviar(h){
   
    var url       = "<?=base_url()?>index.php/insolvencia/ordenar_remision";  
  
        
    redirect_by_post(url, {
            cod_titulo: h
         }, false);
    
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

