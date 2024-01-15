<?php

namespace App\Services\WorkWeixin\Message;

use App\Services\WorkWeixin\Base;

class Rob extends Base
{
    private string $base_uri = '/webhook';

    private string $key;

    public function setKey(string $rob_name)
    {
        $this->key = config('services.work_weixin.rob.' . $rob_name);

        return $this;
    }

    private function getKey()
    {
        return $this->key;
    }

    private function send($data)
    {
        $this->sendPost($this->base_uri . '/send?key=' . $this->getKey(), $data, false);
    }

    public function sendText(string $content, string|array|null $mentioned_list = null, string|array|null $mentioned_mobile_list = null)
    {
        $data = [
            'msgtype' => 'text',
            'text' => compact('content')
        ];

        if (!is_null($mentioned_list)) {
            $data['text']['mentioned_list'] = $mentioned_list;
        }

        if (!is_null($mentioned_mobile_list)) {
            $data['text']['mentioned_mobile_list'] = $mentioned_mobile_list;
        }

        $this->send($data);
    }

    public function sendMarkdown(string $content)
    {
        $this->send([
            'msgtype' => 'markdown',
            'markdown' => compact('content')
        ]);
    }

    public function sendImage(string $path)
    {
        $this->send([
            'msgtype' => 'image',
            'image' => [
                'base64' => base64_encode($path),
                'md5' => md5_file($path)
            ]
        ]);
    }

    public function sendNews(array $articles)
    {
        $this->send([
            'msgtype' => 'news',
            'news' => compact('articles')
        ]);
    }

    public function sendFile(string $media_id)
    {
        $this->send([
            'msgtype' => 'file',
            'file' => compact('media_id')
        ]);
    }

    public function sendVoice(string $media_id)
    {
        $this->send([
            'msgtype' => 'voice',
            'voice' => compact('media_id')
        ]);
    }

    public function sendTemplateCard()
    {

    }

    public function uploadMedia(string $type, $file)
    {
        $this->sendPost($this->base_uri . '/upload_media?key=' . $this->getKey() . '&type=' . $type, $file, false); // 使用multipart/form-data POST上传文件或语音， 文件标识名为"media"
    }
}
