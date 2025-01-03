<?php

namespace App;

use Darken\Events\EventDispatchInterface;
use Darken\Events\EventInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use Yiisoft\Files\FileHelper;

class PhpDocsGenerator implements EventInterface
{
    public function __construct(private Config $config)
    {
        
    }
    public function __invoke(EventDispatchInterface $event): void
    {
        $files = FileHelper::findFiles($this->config->getRootDirectoryPath() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'darkenphp' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'src', ['only' => ['*.php']]);
        $docsOutput = $this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api';
        FileHelper::ensureDirectory($docsOutput);
        $docs = [];
        foreach ($files as $file) {
            $collector = $this->fileToArray($file);
            foreach ($collector->entities as $entity) {
                $slug = strtolower(str_replace('\\', '-', $entity['name']));
                $jsonFile = $docsOutput . DIRECTORY_SEPARATOR . $slug . '.json';
                $entity['github'] = $collector->github;
                file_put_contents($jsonFile, json_encode($entity));
                
                $docs[$slug] = [
                    'class' => $entity['name'],
                    'title' => str_replace('Darken\\', '', $entity['name']),
                    'file' => $file,
                    'json' =>  $slug . '.json',
                    'slugify' => $slug,
                ];
            }
            
        }

        // sort by key
        ksort($docs);

        file_put_contents($this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api.json', json_encode($docs));
    }
    
    private function fileToArray(string $filepath) : object
{
    $content = file_get_contents($filepath);

    $parser = (new ParserFactory())->createForNewestSupportedVersion();

    // Parse the file into an AST
    try {
        $ast = $parser->parse($content);
    } catch (\Throwable $e) {
        throw new \RuntimeException('Parse error: ' . $e->getMessage());
    }

    // Create an anonymous collector object
    $collector = new class {
        public $github;
        public $entities = []; // To store classes, interfaces, and traits

        public function addEntity($type, $name, $description)
        {
            $this->entities[] = [
                'type' => $type, // 'class', 'interface', 'trait'
                'name' => $name,
                'description' => $description,
                'methods' => [],
                'properties' => [],
            ];
        }

        public function addMethod($entityIndex, $name, $visibility, $static, $returnType, $parameters, $description)
        {
            $this->entities[$entityIndex]['methods'][] = [
                'name' => $name,
                'visibility' => $visibility,
                'static' => $static,
                'returnType' => $returnType,
                'parameters' => $parameters,
                'description' => $description,
                'parametersString' => implode(', ', array_map(function ($param) {
                    return $param['type'] . ' ' . $param['name'];
                }, $parameters)),
            ];
        }

        public function addProperty($entityIndex, $name, $visibility, $static, $type, $description, $default)
        {
            $this->entities[$entityIndex]['properties'][] = [
                'name' => $name,
                'visibility' => $visibility,
                'static' => $static,
                'type' => $type,
                'description' => $description,
                'default' => $default,
            ];
        }
    };

    // the path contains "xyz/framework/src", remove everything with and before framework
    $srfFile = explode('framework', $filepath);
    $srcFile = end($srfFile);

    $collector->github = 'https://github.com/darkenphp/framework/tree/main' . $srcFile;

    $traverser = new NodeTraverser();
    $traverser->addVisitor(new NameResolver()); // Add NameResolver first

    $traverser->addVisitor(new class ($collector) extends NodeVisitorAbstract {
        private object $collector;
        private ?int $currentEntityIndex = null;

        public function __construct(object $collector)
        {
            $this->collector = $collector;
        }

        public function enterNode(Node $node)
        {
            if ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
                // Determine the type
                $type = 'class';
                if ($node instanceof Node\Stmt\Interface_) {
                    $type = 'interface';
                } elseif ($node instanceof Node\Stmt\Trait_) {
                    $type = 'trait';
                }

                // Get the full name
                if (isset($node->namespacedName)) {
                    $fullName = $node->namespacedName->toString();
                } else {
                    $fullName = $node->name ? $node->name->toString() : null;
                }

                // Get doc comment
                $description = $this->getDocCommentText($node->getDocComment());

                // Add entity to collector
                $this->collector->addEntity($type, $fullName, $description);
                $this->currentEntityIndex = count($this->collector->entities) - 1;
            }

            // If this is a class method or interface method, capture detailed method info
            if (($node instanceof Node\Stmt\ClassMethod) && $this->currentEntityIndex !== null) {
                $name = $node->name->toString();
                $visibility = $this->getVisibility($node);
                $isStatic = $node->isStatic();
                $returnType = $this->getType($node->getReturnType());

                $parameters = [];
                foreach ($node->getParams() as $param) {
                    $parameters[] = [
                        'name' => '$' . $param->var->name,
                        'type' => $this->getType($param->type),
                        'default' => $param->default ? $this->getDefaultValue($param->default) : null,
                        'byReference' => $param->byRef,
                        'variadic' => $param->variadic,
                    ];
                }

                $description = $this->getDocCommentText($node->getDocComment());

                $this->collector->addMethod($this->currentEntityIndex, $name, $visibility, $isStatic, $returnType, $parameters, $description);
            }

            // If this is a property, capture detailed property info
            if (($node instanceof Node\Stmt\Property) && $this->currentEntityIndex !== null) {
                $visibility = $this->getVisibility($node);
                $isStatic = $node->isStatic();
                $type = $this->getType($node->type);
                $description = $this->getDocCommentText($node->getDocComment());
                $default = $node->props[0]->default ? $this->getDefaultValue($node->props[0]->default) : null;

                foreach ($node->props as $prop) {
                    $name = $prop->name->toString();
                    $this->collector->addProperty($this->currentEntityIndex, $name, $visibility, $isStatic, $type, $description, $default);
                }
            }
        }

        public function leaveNode(Node $node)
        {
            if ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
                // Reset current entity index when leaving the node
                $this->currentEntityIndex = null;
            }
        }

        /**
         * Helper method to get visibility as a string.
         */
        private function getVisibility($node): string
        {
            if ($node->isPublic()) {
                return 'public';
            }
            if ($node->isProtected()) {
                return 'protected';
            }
            if ($node->isPrivate()) {
                return 'private';
            }
            return 'public'; // Default visibility
        }

        /**
         * Helper method to get type as a string.
         */
        private function getType($type): ?string
        {
            if ($type instanceof Node\NullableType) {
                return '?' . $this->getType($type->type);
            } elseif ($type instanceof Node\UnionType) {
                $types = [];
                foreach ($type->types as $t) {
                    $types[] = $this->getType($t);
                }
                return implode('|', $types);
            } elseif ($type instanceof Node\Identifier || $type instanceof Node\Name) {
                return $type->toString();
            }
            return null;
        }

        /**
         * Helper method to get default value as a string.
         */
        private function getDefaultValue($default): string
        {
            if ($default instanceof Node\Scalar) {
                return $default->value;
            } elseif ($default instanceof Node\Expr\ConstFetch) {
                return $default->name->toString();
            } elseif ($default instanceof Node\Expr\Array_) {
                return 'array';
            } else {
                return 'expression';
            }
        }

        /**
         * Helper method to extract doc comment text.
         */
        private function getDocCommentText(Doc|null $docComment): string
        {
            if ($docComment) {
                // Create a DocBlockFactory instance
                $factory = DocBlockFactory::createInstance();
                    
                // Create a DocBlock from the doc comment text
                $docblock = $factory->create($docComment->getText());
                
                // Get the summary and description
                $summary = $docblock->getSummary();
                $description = $docblock->getDescription()->render();
                
                // Combine summary and description
                $fullDescription = trim($summary . "\n" . $description);
                
                $markdown = new Markdown();

                return $markdown->getConverter()->convert($fullDescription)->getContent();
            }

            return '';
        }
    });

    // Traverse the AST with our visitor
    $traverser->traverse($ast);

    // Return the filled collector object
    return $collector;
}

}