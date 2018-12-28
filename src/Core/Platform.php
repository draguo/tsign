<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


class Platform extends AbstractAPI
{
    /**
     * 平台自动盖章
     * @param string $flowId
     * @param array  $posList 盖章位置
     * @return array
     */
    public function signTask($flowId, $posList = [])
    {
        return $this->postJson('/opentreaty-service/sign/contract/platformSignTask', [
            'flowId' => $flowId,
            'posList' => [$posList],
        ]);
    }
}