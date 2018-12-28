<?php

namespace Draguo\Tsign;

use Draguo\Tsign\Core\ServiceContainer;
use Draguo\Tsign\Core\ServiceProvider;

/**
 * Class Tsign.
 *
 * @author: draguo
 * @property \Draguo\Tsign\Core\ServiceProvider $contract
 * @property \Draguo\Tsign\Core\ServiceProvider $person
 * @property \Draguo\Tsign\Core\ServiceProvider $platform
 * @property \Draguo\Tsign\Core\ServiceProvider $organize
 */
class Tsign extends ServiceContainer
{
    protected $providers = [
        ServiceProvider::class,
    ];
}