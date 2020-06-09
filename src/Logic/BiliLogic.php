<?php
declare (strict_types=1);

namespace Smalls\VideoTools\Logic;

use Smalls\VideoTools\Enumerates\BiliQualityType;
use Smalls\VideoTools\Enumerates\UserGentType;
use Smalls\VideoTools\Exception\ErrorVideoException;
use Smalls\VideoTools\Traits\HttpRequest;
use Smalls\VideoTools\Utils\CommonUtil;

/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/6/9 - 12:50
 **/
class BiliLogic
{

    use HttpRequest;

    private $cookie = '';
    private $quality = BiliQualityType::LEVEL_5;
    private $url;
    private $aid;
    private $cid;
    private $contents;

    /**
     * BiliLogic constructor.
     * @param string $cookie
     * @param int $quality
     * @param $url
     */
    public function __construct($url, string $cookie, int $quality)
    {
        $this->cookie = $cookie;
        $this->quality = $quality;
        $this->url = $url;
    }

    public function checkUrlHasTrue()
    {
        if (empty($this->url)) {
            throw new ErrorVideoException("url cannot be empty");
        }
        if (strpos($this->url, "b23.tv") == false && strpos($this->url, "www.bilibili.com") == false) {
            throw new ErrorVideoException("there was a problem with url verification");
        }
    }

    public function setAidAndCid()
    {
        $contents = $this->get($this->url, [], [
            'User-Agent' => UserGentType::WIN_USER_AGENT
        ]);
        preg_match('/"aid":([0-9]+),/i', $contents, $aid);
        preg_match('/"cid":([0-9]+),/i', $contents, $cid);
        if (CommonUtil::checkEmptyMatch($aid) || CommonUtil::checkEmptyMatch($cid)) {
            throw new ErrorVideoException("url parsing failed");
        }
        $this->aid = $aid[1];
        $this->cid = $cid[1];
    }

    public function setContents()
    {
        $apiUrl = 'https://api.bilibili.com/x/player/playurl';
        $contents = $this->get($apiUrl, [
            'avid' => $this->aid,
            'cid' => $this->cid,
            'qn' => $this->quality,
            'otype' => 'json',
        ], [
            'Cookie' => $this->cookie,
            'Referer' => $apiUrl,
            'User-Agent' => UserGentType::WIN_USER_AGENT
        ]);
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function getCookie(): string
    {
        return $this->cookie;
    }

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @return mixed
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    public function getVideoUrl()
    {
        return CommonUtil::getData($this->contents['data']['durl'][0]['url']);
    }

    public function getVideoImage()
    {
        return '';
    }

    public function getVideoDesc()
    {
        return '';
    }

    public function getUsername()
    {
        return '';
    }

    public function getUserPic()
    {
        return '';
    }


}