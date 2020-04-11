<?php


namespace App\Tars;

use \Tars\report\ServerFSync;
use \Tars\report\ServerFAsync;
use \Tars\report\ServerInfo;
use \Tars\Utils;
use Swoft\Log\Helper\CLog;

/**
 *
 */
class Manage
{
    public function getNodeInfo(){
        $conf = $this->getTarsConf();
        if( !empty($conf) ){
            $node = $conf['tars']['application']['server']['node'];
            $nodeInfo = Utils::parseNodeInfo($node);
            return $nodeInfo;
        }else{
            CLog::error('获取tars_node配置信息失败');
            return [];
        }
    }

    public function getTarsConf(){
        $tars_conf = dirname(dirname(config('base_path'))) . '/conf/' . config('app_id') . '.config.conf';
        if( is_file($tars_conf) ){
            $conf = Utils::parseFile($tars_conf);
            return $conf;
        }else{
            CLog::error('获取app配置文件失败: ' . $tars_conf);
            return [];
        }
    }

    public function keepAlive()
    {
        $pnameStr = config("app_id");
        $pname = $pnameStr;
        $pname = explode('.',$pname);

        $adapter = $pnameStr.'.apiObjAdapter';
        $application = $pname[0];
        $serverName = $pname[1];

        $masterPid = explode(',', file_get_contents(config('base_path') . "/runtime/swoft.pid"))[0];
        
        $nodeInfo = $this->getNodeInfo();
        if(empty($nodeInfo) ){
            CLog::error('获取app配置文件失败: ' . $tars_conf);
            return null;
        }

        // 创建 node 端的连接信息
        $host = $nodeInfo['host'];
        $port = $nodeInfo['port'];
        $objName = $nodeInfo['objName'];
        $serverF = new ServerFSync($host, $port, $objName);

        // 应用的 demo.userapi.apiObjAdapter 上报, 对应着一个 servant
        $serverInfo = new ServerInfo();
        $serverInfo->adapter = $adapter;
        $serverInfo->application = $application;
        $serverInfo->serverName = $serverName;
        $serverInfo->pid = $masterPid;
        $serverF->keepAlive($serverInfo);
        CLog::info('发送心跳: master id %s', $masterPid);

         // 应用的 objAdapter 上报，但是这里应该有个端口暴露才对呀
        $adminServerInfo = new ServerInfo();
        $adminServerInfo->adapter = 'AdminAdapter';
        $adminServerInfo->application = $application;
        $adminServerInfo->serverName = $serverName;
        $adminServerInfo->pid = $masterPid;
        $serverF->keepAlive($adminServerInfo);
        CLog::info('发送心跳: master id %s', $masterPid);
    }
}