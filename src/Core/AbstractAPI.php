<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;

use Draguo\Tsign\Exceptions\Exception;
use Draguo\Tsign\Supports\Config;
use Draguo\Tsign\Supports\HasHttpRequest;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

abstract class AbstractAPI
{
    use HasHttpRequest;

    protected $baseUri = 'https://o.tsign.cn';
    protected $config;

    public function __construct(Container $app)
    {
        $this->config = new Config($app->config);
        if ($this->config->get('sandbox')) {
            $this->baseUri = 'https://smlo.tsign.cn';
        }
    }

    protected function postJSON($api, $params, $options = [])
    {
        $options['json'] = $params;
        $this->pushMiddleware($this->headerMiddleware([
            'X-Tsign-Open-App-Id' => $this->config->get('app_id'),
            'X-Tsign-Open-App-Secret' => $this->config->get('secret'),
        ]));

        $response = $this->request('POST', $api, $options)->getBody();
        return $this->checkHasErrors(json_decode(strval($response), true));
    }

    private function checkHasErrors($result)
    {
        if ($result['errCode'] > 0) {
            throw new Exception($result['msg'], $result['errCode']);
        }
        return $result['data'];
    }

    protected function headerMiddleware($headers)
    {
        return function (callable $handler) use ($headers) {
            return function (RequestInterface $request, array $options) use ($handler, $headers) {
                foreach ($headers as $key => $value) {
                    $request = $request->withHeader($key, $value);
                }
                return $handler($request, $options);
            };
        };
    }
}