<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            // General
            ['name' => '무료 Wi-Fi', 'category' => 'general', 'icon' => 'wifi'],
            ['name' => '무료 주차', 'category' => 'general', 'icon' => 'parking'],
            ['name' => '수영장', 'category' => 'general', 'icon' => 'pool'],
            ['name' => '피트니스 센터', 'category' => 'general', 'icon' => 'gym'],
            ['name' => '레스토랑', 'category' => 'general', 'icon' => 'restaurant'],
            ['name' => '조식 포함', 'category' => 'general', 'icon' => 'breakfast'],
            ['name' => '반려동물 동반', 'category' => 'general', 'icon' => 'pet'],
            ['name' => '금연', 'category' => 'general', 'icon' => 'no-smoking'],
            ['name' => '24시간 프런트', 'category' => 'general', 'icon' => 'reception'],
            ['name' => '공항 셔틀', 'category' => 'general', 'icon' => 'shuttle'],

            // Room
            ['name' => '에어컨', 'category' => 'room', 'icon' => 'ac'],
            ['name' => 'TV', 'category' => 'room', 'icon' => 'tv'],
            ['name' => '냉장고', 'category' => 'room', 'icon' => 'fridge'],
            ['name' => '전자레인지', 'category' => 'room', 'icon' => 'microwave'],
            ['name' => '커피머신', 'category' => 'room', 'icon' => 'coffee'],
            ['name' => '금고', 'category' => 'room', 'icon' => 'safe'],
            ['name' => '발코니', 'category' => 'room', 'icon' => 'balcony'],
            ['name' => '업무용 책상', 'category' => 'room', 'icon' => 'desk'],

            // Bathroom
            ['name' => '욕조', 'category' => 'bathroom', 'icon' => 'bathtub'],
            ['name' => '샤워실', 'category' => 'bathroom', 'icon' => 'shower'],
            ['name' => '헤어드라이어', 'category' => 'bathroom', 'icon' => 'hairdryer'],
            ['name' => '세면도구', 'category' => 'bathroom', 'icon' => 'toiletries'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
