# OTA Service - 온라인 여행사 예약 플랫폼

아고다(Agoda)와 같은 OTA(Online Travel Agency) 서비스를 구현한 웹 애플리케이션입니다. Laravel 백엔드와 Blade 템플릿, Vue.js 컴포넌트를 활용하여 구축되었습니다.

## 🚀 주요 기능

### 사용자 기능
- 🔍 **호텔 검색 및 필터링**: 목적지, 날짜, 투숙객 수로 검색
- 🏨 **호텔 상세 정보**: 객실, 편의시설, 리뷰 확인
- 📅 **실시간 예약**: 날짜별 객실 가용성 확인 및 예약
- 💳 **예약 관리**: 예약 내역 조회, 수정, 취소
- ⭐ **리뷰 시스템**: 숙박 후기 작성 및 평점 부여

### 호텔 관리자 기능
- 🏢 **호텔 등록 및 관리**: 호텔 정보, 이미지, 편의시설 관리
- 🛏️ **객실 관리**: 객실 유형, 가격, 재고 관리
- 📊 **예약 관리**: 예약 상태 업데이트 (확인, 체크인, 체크아웃)
- 📈 **대시보드**: 예약 통계 및 수익 분석

### 관리자 기능
- 👥 **사용자 관리**: 회원 및 호텔 파트너 관리
- 🔒 **권한 관리**: 역할별 접근 제어
- 📝 **리뷰 관리**: 리뷰 승인 및 관리

## 🛠️ 기술 스택

### 백엔드
- **Laravel 11**: PHP 웹 프레임워크
- **MySQL**: 주요 데이터베이스 (호텔, 예약, 사용자)
- **Redis**: 캐싱 및 세션 관리
- **MongoDB**: 로그 및 분석 데이터 저장
- **Laravel Sanctum**: API 인증

### 프론트엔드
- **Blade Templates**: Laravel 템플릿 엔진
- **Vue.js 3**: 인터랙티브 컴포넌트
- **TailwindCSS**: 유틸리티 기반 CSS 프레임워크
- **Vite**: 빠른 빌드 도구
- **Axios**: HTTP 클라이언트

### 인프라
- **Docker**: 컨테이너화
- **Docker Compose**: 멀티 컨테이너 오케스트레이션
- **Nginx**: 웹 서버

## 📋 사전 요구사항

- Docker & Docker Compose
- Git

## 🚀 설치 방법

### 1. 저장소 클론

\`\`\`bash
git clone <repository-url>
cd ota
\`\`\`

### 2. 환경 설정

\`\`\`bash
# .env 파일 생성
cd backend
cp .env.example .env
\`\`\`

### 3. Docker 컨테이너 시작

\`\`\`bash
# 루트 디렉토리로 이동
cd ..

# Docker 컨테이너 빌드 및 시작
docker-compose up -d
\`\`\`

### 4. Laravel 설정

\`\`\`bash
# PHP 컨테이너에 접속
docker exec -it ota_php bash

# Composer 의존성 설치
composer install

# 애플리케이션 키 생성
php artisan key:generate

# 데이터베이스 마이그레이션
php artisan migrate

# (선택사항) 샘플 데이터 생성
php artisan db:seed

# 컨테이너 종료
exit
\`\`\`

### 5. 프론트엔드 에셋 빌드

\`\`\`bash
# PHP 컨테이너에 접속
docker exec -it ota_php bash

# NPM 의존성 설치
npm install

# 개발 모드로 실행 (Hot reload)
npm run dev

# 또는 프로덕션 빌드
npm run build
\`\`\`

### 6. 애플리케이션 접속

- 웹사이트: http://localhost
- MySQL: localhost:3306
- Redis: localhost:6379
- MongoDB: localhost:27017

## 📁 프로젝트 구조

\`\`\`
ota/
├── docker/                      # Docker 설정 파일
│   ├── nginx/                   # Nginx 설정
│   ├── php/                     # PHP-FPM Dockerfile
│   ├── node/                    # Node.js Dockerfile
│   └── mysql/                   # MySQL 설정
├── backend/                     # Laravel 애플리케이션
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/        # API 컨트롤러
│   │   │   │   └── Web/        # 웹 컨트롤러
│   │   │   └── Middleware/
│   │   ├── Models/              # Eloquent 모델
│   │   └── Services/            # 비즈니스 로직
│   ├── database/
│   │   ├── migrations/          # 데이터베이스 마이그레이션
│   │   └── seeders/             # 시드 데이터
│   ├── resources/
│   │   ├── views/               # Blade 템플릿
│   │   │   ├── layouts/
│   │   │   ├── hotels/
│   │   │   ├── bookings/
│   │   │   └── auth/
│   │   ├── js/
│   │   │   ├── components/      # Vue 컴포넌트
│   │   │   └── app.js
│   │   └── css/
│   │       └── app.css          # TailwindCSS
│   └── routes/
│       ├── web.php              # 웹 라우트
│       └── api.php              # API 라우트
└── docker-compose.yml

\`\`\`

## 🗄️ 데이터베이스 스키마

### 주요 테이블

- **users**: 사용자 정보 (고객, 호텔 소유자, 관리자)
- **hotels**: 호텔 정보
- **rooms**: 객실 정보
- **bookings**: 예약 정보
- **payments**: 결제 정보
- **reviews**: 리뷰 및 평점

## 🔌 API 엔드포인트

### 인증
- `POST /api/register` - 회원가입
- `POST /api/login` - 로그인
- `POST /api/logout` - 로그아웃
- `GET /api/me` - 현재 사용자 정보

### 호텔
- `GET /api/hotels` - 호텔 목록
- `GET /api/hotels/search` - 호텔 검색
- `GET /api/hotels/{id}` - 호텔 상세
- `POST /api/hotels` - 호텔 등록 (인증 필요)
- `PUT /api/hotels/{id}` - 호텔 수정 (인증 필요)
- `DELETE /api/hotels/{id}` - 호텔 삭제 (인증 필요)

### 객실
- `GET /api/hotels/{hotelId}/rooms` - 객실 목록
- `GET /api/hotels/{hotelId}/rooms/{roomId}` - 객실 상세
- `POST /api/hotels/{hotelId}/rooms` - 객실 등록 (인증 필요)
- `POST /api/hotels/{hotelId}/rooms/{roomId}/check-availability` - 가용성 확인

### 예약
- `GET /api/bookings` - 내 예약 목록 (인증 필요)
- `POST /api/bookings` - 예약 생성 (인증 필요)
- `GET /api/bookings/{id}` - 예약 상세 (인증 필요)
- `POST /api/bookings/{id}/cancel` - 예약 취소 (인증 필요)

### 리뷰
- `GET /api/hotels/{hotelId}/reviews` - 호텔 리뷰 목록
- `POST /api/reviews` - 리뷰 작성 (인증 필요)
- `PUT /api/reviews/{id}` - 리뷰 수정 (인증 필요)
- `DELETE /api/reviews/{id}` - 리뷰 삭제 (인증 필요)

## 🧩 Vue 컴포넌트

### 주요 컴포넌트

- **SearchBar**: 호텔 검색 폼
- **HotelCard**: 호텔 카드 표시
- **BookingForm**: 예약 폼
- **ReviewList**: 리뷰 목록 표시
- **ReviewForm**: 리뷰 작성 폼
- **DateRangePicker**: 날짜 범위 선택기

## 🎨 스타일링

TailwindCSS를 사용하여 일관된 디자인 시스템을 구축했습니다.

### 커스텀 유틸리티 클래스

- `.btn` - 기본 버튼 스타일
- `.btn-primary` - 주요 버튼
- `.btn-secondary` - 보조 버튼
- `.btn-outline` - 아웃라인 버튼
- `.card` - 카드 컨테이너
- `.badge` - 배지 스타일
- `.input` - 입력 필드

## 🔒 보안

- CSRF 보호 (Laravel)
- XSS 방지
- SQL Injection 방지 (Eloquent ORM)
- API 인증 (Laravel Sanctum)
- 비밀번호 해싱 (bcrypt)

## 🧪 테스트

\`\`\`bash
# PHP 유닛 테스트
php artisan test

# 특정 테스트 실행
php artisan test --filter=BookingTest
\`\`\`

## 📝 개발 가이드

### 새로운 Vue 컴포넌트 추가

1. `backend/resources/js/components/` 에 Vue 파일 생성
2. `backend/resources/js/app.js` 에서 컴포넌트 등록
3. Blade 템플릿에서 사용

### 새로운 API 엔드포인트 추가

1. Controller 생성: `php artisan make:controller Api/YourController`
2. `backend/routes/api.php` 에 라우트 추가
3. 필요시 Model, Migration 생성

## 🤝 기여하기

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 라이센스

This project is licensed under the MIT License.

## 👥 개발자

- 개발자 이름

## 📞 문의

프로젝트에 대한 문의사항이 있으시면 이슈를 생성해주세요.
