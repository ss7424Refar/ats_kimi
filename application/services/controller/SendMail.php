<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-12
 * Time: 上午9:11
 */


namespace app\services\controller;

use think\Controller;
use think\Db;
use ext\MailerUtil;

/*
 * mail interface for ats
 */
class SendMail extends Controller {
    private $today;

    public function _initialize(){
        parent::_initialize();

        $this->today = date("Y-m-d");
    }

    /**
     * http://localhost/ats/services/SendMail/sendBaseLine?taskId=81&steps=2
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sendBaseLine() {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');

        $emailTo = $this->getUserAddress($taskId);

        $info = $this->getTaskStepsInfo($taskId, $steps);

        $testImage = json_decode($info[0]['element_json']);
        $testImage = $testImage->Test_Image;

        $mailTitle = '[ATS-'. $taskId .'][' . $this->today . ']['. $info[0]['tool_name'] .'] You Need to run the baseline image';

        $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/' .
            'office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">' .
            '   <head>' .
            '       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Generator" >' .
            $this->getCSSStyle().
            '	</head>' .
            '	<body>' .
            '		<p>Dear ' . $info[0]['tester'] . ',</p>' .
            '		<p>Please click OK button to start running baseline image on the target machine on the test shelf as the OEM image test result doesn\'t satisfy the target metric.</p>' .
            '		<p style="font-size:12px;color:red"><i>The Jumpstart task as below.</i></p>' .
            '	<table id="customers">' .
            '		<tr>' .
            '			<th>Task ID</th><th>Machine ID</th><th>Test Machine</th><th>Assigned Task</th><th>Test Image</th>' .
            '			<th>Test Result</th><th>Start Date</th><th>Finish Date</th>' .
            '		</tr>' .
            '       <tr>' .
            '			<td>ATS_' . $info[0]['task_id'] . '</td>' .
            '			<td>' . $info[0]['machine_id'] . '</td>' .
            '			<td>' . $info[0]['machine_name'] . '</td>' .
            '			<td>'. JUMP_START .'</td>' .
            '			<td>'. $testImage .'</td>' .
            '			<td>' . $info[0]['status'] . '</td>' .
            '			<td>' . $info[0]['task_start_time'] . '</td>' .
//            '			<td>' . $info[0]['task_end_time'] . '</td>' .
            '			<td>-</td>' .
            '		</tr>' .
            '	</table>'.
            '</body>' .
            '<p style="margin-top: 15px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="'.ATS_URL .'">Link To ATS</a></p>' .
            '</html>';

//        return $content;
        return MailerUtil::send($emailTo, config('mail_cc_baseline'), $mailTitle, $content);
    }

    /**@throws
     * send steps result
     * http://localhost/ats/services/SendMail/sendStepsResult?taskId=81&steps=2 (验证用)
     * @param $taskId
     * @param $steps
     * @return string
     */
    public function sendStepsResult($taskId, $steps) {

        $emailTo = $this->getUserAddress($taskId);

        $info = $this->getTaskStepsInfo($taskId, $steps);

        // 过期的邮件还是单独发
        if (EXPIRED == $info[0]['status']) {

            $mailTitle = '[ATS-'. $taskId .'][' . $this->today  . ']['. $info[0]['tool_name'] .']Test result is '. $info[0]['status'];

            $notice = '<p style="margin-top: 5px; font-size:12px;color:red" >This step may run over times updated to expired &nbsp;&nbsp;</p>';

            $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/' .
                'office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">' .
                '   <head>' .
                '       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Generator" >' .
                $this->getCSSStyle().
                '	</head>' .
                '	<body>' .
                '		<p>Dear ' . $info[0]['tester'] . ',</p>' .
                '		<p>' . '[' .  $info[0]['tool_name']  . ']' . ' test result is '. $info[0]['status'] .'.</p>'.
                '		<p style="font-size:12px;color:red"><i>Test task as below.</i></p>' .
                '	<table id="customers">' .
                '		<tr>' .
                '			<th>Task ID</th><th>Steps</th><th>Machine ID</th><th>Test Machine</th><th>Assigned Task</th>' .
                '			<th>Task Status</th><th>Start Date</th><th>Finish Date</th>' .
                '		</tr>' .
                '       <tr>' .
                '			<td>ATS_' . $info[0]['task_id'] . '</td>' .
                '			<td>' . $info[0]['steps'] . '</td>' .
                '			<td>' . $info[0]['machine_id'] . '</td>' .
                '			<td>' . $info[0]['machine_name'] . '</td>' .
                '			<td>' . $info[0]['tool_name'] . '</td>' .
                '			<td>' . $info[0]['status']  . '</td>' .
                '			<td>' . $info[0]['tool_start_time'] . '</td>' .
//                '			<td>' . $info[0]['tool_end_time'] . '</td>' .
                '			<td>-</td>' .
                '		</tr>' .
                '	</table></body>' .
                $notice.
                '<p style="margin-top: 10px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="'.ATS_URL .'">Link To ATS</a></p>' .
                '</html>';

//                        return $content;
            return MailerUtil::send($emailTo, config('mail_cc'), $mailTitle, $content);
        // 不是过期的邮件则合并成一封发
        } else {
            // 统计总的steps
            $total = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->count();

            if ($total > $steps) {
                return -1; // 表示不发送邮件
            }

            // 发送邮件
            $stepsInfo = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->order('steps')->select();

            $tables = '<tr>'.
                      '  <th>Steps</th>'.
                      '  <th>Tool Name</th>'.
                      '  <th>Status</th>'.
                      '  <th>Start Date</th>'.
                      '  <th>Finish Date</th>'.
                      '</tr>';
            for ($i = 0; $i < count($stepsInfo); $i++) {
                if ($i % 2 == 0) {
                    $tables = $tables. '<tr class="alt">'.
                        '	 <td>'. $stepsInfo[$i]['steps'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_name'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['status'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_start_time'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_end_time'] .'</td>'.
                        '</tr>';
                } else {
                    $tables = $tables. '<tr>'.
                        '	 <td>'. $stepsInfo[$i]['steps'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_name'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['status'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_start_time'] .'</td>'.
                        '	 <td>'. $stepsInfo[$i]['tool_end_time'] .'</td>'.
                        '</tr>';
                }
            }

            $mailTitle = '[ATS-'. $taskId .'][' . $this->today  . '] Your Task Has Finished';

            $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/' .
                'office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">' .
                '   <head>' .
                '       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Generator" >' .
                // 获取通用的css
                $this->getCSSStyle().
                '	</head>' .
                '	<body>' .
                '		<p>Dear ' . $info[0]['tester'] . ',</p>' .
                '		<p>'. $info[0]['machine_name'] .' ('. $info[0]['machine_id'] .') on the shelf has finished.</p>'.
                '		<p style="font-size:12px;color:red"><i>Test steps as below.</i></p>' .
                '   <table id="customers">'.
                        $tables.
                '	</table></body>' .
                '<p style="margin-top: 10px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="'.ATS_URL .'">Link To ATS</a></p>' .
                '</html>';

//            return $content;
            return MailerUtil::send($emailTo, config('mail_cc'), $mailTitle, $content);
        }
    }

    /**
     * @param $taskId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getUserAddress($taskId) {
        $testerRes = Db::table('ats_task_basic')->where('task_id', $taskId)->field('tester')->select();

        $emailRes = Db::table('users')->where('login', $testerRes[0]['tester'])->field('email')->select();

        return $emailRes[0]['email'];
    }

    /**
     * @param $taskId
     * @param $steps
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getTaskStepsInfo($taskId, $steps) {
        // 排除basic中的status
        $subsql = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)->buildSql();
        $subQuery = Db::table('ats_task_basic')->where('task_id', $taskId)->field('status',true)->buildSql();
        $res = Db::table($subQuery.' a')->join([$subsql=> 'b'], 'a.task_id = b.task_id')->select();

        return $res;
    }

    private function getCSSStyle() {
        return
            '<style type="text/css">' .
            '	  p {'.
            '        font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;'.
            '        font-size:0.75em;'.
            '        margin: 8px 0px 2px 0px'.
            '     }'.
            '	  #customers{'.
            '        font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;'.
            '        border-collapse:collapse;'.
            '     }'.
            '	  #customers td, #customers th{'.
            '        font-size:0.75em;'.
            '        border:1px solid #D4D4D4;'.
            '        padding:3px 7px 2px 7px;'.
            '	  }'.
            '	  #customers th {'.
            '        font-size:0.75em;'.
            '        text-align:left;'.
            '        padding-top:5px;'.
            '        padding-bottom:4px;'.
            '        background-color:#555555;'.
            '        color:#ffffff;'.
            '	   }'.
            '	   #customers tr.alt td {'.
            '         color:#000000;'.
            '         background-color:#F6F4F0;'.
            '	   }'.
            '</style>' ;
    }
}