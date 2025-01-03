<?php

use Darken\Attributes\ConstructorParam;
use Darken\Attributes\Slot;

$tab = new class {
    #[Slot()]
    public $slot;

    #[ConstructorParam()]
    public $title;

    #[ConstructorParam()]
    public $prefix = '';

    #[ConstructorParam()]
    public $id;
};
?>
<div id="<?= $tab->id; ?>" class=" text-white rounded-lg mb-6">
    <div class="flex items-center justify-between">
        <span class=" bg-darken  rounded-t-lg px-4 py-2">
            <span class="text-grey text-xs mr-1">
                <?= $tab->prefix; ?>
            </span>
            <span class="text-xl font-bold text-primary">
                <?= $tab->title; ?>
            </span>
        </span>
        <a href="#<?= $tab->id; ?>" class="text-grey text-lg">#</a>
    </div>
    <div class="bg-darken p-4 rounded-b-lg">
        <?= $tab->slot; ?>
    </div>
</div>