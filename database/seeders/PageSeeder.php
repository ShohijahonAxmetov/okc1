<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'title' => 'Контакты',
                'main_text' => null,
                'text' => null,
                'meta_title' => null,
                'meta_desc' => null,
                'meta_keywords' => null,
                'video' => null,
            ],
            [
                'id' => 2,
                'title' => 'О компании',
                'main_text' => [
                    'ru' => 'Магазин Original Korean Cosmetics № 1 в Узбекистане. Мы всегда предоставляем самый актуальный и сертифицированный ассортимент косметики из Кореи. В нашем магазине представлены более 1500 разновидностей уходовой и декоративной косметики от самых популярных корейских брендов, как для женщин так и для мужчин.

                        Мы работаем только с официальными дистрибьюторами косметических брендов . Вся продукция имеет декларации и местные сертификаты качества , а так же протоколы безопасности, выданные страной производителя. Соблюдены все температурные режимы и условия хранения продукции

                        Наши основные бренды Missha, Beausta, Tonymoly, Charmzone, Bergamo и т.д.',
                    'uz' => ''
                ],
                'text' => [
                    'ru' => 'Наши адреса
                            Улица Осие, 21. Ориентир: Гидрометцентр.

                            Улица Мирабад 41. Ориентир: Mirabad avenue',
                    'uz' => ''
                ],
                'meta_title' => null,
                'meta_desc' => null,
                'meta_keywords' => null,
                'video' => null,
            ],
            [
                'id' => 3,
                'title' => 'Доставка и оплата',
                'main_text' => null,
                'text' => null,
                'meta_title' => null,
                'meta_desc' => null,
                'meta_keywords' => null,
                'video' => null,
            ],
        ];

        foreach($data as $item) {
            Page::create($item);
        }
    }
}
