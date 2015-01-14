<div id="planilla"><h1>CARGUE ARCHIVOS PLANILLA ÚNICA</h1></div>
<table align='center'>
    <tr>
        <td align="center"><h5>Rutas Archivos a Cargar</h5></td>
    </tr>
</table>
<table>
    <tr>
        <td>Ubicación</td>
        <td><input type='text' name='ubicacion' id='ubicacion'></td>
        <td><button type='button' id='buscar'>Buscar</button></td>
    </tr>
</table>
<table>
    <tr>
        <td><button type='button' id='cargar'>Cargar</button></td>
        <td><button type='button' id='cancelar'>Cancelar</button></td>
    </tr>
</table>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
$('#cargaarchivos').dialog({ autoOpen : true,
                            width:800,
                            height:600
    });
</script>
