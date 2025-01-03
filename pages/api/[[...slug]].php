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
<?php $layout = (new Api($api->data->name))->openContent(); ?>

    <div class="md">
    <h1><?= $api->data->name; ?></h1>
    </div>
    <div>
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="inline-block bg-primary text-white text-xs font-semibold mr-2 px-2.5 py-0.5 rounded"><?= $api->data->type; ?></p>
            </div>
            <div>
                <a target="_blank" href="<?= $api->data->github; ?>" class="text-white hover:underline">
                    <svg class="inline-block w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.263.82-.583 0-.287-.01-1.046-.015-2.053-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.757-1.333-1.757-1.09-.745.083-.73.083-.73 1.205.085 1.84 1.237 1.84 1.237 1.07 1.835 2.807 1.305 3.492.997.108-.775.42-1.305.763-1.605-2.665-.303-5.467-1.332-5.467-5.93 0-1.31.467-2.382 1.235-3.22-.123-.303-.535-1.523.117-3.176 0 0 1.008-.322 3.3 1.23.957-.266 1.983-.398 3.003-.403 1.02.005 2.047.137 3.006.403 2.29-1.552 3.297-1.23 3.297-1.23.653 1.653.24 2.873.118 3.176.77.838 1.233 1.91 1.233 3.22 0 4.61-2.807 5.625-5.48 5.92.43.37.823 1.102.823 2.222 0 1.606-.015 2.898-.015 3.293 0 .322.218.698.825.58C20.565 21.797 24 17.297 24 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="md"><?= $api->data->description; ?></div>

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