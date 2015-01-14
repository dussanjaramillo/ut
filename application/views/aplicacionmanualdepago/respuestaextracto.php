<div style="background: #f0f0f0; width: 470px; margin: auto; overflow: hidden">
  <h3 class="text-center"><?php echo $title; if (!empty($stitle)) : ?><br><small><?php echo $stitle ?></small><?php endif; ?></h3>
  <div style="overflow: hidden; width: 90%; margin: 0 auto">
    <div class="alert alert-<?php echo $class ?>"><?php
      if(!empty($mensaje) and $error == true) echo validation_errors();
      elseif(!empty($file_error)) echo $file_error['error'];
      else echo $mensaje;
    ?></div>
  </div>
</div>