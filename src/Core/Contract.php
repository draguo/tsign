<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


use Draguo\Tsign\Exceptions\Exception;

class Contract extends AbstractAPI
{
    /**
     * 获取文件上传地址
     * @param $filePath
     * @param $fileName
     * @param $contentType
     * @param $contentMd5
     * @return array
     */
    protected function getUploadUrl($filePath, $fileName, $contentType, $contentMd5)
    {
        $fileSize = filesize($filePath);
        $response = $this->postJson('/opentreaty-service/file/uploadurl', compact('fileName', 'fileSize', 'contentType', 'contentMd5'));
        return $response['data'];
    }

    /**
     * 上传合同文件
     * @param string $filePath 文件路径
     * @param String $name 自定义文件名
     * @return mixed
     * @throws Exception
     */
    public function upload(string $filePath, String $name)
    {
        $fileContent = file_get_contents($filePath);
        $contentMd5 = $this->getContentBase64Md5($filePath);
        $contentType = "application/pdf";
        $uploadInfo = $this->getUploadUrl($filePath, $name, $contentType, $contentMd5);
        $uploadUrl = $uploadInfo['uploadUrl'];
        try {
            $this->request('put', $uploadUrl, [
                'body' => $fileContent,
                'headers' => [
                    'Content-MD5' => $contentMd5,
                    'Content-Type' => $contentType,
                ],
            ]);
            return $uploadInfo['fileKey'];
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * 通过 fileKey 创建合同
     * @param $fileKey
     * @return array
     */
    public function create($fileKey)
    {
        return $this->postJson('/opentreaty-service/doc/createbyfilekey', [
            'fileKey' => $fileKey,
        ]);
    }

    /**
     * 创建签署流程
     * @param string $docId
     * @param string $businessScene 场景
     * $param array $options 附加参数
     * @return string $flowId
     */
    public function createProcess($docId, $businessScene = '签署', $options = [])
    {
        $params = array_merge($options, compact('docId', 'businessScene'));
        $response = $this->postJSON('/opentreaty-service/sign/contract/addProcess', $params);
        return $response['flowId'];
    }

    /**
     * 平台自动盖章
     * @param string $flowId
     * @param array $posList 盖章位置
     * @return array
     */
    public function platformSign($flowId, $posList = [])
    {
        return $this->postJson('/opentreaty-service/sign/contract/platformSignTask', [
            'flowId' => $flowId,
            'posList' => [$posList],
        ]);
    }

    /**
     * 合同详情
     * @param $flowId
     * @return array
     */
    public function detail($flowId)
    {
        return $this->postJson('opentreaty-service/sign/contracts/detail', [
            'flowId' => $flowId
        ]);
    }

    /**
     * 合同下载地址
     * @param string $id flowId || docId
     * @param boolean $sign 是否为签署过的
     * @return array
     */
    public function download($id, $sign = false)
    {
        if ($sign) {
            return $this->postJson('opentreaty-service/sign/download', ['flowId' => $id]);
        }
        return $this->postJSON('opentreaty-service/doc/downloadurl', ['docId' => $id]);
    }

    /**
     * 归档合同
     * @param $flowId
     * @return array
     */
    public function close($flowId)
    {
        return $this->postJson('opentreaty-service/sign/contract/archiveProcess', ['flowId' => $flowId]);
    }
}