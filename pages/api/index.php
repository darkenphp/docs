<?php

use App\Config;
use Build\components\Api;
use Darken\Attributes\Inject;

$api = new class {
    #[Inject()]
    public Config $config;

    public function getIndex() : array
    {
        return json_decode(file_get_contents($this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api.json'), true);
    }
}
?>
<?php $layout = (new Api('API'))->openContent(); ?>
<div class="md">
    <h1>API</h1>

    <?php foreach ($api->getIndex() as $index) : ?>
        <a class="mb-2 p-2 text-sm text-white bg-darken rounded-lg block no-underline border-0" href="/api/<?= $index['slugify']; ?>"><?= $index['class']; ?></a>
    <?php endforeach; ?>
</div>
<?= $layout->closeContent(); ?>