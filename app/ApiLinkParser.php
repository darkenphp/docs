<?php

namespace App;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class ApiLinkParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        // Correctly escaped backslashes for single-quoted PHP string
        return InlineParserMatch::regex('@\(([A-Za-z0-9_\\\\]{1,100})\)');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        
        // The @ symbol must not have any other characters immediately prior except start of line or whitespace
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && !ctype_space($previousChar)) {
            return false;
        }

        // Advance the cursor to the end of the match
        $cursor->advanceBy($inlineContext->getFullMatchLength());

        // Grab the full class name including namespaces
        [$fullClassName] = $inlineContext->getSubMatches();

        // Slugify the class name: replace backslashes with hyphens and convert to lowercase
        $slugifiedClassName = strtolower(str_replace('\\', '-', $fullClassName));

        // Create the URL. Adjust the base path as necessary.
        $url = '/api/' . $slugifiedClassName;

        // Create the link text. You can customize this as needed.
        $linkText = ' ↱ ' . $fullClassName;

        // Append the Link node to the container
        $inlineContext->getContainer()->appendChild(new Link($url, $linkText));

        return true;
    }
}
