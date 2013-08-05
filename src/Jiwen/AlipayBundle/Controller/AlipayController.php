<?php

namespace Jiwen\AlipayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jiwen\AlipayBundle\Lib\AlipayCoreFunction;
use Jiwen\AlipayBundle\Lib\AlipayMd5Function;
use Jiwen\AlipayBundle\Lib\AlipayNotify;
use Jiwen\AlipayBundle\Lib\AlipaySubmit;

class AlipayController extends Controller
{

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
		$return_url = $base_url . $this->generateUrl('alipay_return');
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//卖家支付宝帐户
		$seller_email = $this->container->getParameter('alipay.seller');
		//必填
		//商户订单号
		$out_trade_no = '#'.$order->getId();
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		$subject = $order->getId(). ' - ' .$order->getCreatedAt()->format('Y-m-d H:m:s'). ' - 基文商城';
		//必填
		//付款金额
		$price = $order->getTotal() / 100;
		//必填
		//商品数量
		$quantity = "1";
		//必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
		//物流费用
		$logistics_fee = "10.00";
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
//		error_reporting(E_ALL);
//		ini_set('display_errors', TRUE);
		//收货人地址
//		var_dump($shippingAddress->getProvince());
		$receive_address = $shippingAddress->getCountry()
				;
		echo $receive_address;
		//如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
		//收货人邮编
		$receive_zip = '123456';
		//如：123456
		//收货人电话号码
		$receive_phone = '123456';
		//如：0571-88158090
		//收货人手机号码
		$receive_mobile = '123456';
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
		));
	}

	public function notifyAction()
	{

	}

	public function returnAction()
	{

	}

}
