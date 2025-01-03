<?php

use App\Config;
use Build\components\Api;
use Build\components\ApiTab;
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
    <h3 class="mt-8 mb-4 text-xl text-white">Properties</h2>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-grey rounded-lg">
                <tbody>
                    <?php foreach ($api->data->properties as $property) : ?>
                        <tr class="bg-darkgrey border-b border-b-darken">
                            <th scope="row" class="font-medium text-left py-2 pl-2">
                                <a href="#<?= $property->name; ?>">$<?= $property->name; ?></a>
                            </th>
                            <td class="text-left">
                                <?= $property->type; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (count($api->data->methods) > 0) : ?>
        <h3 class="mt-8 mb-4  text-xl text-white">Methods</h2>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-grey rounded-lg">
                    <tbody>
                        <?php foreach ($api->data->methods as $method) : ?>
                            <tr class="bg-darkgrey border-b border-b-darken">
                                <th scope="row" class="font-medium text-left py-2 pl-2">
                                    <a href="#<?= $method->name; ?>"><?= $method->name; ?>(<?= $method->parametersString; ?>)</a>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (count($api->data->properties) > 0) : ?>
            <h2 class="mt-8 mb-4  text-xl text-white">Property Details</h2>
            <div>
                <?php foreach ($api->data->properties as $property) : ?>
                    <?php $tab = (new ApiTab('$' . $property->name, $property->name, $property->visibility))->openSlot(); ?>
                    <?php if (!empty($property->description)) : ?>
                        <div class="md">
                            <?= $property->description; ?>
                        </div>
                    <?php endif; ?>
                    <div class="text-lg">
                        <?= $property->visibility; ?>
                        <?= $property->static ? '<p>Static</p>' : ''; ?>
                        <?= $property->type; ?>
                        $<?= $property->name; ?> = <?= $property->default; ?>;
                    </div>
                    <?= $tab->closeSlot(); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($api->data->methods) > 0) : ?>
            <h2 class="mt-8 mb-4 text-xl text-white">Method Detais</h2>
            <div>
                <?php foreach ($api->data->methods as $method) : ?>
                    <?php $tab = (new ApiTab($method->name . '()', $method->name, $method->visibility))->openSlot(); ?>
                    <?php if (!empty($method->description)) : ?>
                        <div class="md">
                            <?= $method->description; ?>
                        </div>
                    <?php endif; ?>
                    <div class="lg">
                        <?= $method->visibility; ?>
                        <?= $method->static ? '<p>Static</p>' : ''; ?>
                        <?= $method->name; ?>(<?= $method->parametersString; ?>)
                    </div>
                    <div class="mt-3">
                        <table class="w-full text-sm text-grey rounded-lg">
                            <tbody>
                                <?php foreach ($method->parameters as $parameter) : ?>
                                    <tr>
                                        <td class="py-2"><?= $parameter->name; ?></td>
                                        <td class="text-white font-bold"><?= $parameter->type; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="py-2" style="width:140px">Return</td>
                                    <td class="text-white font-bold"><?= $method->returnType; ?></td>
                                </tr>
                                <tr>
                                    <td class="py-2">Throws</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?= $tab->closeSlot(); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?= $layout->closeContent(); ?>