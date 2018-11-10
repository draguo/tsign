<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


class Organize extends Account
{
    /**
     * @param $creatorId
     * @param $params 必填：name, organCode, 非必填：thirdId ,legalName,legalIdNo, legalIdType,email
     * @param int $organType
     * @return array
     */
    public function create($creatorId, $params, $organType = 11)
    {
        $params = array_merge($params, compact('creatorId', 'organType'));
        return $this->postJson('/opentreaty-service/account/create/organize/common', $params);
    }

    // 发起签署
    public function signContractHandOrgSignTask($flowId, $accountId, $authorizationOrgId)
    {
        return $this->postJson('/opentreaty-service/sign/contract/handOrgSignTask', [
            'flowId' => $flowId,
            'accountId' => $accountId,
            'authorizationOrgId' => $authorizationOrgId
        ]);
    }
}