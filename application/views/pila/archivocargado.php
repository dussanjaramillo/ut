<?php if (!empty($cero)): ?>  
    <table border="1" align="center">
        <tr><td style="color:white; background-color: orange;" align="center"><b>Archivos tipo cero</b></td></tr>
        <?php
        foreach ($cero as $archivoscero):
            ?>
            <tr><td style="cursor:pointer" class="tipocero" archivo="<?= $archivoscero ?>"><?= $archivoscero ?></td></tr>
            <?php
        endforeach;
        ?>
    </table>
<?php endif; ?>  
<?php if (!empty($existente)): ?>  
    <table border="1" align="center">
        <tr><td style="color:white; background-color: blue;" align="center"><b>Archivos existentes en el sistema</b></td></tr>
        <?php
        foreach ($existente as $archivosexistentes):
            ?>
            <tr><td><?= $archivosexistentes ?></td></tr>
            <?php
        endforeach;
        ?>
    </table>
    <p>
    <?php endif; ?>   
    <?php if (!empty($carga)): ?>

        <?php $attributes = array('name' => 'form', 'id' => 'form'); ?>
        <?= form_open(base_url('index.php/pila/exportar_excel'), $attributes) ?>
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div id="carga"></div>    
    <div id="excel"> 
        <table border="1" align="center" id="exportardatos" id="tablaexito">
            <tr><td align="center"><button type="button" id="exportar" class="btn btn-success">Exportar Subidos Con Exito</button></td></tr>
            <tr><td style="color:white; background-color: green;" align="center"><b>Archivos Subidos Con exito</b></td></tr>
            <?php
            foreach ($carga as $archivos):
                ?>
                <tr><td class="archivo" archivo="<?= $archivos ?>" style="cursor:pointer"> <?= $archivos ?></td></tr>
                <?php
            endforeach;
            ?>
        </table>
    </div>   
    <p>

        <?= form_close() ?>        
    <?php endif; ?>
    <?php if (!empty($vacios)): ?>    
    <table border="1" align="center">
        <tr><td style="color:white; background-color: red;" align="center"><b>Archivos Vacios</b></td></tr>
        <?php
        foreach ($vacios as $archivosvacios) :
            ?>
            <tr><td><?= $archivosvacios ?></td></tr>
            <?php
        endforeach;
        ?>
    </table>
<?php endif; ?>
<div id="tipos" class="modal hide fade modal-body">
    <table border="1" align="center">
        <tr><td style="background-color: red;color: white;" align="center"><b>TIPO DE ARCHIVO</b></td></tr>
        <tr><td style="background-color: red;color: white;" align="center" id="archivoconsulta" ><b></b></td></tr>
        <tr><td style="cursor:pointer" id="tipouno" align="center"><b>TIPO 1</b></td></tr>
        <tr><td style="cursor:pointer;background-color: #75FFCE"  id="tipodos" align="center"><b>TIPO 2</b></td></tr>
        <tr><td style="cursor:pointer" id="tipotres" align="center"><b>TIPO 3</b></td></tr>
    </table>
    <p>
    <div id="cargadatos"></div>
</div>
<div id="tipocero"  class="modal hide fade modal-body"></div>    

<script>

    $(document).ready(function() {
        $("#exportar").click(function(event) {
                $("#carga *").remove();
                var datos = $("#carga").append($("#excel").eq(0).clone()).html();
                
                $("#carga").hide(); 
            
            $("#datos_a_enviar").val(datos);
            $("#form").submit();
        });
    });




    $('#tipos').hide();
    $('#tipocero').hide();
    $('.archivo').click(function() {

        var archivo = $(this).attr('archivo');
        $('#archivoconsulta').attr('archivo', archivo);
        $('#archivoconsulta').text(archivo);
        $('#tipos').css({
            'width': '500px',
            "position": "fixed",
            "height": 'auto',
//                "top" : "20",
            "left": "70%",
            "top": "20%",
            "margin-left": "-500px"
        });
        $('#tipos').modal('show');
    });

    $('#tipouno').click(function() {

        $('#cargadatos *').remove();
        var archivo = $('#archivoconsulta').attr('archivo');
        var tipo = 1;
        var url = "<?= base_url('index.php/pila/visualizarcarga') ?>";
        $.post(url, {archivo: archivo, tipo: tipo}, function(data) {
            var table = '';
            for (var arreglo in data) {
                table += "<table border ='1'>"
                $.each(data[arreglo], function(key, val) {
                    table += "<tr><td>" + key + "</td><td>" + val + "</td></tr>";
                });
                table += "</table><p>";
            }
            $('#cargadatos').append(table);
        });
    });
    $('#tipodos').click(function() {
        $('#cargadatos *').remove();
        var archivo = $('#archivoconsulta').attr('archivo');
        var tipo = 2;
        var url = "<?= base_url('index.php/pila/visualizarcarga') ?>";
        $.post(url, {archivo: archivo, tipo: tipo}, function(data) {
            var table = '';
            for (var arreglo in data) {
                table += "<table border ='1'>"
                $.each(data[arreglo], function(key, val) {
                    table += "<tr><td>" + key + "</td><td>" + val + "</td></tr>";
                });
                table += "</table><p>";
            }
            $('#cargadatos').append(table);
        });
    });
    $('#tipotres').click(function() {
        $('#cargadatos *').remove();
        var archivo = $('#archivoconsulta').attr('archivo');
        var tipo = 3;
        var url = "<?= base_url('index.php/pila/visualizarcarga') ?>";
        $.post(url, {archivo: archivo, tipo: tipo}, function(data) {
            var table = '';
            for (var arreglo in data) {
                table += "<table border ='1'>"
                $.each(data[arreglo], function(key, val) {
                    table += "<tr><td>" + key + "</td><td>" + val + "</td></tr>";
                });
                table += "</table><p>";
            }
            $('#cargadatos').append(table);
        });
    });
    $('.tipocero').click(function() {
        $('#tipocero *').remove();
        var archivo = $(this).attr('archivo');

        var tipo = 0;
        var url = "<?= base_url('index.php/pila/visualizarcarga') ?>";
        $.post(url, {archivo: archivo, tipo: tipo}, function(data) {
            var table = '';
            for (var arreglo in data) {
                table += "<table border ='1'>"
                $.each(data[arreglo], function(key, val) {
                    table += "<tr><td>" + key + "</td><td>" + val + "</td></tr>";
                });
                table += "</table><p>";
            }
            $('#tipocero').append(table);
        });

        $('#tipocero').css({
            'width': '500px',
            "position": "fixed",
            "height": 'auto',
//                "top" : "20",
            "left": "70%",
            "top": "20%",
            "margin-left": "-500px"
        });

        $('#tipocero').modal('show');
    });
</script>    


