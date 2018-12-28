<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['contract'] = function ($pimple) {
            return new Contract($pimple);
        };

        $pimple['person'] = function ($pimple) {
            return new Person($pimple);
        };

        $pimple['platform'] = function ($pimple) {
            return new Platform($pimple);
        };
    }
}