<?php

use App\Config;
use Build\components\Api;
use Darken\Attributes\Inject;
use Darken\Attributes\RouteParam;

$api = new class {

    #[RouteParam]
    public string $slug;

    public stdClass $data;

    #[Inject()]
    public Config $config;

    public function __construct()
    {
        $apis = json_decode(file_get_contents($this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api.json'), true);

        $data = $apis[$this->slug] ?? false;

        if (!$data) {
            throw new \Exception('API not found');
        }

        $json = file_get_contents($this->config->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $data['json']);

        if (!$json) {
            throw new \Exception('API JSON not found');
        }

        $this->data = json_decode($json, false);
    }
};

?>
<?php $layout = (new Api($api->data->className))->openContent(); ?>
<div class="md">
    <h1><?= $api->data->className; ?></h1>
    <p><?= $api->data->classPhpDocDescription; ?></p>
    <p><a target="_blank" href="<?= $api->data->github; ?>">View Source</a></p>
</div>

<?php if (count($api->data->properties) > 0) : ?>
<h2 class="mt-7 text-3xl text-white mb-2">Properties</h2>
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-grey rounded-lg">
        <thead class="text-xsuppercase bg-darken">
            <tr>
                <th scope="col" class="text-left py-2 pl-2">
                    Property
                </th>
                <th class="text-left">
                    Type
                </th>
                <th class="text-left">
                    Description
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($api->data->properties as $property) : ?>
            <tr class="bg-darkgrey border-b border-b-darken">
                <th scope="row" class="font-medium text-left py-2 pl-2">
                    <a href="#<?= $property->name; ?>">$<?= $property->name; ?></a>
                </th>
                <td class="text-left">
                    <?= $property->type; ?>
                </td>
                <td class="text-left">
                    <?= $property->description; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (count($api->data->methods) > 0) : ?>
<h2 class="mt-7 text-3xl text-white mb-2">Methods</h2>
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-grey rounded-lg">
        <thead class="text-xs uppercase bg-darken">
            <tr>
                <th scope="col" class="text-left py-2 pl-2">
                    Method
                </th>
                <th class="text-left">
                    Description
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($api->data->methods as $method) : ?>
            <tr class="bg-darkgrey border-b border-b-darken">
                <th scope="row" class="font-medium text-left py-2 pl-2">
                    <a href="#<?= $method->name; ?>"><?= $method->name; ?>(<?= $method->parametersString; ?>)</a>
                </th>
                <td class="text-left">
                    <?= $method->description; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (count($api->data->properties) > 0) : ?>
<h2 class="mt-7 text-3xl text-white mb-2">Property Details</h2>
<div>
    <?php foreach ($api->data->properties as $property) : ?>
        <div id="<?= $property->name; ?>" class="bg-darken text-white p-4 rounded-lg mb-4">
            <div>
                <span class="text-xl font-bold">$<?= $property->name; ?></span>
                <span><?= $property->visibility; ?></span>
                <span>property</span>
            </div>
            <?php if (!empty($property->description)) : ?>
            <div class="md">
                <?= $property->description; ?>
            </div>
            <?php endif; ?>
            <div>
                <?= $property->visibility; ?>
                <p><?= $property->static ? '<p>Static</p>' : ''; ?></p>
                <?= $property->type; ?>
                $<?= $property->name; ?> = <?= $property->default; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (count($api->data->methods) > 0) : ?>
<h2 class="mt-7 text-3xl text-white mb-2">Method Detais</h2>
<div>
    <?php foreach ($api->data->methods as $method) : ?>
        <div id="<?= $method->name; ?>" class="bg-darken text-white p-4 rounded-lg mb-4">
            <div>
                <span class="text-xl font-bold"><?= $method->name; ?>()</span>
                <span><?= $method->visibility; ?></span>
                <span>method</span>
            </div>
            <?php if (!empty($method->description)) : ?>
            <div class="md">
                <?= $method->description; ?>
            </div>
            <?php endif; ?>
            <div>
                <?= $method->visibility; ?>
                <?= $method->static ? '<p>Static</p>' : ''; ?>
                <?= $method->name; ?>(<?= $method->parametersString; ?>)
            </div>
            <div>
                <table class="w-full text-sm text-grey rounded-lg">
                    <tbody>
                        <?php foreach ($method->parameters as $parameter) : ?>
                            <tr>
                                <td><?= $parameter->name; ?></td>
                                <td><?= $parameter->type; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Return</td>
                            <td><?= $method->returnType; ?></td>
                        </tr>
                        <tr>
                            <td>Throws</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?= $layout->closeContent(); ?>