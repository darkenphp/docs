<?php

use App\Config;
use App\Markdown;
use Build\components\Layout;
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
        <div class="md">
            <?= $page->getMarkdownContent(); ?>
        </div>
    <?php else : ?>
        <h1>404 - Page not found</h1>

        <p><?= $page->getMarkdownFilePath(); ?></p>
    <?php endif; ?>
<?= $layout->closeContent(); ?>