<p><br>
<p>
<center>
    <h2><center>CITACIONES</center></h2>
    <table id="styletable">
        <thead>
        <th>NUMERO DE LA CITACION</th>
        <th>FECHA CITACION</th>
        <th>ESTADO</th>
        <th>NIT</th>
        <th>GERTIONAR</th>
        </thead>
        <tbody>
                <tr>
                    <td>
                        <a href="aprobar_resolucion_coordinador/">Coordinador</a>
                    </td>
                    <td>
                        <a href="aprobar_resolucion_coordinador/">Coordinador</a>
                    </td>
                    <td>
                        <a href="aprobar_resolucion_coordinador/">Coordinador</a>
                    </td>
                    <td>
                        <a href="aprobar_resolucion_coordinador/">Coordinador</a>
                    </td>
                    <td>
                        <input type="radio" name="radios" onclick="citacion('1')" value=""/>
                    </td>
                </tr>
        </tbody>
    </table>
</center>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="formulario1"></div>
<script>
    $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
    });
    $(".preload, .load").hide();
    function citacion(id){
        $(".preload, .load").show();
       var url = "<?php echo base_url('index.php/resolucion/desicion') ?>";
       $('#formulario1').load(url)
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