<?php

declare(strict_types=1);

namespace MiBo\Properties\Managers;

use MiBo\Properties\Data\Factories\DiscountFactory;
use MiBo\Properties\Data\Factories\PriceFactory;
use Psr\Container\ContainerInterface;

/**
 * Class MoneyManager
 *
 * @package MiBo\Properties\Managers
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class MoneyManager
{
    private PriceFactory $priceFactory;

    private DiscountFactory $discountFactory;

    // @phpstan-ignore-next-line The parameter will be used later.
    public function __construct(ContainerInterface $container)
    {
        $this->priceFactory    = PriceFactory::get();
        $this->discountFactory = DiscountFactory::get();
    }

    final public function priceFactory(): PriceFactory
    {
        return $this->priceFactory::get();
    }

    final public function discountFactory(): DiscountFactory
    {
        return $this->discountFactory::get();
    }
}
