<?php
$aceptar_mostrarplanilla        =   array('name'=>'aceptar_mostrarplanilla','id'=>'aceptar_mostrarplanilla','type'=>'submit','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$cancelar_mostrarplanilla       =   array('name'=>'cancelar_mostrarplanilla','id'=>'cancelar_mostrarplanilla','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?= form_open("devolucion/mostrar_planilla1")?>
    <div id="realizarcalculotabla">
    <table id="realizar_cal" border="1">
        <thead>
    <tr>
        <td>Selec</td>
        <td>N. planilla</td>
        <td>Periodo</td>
        <td>v. Pagado</td>
        <td>F. Pagado</td>
    </tr>
        </thead>
        <tbody id="dblData">
    <?php  foreach ($mostrarplanilla->result_array as $data) { ?>
    <tr>
        <td><input type="radio" name="sel" id="sel"  value="<?= $data['N_INDENT_APORTANTE'] ?>" class="seleccion" required="true"> </td>
        <td><?= $data['COD_PLANILLAUNICA'] ?></td>
        <td><?= $data['PERIDO_PAGO'] ?></td>
        <td><?= $data['APORTE_OBLIG'] ?></td>
        <td><?= $data['FECHA__PAGO'] ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>

</div>
    <table cellpadding="15%">
        <tr>
            <td><?=form_button($aceptar_mostrarplanilla)?></td><td><?=form_button($cancelar_mostrarplanilla)?></td>
        </tr>
    </table>
    <input type="hidden" name="codigo" id="codigo" value="<?= $data['COD_PLANILLAUNICA'] ?>">
    <?= form_close()?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
$('#realizar_cal').dataTable( {
            "bJQueryUI": true,
            "bProcessing": true,
            "bPaginate": false,
            "bScrollCollapse": true    
    });
    
    $("#cancelar_mostrarplanilla").confirm({
         title:"Confirmacion",
        text:"Esta seguro de cancelar?",
        confirm: function(button) {
             $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal_traer').modal('hide').removeData();      
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
   
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal_traer').modal('hide').removeData();
    });

    
    </script>