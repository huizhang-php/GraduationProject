<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午4:43
 * Description:
 */
namespace app\common\tool;

use app\common\config\SelfConfig;
use PHPMailer\PHPMailer\PHPMailer;

class MailTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午5:04
     * @var $mail PHPMailer
     * Description: mail 对象
     */
    private $mail;

    /**
     * MailTool constructor.
     */
    public function __construct()
    {
        $mail = new PHPMailer();
        $mailConfig = SelfConfig::getConfig('Project.mail');
        try{
            $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                    // 设定使用SMTP服务
            $mail->SMTPDebug = $mailConfig['debug'];               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
            $mail->SMTPSecure = 'ssl';          // 使用安全协议
            $mail->Host = $mailConfig['smpt']; // SMTP 服务器
            $mail->Port = 465;                  // SMTP服务器的端口号
            $mail->Username = $mailConfig['smpt_username'];    // SMTP服务器用户名
            $mail->Password = $mailConfig['smpt_password'];     // SMTP服务器密码（如果设置独立密码请填空独立密码）
            $mail->SetFrom($mailConfig['smpt_username'], $mailConfig['send_name']);
        }catch (\Exception $e){
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        $this->mail = $mail;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午5:03
     * @return MailTool
     * Description: 返回当前实例
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function instance() {
        return new MailTool();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午4:50
     * @param $title
     * @param $body
     * Description: 发送邮件
     * @param $to
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendMail($title,$body,$to) {
        $this->mail->Subject = $title;
        $this->mail->MsgHTML($body);
        $this->mail->AddAddress($to);
        $res = $this->mail->Send();
        if ($res) {
            return true;
        }
        return false;
    }
}