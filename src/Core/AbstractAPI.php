<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;

use Draguo\Tsign\Exceptions\Exception;
use Draguo\Tsign\Supports\Config;
use Draguo\Tsign\Supports\HasHttpRequest;
use Psr\Http\Message\RequestInterface;

abstract class AbstractAPI
{
    use HasHttpRequest;

    protected $config;
    protected $baseUri = 'https://o.tsign.cn';

    public function __construct(array $config)
    {
        $this->config = new Config($config);
        if ($this->config->get('sandbox')) {
            $this->baseUri = 'https://toapi.tsign.cn';
        }
    }

    protected function postJSON($api, $params, $options = [])
    {
        $api = $this->config->get('host') . $api;
        $this->pushMiddleware($this->addAuthorization($this->config->get('app_id'), $this->config->get('secret')));
        $options['json'] = $params;
        $response = $this->request('POST', $api, $options)->getBody();
        return $this->checkHasErrors(json_decode(strval($response), true));
    }

    private function checkHasErrors($result): array
    {
        if (isset($result['errors'])) {
            throw new Exception(json_encode($result['errors']), 500);
        }
        return $result;
    }

    protected function addAuthorization($id, $secret)
    {
        return function (callable $handler) use ($id, $secret) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $id, $secret) {
                // 验证规则
                $request->withHeader('X-Tsign-Open-App-Id', $id);
                $request->withHeader('X-Tsign-Open-App-Secret', $secret);
                return $handler($request, $options);
            };
        };
    }
}