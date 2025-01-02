<?php

use App\Config;
use Build\components\Layout;
use Darken\Attributes\Inject;
use Darken\Attributes\QueryParam;
use Darken\Debugbar\DebugBarConfig;

$search = new class {
    #[QueryParam]
    public string|null $query;

    #[Inject]
    public Config $config;

    #[Inject]
    public DebugBarConfig $debugBarConfig;

    public function getSanitizedQuery() : string
    {
        return htmlspecialchars($this->query);
    }

    public function getResults() : array
    {
        $this->debugBarConfig->start('search', 'Searching for ' . $this->getSanitizedQuery());
        
        $json = json_decode(file_get_contents($this->config->getContentsFilePath()), true);

        $results = array_filter($json, fn($item) => stripos($item['content'], $this->getSanitizedQuery()) !== false);

        $this->debugBarConfig->stop('search');
        return $results;
    }
};

$layout = (new Layout('Search'))->openContent();
?>
<h1>Search</h1>
<?php foreach ($search->getResults() as $result) : ?>
    <a class="py-2 border-b border-b-lightgrey block" href="<?= $result['href']; ?>"><?= $result['href']; ?></a>
<?php endforeach; ?>
<?= $layout->closeContent(); ?>