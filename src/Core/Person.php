<?php
/**
 * author: draguo
 * 个人账户操作相关
 */

namespace Draguo\Tsign\Core;


class Person extends Account
{
    /**
     * @param array $params 包含参数 必填 name,idNo, 非必填 thirdId,mobile,email
     * @param int $idType 默认为大陆身份证号
     * @return array
     */
    public function create(array $params, $idType = 19)
    {
        $params = array_merge($params, compact('idType'));
        return $this->postJson('/opentreaty-service/account/create/person', $params);
    }

}