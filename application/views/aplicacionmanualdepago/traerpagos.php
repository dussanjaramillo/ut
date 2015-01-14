<?php
if (isset($message)) {
  echo $message;
}
?>
<div class="text-center">
  <h3><?php echo $title; if (!empty($stitle)) : ?><br><small class="text-info"><?php echo $stitle ?></small><?php endif; ?></h3>
</div>
<?php print_r($empresa); ?>