<?php
$menu = new class {
  #[\Darken\Attributes\ConstructorParam]
  public string $label;

  #[\Darken\Attributes\ConstructorParam]
  public string|null $href;

  public function isTitleOnly(): bool {
    return empty($this->href);
  }
};
?>
<?php if ($menu->isTitleOnly()): ?>
  <div class="text-grey text-xs uppercase py-2 px-4 font-light mt-4"><?= $menu->label ?></div>
<?php else: ?>
<a href="<?= $menu->href; ?>" class="flex items-center py-2 px-4 text-white hover:bg-primary hover:text-white rounded-lg group text-xs font-bold">
  <?= $menu->label ?>
</a>
<?php endif; ?>