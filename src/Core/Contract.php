<?php
/**
 * author: draguo
 */

namespace Draguo\Tsign\Core;


class Contract extends AbstractAPI
{

    /**
     * @param $filePath 服务器文件路径
     * @param $name 文件名/合同名称
     * @return string $docId
     */
    public function createByFile($filePath, $name)
    {
        $fileContent = file_get_contents($filePath);
        $contentMd5 = $this->getContentBase64Md5($filePath);
        $contentType = "application/pdf"; // 暂时支持格式

        $uploadInfo = $this->getUploadUrl($filePath, $name, $contentType, $contentMd5);
        $uploadUrl = $uploadInfo['uploadUrl'];
        $this->request('put', $uploadUrl, [
            'body' => $fileContent,
            'headers' => [
                'Content-MD5' => $contentMd5,
                'Content-Type' => $contentType,
            ],
        ]);
        $fileKey = $uploadInfo['fileKey'];
        return $this->createbyfilekey($fileKey)['docId'];
    }

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
        return $response;
    }

    /**
     * 通过 fileKey 创建合同
     * @param $fileKey
     * @return array
     */
    public function createbyfilekey($fileKey)
    {
        return $this->postJson('/opentreaty-service/doc/createbyfilekey', [
            'fileKey' => $fileKey,
        ]);
    }

    /**
     * 创建签署流程
     * @param        $docId
     * @param string $businessScene
     * @param array  $options
     * @return string $flowId
     */
    public function process($docId, $businessScene = '签署', $options = [])
    {
        $params = array_merge($options, compact('docId', 'businessScene'));
        $response = $this->postJSON('/opentreaty-service/sign/contract/addProcess', $params);

        return $response['flowId'];
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
     * @param string  $id flowId || docId
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

    /**
     * @param $filePath
     * @return string
     */
    protected function getContentBase64Md5($filePath)
    {
        //获取文件MD5的128位二进制数组
        $md5file = md5_file($filePath, true);
        //计算文件的Content-MD5
        $contentBase64Md5 = base64_encode($md5file);
        return $contentBase64Md5;
    }
}