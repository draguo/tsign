<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


abstract class Account extends AbstractAPI
{
    /**
     * @param $accountId
     * @return array
     */
    public function delete($accountId)
    {
        return $this->postJson('/opentreaty-service/account/delete', [
            'accountId' => $accountId,
        ]);
    }
}