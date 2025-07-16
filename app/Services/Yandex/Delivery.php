<?php

namespace App\Services\Yandex;

use App\Services\Yandex\Classes\Claim;
use Http, Log;
use Illuminate\Support\Arr;

class Delivery {
	public int $addPrice = 2000; // страховая сумма

	protected $baseUrl;
	protected $token;
	protected $country = 'Узбекистан';
	protected $city = 'Ташкент';

	protected $callbackUrl = 'https://admin.okc.uz/for/delivery';
	protected $referralSource = 'website';

	public function __construct()
	{
		$this->baseUrl = config('yandex_delivery.base_url');
		$this->token = config('yandex_delivery.token');
	}

	// base methods
	public function checkPrice(array $routePoints, array $items)
	{
		$url = $this->baseUrl.'/check-price';
		$body = [
			'items' => $items,
			// 'items' => [
		    //     [
		    //         'quantity' => 1,
		    //         // 'size' => [
		    //         //     'length' => 0.1,
		    //         //     'width' => 0.2,
		    //         //     'height' => 0.3
		    //         // ],
		    //         // 'weight' => 2.105,
		    //         // 'pickup_point' => 1,
		    //         // 'dropoff_point' => 2
		    //     ]
		    // ],
		    'route_points' => $routePoints,
		    // 'route_points' => [
		    //     [
		    //         'id' => $routePoints['id'],
		    //         'coordinates' => $routePoints['coordinates'], // [долгота, широта]
		    //         'fullname' => $routePoints['address'], // Полный адрес с указанием города, улицы и номера дома. Номер квартиры, подъезда и этаж указывать не нужно.
		    //         // 'country' => $this->country,
		    //         // 'city' => $this->city,
		    //         // 'street' => $routePoints['street'], // Улица
		    //         // 'building' => $routePoints['building'], // Строение
		    //         'porch' => $routePoints['porch'], // Подъезд (может быть A)
		    //         'sfloor' => $routePoints['floor'], // Этаж
		    //         'sflat' => $routePoints['flat'] // Квартира
		    //     ]
		    // ],
		    'requirements' => [
		        // 'pro_courier' => false,
		        'taxi_class' => "express",
		        // 'cargo_options' => ['auto_courier', 'thermobag'],
		        // 'cargo_type' => "lcv_m",
		        // 'cargo_loaders' => 1, // Число грузчиков для грузового тарифа. Возможные значения: 0, 1, 2.
		        // 'same_day_data' => [
		        //     'delivery_interval' => [
		        //         'from' => "2020-01-01T00:00:00+00:00",
		        //         'to' => "2020-01-02T00:00:00+00:00"
		        //     ]
		        // ]
		    ],
		    'skip_door_to_door' => false
		];

		$res = Http::withToken($this->token)
			->withHeaders(['Accept-Language' => 'ru'])	
			->post($url, $body);

		return $res;
	}

	// Создание заявки
	public function claimsCreate(
		array $routePoints,
		array $items,
		string $requestId
		// array $emergencyContact
		// string $comment
	) {

		$url = $this->baseUrl.'/claims/create?request_id='.$requestId;
		$body = [
		    "items" => $items,
		    "route_points" => $routePoints,
		    // "emergency_contact" => $emergencyContact,
		    "client_requirements" => [
		        "taxi_class" => "express",
		        // "cargo_options" => [
		        //     "thermobag",
		        //     "auto_courier"
		        // ],
		        // "pro_courier" => false,
		    ],
		    // "callback_properties" => [
		    //     "callback_url" => $this->callbackUrl
		    // ],
		    "skip_door_to_door" => false,
		    "skip_client_notify" => false,
		    "skip_emergency_notify" => false,
		    "skip_act" => false,
		    "optional_return" => false,
		    "referral_source" => $this->referralSource,

		    "auto_accept" => false,
		];

		$res = Http::withToken($this->token)
			->withHeaders(['Accept-Language' => 'ru'])	
			->post($url, $body);

		return $res;
		
		// $body = [
		//     // "shipping_document" => "string",
		//     "items" => [
		//         [
		//             "extra_id" => "БП-208", // unique not required
		//             "pickup_point" => 1, // Идентификатор точки (int64), откуда нужно забрать товар
		//             "dropoff_point" => 2, // Идентификатор точки (int64), куда нужно доставить товар
		//             "title" => "Плюмбус", // Наименование единицы товара
		//             "size" => [
		//                 "length" => 0.1,
		//                 "width" => 0.2,
		//                 "height" => 0.3
		//             ],
		//             "weight" => 2,
		//             "cost_value" => "2.00", // Цена за единицу товара
		//             "cost_currency" => "UZS",
		//             "quantity" => 1, // Количество товара в единицах
		//             // "fiscalization" => [
		//             //     "excise" => "12.50",
		//             //     "vat_code_str" => "vat_none",
		//             //     "supplier_inn" => 3664069397,
		//             //     "article" => "20ML50OWKY4FC86",
		//             //     "mark" => [
		//             //         "kind" => "gs1_data_matrix_base64",
		//             //         "code" => "444D00000000003741"
		//             //     ],
		//             //     "item_type" => "product"
		//             // ]
		//         ]
		//     ],
		//     "route_points" => [
		//         [
		//             "point_id" => 6987, // Целочисленный идентификатор точки (int64), уникальна в рамках создания заявки
		//             "visit_order" => 1, // Порядок посещения точки (нумерация начинается с 1) (int64)
		//             "contact" => [
		//                 "name" => "Морти",
		//                 "phone" => "+79099999998",
		//                 // "phone_additional_code" => "602 17 500",
		//                 // "email" => "example@yandex.ru"
		//             ],
		//             "address" => [
		//                 "fullname" => "Санкт-Петербург, Большая Монетная улица, 1к1А",
		//                 "shortname" => "Большая Монетная улица, 1к1А",
		//                 "coordinates" => [
		//                     0
		//                 ],
		//                 "country" => $this->country,
		//                 "city" => $this->city,
		//                 "comment" => "Домофон не работает",
		//                 // "building_name" => "БЦ На Большой Монетной",
		//                 // "street" => "Большая Монетная улица",
		//                 // "building" => "23к1А",
		//                 // "porch" => "A",
		//                 // "sfloor" => "1",
		//                 // "sflat" => "1",
		//                 // "door_code" => "169",
		//                 // "door_code_extra" => "код на вход во двор #1234, код от апартаментов #4321",
		//                 // "doorbell_name" => "Магидович",
		//                 // "uri" => "ymapsbm1://geo?ll=38.805%2C55.084",
		//                 // "description" => "Санкт-Петербург, Россия"
		//             ],
		//             "skip_confirmation" => false,
		//             "leave_under_door" => false,
		//             "meet_outside" => false,
		//             "no_door_call" => false,
		//             "type" => "source", // source - точка отправления, где курьер забирает товар, destination – точки назначения, где курьер передает товар
		//             // "buyout" => [
		//             //     "payment_method" => "card"
		//             // ],
		//             // "payment_on_delivery" => [
		//             //     "customer" => [
		//             //         "inn" => 3664069397,
		//             //         "email" => "example@yandex.ru",
		//             //         "phone" => "79000000000"
		//             //     ],
		//             //     "payment_method" => "card"
		//             // ],
		//             "external_order_id" => "100", // Номер заказа из системы клиента. Передается для точки с типом destination
		//             // "external_order_cost" => [
		//             //     "value" => "100.0",
		//             //     "currency" => "RUB",
		//             //     "currency_sign" => "₽"
		//             // ],
		//             // "pickup_code" => "893422",
		//             "should_notify_on_order_readiness" => false
		//         ]
		//     ],
		//     "emergency_contact" => $emergencyContact,
		//     // "emergency_contact" => [
		//     //     "name" => "Рик",
		//     //     "phone" => "+79826810246",
		//     //     // "phone_additional_code" => "602 17 500"
		//     // ],
		//     "client_requirements" => [
		//         "taxi_class" => "express",
		//         // "cargo_type" => "lcv_m",
		//         // "cargo_loaders" => 0,
		//         "cargo_options" => [
		//             "thermobag",
		//             "auto_courier"
		//         ],
		//         "pro_courier" => false,
		//         // "rental_duration"=> 0
		//     ],
		//     "callback_properties" => [
		//         "callback_url" => $this->callbackUrl
		//     ],
		//     "skip_door_to_door" => false,
		//     "skip_client_notify" => false,
		//     "skip_emergency_notify" => false,
		//     "skip_act" => false,
		//     "optional_return" => false,
		//     // "due" => "2020-01-01T00:00:00+00:00",
		//     // "comment" => $comment,
		//     "referral_source" => $this->referralSource,
		//     // "same_day_data" => [
		//     //     "delivery_interval" => [
		//     //         "from" => "2020-01-01T07:00:00+00:00",
		//     //         "to" => "2020-01-01T07:00:00+00:00"
		//     //     ]
		//     // ],
		//     "auto_accept" => false,
		//     // "offer_payload" => "asjdijasDKL;ahsdfljhlkjhasF;HS;Ldjf;ljloshf"
		// ];

	}

	public function claimsInfo(string $claimId)
	{
		$url = $this->baseUrl.'/claims/info?claim_id='.$claimId;

		$res = Http::withToken($this->token)
			->withHeaders(['Accept-Language' => 'ru'])
			->post($url);

		return $res;
	}

	public function claimsAccept(string $claimId)
	{
		$url = $this->baseUrl.'/claims/accept?claim_id='.$claimId;
		$body = [
			'version' => 1
		];

		$res = Http::withToken($this->token)
			->withHeaders(['Accept-Language' => 'ru'])
			->post($url, $body);

		return $res;
	}

	public function claimsBulkInfo(array $claimIds)
	{
		$url = $this->baseUrl.'/claims/bulk_info';
		$body = [
			'claim_ids' => $claimIds
		];

		$res = Http::withToken($this->token)
			->withHeaders(['Accept-Language' => 'ru'])
			->post($url, $body);

		return $res;
	}

	public function statuses(): array
	{
		return [
			'new' => [
				'desc' => 'Создана новая заявка',
				'status' => 1
			],
			'estimating' => [
				'desc' => 'Идет процедура оценки заявки: подбор типа автомобиля по параметрам товара и расчет стоимости',
				'status' => 2
			],
			'ready_for_approval' => [
				'desc' => 'Заявка успешно оценена и ожидает подтверждения. Подтвердите заявку в течение 10 минут после присвоения заявке статуса ready_for_approval. Если вы не согласны со стоимостью и условиями доставки, попробуйте изменить параметры заявки. После редактирования заявка автоматически отправится на переоценку',
				'status' => 3
			],
			'accepted' => [
				'desc' => 'Заявка подтверждена. Если вы не успели подтвердить заявку по истечении 10 минут с момента присвоения заявке статуса ready_for_approval, в ответ на подтверждение заявки вернется статус failed. В таком случае создайте новую заявку',
				'status' => 4
			],
			'performer_lookup' => [
				'desc' => 'После подтверждения заявки формируется заказ, которому присваивается route_id. Начинается поиск курьера',
				'status' => 5
			],
			'performer_draft' => [
				'desc' => 'Производится поиск курьера в соответствии с указанными в заявке требованиями',
				'status' => 6
			],
			'performer_found' => [
				'desc' => 'Курьер найден и едет к отправителю (точка А). С этого момента и до завершения заказа вы можете запрашивать следующую информацию: Данные о курьере и транспортном средстве (поле performer_info в ответе на запрос Получение информации по заявке). Номер телефона курьера. Если курьер будет заменен по какой-либо причине, перезапросите номер телефона.Координаты текущего местоположения курьера',
				'status' => 7
			],
			'pickup_arrived' => [
				'desc' => 'Курьер приехал в точку А, чтобы забрать заказ',
				'status' => 8
			],
			'ready_for_pickup_confirmation' => [
				'desc' => 'Курьер ждет, когда отправитель назовет ему код подтверждения (статус актуален только при skip_confirmation = false). Код генерируется автоматически после того, как курьер сообщит системе, что он прибыл к отправителю. Получить код можно в личном кабинете, по смс или с помощью метода API',
				'status' => 9
			],
			'pickuped' => [
				'desc' => 'Курьер вводит код подтверждения в систему. Передача товара курьеру подтверждена. Запрос Получение информации по заявке вернет информацию о посещении точки в поле visit_status',
				'status' => 10
			],
			'delivery_arrived' => [
				'desc' => 'Курьер приехал к получателю (точка Б). Курьер пытается дозвониться до получателя в течение 10 минут. Если получатель не отвечает, товар будет возвращен отправителю',
				'status' => 11
			],
			'ready_for_delivery_confirmation' => [
				'desc' => 'Курьер сообщает системе, что готов передать товар получателю. Автоматически генерируется код подтверждения и отправляется получателю по смс. Получатель должен сообщить этот код курьеру',
				'status' => 12
			],
			'pay_waiting' => [
				'desc' => 'Заказ ожидает оплаты (статус актуален, если в параметрах заказа выбрана оплата при получении)',
				'status' => 13
			],
			'delivered' => [
				'desc' => 'Курьер вводит код подтверждения в систему и передает товар получателю. Доставка подтверждена. Если в заказе несколько точек, курьер отправляется к следующему получателю',
				'status' => 14
			],
			'delivered_finish' => [
				'desc' => 'Заказ завершен, курьер доставил товары всем получателям в заказе',
				'status' => 15
			],
			'returning' => [
				'desc' => 'Если хотя бы один товар в заказе невозможно передать получателю, курьер возвращает товар. По умолчанию точка возврата совпадает с точкой А, при необходимости можно указать другой адрес возврата',
				'status' => 16
			],
			'return_arrived' => [
				'desc' => 'Курьер приехал в точку возврата',
				'status' => 17
			],
			'ready_for_return_confirmation' => [
				'desc' => 'Курьер ждет, когда отправитель в точке возврата назовет код подтверждения. Код генерируется автоматически после того как курьер сообщит системе, что он прибыл на точку возврата. Получить этот код можно через личный кабинет, смс или с помощью метода API',
				'status' => 18
			],
			'returned' => [
				'desc' => 'Курьер ввел код подтверждения в систему и вернул товар отправителю. Возврат товара подтвержден',
				'status' => 19
			],
			'returned_finish' => [
				'desc' => 'Заказ завершен с возвратом товара',
				'status' => 20
			],

			// Статусы при отмене заказа
			'cancelled_by_taxi' => [
				'desc' => 'Заказ отменен курьером. Курьер может отменить заказ до момента, пока не получил товар от отправителя (статус pickuped)',
				'status' => 21
			],
			'cancelled' => [
				'desc' => 'Заказ отменен бесплатно',
				'status' => 22
			],
			'cancelled_with_payment' => [
				'desc' => 'Заказ отменен платно с возвратом товара',
				'status' => 23
			],
			'cancelled_with_items_on_hands' => [
				'desc' => 'Заказ отменен платно без возврата товара (заявка была создана с флагом optional_return)',
				'status' => 24
			],

			// Статусы ошибок
			'failed' => [
				'desc' => 'При выполнении заказа произошла ошибка, дальнейшее выполнение невозможно.',
				'status' => 25
			],
			'estimating_failed' => [
				'desc' => 'Не удалось оценить заявку. Узнать причину можно по запросу Получение информации по заявке: причина будет указана в ответе в поле error_messages. Отредактируйте заявку с помощью метода Редактирование заявки, и процесс оценки запустится снова',
				'status' => 26
			],
			'performer_not_found' => [
				'desc' => 'Не удалось найти курьера. Попробуйте создать новую заявку через некоторое время',
				'status' => 27
			],
		];
	}

	public function status2Number(string $status)
	{
		return $this->statuses()[$status]['status'];
	}

	public function status2Str($status)
	{
		$result = Arr::where($this->statuses(), function ($item) use ($status) {
			return $item['status'] == $status;
		});

		return array_values($result)[0];
	}
}