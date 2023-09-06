<?php

declare(strict_types=1);

namespace MiBo\Prices\Managers;

use MiBo\Prices\Data\Factories\PriceFactory;
use Psr\Container\ContainerInterface;

/**
 * Class MoneyManager
 *
 * @package MiBo\Prices\Managers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class MoneyManager
{
    private PriceFactory $factory;

    private array $config;

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->factory   = $container->get(PriceFactory::class);
        $this->config    = $container["config"]["mibo::prices"];

        $this->resolveDefaultQuantities();
    }

    final public function factory(): PriceFactory
    {
        return $this->factory;
    }

    final protected function getConfig(): array
    {
        return $this->config;
    }
}
