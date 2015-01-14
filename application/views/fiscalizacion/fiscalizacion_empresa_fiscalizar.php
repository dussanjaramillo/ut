<?php 
if (isset($message)){
    echo $message;
   }
?>
 
<h1>Detalle Empresa a Fiscalizar</h1>





<CENTER>
		
		
   		NIT:<input name="nit" id="nit" size="15" value="" type="text" style="text-align:right;">
<br>
	<input type='button' id='consultar_asignacion' name='consultar_asignacion' class='btn btn-success' value='CONSULTAR' >
</CENTER>

<form id="f2" action="<?= base_url('index.php/fiscalizacion/detalle_empresa_fiscalizar') ?>" method="post" >
    <input type="hidden" id="cod_nit" name="cod_nit">

</form>

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
 $('#tablaemp').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aaSorting": [[ 2, "desc" ]],
        
          	"bFilter": false,
        	"aoColumns": [ 

					{ "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                    
                      ],
        
    });
$(document).ready(function() {
$(".preload, .load").hide();

$('#consultar_asignacion').click(function(){
$(".preload, .load").show();	
var nit = $("#nit").val();
 $('#cod_nit').val(nit);
 $('#f2').submit();
});	



});


function ajaxValidationCallback(status, form, json, options) {
	
}

</script>

<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>

<a name="abajo"></a>
