<?php

use Darken\Web\Request;

$menu = new class {
  #[\Darken\Attributes\ConstructorParam]
  public string $label;

  #[\Darken\Attributes\ConstructorParam]
  public string|null $href;

  #[\Darken\Attributes\Inject]
  public Request $request;

  public function isTitleOnly(): bool
  {
    return empty($this->href);
  }

  public function isActive(): bool
  {
    return $this->request->getUri()->getPath() === $this->href;
  }
};
?>
<?php if ($menu->isTitleOnly()): ?>
  <div class="text-grey text-xs uppercase py-2 px-4 font-light mt-4"><?= $menu->label ?></div>
<?php else: ?>
<a href="<?= $menu->href; ?>" class="<?= $menu->isActive() ? 'text-primary' : 'text-white'; ?> flex items-center py-2 px-4  hover:bg-primary hover:text-white rounded-lg group text-xs font-bold">
  <?= $menu->label ?>
  <?php if ($menu->isActive()): ?>
    <span class="ml-auto text-primary hover:text-white">&larr;</span>
  <?php endif; ?>
</a>
<?php endif; ?>