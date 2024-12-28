<?php

use Darken\Attributes\Param;

$search = new class {
    #[Param]
    public string $query;
};

?>
<h1>Search</h1>