<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-10
 * Time: 下午2:00
 */
namespace app\push\controller;

use think\worker\Server;

use Workerman\Lib\Timer;
use think\Log;

class Worker extends Server
{
    protected $socket = 'websocket://0.0.0.0:2346';

    private $clientMsg = array('port_check');
    private $infoMsg = array();

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {

        if ($this->clientMsg[0] == $data) {
            $port = controller('push/PortChecker');
            $connection->port_timer_id = Timer::add(5, function()use($connection, $data, &$port_timer_id, $port)
            {
                $connection->send($port->getPortInfo());
            });

        }
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {
        Log::record('connection id =====> '. $connection->id);
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        // 删除定时器
        Timer::del($connection->port_timer_id);
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

        // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
        // 执行watchExpired
        if($worker->id === 0) {
            Timer::add(3600, function()use($worker){
                Log::record('expired dog is watching =====> '. date("Y-m-d H:i:s", time()).PHP_EOL);
                // 每天0点执行任务
                if('00' == date("H", time())) {
                    $watcher = controller('push/WatchExpired');
                    $watcher->dog();
                }

            });
        }
        else if($worker->id === 1) {
            Timer::add(3600, function()use($worker){
                Log::record('cifs dog is watching =====> '. date("Y-m-d H:i:s", time()).PHP_EOL);
                $watcher = controller('push/WatchCifs');
                $watcher->dog();
            });

        }

//        else if($worker->id === 2) {
//            Timer::add(5, function()use($worker){
//                Log::record('sync dog is watching =====> '. date("Y-m-d H:i:s", time()).PHP_EOL);
//                $watcher = controller('push/SyncFile');
//                $watcher->dog();
//            });
//
//        }
    }
}