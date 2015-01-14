<script type="text/javascript">
  $(document).ready(function() {
    $(".tipogestion").change(function()
    {
      var id = $(this).val();
      var dataString = 'id=' + id;
      if (id != "") {
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(), "index.php/respuestagestion/filtro"; ?>',
          data: dataString,
          cache: true,
          success: function(html)
          {
            $(".respuestagestion").html(html);
          }
        });
      }
    });
    $(".button").click(function()
    {
      var tipogestion = $("select#tipogestion").val();
      var dataString = 'tipogestion=' + tipogestion;
      //alert (dataString);return false;
      if (tipogestion != "") {
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(), "index.php/respuestagestion/filtrofinal"; ?>',
          data: dataString,
          success: function() {

          }
        });
      }
      return false;
    });

  });
</script>



<h1>Respuesta Gestion</h1>
<?php echo form_open('respuestagestion/filtrofinal'); ?>
<?php
if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('respuestagestion/add')) {
  echo anchor(base_url() . 'index.php/respuestagestion/add/', '<i class="icon-star"></i> Nuevo', 'class="btn btn-large  btn-primary"');
}
?>
<br><br>

Tipo Gestión:
<select name="tipogestion" id="tipogestion" class="tipogestion inputbox">
  <?php
  echo '<option selected="selected" value="">--Seleccione--</option>';
  foreach ($idgestionn as $row) {
    $id = $row->COD_GESTION;
    $data = $row->TIPOGESTION;
    echo '<option value="' . $id . '">' . $data . '</option>';
  }
  ?>

</select>

Respuesta Gestión:

<select name="respuestagestion"  id="respuestagestion" class="respuestagestion inputbox" >
  <option selected="selected">--Seleccione--</option>
</select> 



<?php
$data = array(
    'name' => 'button',
    'id' => 'submit-button',
    'value' => 'Consultar',
    'type' => 'submit',
    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Consultar',
    'class' => 'btn btn-info'
);

echo form_button($data);
?>

<?php
if (isset($message)) {
  echo $message;
}
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
  $(document).ready(function() {

    $('#tablaq').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sPaginationType": "full_numbers",
      "sAjaxSource": "<?php echo base_url(); ?>index.php/respuestagestion/dataTable/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>",
            "sServerMethod": "POST",
            "aoColumns": [
              {"sClass": "center"}, /*id 0*/
              {"sClass": "center"},
              {"sClass": "item"},
              //{"sClass": "item"},
              //{ "sClass": "center" },      
              //{ "sClass": "center" },
              {"bSearchable": false, "bVisible": false},
              {"sClass": "center", "bSortable": false, "bSearchable": false, "sWidth": "1%"},
            ],
          });


        });
</script> 
<br><br>
<table id="tablaq">
  <thead>
    <tr>
      <th>Id Respuesta</th>
      <th>Tipo Gestión</th>
      <th>Respuesta Gestión</th>
      <!--<th>Descripción</th>-->
      <!--<th>Fecha Creación</th>-->
      <!--<th>Estado</th>-->
      <th>num</th>
      <th>Accíones</th>
    </tr>
  </thead>
  <tbody></tbody>     
</table>

<?php echo form_close(); ?>
