<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Test Users
        $this->createUsers();

        // Create Accommodations with Rooms
        $this->createAccommodations();

        // Create Bookings, Payments, and Reviews
        $this->createBookingsAndReviews();

        $this->command->info('Test data created successfully!');
    }

    private function createUsers(): void
    {
        $this->command->info('Creating test users...');

        // Admin User
        User::create([
            'name' => '관리자',
            'email' => 'admin@ota.com',
            'password' => Hash::make('password123'),
            'phone' => '010-0000-0000',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Accommodation Managers
        User::create([
            'name' => '서울호텔 매니저',
            'email' => 'manager1@ota.com',
            'password' => Hash::make('password123'),
            'phone' => '010-1111-1111',
            'role' => 'accommodation_manager',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => '부산리조트 매니저',
            'email' => 'manager2@ota.com',
            'password' => Hash::make('password123'),
            'phone' => '010-2222-2222',
            'role' => 'accommodation_manager',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => '제주게스트하우스 매니저',
            'email' => 'manager3@ota.com',
            'password' => Hash::make('password123'),
            'phone' => '010-3333-3333',
            'role' => 'accommodation_manager',
            'email_verified_at' => now(),
        ]);

        // Customer Users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "고객 {$i}",
                'email' => "customer{$i}@example.com",
                'password' => Hash::make('password123'),
                'phone' => '010-' . str_pad($i * 1000, 4, '0', STR_PAD_LEFT) . '-' . str_pad($i * 100, 4, '0', STR_PAD_LEFT),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Users created: 1 admin, 3 managers, 10 customers');
    }

    private function createAccommodations(): void
    {
        $this->command->info('Creating accommodations...');

        $amenities = Amenity::all();
        $managers = User::where('role', 'accommodation_manager')->get();

        // Seoul Accommodations
        $seoulHotel = Accommodation::create([
            'user_id' => $managers[0]->id,
            'name' => '서울 그랜드 호텔',
            'category' => 'hotel',
            'description' => '서울 중심부에 위치한 5성급 비즈니스 호텔입니다. 명동, 남산타워와 가까우며 최고급 서비스를 제공합니다.',
            'address' => '서울특별시 중구 명동길 123',
            'city' => '서울',
            'phone' => '02-1234-5678',
            'email' => 'info@seoulgrand.com',
            'is_active' => true,
            'average_rating' => 4.5,
            'total_reviews' => 0,
        ]);
        $seoulHotel->amenities()->attach($amenities->random(8)->pluck('id'));

        $seoulGuesthouse = Accommodation::create([
            'user_id' => $managers[0]->id,
            'name' => '홍대 스테이 게스트하우스',
            'category' => 'guesthouse',
            'description' => '홍대 중심부의 깔끔하고 현대적인 게스트하우스입니다. 젊고 활기찬 분위기를 즐기실 수 있습니다.',
            'address' => '서울특별시 마포구 홍익로 45',
            'city' => '서울',
            'phone' => '02-2222-3333',
            'email' => 'info@hongdaestay.com',
            'is_active' => true,
            'average_rating' => 4.2,
            'total_reviews' => 0,
        ]);
        $seoulGuesthouse->amenities()->attach($amenities->random(6)->pluck('id'));

        // Busan Accommodations
        $busanResort = Accommodation::create([
            'user_id' => $managers[1]->id,
            'name' => '해운대 오션 리조트',
            'category' => 'resort',
            'description' => '해운대 해변이 한눈에 보이는 최고급 리조트입니다. 가족 단위 여행객에게 완벽한 휴양지입니다.',
            'address' => '부산광역시 해운대구 해운대해변로 100',
            'city' => '부산',
            'phone' => '051-1111-2222',
            'email' => 'info@haeundaeresort.com',
            'is_active' => true,
            'average_rating' => 4.7,
            'total_reviews' => 0,
        ]);
        $busanResort->amenities()->attach($amenities->random(10)->pluck('id'));

        $busanMotel = Accommodation::create([
            'user_id' => $managers[1]->id,
            'name' => '광안리 비치 모텔',
            'category' => 'motel',
            'description' => '광안대교가 보이는 깔끔한 모텔입니다. 가성비가 좋고 바다와 가까워 인기가 많습니다.',
            'address' => '부산광역시 수영구 광안해변로 200',
            'city' => '부산',
            'phone' => '051-3333-4444',
            'email' => 'info@gwanganmotel.com',
            'is_active' => true,
            'average_rating' => 4.0,
            'total_reviews' => 0,
        ]);
        $busanMotel->amenities()->attach($amenities->random(5)->pluck('id'));

        // Jeju Accommodations
        $jejuPension = Accommodation::create([
            'user_id' => $managers[2]->id,
            'name' => '제주 힐링 펜션',
            'category' => 'pension',
            'description' => '한라산이 보이는 조용하고 평화로운 펜션입니다. 자연 속에서 힐링하기 좋습니다.',
            'address' => '제주특별자치도 제주시 한림읍 협재리 123',
            'city' => '제주',
            'phone' => '064-1234-5678',
            'email' => 'info@jejuhealing.com',
            'is_active' => true,
            'average_rating' => 4.6,
            'total_reviews' => 0,
        ]);
        $jejuPension->amenities()->attach($amenities->random(7)->pluck('id'));

        // Create Rooms for each Accommodation
        $this->createRooms($seoulHotel, 'hotel');
        $this->createRooms($seoulGuesthouse, 'guesthouse');
        $this->createRooms($busanResort, 'resort');
        $this->createRooms($busanMotel, 'motel');
        $this->createRooms($jejuPension, 'pension');

        $this->command->info('Accommodations created: 6 with rooms');
    }

    private function createRooms(Accommodation $accommodation, string $type): void
    {
        $amenities = Amenity::all();

        switch ($type) {
            case 'hotel':
                // Standard Room
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '스탠다드 더블룸',
                    'description' => '편안한 더블 침대가 있는 기본 객실입니다.',
                    'size' => 25,
                    'max_occupancy' => 2,
                    'bed_type' => 'double',
                    'bed_count' => 1,
                    'base_price' => 120000,
                    'total_rooms' => 10,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(5)->pluck('id'));

                // Deluxe Room
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '디럭스 트윈룸',
                    'description' => '넓고 쾌적한 트윈 침대 객실입니다.',
                    'size' => 35,
                    'max_occupancy' => 3,
                    'bed_type' => 'single',
                    'bed_count' => 2,
                    'base_price' => 180000,
                    'total_rooms' => 8,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(6)->pluck('id'));

                // Suite
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '이그제큐티브 스위트',
                    'description' => '거실과 침실이 분리된 럭셔리 스위트룸입니다.',
                    'size' => 60,
                    'max_occupancy' => 4,
                    'bed_type' => 'king',
                    'bed_count' => 1,
                    'base_price' => 350000,
                    'total_rooms' => 5,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(8)->pluck('id'));
                break;

            case 'resort':
                // Ocean View
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '오션뷰 패밀리룸',
                    'description' => '바다가 한눈에 보이는 넓은 패밀리룸입니다.',
                    'size' => 50,
                    'max_occupancy' => 4,
                    'bed_type' => 'queen',
                    'bed_count' => 2,
                    'base_price' => 250000,
                    'total_rooms' => 15,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(7)->pluck('id'));

                // Premium Suite
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '프리미엄 스위트',
                    'description' => '발코니와 개인 자쿠지가 있는 최고급 객실입니다.',
                    'size' => 80,
                    'max_occupancy' => 6,
                    'bed_type' => 'king',
                    'bed_count' => 2,
                    'base_price' => 450000,
                    'total_rooms' => 10,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(9)->pluck('id'));
                break;

            case 'guesthouse':
                // Dormitory
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '4인 도미토리',
                    'description' => '배낭여행자를 위한 깔끔한 도미토리입니다.',
                    'size' => 20,
                    'max_occupancy' => 4,
                    'bed_type' => 'single',
                    'bed_count' => 4,
                    'base_price' => 25000,
                    'total_rooms' => 4,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(4)->pluck('id'));

                // Private Room
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '프라이빗 더블룸',
                    'description' => '개인 욕실이 있는 프라이빗 객실입니다.',
                    'size' => 15,
                    'max_occupancy' => 2,
                    'bed_type' => 'double',
                    'bed_count' => 1,
                    'base_price' => 60000,
                    'total_rooms' => 6,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(5)->pluck('id'));
                break;

            case 'motel':
                // Standard
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '스탠다드룸',
                    'description' => '깔끔한 기본 객실입니다.',
                    'size' => 20,
                    'max_occupancy' => 2,
                    'bed_type' => 'queen',
                    'bed_count' => 1,
                    'base_price' => 70000,
                    'total_rooms' => 20,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(4)->pluck('id'));

                // Deluxe
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '디럭스룸',
                    'description' => '넓고 편안한 디럭스 객실입니다.',
                    'size' => 28,
                    'max_occupancy' => 3,
                    'bed_type' => 'queen',
                    'bed_count' => 1,
                    'base_price' => 100000,
                    'total_rooms' => 15,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(5)->pluck('id'));
                break;

            case 'pension':
                // Pension Room
                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '복층 펜션 A동',
                    'description' => '독채 복층 구조의 넓은 펜션입니다.',
                    'size' => 45,
                    'max_occupancy' => 6,
                    'bed_type' => 'queen',
                    'bed_count' => 2,
                    'base_price' => 180000,
                    'total_rooms' => 5,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(6)->pluck('id'));

                $room = Room::create([
                    'accommodation_id' => $accommodation->id,
                    'name' => '단층 펜션 B동',
                    'description' => '아늑한 단층 펜션입니다.',
                    'size' => 30,
                    'max_occupancy' => 4,
                    'bed_type' => 'double',
                    'bed_count' => 2,
                    'base_price' => 130000,
                    'total_rooms' => 8,
                    'is_active' => true,
                ]);
                $room->amenities()->attach($amenities->random(5)->pluck('id'));
                break;
        }
    }

    private function createBookingsAndReviews(): void
    {
        $this->command->info('Creating bookings, payments, and reviews...');

        $customers = User::where('role', 'customer')->get();
        $accommodations = Accommodation::with('activeRooms')->get();

        $bookingCount = 0;
        $reviewCount = 0;

        foreach ($accommodations as $accommodation) {
            foreach ($accommodation->activeRooms as $room) {
                // Create 3-5 bookings per room
                $numBookings = rand(3, 5);

                for ($i = 0; $i < $numBookings; $i++) {
                    $customer = $customers->random();
                    $daysAgo = rand(1, 90);
                    $nights = rand(1, 5);

                    $checkIn = now()->subDays($daysAgo);
                    $checkOut = $checkIn->copy()->addDays($nights);

                    $totalPrice = $room->base_price * $nights;

                    // Determine booking status based on dates
                    if ($checkOut->isPast()) {
                        $status = BookingStatus::CHECKED_OUT;
                    } elseif ($checkIn->isPast()) {
                        $status = BookingStatus::CHECKED_IN;
                    } elseif ($daysAgo < 30) {
                        $status = rand(0, 10) > 2 ? BookingStatus::CONFIRMED : BookingStatus::PENDING;
                    } else {
                        $status = rand(0, 10) > 1 ? BookingStatus::CONFIRMED : BookingStatus::CANCELLED;
                    }

                    $booking = Booking::create([
                        'user_id' => $customer->id,
                        'accommodation_id' => $accommodation->id,
                        'room_id' => $room->id,
                        'booking_number' => 'BK' . now()->format('Ymd') . str_pad($bookingCount + 1, 6, '0', STR_PAD_LEFT),
                        'check_in_date' => $checkIn->format('Y-m-d'),
                        'check_out_date' => $checkOut->format('Y-m-d'),
                        'nights' => $nights,
                        'guests' => rand(1, $room->max_occupancy),
                        'guest_name' => $customer->name,
                        'guest_phone' => $customer->phone,
                        'guest_email' => $customer->email,
                        'room_price' => $room->base_price,
                        'total_price' => $totalPrice,
                        'status' => $status,
                        'special_requests' => rand(0, 10) > 7 ? '늦은 체크인 부탁드립니다.' : null,
                        'created_at' => $checkIn->copy()->subDays(rand(1, 7)),
                    ]);

                    $bookingCount++;

                    // Create Payment for confirmed/checked-in/checked-out bookings
                    if (in_array($status, [BookingStatus::CONFIRMED, BookingStatus::CHECKED_IN, BookingStatus::CHECKED_OUT])) {
                        $payment = Payment::create([
                            'booking_id' => $booking->id,
                            'merchant_uid' => 'merchant_' . time() . '_' . $booking->id,
                            'imp_uid' => 'imp_' . time() . '_' . $booking->id,
                            'amount' => $totalPrice,
                            'status' => 'paid',
                            'paid_at' => $booking->created_at->addMinutes(rand(5, 30)),
                            'payment_method' => 'card',
                            'card_name' => ['신한카드', '삼성카드', '현대카드', '국민카드'][rand(0, 3)],
                            'card_number' => '****-****-****-' . rand(1000, 9999),
                        ]);

                        $booking->update(['paid_amount' => $totalPrice]);
                    }

                    // Create cancelled bookings with refunds
                    if ($status === BookingStatus::CANCELLED) {
                        $refundAmount = $totalPrice * 0.5; // 50% refund
                        $booking->update([
                            'cancelled_at' => $checkIn->copy()->subDays(rand(1, 5)),
                            'cancellation_reason' => '일정 변경',
                            'refund_amount' => $refundAmount,
                        ]);
                    }

                    // Create Reviews for checked-out bookings
                    if ($status === BookingStatus::CHECKED_OUT && rand(0, 10) > 3) {
                        $rating = rand(3, 5);

                        $review = Review::create([
                            'booking_id' => $booking->id,
                            'user_id' => $customer->id,
                            'accommodation_id' => $accommodation->id,
                            'rating' => $rating,
                            'title' => $this->getReviewTitle($rating),
                            'content' => $this->getReviewContent($rating),
                            'cleanliness_rating' => rand(3, 5),
                            'service_rating' => rand(3, 5),
                            'location_rating' => rand(3, 5),
                            'value_rating' => rand(3, 5),
                            'helpful_count' => rand(0, 20),
                            'is_hidden' => false,
                            'created_at' => $checkOut->copy()->addDays(rand(1, 7)),
                        ]);

                        // Add accommodation response to some reviews
                        if (rand(0, 10) > 5) {
                            $review->update([
                                'response' => '소중한 리뷰 감사합니다. 더 나은 서비스로 보답하겠습니다.',
                                'responded_at' => $review->created_at->addDays(rand(1, 3)),
                            ]);
                        }

                        $reviewCount++;

                        // Update accommodation statistics
                        $accommodation->increment('total_reviews');
                        $accommodation->update([
                            'average_rating' => $accommodation->reviews()->avg('rating'),
                        ]);
                    }
                }
            }
        }

        $this->command->info("Bookings created: {$bookingCount}");
        $this->command->info("Reviews created: {$reviewCount}");
    }

    private function getReviewTitle(int $rating): string
    {
        $titles = [
            5 => ['완벽한 숙소!', '최고의 선택이었어요', '정말 만족스러웠습니다', '다시 방문하고 싶어요'],
            4 => ['좋은 숙소였습니다', '전반적으로 만족해요', '괜찮은 선택', '추천합니다'],
            3 => ['보통이에요', '나쁘지 않았어요', '기대했던 것과 비슷해요', '무난한 숙소'],
        ];

        return $titles[$rating][array_rand($titles[$rating])];
    }

    private function getReviewContent(int $rating): string
    {
        $contents = [
            5 => [
                '위치도 좋고 시설도 깔끔해서 정말 만족스러웠습니다. 직원분들도 친절하시고 다음에도 꼭 다시 방문하고 싶습니다.',
                '가족들과 함께 방문했는데 모두 만족했습니다. 특히 조식이 훌륭했고 객실도 넓고 쾌적했습니다.',
                '기대 이상으로 좋았습니다. 청결도도 완벽하고 편의시설도 잘 갖춰져 있었어요. 강력 추천합니다!',
            ],
            4 => [
                '전반적으로 좋았습니다. 위치가 약간 아쉬웠지만 그 외에는 만족스러웠어요.',
                '깔끔하고 조용해서 좋았습니다. 가성비도 괜찮은 편이에요. 다만 주차가 조금 불편했습니다.',
                '친절한 서비스와 깨끗한 시설이 인상적이었습니다. 다음에도 이용할 것 같아요.',
            ],
            3 => [
                '가격 대비 무난한 수준입니다. 특별히 나쁜 점은 없었지만 크게 인상적이지도 않았어요.',
                '시설이 조금 오래된 느낌이지만 청결도는 괜찮았습니다. 위치는 좋은 편이에요.',
                '보통 수준의 숙소입니다. 하룻밤 묵기에는 괜찮았어요.',
            ],
        ];

        return $contents[$rating][array_rand($contents[$rating])];
    }
}
