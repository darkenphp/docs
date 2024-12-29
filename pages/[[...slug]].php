<?php

use App\Config;
use Build\components\Layout;
use Darken\Attributes\Inject;
use Darken\Attributes\RouteParam;

$page = new class {
    #[RouteParam]
    public string $slug;

    #[Inject]
    public Config $config;

    public function getMarkdownContent()
    {
        $text = file_get_contents($this->getMarkdownFilePath());

        $parsedown = new Parsedown();
        return $parsedown->text($text);
    }

    public function hasMarkdownFile()
    {
        return file_exists($this->getMarkdownFilePath());
    }

    public function getMarkdownFilePath(): string
    {
        $page = empty($this->slug) ? 'index' : $this->slug;

        // remove any dangerous things from $page since we use it in a file path
        $page = preg_replace('/[^a-zA-Z0-9_\-]/', '', $page);

        return $this->config->getRootDirectoryPath() . DIRECTORY_SEPARATOR . "contents/{$page}.md";
    }
};
$layout = (new Layout($page->slug))->openContent();
?>
    <?php if ($page->hasMarkdownFile()) : ?>
        <?= $page->getMarkdownContent(); ?>
    <?php else : ?>
        <?= $page->getMarkdownFilePath(); ?>
        <h1>404 - Page not found</h1>
    <?php endif; ?>
<?= $layout->closeContent(); ?>