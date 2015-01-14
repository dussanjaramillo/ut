<!--
    /**
     * Formulario Principal para la consulta de Procesos
     * En este formulario se escoge cual de los procesos se desea ver
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<h1>Seleccione la Direcci&oacute;n que Desea Consultar</h1>
<br>
<form action= "<?php echo base_url('index.php/consultarprocesos/consultadaf'); ?>" method="post">
    <button type='submit' name='daf' id='admon' class='btn btn-success' style="height: 70px; width: 200px;display: block; margin: 0 auto 0 auto" ><i class='fa fa-users' > Direcci&oacute;n Administrativa y Financiera</i></button>    
</form>

<form action= "<?php echo base_url('index.php/consultarprocesos/consultadj'); ?>" method="post">
    <button type='submit' name='dj' id='admon' class='btn btn-success' style="height: 70px; width: 200px; display: block; margin: 0 auto 0 auto "><i class='fa fa-institution' > Direcci&oacute;n Jur&iacute;dica</i></button>
</form>

<?php
