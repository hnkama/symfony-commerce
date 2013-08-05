<?php

namespace Jiwen\AlipayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jiwen\AlipayBundle\Lib\AlipayCoreFunction;
use Jiwen\AlipayBundle\Lib\AlipayMd5Function;
use Jiwen\AlipayBundle\Lib\AlipayNotify;
use Jiwen\AlipayBundle\Lib\AlipaySubmit;

class AlipayController extends Controller
{
	public function __construct()
	{
	}

	public function indexAction()
	{
		require_once("alipay.config.php");
		// prepare the payment information
		$session = $this->getRequest()->getSession();
		$order = $session->get('order');

		//支付类型
		$payment_type = "1";
		//必填，不能修改
		//服务器异步通知页面路径
		$base_url = 'http://'.$this->container->get('router')->getContext()->getHost();
		$notify_url = $base_url . $this->generateUrl('alipay_notify');
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = $base_url . $this->generateUrl('sylius_account_order_show', array('id'=>$order->getId()));
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//卖家支付宝帐户
		$seller_email = $this->container->getParameter('alipay.seller');
		//必填
		//商户订单号
		$out_trade_no = $order->getId();
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		$subject = $order->getId(). ' - ' .$order->getCreatedAt()->format('Y-m-d H:m:s'). ' - 基文商城';
		//必填
		//付款金额
		$price = $order->getTotal() / 100;
		$tesMode = $this->container->getParameter('alipay.test.mode');
		if($tesMode) {
			$price = 0.01;
		}
		$alipay_config['partner'] = $this->container->getParameter('alipay.partner');
		$alipay_config['key'] = $this->container->getParameter('alipay.key');
		//必填
		//商品数量
		$quantity = "1";
		//必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
		//物流费用
		$logistics_fee = "10.00";
		if($tesMode) {
			$logistics_fee = 0.01;
		}
		//必填，即运费
		//物流类型
		$logistics_type = "EXPRESS";
		//必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		//物流支付方式
		$logistics_payment = "SELLER_PAY";
		//必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		//订单描述

		$body = '基文商城';
		//商品展示地址
		$show_url = $base_url;
		//需以http://开头的完整路径，如：http://www.jiwenmall.com/myorder.html
		//收货人姓名
		$shippingAddress = $order->getShippingAddress();
		$receive_name = $shippingAddress->getFirstName();
		//如：张三
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		//收货人地址
		$receive_address = $shippingAddress->getCountry(). ' ' .
				$shippingAddress->getProvince() . ' ' .
				$shippingAddress->getCity() . ' ' .
				$shippingAddress->getStreet(). ' '
				;
		//如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
		//收货人邮编
		$receive_zip = $shippingAddress->getPostcode();
		//如：123456
		//收货人电话号码
		$receive_phone = $shippingAddress->getPhone();
		//如：0571-88158090
		//收货人手机号码
		$receive_mobile = $shippingAddress->getCellphone();
		//如：13312341234


		/*		 * ********************************************************* */

//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "create_partner_trade_by_buyer",
			"partner" => trim($alipay_config['partner']),
			"payment_type" => $payment_type,
			"notify_url" => $notify_url,
			"return_url" => $return_url,
			"seller_email" => $seller_email,
			"out_trade_no" => $out_trade_no,
			"subject" => $subject,
			"price" => $price,
			"quantity" => $quantity,
			"logistics_fee" => $logistics_fee,
			"logistics_type" => $logistics_type,
			"logistics_payment" => $logistics_payment,
			"body" => $body,
			"show_url" => $show_url,
			"receive_name" => $receive_name,
			"receive_address" => $receive_address,
			"receive_zip" => $receive_zip,
			"receive_phone" => $receive_phone,
			"receive_mobile" => $receive_mobile,
			"_input_charset" => trim(strtolower($alipay_config['input_charset']))
		);

//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");

		// redirect to alipay page
		return $this->render('JiwenAlipayBundle:Default:index.html.twig', array(
					'order' => $order,
					'html' => $html_text,
		));
	}

	public function notifyAction()
	{
		require_once("alipay.config.php");
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner'] = $this->container->getParameter('alipay.partner');

		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key'] = $this->container->getParameter('alipay.key');
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		$logger = $this->get('logger');
//    	$logger->info('Alipay result'.print_r($verify_result, true));

		if ($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
//    		$logger->info('Alipay: out_trade_no '.$out_trade_no);

			//支付宝交易号

			$trade_no = $_POST['trade_no'];
//    		$logger->info('Alipay: trade_no '.$trade_no);

			//交易状态
			$trade_status = $_POST['trade_status'];


			if ($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
				//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
				// 修改order状态为已经支付
				$orderRepository = $this->container->get('sylius.repository.order');
				$order = $orderRepository->find($out_trade_no);
    			$logger->info('Payment Status before payments: '.$order->getPaymentStatus());
				$order->setPaymentStatus(1);
				$em = $this->getDoctrine()->getManager();
				$em->persist($order);
				$em->flush();
    			$logger->info('Payment Status After payment: '.$order->getPaymentStatus());



				echo "success";  //请不要修改或删除
				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			} else if ($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
				//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序

				echo "success";  //请不要修改或删除
				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    			$logger->info('Alipay: WAIT_SELLER_SEND_GOODS');
			} else if ($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
				//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
				
				echo "success";  //请不要修改或删除
				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
//    			$logger->info('Alipay: WAIT_BUYER_CONFIRM_GOODS');
			} else if ($_POST['trade_status'] == 'TRADE_FINISHED') {
				//该判断表示买家已经确认收货，这笔交易完成
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序

				echo "success";  //请不要修改或删除
				//调试用，写文本函数记录程序运行情况是否正常
    			$logger->info('Alipay: TRADE_FINISHED');
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			} else {
				//其他状态判断
				echo "success";

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			//验证失败
			echo "fail";

			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}

	}

	public function returnAction()
	{

	}

}
