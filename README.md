(Laravel + RabbitMQ sistemi üçün strukturlaşdırılmış təlimat)

======================================================================
1. ÜMUMİ MƏLUMAT

Call Events Service – SIP/telefoniya serverindən daxil olan zəng eventlərini qəbul edən, validasiya edən, məlumat bazasına loqlayan və RabbitMQ queue-a göndərən mikroservisdir. Sistem performanslı və genişlənə bilən memarlıqla hazırlanmışdır.

======================================================================
2. QURAŞDIRMA (SETUP İNSTRUKSİYASI)

2.1. Layihənin yüklənməsi

Terminalda aşağıdakı əmrləri icra edin:

git clone <REPO_URL> call-events-service
cd call-events-service

2.2. Docker ilə sistemin qaldırılması

Layihə Laravel, MySQL və RabbitMQ ilə birlikdə Docker vasitəsilə qaldırılır:

docker compose up -d --build

2.3. Migrasiyaların icra edilməsi

Məlumat bazası cədvəllərinin yaradılması üçün:

docker compose exec app php artisan migrate

2.4. Queue Worker-in işə salınması

RabbitMQ mesajları Laravel Job vasitəsilə göndərildiyi üçün worker aktiv olmalıdır:

docker compose exec app php artisan queue:work --queue=call-events

======================================================================
3. API MƏLUMATLARI

3.1. Endpoint məlumatı

Method: POST
URL: http://localhost:8000/api/call-events

3.2. Header

X-API-TOKEN: super-secret-token

3.3. Request Body nümunəsi

{
"call_id": "abc-123",
"from_number": "+994501234567",
"to_number": "+994701234567",
"event_type": "call_ended",
"timestamp": "2025-11-30T10:00:00Z",
"duration": 40
}

3.4. Uğurlu cavab

{ "status": "queued" }

======================================================================
4. SİSTEMİN İŞ PRİNSİPİ

4.1. Event qəbul axını

Telefoniya serveri /api/call-events endpointinə məlumat göndərir.

Token (X-API-TOKEN) doğrulanır.

Validasiya icra edilir:

Bütün sahələr məcburidir.

“duration” yalnız event_type = call_ended olduqda tələb olunur.

Event məlumatları call_event_logs cədvəlinə yazılır.

Məlumat RabbitMQ-a göndərilməsi üçün iş növbəsinə (Job Queue) əlavə olunur.

======================================================================
5. RABBITMQ İNTEQRASİYASI

Validasiya olunmuş məlumatlar birbaşa RabbitMQ-a göndərilmir. Bunun əvəzinə daha peşəkar və dayanıqlı yanaşma seçilmişdir:

5.1. Laravel Job-un işə düşməsi

Event qəbul edildikdən sonra SendCallEventToRabbitJob adlı Job dispatch olunur.

5.2. Job daxilində RabbitMQ Publisher prosesləri

Publisher aşağıdakı addımları icra edir:

RabbitMQ serverinə qoşulur.

“call-events” adlı queue-u yaradır (idempotent şəkildə).

Event JSON məlumatını queue-a push edir.

Mesaj persistent olaraq göndərilir.

5.3. Error handling

RabbitMQ əlçatmaz olarsa:

Exception tutulur

“RabbitMQ publish failed” loqa yazılır

Job avtomatik retry edə bilir

API lazım olduqda 500 cavab qaytarır

Bu yanaşma sistemi daha dayanıqlı və sürətli edir.

======================================================================
6. LOQLAMA

Hər bir event məlumat bazasında saxlanılır.

Cədvəl: call_event_logs

Sutunlar:

id

call_id

event_type

payload (JSON formatında)

created_time

======================================================================
7. RABBITMQ WEB PANEL

RabbitMQ üçün idarəetmə panelinə keçid:

URL: http://localhost:15672

İstifadəçi adı: guest
Şifrə: guest

Bu paneldən “call-events” queue-na daxil olan mesajları izləmək mümkündür.