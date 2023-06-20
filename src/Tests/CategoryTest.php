<?php

namespace QueSoft\Ntak\Tests;

use QueSoft\Ntak\Enums\NTAKCategory;
use QueSoft\Ntak\Enums\NTAKSubcategory;
use QueSoft\Ntak\NTAK;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * test_list_categories
     *
     * @return void
     */
    public function test_list_categories(): void
    {
        $this->assertEquals(
            NTAKCategory::cases(),
            NTAK::categories()
        );
    }

    /**
     * test_list_sub_categories
     *
     * @return void
     */
    public function test_list_sub_categories(): void
    {
        $randomCategory = $this->randomCategory();

        $this->assertEquals(
            $randomCategory->subcategories(),
            NTAK::subcategories($randomCategory)
        );

        $randomSubcategory = collect($randomCategory->subcategories())
            ->random();

        $this->assertSame(
            true,
            collect(NTAK::subcategories($randomCategory))->contains(  
                $randomSubcategory     
            )
        );
    }

    /**
     * randomCategory
     *
     * @return NTAKCategory
     */
    protected function randomCategory(): NTAKCategory
    {
        return NTAKCategory::random();
    }
}
