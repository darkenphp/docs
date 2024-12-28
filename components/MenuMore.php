<?php

use Build\components\Menu;

$more = new class {
    #[\Darken\Attributes\Param]
    public string $label;

    #[\Darken\Attributes\Slot]
    public string $buttons;

    #[\Darken\Attributes\Param]
    public array $items = [];
};
?>
<!-- Messages with subitems -->
<div class="mb-2 relative group">
    <input type="checkbox" id="messages-toggle" class="hidden peer">

    <label for="messages-toggle"
        class="flex items-center px-12 py-2 mt-2 text-gray-100 hover:bg-gray-700 cursor-pointer w-full">
        
        <?= $more->label; ?>
    </label>

    <!-- SVG Icons op hetzelfde niveau als input -->
    <!-- <div class="absolute left-4 top-2 transform #dis-translate-y-1/2 flex items-center text-white"> -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="absolute top-2 left-4 text-white group-hover:hidden h-6 w-6 mr-2 peer-checked:hidden">
        <path stroke-linecap="round" stroke-linejoin="round"
        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
    </svg>

    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="absolute top-2 left-4 text-white hidden group-hover:block peer-checked:block h-6 w-6 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round"
        d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z" />
    </svg>
    <!-- </div> -->

    <!-- Arrow Icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
        class="h-4 w-4 ml-auto transition-transform transform peer-checked:rotate-180 absolute right-4 top-3 transform #dis--translate-y-1/2 text-white"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>

    <div class="hidden peer-checked:flex flex-col mt-1 transition-all duration-300">
        <?= $more->buttons; ?>
        <?php foreach ($more->items as $href => $l): ?>
            <?= new Menu($l, $href); ?>
        <?php endforeach; ?>
    </div>
</div>