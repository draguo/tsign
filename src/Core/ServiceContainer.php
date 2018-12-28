<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


use Pimple\Container;

class ServiceContainer extends Container
{
    protected $providers = [];
    public $config;

    /**
     * ServiceContainer constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct();
        $this->setConfig($config);
        $this->registerProviders();
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig($key = null)
    {
        return $key ? $this->config[$key] : $this->config;
    }
}