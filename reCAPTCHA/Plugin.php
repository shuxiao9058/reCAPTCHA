<?php
/**
 * reCAPTCHA验证码插件
 * 
 * @package reCAPTCHA
 * @author 啸傲居士
 * @version 0.0.1
 * @link http://geaya.com
 */

require_once('lib/recaptchalib.php');

class reCAPTCHA_Plugin implements Typecho_Plugin_Interface
{

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
		Typecho_Plugin::factory('Widget_Feedback')->comment = array(__CLASS__, 'filter');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {}
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}
    
	/**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
	public static function config(Typecho_Widget_Helper_Form $form) {
		$publickeyDescription = _t("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
		$publickey = new Typecho_Widget_Helper_Form_Element_Text('publickey', NULL, '', _t('Public Key:'), $publickeyDescription);
		$privatekey = new Typecho_Widget_Helper_Form_Element_Text('privatekey', NULL, '', _t('Private Key:'), _t(''));
		
		$form->addInput($publickey);
		$form->addInput($privatekey);
	}
	
	/**
	 * 展示验证码
	 */
	public static function output() {

	 	echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'blackglass\' }; </script>';

	    $publickey = Typecho_Widget::widget('Widget_Options')->plugin('reCAPTCHA')->publickey;
		echo recaptcha_get_html($publickey);
	}
  
	public static function filter($comment, $obj) {
		$privatekey = Typecho_Widget::widget('Widget_Options')->plugin('reCAPTCHA')->privatekey;
		$userObj = $obj->widget('Widget_User');
		
		if($userObj->hasLogin() && $userObj->pass('administrator', true)) {
			return $comment;
		}
		
		$resp = recaptcha_check_answer($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			// die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			// 	"(reCAPTCHA said: " . $resp->error . ")");
			throw new Typecho_Widget_Exception(_t('验证码不正确哦！'));
		}
		
		return $comment;
	}
}
