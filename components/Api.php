<?php

use App\Config;
use Build\components\Layout;
use Darken\Attributes\ConstructorParam;
use Darken\Attributes\Inject;
use Darken\Attributes\Slot;

$api = new class {
    
    #[ConstructorParam]
    public string $title;

    #[Slot()]
    public string $content;


    #[Inject()]
    public Config $config;
    public function getNavigation() : array
    {
        $file = $this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api.json';

        $files = json_decode(file_get_contents($file), true);
        
        $nav = [];
        foreach ($files as $file) {
            $nav[$file['class']] = '/api/'.$file['slugify'];
        }

        return $nav;
    }
    
};

$layout = (new Layout($api->title, $api->getNavigation(), true))->openContent(); ?>
  <?= $api->content; ?>
<?= $layout->closeContent(); ?>