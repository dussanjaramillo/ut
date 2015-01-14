
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1>Acumulación de Titulos</h1>
    <h2><?php echo 'Gestión de Actividades ' . $grupo; ?></h2>

</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Identificación</th>
                <th>Ejecutado</th>
                <th>Representante Legal</th>                
                <th>Teléfono</th>
                <th>Instancia</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //var_dump($remisibilidad_seleccionados);die;
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    ?> 
                    <tr>          
                        <td><?= $data->IDENTIFICACION ?></td>
                        <td><?= $data->NOMBRE ?></td> 
                        <td><?= $data->REPRESENTANTE ?></td> 
                        <td><?= $data->TELEFONO ?></td> 
                        <td><?= $data->ORIGEN ?></td> 
                        <td><input type="radio" onclick="enviar(<?php echo $data->IDENTIFICACION; ?>)"></td> 
                        <?php
                    }
                    ?>  
                </tr>
                <?php
            }
            ?>
        </tbody>     
    </table>
</div>
<form id="form1" action="<?php echo base_url("index.php/acumulaciontitulos/ListadoAcumular")?>" method="post" >   
    <input type="hidden" id="cod_titulo" name="cod_titulo"> 
</form>
<br>

<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
    jQuery(".preload, .load").hide();

    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
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
            },
            "fnInfoCallback": null,
        },
        "sServerMethod": "POST",
        "aoColumns": [
            {"sClass": "center"}, /*id 0*/
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ],
    });
   function enviar(identificacion) {
        $(".preload, .load").show();
        $('#cod_titulo').val(identificacion);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }


</script> 