<?php

namespace App\Services\WorkWeixin\Directory;

use App\Services\WorkWeixin\Base;

class User extends Base
{
    private string $base_uri = '/user';

    public function create(array $credentials = [])
    {

    }

    public function get($user_id)
    {

    }

    public function update(array $credentials = [])
    {

    }

    public function delete($user_id)
    {

    }

    public function batchDelete(array $user_id_list = [])
    {

    }

    public function getDepartmentUserList($department_id)
    {
        $this->sendGet($this->base_uri . '/simplelist', compact('department_id'));

        /**
         * {
         * "errcode": 0,
         * "errmsg": "ok",
         * "userlist": [
         * {
         * "userid": "zhangsan",
         * "name": "张三",
         * "department": [1, 2],
         * "open_userid": "xxxxxx"
         * }
         * ]
         * }
         */
    }

    public function getDepartmentUserInfo($department_id)
    {
        $this->sendGet($this->base_uri . '/list', compact('department_id'));

        /**
         * {
         * "errcode": 0,
         * "errmsg": "ok",
         * "userlist": [{
         * "userid": "zhangsan",
         * "name": "李四",
         * "department": [1, 2],
         * "order": [1, 2],
         * "position": "后台工程师",
         * "mobile": "13800000000",
         * "gender": "1",
         * "email": "zhangsan@gzdev.com",
         * "biz_mail":"zhangsan@qyycs2.wecom.work",
         * "is_leader_in_dept": [1, 0],
         * "direct_leader":["lisi"],
         * "avatar": "http://wx.qlogo.cn/mmopen/ajNVdqHZLLA3WJ6DSZUfiakYe37PKnQhBIeOQBO4czqrnZDS79FH5Wm5m4X69TBicnHFlhiafvDwklOpZeXYQQ2icg/0",
         * "thumb_avatar": "http://wx.qlogo.cn/mmopen/ajNVdqHZLLA3WJ6DSZUfiakYe37PKnQhBIeOQBO4czqrnZDS79FH5Wm5m4X69TBicnHFlhiafvDwklOpZeXYQQ2icg/100",
         * "telephone": "020-123456",
         * "alias": "jackzhang",
         * "status": 1,
         * "address": "广州市海珠区新港中路",
         * "english_name" : "jacky",
         * "open_userid": "xxxxxx",
         * "main_department": 1,
         * "extattr": {
         * "attrs": [
         * {
         * "type": 0,
         * "name": "文本名称",
         * "text": {
         * "value": "文本"
         * }
         * },
         * {
         * "type": 1,
         * "name": "网页名称",
         * "web": {
         * "url": "http://www.test.com",
         * "title": "标题"
         * }
         * }
         * ]
         * },
         * "qr_code": "https://open.work.weixin.qq.com/wwopen/userQRCode?vcode=xxx",
         * "external_position": "产品经理",
         * "external_profile": {
         * "external_corp_name": "企业简称",
         * "wechat_channels": {
         * "nickname": "视频号名称",
         * "status": 1
         * },
         * "external_attr": [{
         * "type": 0,
         * "name": "文本名称",
         * "text": {
         * "value": "文本"
         * }
         * },
         * {
         * "type": 1,
         * "name": "网页名称",
         * "web": {
         * "url": "http://www.test.com",
         * "title": "标题"
         * }
         * },
         * {
         * "type": 2,
         * "name": "测试app",
         * "miniprogram": {
         * "appid": "wx8bd80126147dFAKE",
         * "pagepath": "/index",
         * "title": "miniprogram"
         * }
         * }
         * ]
         * }
         * }]
         * }
         */
    }

    public function getUserIdByMobile($mobile)
    {
        return $this->sendPost($this->base_uri . '/getuserid', compact('mobile'))['userid'];

        /**
         * {
         * "errcode": 0,
         * "errmsg": "ok",
         * "userid": "zhangsan"
         * }
         */
    }

    public function getUserIdByEmail(string $email, int $email_type = 1)
    {
        $this->sendPost($this->base_uri . '/get_userid_by_email', compact('email', 'email_type'));

        /**
         * {
         * "errcode": 0,
         * "errmsg": "ok",
         * "userid": "zhangsan"
         * }
         */
    }

    public function getUserList(string $cursor = null, int $limit = 10000)
    {
        $this->sendPost($this->base_uri . '/list_id', compact('cursor', 'limit'));
    }
}
