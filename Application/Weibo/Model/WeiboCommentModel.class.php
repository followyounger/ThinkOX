<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM9:01
 */

namespace Weibo\Model;
use Think\Model;

class WeiboCommentModel extends Model {
    protected $_validate = array(
        array('content','1,99999','内容不能为空',self::EXISTS_VALIDATE,'length'),
        array('content','0,500','内容太长',self::EXISTS_VALIDATE,'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addComment($uid, $weibo_id, $content) {
        //将评论内容写入数据库
        $data = array('uid'=>$uid, 'weibo_id'=>$weibo_id, 'content'=>$content);
        $data = $this->create($data);
        if(!$data) return false;
        $result = $this->add($data);

        //增加微博的评论数量
        D('Weibo')->where(array('id'=>$weibo_id))->setInc('comment_count');

        //返回评论编号
        return $result;
    }
}