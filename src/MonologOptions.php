<?php declare(strict_types=1);

namespace LaminasMonolog;

use Laminas\Stdlib\AbstractOptions;

class MonologOptions extends AbstractOptions
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var array
     */
    protected $processors = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param array $handlers
     */
    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
    }

    /**
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * @param array $processors
     */
    public function setProcessors(array $processors = []): void
    {
        $this->processors = $processors;
    }
}
