<?php
$menu = new class {
  #[\Darken\Attributes\ConstructorParam]
  public string $label;

  #[\Darken\Attributes\ConstructorParam]
  public string $href;
};
?>
<a href="<?= $menu->href; ?>" class="flex items-center py-2 px-4 text-white hover:bg-primary hover:text-white rounded-lg group text-xs font-bold">
  <?= $menu->label ?>
</a>