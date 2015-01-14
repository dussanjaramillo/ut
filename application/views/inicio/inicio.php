<?php 
if (isset($message)){
    echo $message;
   }

?>


<h1>Página de bienvenida</h1>
<p>
     <b>Hola <?php echo $user->NOMBRES.' '.$user->APELLIDOS; ?></b>
</p>
<p>
  Esta página estará destinada a mostrar algunas alertas, mensajes de entrada... etc.
</p>

