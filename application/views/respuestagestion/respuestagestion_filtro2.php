<?php

echo '<option selected="selected">--Seleccione--</option>';
foreach ($respuestas->result() as $row) {
  $id = $row->COD_RESPUESTA;
  $data = $row->NOMBRE_GESTION;
  echo '<option value="' . $id . '">' . $data . '</option>';
} 