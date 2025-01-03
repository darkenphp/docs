<?php
namespace App;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;
use Zenstruck\CommonMark\Extension\GitHub\AdmonitionExtension;

class Markdown
{
    public function getConverter() : MarkdownConverter
    {
         // Define your configuration, if needed
         $config = [];

         // Configure the Environment with all the CommonMark parsers/renderers
         $environment = new Environment($config);
         $environment->addExtension(new CommonMarkCoreExtension());
         $environment->addExtension(new GithubFlavoredMarkdownExtension());
         $environment->addExtension(new FrontMatterExtension());
         $environment->addExtension(new AdmonitionExtension());

         $environment->addInlineParser(new ApiLinkParser());
 
         $converter = new MarkdownConverter($environment);

         return $converter;
    }

    public function toResult(string $text) : RenderedContentInterface
    {
        $converter = $this->getConverter();
        return $converter->convert($text);
    }

    public function getFrontMatter(RenderedContentInterface $result) : array
    {
        if ($result instanceof RenderedContentWithFrontMatter) {
            return $result->getFrontMatter();
        }

        return [];
    }
}