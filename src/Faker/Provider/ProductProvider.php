<?php

namespace App\Faker\Provider;

use Faker\Provider\Base as BaseProvider;

/**
 *
 */
final class ProductProvider extends BaseProvider
{
    /**
     *
     */
    private const PROVIDER = [
        'fruit' => [
            'Бананы',
            'Яблоки',
            'Ананасы',
            'Персики',
            'Ананас',
            'Манго',
        ],
        'vegetables' => [
            'Картошка',
            'Помидоры',
            'Огурцы',
            'Морковь',
            'Капуста',
            'Лук',
        ],
        'snacks' => [
            'Чипсы',
            'Шоколад',
            'Печенье',
            'Конфеты',
            'Мармелад',
            'Жевательная резинка',
        ],
    ];

    /**
     * @return string
     */
    public function fruit(): string
    {
        return self::randomElement(self::PROVIDER['fruit']);
    }

    /**
     * @return string
     */
    public function vegetable(): string
    {
        return self::randomElement(self::PROVIDER['vegetables']);
    }

    /**
     * @return string
     */
    public function snack(): string
    {
        return self::randomElement(self::PROVIDER['snacks']);
    }
}
