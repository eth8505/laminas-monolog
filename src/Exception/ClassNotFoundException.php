<?php declare(strict_types=1);

namespace LaminasMonolog\Exception;

use Throwable;

/**
 * Exception to throw if a class is not found
 */
class ClassNotFoundException extends RuntimeException
{

    /**
     * Constructor
     *
     * @param string $class
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $class, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Class "%s" not found', $class), $code, $previous);
    }

}
