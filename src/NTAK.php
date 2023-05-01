<?php

namespace Kiralyta\Ntak;

use Carbon\Carbon;
use Kiralyta\Ntak\Enums\Category;
use Kiralyta\Ntak\Enums\DayType;

class NTAK
{
    /**
     * __construct
     *
     * @param  NTAKClient $client
     * @param  Carbon     $when
     * @return void
     */
    public function __construct(
        protected NTAKClient $client,
        protected Carbon $when
    ) { }

    /**
     * Lists the categories
     *
     * @return array
     */
    public static function categories(): array
    {
        return Category::values();
    }

    /**
     * Lists the subcategories of a category
     *
     * @param  Category $category
     * @return array
     */
    public static function subCategories(Category $category): array
    {
        return $category->subCategories();
    }

    /**
     * message
     *
     * @param  NTAKClient $client
     * @param  Carbon     $when
     * @return NTAK
     */
    public static function message(NTAKClient $client, Carbon $when): NTAK
    {
        return new static($client, $when);
    }

    /**
     * closeDay
     *
     * @param  Carbon  $start
     * @param  Carbon  $end
     * @param  DayType $dayType
     * @param  int     $tips
     * @return void
     */
    public function closeDay(
        ?Carbon $start,
        ?Carbon $end,
        DayType $dayType,
        int $tips = 0): void
    {
        $message = [
            'zarasiInformaciok' => [
                'targynap'           => $start->format('Y-m-d'),
                'targynapBesorolasa' => $dayType,
                'nyitasIdopontja'    => $dayType !== DayType::ADOTT_NAPON_ZARVA
                    ? $start->toRfc3339String()
                    : null,
                'zarasIdopontja'     => $dayType !== DayType::ADOTT_NAPON_ZARVA
                    ? $end->toRfc3339String()
                    : null,
                'osszesBorravalo'    => $tips,
            ],
        ];

        $this->client->message($message, $this->when);
    }
}
