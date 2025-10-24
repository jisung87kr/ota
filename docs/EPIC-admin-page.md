# 에픽: 관리자 페이지 관리 시스템

**에픽 ID:** ADMIN-001
**상태:** 할 일
**우선순위:** 높음
**예상 작업 시간:** 20-30시간
**작성일:** 2025-10-25

---

## 개요

시스템 관리자가 OTA 플랫폼의 모든 측면(사용자, 숙소, 예약, 리뷰, 결제)을 감독하고 관리할 수 있는 포괄적인 관리자 관리 시스템을 구현합니다.

## 비즈니스 목표

- 관리자가 중앙 대시보드에서 전체 플랫폼을 모니터링하고 관리할 수 있도록 함
- 사용자 지원 문제 및 분쟁을 처리할 도구 제공
- 콘텐츠(리뷰, 숙소) 조정 가능
- 시스템 상태 및 수익 지표 추적
- 플랫폼 품질 및 사용자 안전 보장

## 현재 상태

### 존재하는 것
- ✅ Role enum을 사용한 기본 역할 시스템 (`app/Enums/Role.php`) - **마이그레이션 필요**
- ✅ CheckRole 미들웨어 작동 및 등록됨 - **교체 예정**
- ✅ 기본 관리자 대시보드 뷰 (정적 데이터가 있는 플레이스홀더)
- ✅ 관리자 라우트 보호 설정됨
- ✅ 테스트 관리자 사용자 (admin@ota.com / password123)
- ✅ 적절한 관계가 있는 모든 핵심 모델
- ✅ 참고 패턴으로서의 매니저 대시보드

### 누락된 것
- ❌ **Laravel-permission 패키지 설정 및 구성**
- ❌ **Role enum에서 laravel-permission으로 마이그레이션**
- ❌ **권한 기반 접근 제어 시스템**
- ❌ 대시보드의 실제 통계 및 데이터
- ❌ 사용자 관리 인터페이스 (역할/권한 할당 포함)
- ❌ 시스템 전체 숙소 감독
- ❌ 예약 관리 인터페이스
- ❌ 리뷰 조정 도구
- ❌ 결제 추적 및 보고
- ❌ 분석 및 보고서

### 마이그레이션 전략
현재의 간단한 Role enum 시스템을 **laravel-permission** (Spatie 패키지)로 마이그레이션하여 다음을 제공합니다:
- 유연한 역할 및 권한 관리
- 사용자당 여러 역할 (향후 필요 시)
- 세밀한 권한 제어
- 데이터베이스 기반 권한 (새로운 권한을 위한 코드 변경 불필요)
- 내장 미들웨어 및 블레이드 지시문
- 향후 기능을 위한 더 나은 확장성

---

## 사용자 스토리

### 1. 대시보드 및 개요

**관리자로서 나는 다음을 원합니다:**
- 실시간 통계 보기 (총 사용자, 숙소, 예약, 수익)
- 시간 경과에 따른 예약 추세를 보여주는 차트 보기
- 최근 활동 보기 (신규 사용자, 예약, 리뷰)
- 일반적인 작업에 대한 빠른 액세스
- 시스템 상태 지표 보기

**수락 기준:**
- 대시보드가 데이터베이스의 정확한 개수 표시
- 수익 계산에 완료된 모든 예약 포함
- 차트가 최근 7일, 30일, 12개월 데이터 표시
- 최근 활동이 카테고리별로 마지막 10개 항목 표시
- 대시보드가 2초 이내에 로드

---

### 2. 사용자 관리

**관리자로서 나는 다음을 원합니다:**
- 페이지네이션이 있는 모든 사용자 목록 보기
- 역할별 사용자 필터링 (고객, 매니저, 관리자)
- 이름, 이메일 또는 전화번호로 사용자 검색
- 상세한 사용자 프로필 보기 (예약, 소유한 숙소)
- 사용자 정보 수정 (이름, 이메일, 전화번호)
- 사용자 역할 변경
- 사용자 계정 정지/재활성화
- 사용자 활동 이력 보기

**수락 기준:**
- 사용자 목록이 페이지당 20명의 사용자 표시
- 이름, 이메일, 전화번호 필드에서 검색 작동
- 세 가지 역할 모두에 대해 역할 필터 작동
- 사용자 프로필이 완전한 예약 이력 표시
- 사용자 프로필이 숙소 표시 (매니저용)
- 역할 변경이 기록되고 즉시 반영됨
- 정지된 사용자는 로그인할 수 없음
- 모든 변경 사항이 감사 가능

**기술 노트:**
- users 테이블에 `is_active` 또는 `suspended_at` 필드 추가 필요
- 감사 추적을 위한 활동 로그 테이블 추가 고려

---

### 3. 숙소 관리

**관리자로서 나는 다음을 원합니다:**
- 시스템 전체의 모든 숙소 보기
- 상태, 도시, 유형, 매니저별로 숙소 필터링
- 이름 또는 주소로 숙소 검색
- 상세한 숙소 정보 보기
- 각 숙소를 소유한 매니저 보기
- 문제가 있는 숙소 비활성화
- 숙소 재활성화
- 숙소 통계 보기 (예약, 수익, 평점)

**수락 기준:**
- 숙소 목록이 페이지당 20개 항목 표시
- 여러 기준으로 동시에 필터링 가능
- 숙소 이름 및 주소에서 검색 작동
- 상세 페이지가 모든 객실, 편의시설, 이미지 표시
- 상세 페이지가 예약 이력 표시
- 상세 페이지가 리뷰 요약 표시
- 비활성화된 숙소는 공개 검색에 나타나지 않음
- 숙소가 비활성화되면 매니저에게 알림

**기술 노트:**
- accommodations 테이블에 `is_active` 또는 `deactivated_at` 필드 필요할 수 있음
- 승인 워크플로 고려 (대기 중, 승인됨, 거부됨)

---

### 4. 예약 관리

**관리자로서 나는 다음을 원합니다:**
- 시스템 전체의 모든 예약 보기
- 상태, 날짜 범위, 숙소별로 예약 필터링
- 확인 번호 또는 사용자로 예약 검색
- 상세한 예약 정보 보기
- 각 예약의 결제 상태 보기
- 예약 분쟁 처리
- 예약 취소 (환불 처리 포함)
- 예약 데이터를 CSV로 내보내기

**수락 기준:**
- 예약 목록이 페이지당 20개 항목 표시
- BookingStatus enum의 모든 상태로 필터링 가능
- 체크인 및 체크아웃 날짜에 대한 날짜 범위 필터 작동
- 상세 페이지가 사용자, 숙소, 객실, 결제 정보 표시
- 관련 결제 및 환불 이력 볼 수 있음
- 취소 시 자동으로 환불 프로세스 트리거
- 내보내기에 모든 예약 세부 정보가 CSV 형식으로 포함

**기술 노트:**
- 기존 BookingStatus enum 사용
- Payment 모델 관계 활용
- bookings에 admin_notes 필드 추가 고려

---

### 5. 리뷰 조정

**관리자로서 나는 다음을 원합니다:**
- 모든 리뷰 보기 (숨겨진 것 포함)
- 평점, 상태(보임/숨김)별로 리뷰 필터링
- 내용 또는 숙소 이름으로 리뷰 검색
- 부적절한 리뷰 숨기기
- 이전에 숨긴 리뷰 숨김 해제
- 조정 노트 추가
- 누가 언제 리뷰를 숨기거나 숨김 해제했는지 보기
- 리뷰에 대한 숙소의 응답 보기

**수락 기준:**
- 리뷰 목록이 페이지당 20개 리뷰 표시
- 숨겨진 리뷰가 명확하게 표시됨
- 숨겨진 것만 또는 보이는 것만 표시하도록 필터링 가능
- 리뷰를 숨기려면 이유 필요 (드롭다운: 부적절, 스팸, 가짜, 기타)
- 숨겨진 리뷰는 공개 페이지에 나타나지 않음
- Review 모델에 이미 `hide()` 및 `unhide()` 메서드 있음
- 조정 작업이 기록됨

**기술 노트:**
- Review 모델에 이미 `hidden_at` 필드 있음
- `hidden_by` 및 `hidden_reason` 필드 추가
- 기존 `hide()` 및 `unhide()` 메서드 사용

---

### 6. 결제 추적

**관리자로서 나는 다음을 원합니다:**
- 시스템 전체의 모든 결제 보기
- 상태, 날짜 범위별로 결제 필터링
- 각 거래의 결제 방법 보기
- 성공한 결제 추적
- 실패한 결제 추적
- 환불 추적
- 결제 게이트웨이 거래 ID 보기
- 결제-예약 관계 보기
- 총 수익 계산

**수락 기준:**
- 결제 목록이 페이지당 20개 결제 표시
- 결제 상태별로 필터링 가능 (완료됨, 대기 중, 실패, 환불됨)
- 날짜 범위 필터 작동
- 결제 방법 표시 (카드, 은행 송금 등)
- 수익 계산에서 환불된 결제 제외
- 관련 예약으로 클릭 가능
- 문제 해결을 위해 거래 ID 표시

**기술 노트:**
- 기존 Payment 모델 사용
- Payment 상태 enum이 이미 존재함
- 결제 게이트웨이 조정 추가 고려

---

### 7. 보고서 및 분석

**관리자로서 나는 다음을 원합니다:**
- 날짜 범위별 수익 보고서 보기
- 시간 경과에 따른 예약 추세 보기
- 가장 인기 있는 숙소 보기
- 사용자 증가 지표 보기
- 평균 예약 금액 보기
- 보고서를 CSV/Excel로 내보내기
- 숙소별 수익 보기
- 취소율 보기

**수락 기준:**
- 수익 보고서가 일별, 주별, 월별, 연별 보기 표시
- 차트가 명확하고 읽기 쉬움
- 사용자 정의 날짜 범위 선택 가능
- 상위 숙소가 예약 및 수익별로 순위 매겨짐
- 사용자 증가가 기간별 신규 가입 표시
- 보고서 내보내기 가능
- 데이터가 정확하고 데이터베이스와 일치

**기술 노트:**
- 집계를 위해 Laravel의 쿼리 빌더 사용
- 성능을 위해 보고서 데이터 캐싱 고려
- 시각화를 위해 Chart.js 또는 유사한 것 사용

---

## 기술 요구사항

### Laravel-Permission 설정

**패키지:** `spatie/laravel-permission` (Laravel 11용 v6.x)

**설치:**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**생성할 역할:**
- `customer` - 숙소를 예약하는 일반 사용자
- `accommodation_manager` - 숙소를 관리하는 사용자
- `admin` - 전체 액세스 권한이 있는 시스템 관리자

**생성할 권한:**

*사용자 관리:*
- `view users` - 사용자 목록 보기
- `view user details` - 개별 사용자 세부 정보 보기
- `edit users` - 사용자 정보 수정
- `change user roles` - 역할 할당/제거
- `suspend users` - 계정 정지/재활성화

*숙소 관리:*
- `view all accommodations` - 시스템 전체 숙소 보기
- `manage accommodations` - 숙소 활성화/비활성화
- `view accommodation stats` - 상세 통계 보기

*예약 관리:*
- `view all bookings` - 시스템 전체 예약 보기
- `manage bookings` - 예약 취소, 분쟁 처리
- `export bookings` - 예약 데이터 내보내기

*리뷰 관리:*
- `view all reviews` - 숨겨진 것을 포함한 모든 리뷰 보기
- `moderate reviews` - 리뷰 숨기기/숨김 해제

*결제 관리:*
- `view payments` - 모든 결제 보기
- `view revenue` - 수익 보고서 액세스

*보고:*
- `view reports` - 분석 및 보고서 액세스

**모델 업데이트:**

`app/Models/User.php` 업데이트:
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // 이전 역할 관련 메서드 제거 또는 사용 중단
    // public function hasRole($role) - 이제 HasRoles trait에서 제공
    // public function isCustomer() - 헬퍼로 유지 가능하지만 hasRole('customer') 사용
    // public function isAdmin() - 헬퍼로 유지 가능하지만 hasRole('admin') 사용
}
```

**미들웨어 업데이트:**

`CheckRole` 미들웨어를 laravel-permission의 내장 미들웨어로 교체:
- 역할 기반 액세스에 `role:admin` 사용
- 권한 기반 액세스에 `permission:view users` 사용
- 결합 확인에 `role_or_permission:admin|view users` 사용

**시더 업데이트:**

`database/seeders/RolePermissionSeeder.php` 생성:
```php
public function run()
{
    // 역할 생성
    $customer = Role::create(['name' => 'customer']);
    $manager = Role::create(['name' => 'accommodation_manager']);
    $admin = Role::create(['name' => 'admin']);

    // 권한 생성
    $permissions = [
        'view users', 'view user details', 'edit users',
        'change user roles', 'suspend users',
        'view all accommodations', 'manage accommodations',
        'view all bookings', 'manage bookings', 'export bookings',
        'view all reviews', 'moderate reviews',
        'view payments', 'view revenue', 'view reports'
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }

    // 관리자에게 모든 권한 할당
    $admin->givePermissionTo(Permission::all());

    // 매니저에게 제한된 권한 할당
    $manager->givePermissionTo([
        'view all accommodations', // 자신의 것만
        'view all bookings', // 자신의 숙소만
        'view all reviews', // 자신의 숙소만
    ]);
}
```

**데이터 마이그레이션:**

`database/seeders/MigrateExistingRolesSeeder.php` 생성:
```php
public function run()
{
    // Role enum에서 laravel-permission으로 기존 사용자 마이그레이션
    User::chunk(100, function ($users) {
        foreach ($users as $user) {
            switch ($user->role->value) {
                case 'customer':
                    $user->assignRole('customer');
                    break;
                case 'accommodation_manager':
                    $user->assignRole('accommodation_manager');
                    break;
                case 'admin':
                    $user->assignRole('admin');
                    break;
            }
        }
    });
}
```

### 새로운 컨트롤러
`app/Http/Controllers/Admin/`에 생성:

1. **AdminDashboardController**
   - `index()` - 실제 통계가 있는 대시보드

2. **UserManagementController**
   - `index()` - 사용자 목록
   - `show($id)` - 사용자 세부 정보
   - `edit($id)` - 수정 양식
   - `update($id)` - 사용자 업데이트
   - `toggleStatus($id)` - 정지/활성화
   - `assignRole($id)` - 사용자 역할 할당/변경
   - `syncPermissions($id)` - 사용자 정의 권한 동기화 (선택 사항)

3. **AccommodationManagementController**
   - `index()` - 모든 숙소 목록
   - `show($id)` - 숙소 세부 정보
   - `toggleStatus($id)` - 활성화/비활성화

4. **BookingManagementController**
   - `index()` - 모든 예약 목록
   - `show($id)` - 예약 세부 정보
   - `export()` - CSV로 내보내기

5. **ReviewManagementController**
   - `index()` - 모든 리뷰 목록
   - `hide($id)` - 리뷰 숨기기
   - `unhide($id)` - 리뷰 숨김 해제

6. **PaymentManagementController**
   - `index()` - 모든 결제 목록

7. **ReportController**
   - `revenue()` - 수익 분석
   - `bookings()` - 예약 분석
   - `users()` - 사용자 분석

### 새로운 뷰
`resources/views/admin/`에 생성:

```
admin/
├── dashboard.blade.php (기존 것 개선)
├── users/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
├── accommodations/
│   ├── index.blade.php
│   └── show.blade.php
├── bookings/
│   ├── index.blade.php
│   └── show.blade.php
├── reviews/
│   └── index.blade.php
├── payments/
│   └── index.blade.php
├── reports/
│   ├── revenue.blade.php
│   ├── bookings.blade.php
│   └── users.blade.php
└── partials/
    ├── navigation.blade.php
    └── stats-card.blade.php
```

### 새로운 라우트
`routes/web.php`에 추가:

```php
// laravel-permission 미들웨어 사용
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // 대시보드
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 사용자 관리 - 특정 권한과 함께
    Route::middleware('permission:view users')->group(function () {
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserManagementController::class, 'show'])->name('users.show')
            ->middleware('permission:view user details');
    });

    Route::middleware('permission:edit users')->group(function () {
        Route::get('users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });

    Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('users.toggle-status')
        ->middleware('permission:suspend users');

    Route::post('users/{user}/assign-role', [UserManagementController::class, 'assignRole'])
        ->name('users.assign-role')
        ->middleware('permission:change user roles');

    // 숙소 관리 - 권한과 함께
    Route::middleware('permission:view all accommodations')->group(function () {
        Route::get('accommodations', [AccommodationManagementController::class, 'index'])->name('accommodations.index');
        Route::get('accommodations/{accommodation}', [AccommodationManagementController::class, 'show'])->name('accommodations.show');
    });

    Route::post('accommodations/{accommodation}/toggle-status', [AccommodationManagementController::class, 'toggleStatus'])
        ->name('accommodations.toggle-status')
        ->middleware('permission:manage accommodations');

    // 예약 관리 - 권한과 함께
    Route::get('bookings', [BookingManagementController::class, 'index'])
        ->name('bookings.index')
        ->middleware('permission:view all bookings');

    Route::get('bookings/{booking}', [BookingManagementController::class, 'show'])
        ->name('bookings.show')
        ->middleware('permission:view all bookings');

    Route::get('bookings/export', [BookingManagementController::class, 'export'])
        ->name('bookings.export')
        ->middleware('permission:export bookings');

    // 리뷰 조정 - 권한과 함께
    Route::get('reviews', [ReviewManagementController::class, 'index'])
        ->name('reviews.index')
        ->middleware('permission:view all reviews');

    Route::post('reviews/{review}/hide', [ReviewManagementController::class, 'hide'])
        ->name('reviews.hide')
        ->middleware('permission:moderate reviews');

    Route::post('reviews/{review}/unhide', [ReviewManagementController::class, 'unhide'])
        ->name('reviews.unhide')
        ->middleware('permission:moderate reviews');

    // 결제 추적 - 권한과 함께
    Route::get('payments', [PaymentManagementController::class, 'index'])
        ->name('payments.index')
        ->middleware('permission:view payments');

    // 보고서 - 권한과 함께
    Route::middleware('permission:view reports')->group(function () {
        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
        Route::get('reports/users', [ReportController::class, 'users'])->name('reports.users');
    });
});
```

### 데이터베이스 마이그레이션

**참고:** Laravel-permission 패키지가 자체 테이블을 생성합니다:
- `roles` - 역할 정의 저장
- `permissions` - 권한 정의 저장
- `model_has_roles` - 사용자-역할 관계를 위한 피벗 테이블
- `model_has_permissions` - 사용자-권한 관계를 위한 피벗 테이블 (직접 권한)
- `role_has_permissions` - 역할-권한 관계를 위한 피벗 테이블

추가 사용자 정의 마이그레이션 생성:

1. **사용자 정지 추적 추가**
```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_active')->default(true)->after('role');
    $table->timestamp('suspended_at')->nullable()->after('is_active');
    $table->unsignedBigInteger('suspended_by')->nullable()->after('suspended_at');
});
```

2. **숙소 상태 추적 추가**
```php
Schema::table('accommodations', function (Blueprint $table) {
    $table->boolean('is_active')->default(true)->after('total_reviews');
    $table->timestamp('deactivated_at')->nullable()->after('is_active');
    $table->unsignedBigInteger('deactivated_by')->nullable()->after('deactivated_at');
});
```

3. **리뷰 조정 추적 추가**
```php
Schema::table('reviews', function (Blueprint $table) {
    $table->unsignedBigInteger('hidden_by')->nullable()->after('hidden_at');
    $table->string('hidden_reason')->nullable()->after('hidden_by');
});
```

4. **활동 로그 테이블 생성 (선택 사항이지만 권장됨)**
```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id'); // 작업을 수행한 사람
    $table->string('action'); // 예: 'user.suspended', 'review.hidden'
    $table->string('model_type'); // 예: 'App\Models\User'
    $table->unsignedBigInteger('model_id'); // 영향을 받은 레코드의 ID
    $table->json('changes')->nullable(); // 이전/이후 데이터
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### 정책 (권장 - Laravel-Permission과 함께 작동)

권한을 사용하는 Laravel 정책 생성:

1. `app/Policies/UserPolicy.php`
```php
public function viewAny(User $user)
{
    return $user->hasPermissionTo('view users');
}

public function view(User $user, User $model)
{
    return $user->hasPermissionTo('view user details');
}

public function update(User $user, User $model)
{
    return $user->hasPermissionTo('edit users');
}

public function suspend(User $user, User $model)
{
    return $user->hasPermissionTo('suspend users');
}
```

2. `app/Policies/AccommodationPolicy.php`
```php
public function viewAny(User $user)
{
    return $user->hasPermissionTo('view all accommodations');
}

public function deactivate(User $user, Accommodation $accommodation)
{
    return $user->hasPermissionTo('manage accommodations');
}
```

3. `app/Policies/BookingPolicy.php`
```php
public function viewAny(User $user)
{
    return $user->hasPermissionTo('view all bookings');
}

public function cancel(User $user, Booking $booking)
{
    return $user->hasPermissionTo('manage bookings');
}
```

4. `app/Policies/ReviewPolicy.php`
```php
public function viewAny(User $user)
{
    return $user->hasPermissionTo('view all reviews');
}

public function moderate(User $user, Review $review)
{
    return $user->hasPermissionTo('moderate reviews');
}
```

---

## 구현 단계

### 단계 0: Laravel-Permission 설정 (2-3시간)
- [ ] Composer를 통해 laravel-permission 패키지 설치
- [ ] 패키지 구성 및 마이그레이션 게시
- [ ] laravel-permission 마이그레이션 실행
- [ ] User 모델을 업데이트하여 HasRoles trait 사용
- [ ] 역할 및 권한과 함께 RolePermissionSeeder 생성
- [ ] 현재 사용자를 마이그레이션하기 위한 MigrateExistingRolesSeeder 생성
- [ ] 두 시더 모두 실행
- [ ] 역할/권한 할당이 작동하는지 테스트
- [ ] 라우트에서 기존 미들웨어 사용 업데이트
- [ ] 이전 CheckRole 미들웨어 제거 또는 사용 중단 (역호환성을 위해 일시적으로 유지)
- [ ] 기존 인증 흐름이 여전히 작동하는지 테스트
- [ ] 기존 매니저 및 고객 라우트를 새 미들웨어를 사용하도록 업데이트

### 단계 1: 기초 (4-6시간)
- [ ] 데이터베이스 마이그레이션 생성
- [ ] 마이그레이션 실행
- [ ] 컨트롤러 디렉터리 구조 생성
- [ ] 뷰 디렉터리 구조 생성
- [ ] 관리자 네비게이션 설정
- [ ] 재사용 가능한 컴포넌트 생성 (통계 카드, 테이블)

### 단계 2: 대시보드 개선 (2-3시간)
- [ ] AdminDashboardController 생성
- [ ] 실제 통계 계산
- [ ] 차트 구현 (Chart.js)
- [ ] 최근 활동 피드 추가
- [ ] 대시보드 뷰 업데이트

### 단계 3: 사용자 관리 (4-6시간)
- [ ] 권한 확인과 함께 UserManagementController 생성
- [ ] 필터가 있는 사용자 목록 뷰 구축 (역할 필터 포함)
- [ ] 할당된 역할 및 권한을 표시하는 사용자 세부 정보 뷰 구축
- [ ] 역할 할당 드롭다운이 있는 사용자 수정 양식 구축
- [ ] 정지/활성화 기능 구현
- [ ] 역할 할당/변경 기능 구현
- [ ] 권한 확인을 위한 블레이드 지시문 추가 (@can, @role)
- [ ] 검색 기능 추가

### 단계 4: 숙소 관리 (3-4시간)
- [ ] AccommodationManagementController 생성
- [ ] 필터가 있는 숙소 목록 뷰 구축
- [ ] 숙소 세부 정보 뷰 구축
- [ ] 활성화/비활성화 기능 구현

### 단계 5: 예약 관리 (3-4시간)
- [ ] BookingManagementController 생성
- [ ] 필터가 있는 예약 목록 뷰 구축
- [ ] 예약 세부 정보 뷰 구축
- [ ] CSV 내보내기 기능 구현

### 단계 6: 리뷰 조정 (2-3시간)
- [ ] ReviewManagementController 생성
- [ ] 필터가 있는 리뷰 목록 뷰 구축
- [ ] 숨기기/숨김 해제 기능 구현
- [ ] 조정 이유 추적 추가

### 단계 7: 결제 추적 (2-3시간)
- [ ] PaymentManagementController 생성
- [ ] 필터가 있는 결제 목록 뷰 구축
- [ ] 수익 계산 추가

### 단계 8: 보고서 및 분석 (4-6시간)
- [ ] ReportController 생성
- [ ] 수익 보고서 뷰 구축
- [ ] 예약 분석 뷰 구축
- [ ] 사용자 분석 뷰 구축
- [ ] 차트 시각화 구현
- [ ] 보고서용 CSV 내보내기 추가

### 단계 9: 테스트 및 다듬기 (2-4시간)
- [ ] 모든 CRUD 작업 테스트
- [ ] 권한 및 미들웨어 테스트
- [ ] 다양한 데이터 세트로 테스트
- [ ] 반응형 디자인 확인
- [ ] 로딩 상태 추가
- [ ] 오류 처리 추가
- [ ] 문서 작성

---

## 디자인 고려사항

### 네비게이션
- 고정 사이드바 또는 상단 네비게이션
- 현재 섹션 강조
- 일반 작업에 대한 빠른 링크
- 로그아웃이 있는 사용자 드롭다운

### UI 컴포넌트
- 기존 Tailwind CSS 스타일과 일관성 유지
- 재사용 가능한 통계 카드
- 페이지네이션이 있는 정렬 가능한 테이블
- 필터 패널
- 확인을 위한 모달 대화상자
- 성공/오류를 위한 토스트 알림

### 반응성
- 모바일 친화적인 테이블 (가로 스크롤 또는 카드)
- 반응형 네비게이션 (햄버거 메뉴)
- 터치 친화적인 버튼 및 링크

### 성능
- 모든 목록 페이지네이션 (페이지당 20개 항목)
- N+1 쿼리를 피하기 위해 관계 즉시 로드
- 대시보드 통계 캐시 (5분 캐시)
- 필터링된 컬럼에 데이터베이스 인덱스 사용

### 보안
- 모든 라우트에서 역할 미들웨어 재확인
- 모든 양식에 CSRF 보호
- 모든 입력 검증
- 사용자 생성 콘텐츠 삭제
- 감사 추적을 위해 모든 관리자 작업 기록

---

## 의존성

- ✅ Laravel 11.x
- ✅ Tailwind CSS
- ✅ Alpine.js (상호작용이 필요한 경우)
- 📦 **spatie/laravel-permission v6.x** - 역할 및 권한 관리 (필수)
- 📦 Chart.js (차트용 - 추가 필요)
- 📦 Laravel Excel (CSV 내보내기용 - 선택 사항)

---

## 테스트 전략

### 수동 테스트
1. 테스트 관리자 사용자 생성
2. 테스트 데이터 생성 (사용자, 숙소, 예약, 리뷰)
3. 각 기능을 체계적으로 테스트
4. 엣지 케이스로 테스트 (빈 목록, 잘못된 입력)
5. 권한 테스트 (관리자가 아닌 사용자로 액세스 시도)

### 자동화된 테스트 (향후)
- 각 컨트롤러 작업에 대한 기능 테스트
- 역할 및 권한 미들웨어 보호 테스트
- 권한과 함께 정책 권한 부여 테스트
- 데이터베이스 쿼리가 올바른 데이터를 반환하는지 테스트
- 역할 할당 및 권한 동기화 테스트
- 제거된 권한이 액세스를 거부하는지 테스트

---

## 성공 지표

- 관리자 대시보드가 2초 이내에 로드됨
- 모든 목록이 페이지네이션되고 필터링 가능
- 모든 CRUD 작업이 올바르게 작동
- 정지된 사용자는 로그인할 수 없음
- 숨겨진 리뷰가 공개적으로 나타나지 않음
- 비활성화된 숙소가 검색에 나타나지 않음
- 수익 계산이 정확함
- N+1 쿼리 문제 없음
- 모바일 기기에서 반응형

---

## 위험 및 완화

| 위험 | 영향 | 완화 |
|------|--------|------------|
| 대용량 데이터 세트의 성능 문제 | 높음 | 페이지네이션, 캐싱, 데이터베이스 인덱스 구현 |
| 우발적인 데이터 삭제 | 높음 | 확인 대화상자 추가, 소프트 삭제 구현 |
| 무단 액세스 | 높음 | 미들웨어, 정책 및 권한 부여 확인 사용 |
| 성능에 영향을 미치는 복잡한 쿼리 | 중간 | 쿼리 최적화, 즉시 로드 사용, 인덱스 추가 |
| 모바일에서 반응하지 않는 UI | 중간 | 여러 기기에서 테스트, 반응형 디자인 사용 |

---

## 향후 개선사항

- 감사 추적을 위한 활동 로그 뷰어
- 관리자 작업에 대한 이메일 알림
- 대량 작업 (사용자 대량 정지 등)
- 고급 필터링 및 저장된 필터
- 사용자 정의 날짜 범위 선택기
- WebSocket을 사용한 실시간 통계
- 관리자 사용자를 위한 2단계 인증
- 관리자 작업을 위한 API 엔드포인트
- 고급 분석 및 예측
- 자동화된 보고서 예약

---

## 관련 에픽

- 없음 (첫 번째 관리자 기능)

## 참고자료

- 기존 매니저 대시보드: `app/Http/Controllers/Manager/ManagerDashboardController.php`
- 현재 역할 시스템: `app/Enums/Role.php` (사용 중단 예정)
- 현재 미들웨어: `app/Http/Middleware/CheckRole.php` (교체 예정)
- Laravel-Permission 문서: https://spatie.be/docs/laravel-permission/v6/introduction
- Laravel-Permission GitHub: https://github.com/spatie/laravel-permission
- 모델: `app/Models/`

---

## 참고사항

- 매니저 대시보드의 기존 코드 패턴 따르기
- 현재 UI/UX와 일관성 유지
- 모든 작업이 감사 가능하도록 보장
- 처음부터 활동 로깅 추가 고려
- 실제 데이터 볼륨으로 테스트

### Laravel-Permission을 사용하는 이유?

**이점:**
1. **업계 표준** - 널리 사용되고 잘 유지관리되는 패키지
2. **유연함** - 코드 변경 없이 새로운 권한 추가 용이
3. **데이터베이스 기반** - 권한이 데이터베이스에 저장되어 UI를 통해 관리 가능
4. **블레이드 지시문** - 뷰를 위한 내장 `@role`, `@can` 지시문
5. **여러 역할** - 사용자가 여러 역할을 가질 수 있음 (미래 대비)
6. **직접 권한** - 역할 없이 사용자에게 직접 권한 할당 가능
7. **미들웨어** - 라우트 보호를 위한 내장 미들웨어
8. **캐싱** - 성능을 위한 자동 권한 캐싱
9. **정책 통합** - Laravel 정책과 원활하게 작동

**마이그레이션 경로:**
1. laravel-permission 설치
2. 기존 Role enum 값을 roles 테이블로 마이그레이션
3. 현재 역할을 기반으로 기존 사용자에게 역할 할당
4. `CheckRole`에서 `role:admin`으로 미들웨어 점진적으로 업데이트
5. 역호환성을 위해 이전 Role enum 일시적으로 유지
6. 완전히 마이그레이션되면 Role enum 제거

**향후 확장성:**
- 새로운 권한 추가 용이 (예: "프로모션 관리", "분석 보기")
- 사용자 정의 역할 생성 가능 (예: "지원 상담원", "콘텐츠 관리자")
- 관리자를 위한 권한 관리 UI 구축 가능
- 기능별 세밀한 액세스 제어 구현 가능
