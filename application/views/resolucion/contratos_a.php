<div id="cuerpo" style="width: 90%;margin: 0 auto">
<?php
//Aprobar_resolucion::pdf($html);
if (!empty($post['pdf'])) {
    Resolucion::pdf($html);
} else {
    ?><div class="resolucion"><?php
        echo $html;
        ?></div><?php
        }
        ?>
</div>