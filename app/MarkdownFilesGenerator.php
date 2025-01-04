<?php

namespace App;

use Darken\Events\EventDispatchInterface;
use Darken\Events\EventInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Files\FileHelper;

class MarkdownFilesGenerator implements EventInterface
{
    public function __construct(private Config $config)
    {
        
    }
    public function __invoke(EventDispatchInterface $event): void
    {
        $files = FileHelper::findFiles($this->config->getRootDirectoryPath() . DIRECTORY_SEPARATOR . 'contents', ['only' => ['*.md']]);
        $json = [];
        foreach ($files as $markdown) {
            $text = file_get_contents($markdown);
            $md = new Markdown();
            $r = $md->toResult($text);
            $cfg = $md->getFrontMatter($r);
            $href = pathinfo($markdown, PATHINFO_FILENAME);
            $json[$href] = [
                'content' => $text,
                'href' => $href,
                'title' => ArrayHelper::getValue($cfg, 'title', ''),
                'description' => ArrayHelper::getValue($cfg, 'description', ''),
            ];
        }
        file_put_contents($this->config->getContentsFilePath(), json_encode($json));
    }
}