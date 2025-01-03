<?php

use App\Config;
use Build\components\Guide;
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

$layout = (new Guide('Search'))->openContent();
?>
<div class="md p-2">
    <h1>Search</h1>
    <h2>Look up in the Documentaton for "<?= $search->getSanitizedQuery(); ?>" in all Pages.</h2>
</div>
<?php foreach ($search->getResults() as $result) : ?>
    <a class="p-2 block no-underline border-none group hover:bg-primary hover:text-white hover:rounded-lg" href="<?= $result['href']; ?>">
        <div class="text-primary text-lg font-bold border-none no-underline group-hover:text-white"><?= $result['title']; ?></div>
        <div class="text-grey group-hover:text-white"><?= $result['description']; ?></div>
    </a>
<?php endforeach; ?>
<?= $layout->closeContent(); ?>