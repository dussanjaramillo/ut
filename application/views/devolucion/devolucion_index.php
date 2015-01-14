<?php
$tipo_solicitud = array('name'=>'tipo_solicitud','id'=>'tipo_solicitud','type'=>'hidden');
?>
<form id="frmTmp" method="POST">
    <div id='dialog' style="display: none;">
        <center>
            <p><b>Que tipo de Devolución es?</b></p>
        </center>  
    </div>
    <div id='dialog1' style="display: none;">
        <center>
            <p><b>La Solicitud es de Pila?</b></p>
        </center>  
    </div>
<?php
echo form_input($tipo_solicitud);
?>
</form>    
<script>
    $("#dialog").dialog({width: 300, height: 150, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});    
    $("#dialog").dialog({
    buttons: [{
        id: "si",
        text: "Misional",
        class: "btn btn-success",
        click: function() { 
                    $("#tipo_solicitud").val('M');
                    var tipo_solicitud = $("#tipo_solicitud").val();
                    $("#dialog1").dialog({width: 300, height: 150, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});    
                        $("#dialog1").dialog({
                        buttons: [{
                            id: "si",
                            text: "Si",
                            class: "btn btn-success",
                            click: function() {
                                var url = "<?=base_url()?>index.php/devolucion/radicar_Onbase";
                                $(".ajax_load").show("slow");
                                $.post( url, { tipo_solicitud:tipo_solicitud});
                                $("#frmTmp").attr("action", url);
                                $('#frmTmp').submit();
                            }
                        },
                        {
                            id: "no",
                            text: "No",
                            class: "btn btn-success",
                            click: function() {
                                var url = "<?=base_url()?>index.php/devolucion/asignar_comunicacion";
                                $(".ajax_load").show("slow");                           
                                $.post( url, { tipo_solicitud:tipo_solicitud});
                                $("#frmTmp").attr("action", url);
                                $('#frmTmp').submit();    
                            }                                            
                            }]
                        });
                    }
                },
        {
        id: "nom",
        text: "No Misional",
        class: "btn btn-success",
        click: function() {
        $("#tipo_solicitud").val('N');
        var tipo_solicitud = $("#tipo_solicitud").val();
        var url = "<?=base_url()?>index.php/devolucion/asignar_comunicacion";
            $(".ajax_load").show("slow");                           
            $.post( url, { tipo_solicitud:tipo_solicitud} );
            $("#frmTmp").attr("action", url);
            $('#frmTmp').submit();    
        }                                            
        }]
    });
</script>
