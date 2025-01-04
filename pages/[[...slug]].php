<?php

use App\Config;
use App\Markdown;
use Build\components\Guide;
use Darken\Attributes\Inject;
use Darken\Attributes\RouteParam;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;

$page = new class {
    #[RouteParam]
    public string $slug;

    #[Inject]
    public Config $config;

    public function getMarkdownContent()
    {
        $text = file_get_contents($this->getMarkdownFilePath());

        $markdown = new Markdown();

        return $markdown->toResult($text)->getContent();
    }

    public function getContentsJsonItem() : array
    {
        $json = json_decode(file_get_contents($this->config->getContentsFilePath()), true);

        return $json[$this->getSanitizedSlug()] ?? [];
    }

    public function hasMarkdownFile()
    {
        return file_exists($this->getMarkdownFilePath());
    }

    public function getSanitizedSlug() : string
    {
        return empty($this->slug) ? 'index' : preg_replace('/[^a-zA-Z0-9_\-]/', '', $this->slug);
    }

    public function getMarkdownFilePath(): string
    {
        return $this->config->getRootDirectoryPath() . DIRECTORY_SEPARATOR . "contents/{$this->getSanitizedSlug()}.md";
    }
};
$layout = (new Guide($page->getContentsJsonItem()['title'] ?? '404'))->openContent();
?>
    <?php if ($page->hasMarkdownFile()) : ?>
        <div class="md">
            <?= $page->getMarkdownContent(); ?>
        </div>
    <?php else: ?>
        <div class="md">
            <h1>404 - Page not found</h1>
            <p><?= $page->getMarkdownFilePath(); ?></p>
        </div>
    <?php endif; ?>
<?= $layout->closeContent(); ?>