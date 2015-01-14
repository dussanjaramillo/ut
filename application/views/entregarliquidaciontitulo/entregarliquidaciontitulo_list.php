<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<h1>Entregar Liquidación</h1>
<br>
<?php
/* boton para nuevo
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulosjudiciales/add'))
  {
  echo anchor(base_url().'index.php/titulo/add/','<i class="icon-star"></i> Nuevo','class="btn btn-large  btn-primary"');
  } */
?>
<br><br>
<table id="tablaq">
    <thead>
        <tr>        
            <th>Codigo Regional</th>
            <th>Nombre Regional</th>
            <th>No. Escritura</th>
            <th>Notaria</th>
            <th>Ciudad</th>
            <th>Id. Propietario</th>
            <th>Nombre Propietario</th>
            <th>Estado</th>
            <th>Fecha Cambio de Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // var_dump($titulos_seleccionados);die;

        if (!empty($titulos_seleccionados)) {
            foreach ($titulos_seleccionados as $data) {
                ?> 
                <tr>            
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->COD_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NUM_ESCRITURA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOTARIA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBREMUNICIPIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->COD_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_ESTADOTITULO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->FECHA_GESTION ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<br>
<center>
    <?php echo anchor('procesojudicial/', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>

<form id="form1" action="<?= base_url('index.php/procesojudicial/Agregar_Entregar_Liquidacion') ?>" method="post" >
    <input type="hidden" id="cod_titulo" name="cod_titulo" >                
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    ?>
</form>


<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
    jQuery(".preload, .load").hide();
    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
        "aoColumns": [
            {"sClass": "center"}, /*id 0*/
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ],
    });

    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_titulo = $(this).attr('at');
        $('#cod_titulo').val(cod_titulo);
        $('#form1').submit();
    });

</script> 
