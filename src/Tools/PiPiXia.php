<?php
declare (strict_types=1);

namespace Smalls\VideoTools\Tools;
/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/4/26 - 21:57
 **/

use Smalls\VideoTools\Interfaces\IVideo;
use Smalls\VideoTools\Logic\PiPiXiaLogic;

class PiPiXia extends Base implements IVideo
{

    /**
     * 更新时间：2020/6/10
     * @param string $url
     * @return array
     */
    public function start(string $url): array
    {
        $this->logic = new PiPiXiaLogic($url, $this->urlValidator->get('pipixia'), $this->config);
        $this->logic->checkUrlHasTrue();
        $this->logic->setItemId();
        $this->logic->setContents();
        return $this->exportData();
    }

}