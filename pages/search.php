<?php

use Build\components\Layout;
use Darken\Attributes\QueryParam;

$search = new class {
    #[QueryParam]
    public string|null $query;
};

$layout = (new Layout('Search'))->openContent();
?>
<h1>Search</h1>
<p>Searching for: <?= htmlspecialchars($search->query); ?></p>
<?= $layout->closeContent(); ?>