<?php 
if (isset($message)){
    echo $message;
   }
?>

<h2>Cargar Extracto Asobancaria</h2>
<?php
if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarextractoasobancaria/add'))
    {
      echo anchor(base_url().'index.php/cargarextractoasobancaria/add/','<i class="icon-star"></i> Cargar','class="btn btn-large  btn-primary" style="margin-left: 10px;"');
      echo anchor(base_url().'index.php/cargarextractoasobancaria/cargados','<i class="icon-star"></i> Cargados','class="btn btn-large  btn-primary" style="margin-left: 10px;"');
      echo anchor(base_url().'index.php/cargarextractoasobancaria/conciliar','<i class="icon-star"></i> Conciliar Pagos','class="btn btn-large  btn-primary" style="margin-left: 10px;"');
    }
?>
<br><br>

