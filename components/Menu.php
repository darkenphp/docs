<?php
$menu = new class {
  #[\Darken\Attributes\Param]
  public string $label;

  #[\Darken\Attributes\Param]
  public string $href;
};
?>
<a href="<?= $menu->href; ?>" class="flex items-center py-2 px-2 text-white hover:bg-orange hover:text-white rounded-lg group">
  <?= $menu->label ?>
</a>