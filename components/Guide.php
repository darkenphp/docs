<?php

use Build\components\Layout;
use Darken\Attributes\ConstructorParam;
use Darken\Attributes\Slot;

$guide = new class {
    
    #[ConstructorParam]
    public string $title;

    #[Slot()]
    public string $content;
    
};

$layout = (new Layout($guide->title, [
    'About' => '/',
    'Motivation' => '/motivation',
    'Getting Started' => '/install',
    'Concepts' => null,
    'Build Compiler' => '/compile',
    'Extend Runtime' => '/runtime',
    'Config' => '/config',
    'Containers' => '/containers',
    'Application' => null,
    'Routing' => '/routing',
    'Middleware' => '/middleware',
    'Dependency Injection' => '/di',
    'APIs' => 'apis',
    'Events' => '/events',
    'Frontend' => null,
    'Components' => '/components',
    'Layouts' => '/layouts',
    'Tailwind' => '/tailwind',
    'Deplyoment' => null,
    'Vercel' => '/deploy',
    'FrankenPHP' => '/frankenphp',
    'Developers' => null,
    'Extensions' => '/extensions',
    'Testing' => '/testing',
  ]))->openContent(); ?>
  <?= $guide->content; ?>
<?= $layout->closeContent(); ?>