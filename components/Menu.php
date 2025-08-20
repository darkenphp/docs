<?php

use Darken\Web\Request;

$menu = new class {
  #[\Darken\Attributes\ConstructorParam(name: 'label', order: 1)]
  public string $label;

  #[\Darken\Attributes\ConstructorParam(name: 'href', order: 2)]
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
  <div class="text-grey text-xs uppercase px-3 font-light mb-1 mt-5"><?= $menu->label ?></div>
<?php else: ?>
<a href="<?= $menu->href; ?>" class="<?= $menu->isActive() ? 'text-primary' : 'text-white'; ?> flex items-center py-1 px-3 hover:bg-primary hover:text-white rounded-lg group text-xs font-bold text-ellipsis whitespace-nowrap overflow-hidden">
  <?= $menu->label ?>
  <?php if ($menu->isActive()): ?>
    <span class="ml-auto text-primary hover:text-white">&larr;</span>
  <?php endif; ?>
</a>
<?php endif; ?>