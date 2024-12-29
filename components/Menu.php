<?php
$menu = new class {
  #[\Darken\Attributes\ConstructorParam]
  public string $label;

  #[\Darken\Attributes\ConstructorParam]
  public string $href;
};
?>
<a href="<?= $menu->href; ?>" class="flex items-center py-2 px-2 text-white hover:bg-orange hover:text-white rounded-lg group">
  <?= $menu->label ?>
</a>