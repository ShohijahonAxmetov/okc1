<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\PlaymobileMessage;
use Illuminate\Support\Facades\Http;

trait Playmobile {

	protected $sms_username = 'beautyprof';
    protected $sms_password = 'p5pkK46SGJb8';
    protected $sms_basic_url = 'http://91.204.239.44/broker-api/send';

	protected function order_status_send(Order $order)
	{
		abort(503);
		switch ($order->status) {
		    case 'accepted':

		    	$message = PlaymobileMessage::create([
	            	'order_id' => $order->id,
	            	'phone_number' => $order->phone_number,
	            	'text' => 'Уважаемый покупатель, рады сообщить Вам, что Ваш заказ № '.$order->id.' принят в обработку. Original Korean Cosmetics.'
	            ]);
		        
		    	$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $order->phone_number,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => 'Уважаемый покупатель, рады сообщить Вам, что Ваш заказ № '.$order->id.' принят в обработку. Original Korean Cosmetics.'
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

	            if(isset($res->json()['error_code'])) {
	            	$message->update([
	            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
	            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
	            	]);
	            }

		        break;
		    case 'collected':

	            $message = PlaymobileMessage::create([
	            	'order_id' => $order->id,
	            	'phone_number' => $order->phone_number,
	            	'text' => $order->with_delivery == 1 ? 'Уважаемый (ая) '.$order->name.', Ваш заказ № '.$order->id.' собран, в ближайшее время курьер с Вами свяжется для уточнения параметров доставки. Магазин ОКС.' : 'Уважаемый (ая) '.$order->name.', Ваш заказ № '.$order->id.' собран. Вы можете забрать его в магазине. Original Korean Cosmetics.'
	            ]);
		        
		    	$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $order->phone_number,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => $order->with_delivery == 1 ? 'Уважаемый (ая) '.$order->name.', Ваш заказ № '.$order->id.' собран, в ближайшее время курьер с Вами свяжется для уточнения параметров доставки. Магазин ОКС.' : 'Уважаемый (ая) '.$order->name.', Ваш заказ № '.$order->id.' собран. Вы можете забрать его в магазине. Original Korean Cosmetics.'
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

	            if(isset($res->json()['error_code'])) {
	            	$message->update([
	            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
	            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
	            	]);
	            }

		        break;
		    case 'on_the_way':

		    	$message = PlaymobileMessage::create([
	            	'order_id' => $order->id,
	            	'phone_number' => $order->phone_number,
	            	'text' => 'Уважаемый покупатель, рады сообщить Вам, что Ваш заказ № '.$order->id.' в пути. Original Korean Cosmetics.'
	            ]);
		        
		    	$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $order->phone_number,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => 'Уважаемый покупатель, рады сообщить Вам, что Ваш заказ № '.$order->id.' в пути. Original Korean Cosmetics.'
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

	            if(isset($res->json()['error_code'])) {
	            	$message->update([
	            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
	            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
	            	]);
	            }

		        break;
	        case 'done':

	        	$message = PlaymobileMessage::create([
	            	'order_id' => $order->id,
	            	'phone_number' => $order->phone_number,
	            	'text' => 'Здравствуйте! Заказ № '.$order->id.' доставлен адресату. Спасибо за покупку. С уважением,  интернет-магазин Original Korean Cosmetics'
	            ]);
        		
	        	$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $order->phone_number,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => 'Здравствуйте! Заказ № '.$order->id.' доставлен адресату. Спасибо за покупку. С уважением,  интернет-магазин Original Korean Cosmetics'
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

	            if(isset($res->json()['error_code'])) {
	            	$message->update([
	            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
	            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
	            	]);
	            }

		        break;
	        case 'cancelled':

	        	$message = PlaymobileMessage::create([
	            	'order_id' => $order->id,
	            	'phone_number' => $order->phone_number,
	            	'text' => 'Уважаемый покупатель,  Ваш заказ № '.$order->id.' отменен. Original Korean Cosmetics.'
	            ]);
		        
	        	$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $order->phone_number,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => 'Уважаемый покупатель,  Ваш заказ № '.$order->id.' отменен. Original Korean Cosmetics.'
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

	            if(isset($res->json()['error_code'])) {
	            	$message->update([
	            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
	            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
	            	]);
	            }

		        break;
		}
	}

	protected function mailing($phone_numbers, $text)
	{
		abort(503);
		foreach($phone_numbers as $item) {

			$message = PlaymobileMessage::create([
	            	'phone_number' => $item,
	            	'text' => $text
	            ]);

			$res = Http::withBasicAuth($this->sms_username, $this->sms_password)
		            ->post($this->sms_basic_url, [
		                'messages' => [
		                    [
		                        'recipient' => $item,
		                        'message-id' => strval($message->id),
		                        'sms' => [
		                            'originator' => "3700",
		                            'content' => [
		                                'text' => $text
		                            ],
		                        ],
		                    ],
		                ]
		            ]);

            if(isset($res->json()['error_code'])) {
            	$message->update([
            		'error_code' => isset($res->json()['error_code']) ? $res->json()['error_code'] : null,
            		'error_description' => isset($res->json()['error_description']) ? $res->json()['error_description'] : null
            	]);
            }
		}
	}
}