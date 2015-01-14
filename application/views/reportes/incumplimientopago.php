<table align='center' style="border: 3px solid; border-color: #777777">
    <tr>
        <td>Nombre o Razón Social</td>
        <td>Numero</td>
        <td>Fecha Aviso Cumplimiento</td>
        <td>Estado</td>
    </tr>
    <tr>
        <td><input type="text" name="nombre" id="nombre"></td>
        <td><input type="text" name="numero" id="numero"></td>
        <td><input type="date" name="fechaaviso" id="fechaaviso"></td>
        <td><select><option id="estado">-Seleccionar-</option></select></td>
    </tr>
    <tr align='center'>
        <td colspan="4"><button id="consultar" class="btn btn-success">Consultar</button>
            <button id="crear" class="btn btn-success">Crear</button>
            <button id="cancelar" class="btn btn-success">Cancelar</button></td>
    </tr>
</table>
<br>
<br>
<div id="incumplimiento" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Eliminar</th>
            <th>Ver</th>
            <th>Imprimir</th>
            <th>Número de Documento</th>
            <th>Creado por</th>
            <th>Fecha de Creación</th>
            <th>Fecha de Envio</th>
            <th>Estado</th>
    </thead>
        <thead align="center">
            <th><input type="radio" name="radio" class="radio" id="eliminar" value="1"></th>
            <th><input type="radio" name="radio" class="radio1" id="ver" value="1"></th>
            <th><input type="radio" name="radio" class="radio2" id="imprimir" value="1"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
    </thead>
    </table>
    <br>
    <br>
    <table align="center">
        <tr>
            <td><button id="salir" class="btn btn-success">Salir</button></td>
        </tr>
    </table>
</div>
<div id="aviso" style="display: none">
    <table align="center">
        <tr>
            <td><h3>AVISO DE INCUMPLIMIENTO EN EL PAGO</h3></td>
        </tr>
    </table>
<br>
<table align="center" border="1" style="width: 1700px; height: 50px;">
        <tr align="center" bgcolor="green" style="color: white">
            <td>Nombre o Razon Social del Aportante</td>
            <td>Tipo de Documento</td>
            <td>Número de Documento del Aportante</td>
            <td>Número de Digito de Verificación</td>
            <td>Nombre del Archivo</td>
            <td>Fecha de Envio de la Comunicación</td>
        </tr>
        <tr>
            <td><br></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><br></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
<input type="hidden" name="opcion" id="opcion">
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    $(document).ready(function() {
    $('#estilotabla').dataTable({
                    "sDom": 'T<"clear">lfrtip',
                    "oTableTools": {
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }
                });  
                });  
   
    $('#consultar').click(function(){
      $('#incumplimiento').css('display','block');
    });
    $('#salir').click(function(){
      $('#incumplimiento').css('display','none');  
    });
    
    $('.radio1').click(function(){
        $('#aviso').dialog({
                        width : 900,
                        height : 400,
                        modal : true,
                        buttons : [{
                                id : 'imprimir1',
                                text : 'Imprmir',
                                class : 'btn btn-success'
                            },  
                            {        
                             id : 'regresar',
                             text : 'Regresar',
                             class : 'btn btn-success',
                             click : function(){
                                 $('#aviso').dialog('close');
                             }
                        }]
        });
    });
    
$("#fechaaviso").datepicker();
</script>