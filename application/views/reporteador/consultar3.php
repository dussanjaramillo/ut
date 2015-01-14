<center><h3>Consulta de reportes Guardados</h3></center>
<?php
$i = 0;
$tbody = "";
$thead = "";
$k = 0;
foreach ($general as $detalle) {
    if ($i % 2 == 0)
        $tbody.= '<tr style="background-color: #f9f9f9">';
    else
        $tbody.= '<tr >';
    $j = 0;
    foreach ($detalle as $key => $value) {
        if ($k < count($detalle)) {
            $thead.="<th bgcolor='#5BB75B' style='border:1px solid #CCC'>" . $key . "</th>";
            $k++;
        }
        if ($j == 2) {
            $value = "<center><button valor='" . $value . "' class='cunsulta fa fa-eye' ></button>"
                    . '<button class="cunsulta_excel btn btn-info fa fa-table" valor="' . $value . '"  id="excel">Excel</button></center>';
        }
        $tbody.="<td style='border:1px solid #CCC'>" . $value . "</td>";
        $j++;
    }
    $tbody.= "</tr>";
    $i++;
}
?>
<table width="100%">
    <thead style="color: #ffffff">
        <?php echo $thead; ?>
    </thead>
    <tbody>
        <?php echo $tbody; ?>
        <?php // echo (empty($tbody))?$tbody:$tbody2; ?>
    </tbody>
</table> <br>
<div id="impri" style="display: none" align="center">
    <button class="btn btn-info" id="imprimir">Imprimir</button>
    <button class="btn btn-danger" id="pdf">PDF</button>
</div>
<p>
<div id="resultado2"></div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<form action="<?php echo base_url('index.php/reporteador/ingresos') ?>" method="post" id="form3">
    <textarea id="consultass" name="consultass" style="display: none"></textarea>    
    <input type="hidden" id="accion" name="accion" value="1">
    <input type="hidden" id="name_reporte" name="name_reporte" value="informacion">
    <input id="reporte" name="reporte" type="hidden" value="77">
</form>

<script>


    $('.cunsulta_excel').click(function() {
        var consulta_info = $(this).attr('valor');
        $('#consultass').val(consulta_info);
        $('#form3').submit();
    })
    $('.cunsulta').click(function() {
        $('#impri').show();
        $(".preload, .load").show();
        var consulta_info = $(this).attr('valor');
        var url = '<?php echo base_url('index.php/reporteador/consultar2') ?>';
        $.post(url, {consulta_info: consulta_info})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    $('#resultado2').html(msg);
                    $('#consulta_info').val(consulta_info);
                }).fail(function() {
            alert("Error en la consulta");
            $('#consulta_info').val(consulta_info);
            $(".preload, .load").hide();
        });
    });
    $(".preload, .load").hide();

    $('#imprimir').click(function() {
        printdiv();
    });
    $('#pdf').click(function() {
        $('#accion').val('1');
        $('#form2').submit();
    });
    $('#excel').click(function() {
        $('#accion').val('2');
        $('#form2').submit();
    });
    function printdiv()
    {
//        var tittle = document.getElementById(id_cabecera);
        var tittle = "";
        var content = document.getElementById('resultado2');
        var printscreen = window.open('', '', 'left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
//        printscreen.document.write(tittle.innerHTML);
        printscreen.document.write(content.innerHTML);
        printscreen.document.close();

        var css = printscreen.document.createElement("link");
        css.setAttribute("href", "<?php echo base_url() ?>/css/bootstrap.css");
        css.setAttribute("rel", "stylesheet");
        css.setAttribute("type", "text/css");

        var css1 = printscreen.document.createElement("link");
        css1.setAttribute("href", "<?php echo base_url() ?>/css/style.css");
        css1.setAttribute("rel", "stylesheet");
        css1.setAttribute("type", "text/css");

        printscreen.document.head.appendChild(css);
        printscreen.document.head.appendChild(css1);


        printscreen.focus();
        printscreen.print();
        printscreen.close();
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
