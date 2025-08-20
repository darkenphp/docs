<?php

use Darken\Attributes\ConstructorParam;
use Darken\Attributes\Slot;

$tab = new class {
    #[Slot()]
    public $slot;

    #[ConstructorParam(name: 'title', order: 1)]
    public string $title;

    #[ConstructorParam(name: 'id', order: 2)]
    public string $id;

    #[ConstructorParam(name: 'lineNumber', order: 3)]
    public string|int $lineNumber;

    #[ConstructorParam(name: 'github', order: 4)]
    public string $github;

    #[ConstructorParam(name: 'visibility', order: 5)]
    public string $visibility;

    #[ConstructorParam(name: 'returnType', order: 6)]
    public string|null $returnType;

    #[ConstructorParam(name: 'arguments', order: 7)]
    public array|null $arguments;

    public function getFileName() : string
    {
        return pathinfo($this->github, PATHINFO_BASENAME);
    }

    public function getArgumentsString() : string
    {
        $arguments = [];
        foreach ($this->arguments as $argument) {
            $arg = '';
            if ($argument->type) {
                $arg .= "<span class=\"text-xs text-grey\">$argument->type</span> ";
            }
            $arguments[] = $arg .= "<span class=\"text-xs\">".trim($argument->name)."</span>";
        }

        return implode(', ', $arguments);
    }
};
?>
<div id="<?= $tab->id; ?>" class=" text-white rounded-lg mb-6">
    <div class="flex items-center justify-between">
        <span class=" bg-darken  rounded-t-lg px-4 py-2">
            <span class="text-grey text-xs mr-1">
                <?= $tab->visibility; ?>
            </span>
            <?php if ($tab->returnType): ?>
            <span class="text-grey text-xs mr-1">
                <?= $tab->returnType; ?>
            </span>
            <?php endif; ?>
            <span class="text-base font-bold text-primary">
                <?= $tab->title; ?>
                </span>
                <?php if (is_array($tab->arguments)) : ?>
                (<?= $tab->getArgumentsString(); ?>)
                <?php endif; ?>
            
        </span>
        <a href="<?= $tab->github; ?>#L<?= $tab->lineNumber; ?>" target="_blank" class="text-grey text-xs"><?= $tab->getFileName(); ?>#<?= $tab->lineNumber; ?></a>
    </div>
    <div class="bg-darken p-4 rounded-b-lg">
        <?= $tab->slot; ?>
    </div>
</div>