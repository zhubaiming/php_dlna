<?php

namespace App\Services\WorkWeixin\Message;

use App\Services\WorkWeixin\Base;

class Appliance extends Base
{
    private string $base_uri = '/message';

    private string $to_user = '@all';
    private $to_party = null;
    private $to_tag = null;

    private int $safe = 0; // 表示是否是保密消息，0表示可对外分享，1表示不能分享且内容显示水印

    private int $enable_duplicate_check = 0; // 表示是否开启重复消息检查，0表示否，1表示是

    private int $duplicate_check_interval = 14400; // 表示是否重复消息检查的时间间隔，默认1800s，最大不超过4小时

    private function send($data)
    {
        $to = [];

        if (!is_null($this->getToParty())) $to['toparty'] = $this->getToParty();

        if (!is_null($this->getToTag())) $to['totag'] = $this->getToTag();

        if (empty($to)) $to['touser'] = $this->getToUser();

        $this->sendPost($this->base_uri . '/send', array_merge($data, $to, [
            'agentid' => $this->getAgentId(),
            'safe' => $this->getSafe(),
            'enable_duplicate_check' => $this->getEnableDuplicateCheck(),
            'duplicate_check_interval' => $this->getDuplicateCheckInterval()
        ]));
    }

    public function setToUser(array $user_list)
    {
        if (!empty($user_list)) {
            $this->to_user = implode('|', $user_list);
        }

        return $this;
    }

    private function getToUser()
    {
        return $this->to_user;
    }

    public function setToParty(array $party_list)
    {
        if (!empty($party_list)) {
            $this->to_party = implode('|', $party_list);
        }

        return $this;
    }

    private function getToParty()
    {
        return $this->to_party;
    }

    public function setToTag(array $tag_list)
    {
        if (!empty($party_list)) {
            $this->to_tag = implode('|', $tag_list);
        }

        return $this;
    }

    private function getToTag()
    {
        return $this->to_tag;
    }

    public function setSafe(int $safe)
    {
        $this->safe = $safe;

        return $this;
    }

    private function getSafe()
    {
        return $this->safe;
    }

    public function setEnableDuplicateCheck(int $enable_duplicate_check)
    {
        $this->enable_duplicate_check = $enable_duplicate_check;

        return $this;
    }

    private function getEnableDuplicateCheck()
    {
        return $this->enable_duplicate_check;
    }

    public function setDuplicateCheckInterval(int $duplicate_check_interval)
    {
        $this->duplicate_check_interval = $duplicate_check_interval;

        return $this;
    }

    private function getDuplicateCheckInterval()
    {
        return $this->duplicate_check_interval;
    }

    public function sendText(string $content)
    {
        $this->send([
            'msgtype' => 'text',
            'text' => compact('content')
        ]);
    }

    public function sendMarkdown(string $content)
    {
        $this->send([
            'msgtype' => 'markdown',
            'markdown' => compact('content')
        ]);
    }

    public function sendNews(array $articles)
    {
        $this->send([
            'msgtype' => 'news',
            'news' => compact('articles')
        ]);
    }
}
