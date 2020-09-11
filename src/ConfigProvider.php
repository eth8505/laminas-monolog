<?php declare(strict_types=1);

namespace LaminasMonolog;

class ConfigProvider
{

    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'monolog' => $this->getMonologConfig(),
        ];
    }

    public function getDependencyConfig(): array
    {
        return $this->loadConfigFile()['service_manager'];
    }

    private function loadConfigFile(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }

    private function getMonologConfig(): array
    {
        return $this->loadConfigFile()['monolog'];
    }

}
