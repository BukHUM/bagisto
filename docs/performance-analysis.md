# Bagisto Performance Analysis

> เอกสารวิเคราะห์ประสิทธิภาพของโปรเจกต์ Bagisto (v2.4.6 / Laravel 12)  
> สภาพแวดล้อม: Windows + XAMPP, `http://bagisto.local`  
> วันที่วิเคราะห์: มิถุนายน 2026  
> ดัชนีเอกสาร: [docs/README.md](README.md) · Cursor rules: `.cursor/rules/bagisto-performance.mdc`

---

## สิ่งที่ดำเนินการแล้ว (มิถุนายน 2026)

### Code-level

| รายการ | ไฟล์ | รายละเอียด |
|--------|------|------------|
| แก้ N+1 Order DataGrid | `packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php` | Override `formatRecords()` — preload orders พร้อม `items.product.images` ครั้งเดียวต่อหน้า แทน query ต่อแถว |
| ปรับ DB search (ไม่ใช้ ES) | `packages/Webkul/Product/src/Repositories/ProductRepository.php` | ค้นหาชื่อสินค้าใช้ FULLTEXT `MATCH...AGAINST` สำหรับภาษาอังกฤษ (≥4 ตัวอักษร), fallback `LIKE` สำหรับไทย/CJK, `url_key` ใช้ exact match |

**ก่อนแก้:** หน้า Order list 20 แถว = 20+ queries (+ lazy load product/images ต่อ item)  
**หลังแก้:** 1 query สำหรับ orders + items + product + images ทั้งหน้า

### Database indexes — Phase 1

| รายการ | ไฟล์ |
|--------|------|
| Index สำหรับ Sales + product_flat | `database/migrations/2026_06_08_000001_add_performance_indexes_to_sales_and_product_flat.php` |

Indexes ที่เพิ่ม: `orders(status, created_at, customer_email, customer_id+created_at)`, `order_items(product_id)`, `product_flat(status, visible_individually, url_key, parent_id)`, `products(type)`

### Database indexes — Phase 2

| รายการ | ไฟล์ |
|--------|------|
| Index สำหรับ config, cart, EAV, admin grids | `database/migrations/2026_06_08_000002_add_performance_indexes_phase2.php` |

| ตาราง | Index | ใช้กับ |
|-------|-------|--------|
| `core_config` | `code`, `(code, channel_code, locale_code)` | `getConfigData()` ทุก request |
| `addresses` | `(order_id, address_type)`, `(customer_id, address_type)` | Order/Customer DataGrid joins |
| `cart` | `(customer_id, is_active)` | Cart lookup ตอน login |
| `search_terms` | UNIQUE `(term, channel_id, locale)` | Search term upsert |
| `product_flat` | `(channel, locale)`, `(channel, locale, status, visible_individually)` | Admin Product grid, storefront listing |
| `product_attribute_values` | `(attribute_id, product_id)`, FULLTEXT `text_value` | EAV joins, DB search |
| `invoices` / `refunds` / `shipments` | `created_at`, `state` | Admin Sales DataGrids |
| `product_reviews` | `status`, `created_at` | Admin Review grid |
| `customers` | `status`, `created_at` | Admin Customer grid |
| `wishlist_items` / `compare_items` | `(customer_id, product_id)` | Wishlist/compare lookup |

### ยังไม่ทำ (เจตนา — รอ production / ไม่จำเป็นบน dev)

- เปลี่ยน `CACHE_STORE` / `SESSION_DRIVER` / `QUEUE_CONNECTION` → Redis
- เปิด Elasticsearch search engine
- เปิด Laravel Octane

---

## สรุปผู้บริหาร

Bagisto มีกลไก performance ในตัวหลายอย่างที่ออกแบบมาดีแล้ว เช่น **Full Page Cache (FPC)**, **Catalog API Cache แบบ versioned**, และ **index migrations ชุด 2025-09** สำหรับตาราง Product/Catalog

อย่างไรก็ตาม การตั้งค่า `.env` ปัจจุบันยังเป็นโหมด development ทั้งหมด ทำให้ศักยภาพ performance ถูกจำกัดอย่างมาก:

| ปัจจัย | ค่าปัจจุบัน | ผลกระทบ |
|--------|------------|---------|
| Queue | `sync` | Job ทุกตัวรันใน HTTP request (index ราคา, สต็อก, Elasticsearch) |
| Cache | `file` | ช้ากว่า Redis, lock contention สูงเมื่อมี concurrent users |
| Session | `database` | ทุก request อ่าน/เขียน DB สำหรับ session |
| Debug | `APP_DEBUG=true` | ปิด config/route cache, log verbose |
| Search engine | Database (default) | `LIKE '%term%'` บนตาราง EAV ขนาดใหญ่ |

**Quick wins ที่ได้ผลเร็วที่สุด:** เปลี่ยน queue/cache/session ไป Redis, ปิด debug ใน production, แก้ N+1 ใน Order DataGrid, เพิ่ม index บนตาราง Sales

---

## 1. สภาพแวดล้อมปัจจุบัน

### 1.1 Stack

| รายการ | ค่า |
|--------|-----|
| PHP | 8.4.16 (OPcache เปิด, 128MB) |
| Laravel | 12.56.0 |
| Bagisto | 2.4.6 |
| Database | MariaDB/MySQL (`bagisto`, 145 ตาราง) |
| Node | v24.12.0 |
| Redis | ยังไม่ติดตั้ง (comment ใน `.env`) |

### 1.2 การตั้งค่า `.env` ที่เกี่ยวกับ Performance

```env
APP_DEBUG=true
LOG_LEVEL=debug
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
CACHE_STORE=file
RESPONSE_CACHE_ENABLED=true
# RESPONSE_CACHE_DRIVER ไม่ได้ตั้ง → default = file
```

### 1.3 สิ่งที่ติดตั้งแล้วแต่ยังไม่ได้ใช้

จาก `composer.json`:

- `predis/predis` — Redis client (PHP ล้วน, ไม่ต้องมี extension)
- `laravel/octane` — Long-lived worker model (ลด bootstrap overhead ต่อ request)
- `spatie/laravel-responsecache` — ใช้งานผ่าน package `Webkul\FPC`
- `elasticsearch/elasticsearch` v8 — ใช้เมื่อเปิด search engine เป็น Elasticsearch

---

## 2. สถาปัตยกรรม Caching ที่มีอยู่

### 2.1 Full Page Cache (FPC) — ดีแล้ว

**ไฟล์:** `config/responsecache.php`, `packages/Webkul/FPC/`

- เปิดด้วย `RESPONSE_CACHE_ENABLED=true`
- Cache lifetime: 7 วัน (default)
- Cache store: `file` (ควรเปลี่ยนเป็น `redis` ใน production)
- มี replacers สำหรับ CSRF token และ flash messages

**Routes ที่ใช้ `cache.response` middleware** (`packages/Webkul/Shop/src/Routes/store-front-routes.php`):

- หน้าแรก (home)
- หน้าค้นหา (search)
- CMS pages
- Compare products
- Product / Category fallback routes

**Cache invalidation:** Event-driven ผ่าน `packages/Webkul/FPC/src/Listeners/` — เมื่อ product, category, order, review, channel เปลี่ยน จะ flush cache อัตโนมัติ

### 2.2 Catalog API Cache — ออกแบบดีมาก

**ไฟล์:** `packages/Webkul/Shop/src/Helpers/CatalogApiCache.php`

- ใช้ **versioned cache key** — เมื่อ catalog เปลี่ยน จะ increment version แทนการลบ cache ทีละรายการ
- Scope ตาม channel + locale + currency
- Cache เฉพาะ **guest** (ไม่ cache สำหรับ logged-in customer เพราะมี personalized data)
- TTL: 3600 วินาที (1 ชั่วโมง)
- ใช้ atomic `Cache::increment()` ป้องกัน race condition

### 2.3 ข้อจำกัดของ Cache ปัจจุบัน

เมื่อใช้ `CACHE_STORE=file`:

- Concurrent requests อาจชน file lock
- ไม่รองรับ cache tags (ใช้ partial invalidation ไม่ได้)
- Response cache บน disk ช้ากว่า Redis อย่างมีนัยสำคัญ

---

## 3. วิเคราะห์โครงสร้างฐานข้อมูล

### 3.1 ภาพรวม

- **145 ตาราง** — schema ขนาดใหญ่, normalized สูง
- ตอนนี้ยังไม่มีข้อมูลจริง (products = 0, orders = 0) แต่ schema design ส่งผลต่อ performance เมื่อ scale

### 3.2 ตาราง Hot Path (อ่านบ่อย)

| ตาราง | บทบาท | ความเสี่ยง |
|-------|-------|-----------|
| `product_attribute_values` | EAV — เก็บ attribute ทุกตัวของ product | ตารางใหญ่ที่สุด, join หลายครั้งต่อ query |
| `product_flat` | Denormalized product data สำหรับ listing | ไม่มี index บน `url_key`, `status` |
| `product_price_indices` | ราคาตาม customer group | มี index แล้ว (2025-09 migration) |
| `product_inventory_indices` | สต็อก | มี index แล้ว |
| `orders` / `order_items` | คำสั่งซื้อ | ✅ เพิ่ม index แล้ว (Phase 1) |
| `core_config` | ค่า config ทุกหน้า | ✅ เพิ่ม index แล้ว (Phase 2) |
| `sessions` | Session storage | ทุก request เขียน/อ่าน (เพราะ `SESSION_DRIVER=database`) — มี index `last_activity` แล้ว |

### 3.3 Indexes ที่มีอยู่แล้ว (Migration 2025-09-05)

Bagisto เพิ่ม index ในชุด migration เหล่านี้:

```
packages/Webkul/Product/src/Database/Migrations/
  2025_09_05_000200_add_indexes_to_product_relation_tables.php
  2025_09_05_000300_add_indexes_to_product_media_and_attributes.php
  2025_09_05_000500_add_indexes_to_product_grouped_products_...php

packages/Webkul/Core/src/Database/Migrations/
  2025_09_05_000100_add_indexes_to_channels_tables.php

packages/Webkul/Attribute/src/Database/Migrations/
  2025_09_05_000400_add_indexes_to_attributes_and_product_types.php

packages/Webkul/Marketing/src/Database/Migrations/
  2025_09_05_000500_add_indexes_to_url_rewrites_and_visits.php
```

**Indexes ที่เพิ่มแล้ว:**

| ตาราง | Index |
|-------|-------|
| `product_price_indices` | `(product_id, customer_group_id)` |
| `product_channels` | `(product_id, channel_id)` |
| `product_images` | `product_id` |
| `product_videos` | `product_id` |
| `product_reviews` | `product_id` |
| `product_inventory_indices` | `product_id` |
| `product_attribute_values` | `product_id` |
| `channels` | `hostname` |
| `channel_locales` | `(channel_id, locale_id)` |
| `channel_currencies` | `(channel_id, currency_id)` |
| `attributes` | `code` |
| `url_rewrites` | `(entity_type, request_path, locale)` |

### 3.4 Custom Performance Indexes (ดำเนินการแล้ว)

ดูรายละเอียดใน section **"สิ่งที่ดำเนินการแล้ว"** ด้านบน — Phase 1 + Phase 2 migrations

#### ยังไม่ทำ (optional / production)

```sql
-- ถ้าใช้ database queue driver
-- jobs(queue, reserved_at) — Laravel default migration มีอยู่แล้ว

-- Reporting ขั้นสูง (เมื่อ order หลักแสน)
-- orders(status, created_at) composite แทน index แยก (พิจารณาตาม query plan จริง)
```

> **หมายเหตุ:** FULLTEXT ใช้งานแล้วใน `ProductRepository::searchFromDatabase()` สำหรับคำค้นหาภาษาอังกฤษ — ภาษาไทยยังใช้ `LIKE` (MariaDB XAMPP ไม่มี ngram parser) — Elasticsearch ยังไม่เปิดใช้

### 3.5 ปัญหาเชิงโครงสร้าง (Architectural)

**EAV Pattern (`product_attribute_values`)**

- ทุก attribute ของ product เก็บเป็น row แยก (name, price, status, color, size, ...)
- Storefront search/filter ต้อง self-join ตารางนี้หลายครั้ง (1 join ต่อ 1 attribute ที่ filter)
- การค้นหาชื่อสินค้าใช้ `LIKE '%term%'` ซึ่ง **ไม่สามารถใช้ B-tree index ได้**

**แนวทางแก้:**

1. ใช้ **Elasticsearch** สำหรับ search/listing (แนะนำเมื่อมีสินค้า > 1,000 SKU)
2. ใช้ `product_flat` สำหรับ read path ที่เป็นไปได้
3. ใช้ `product_price_indices` / `product_inventory_indices` แทนการคำนวณ real-time

---

## 4. วิเคราะห์ Code-Level

### 4.1 Bottleneck: Database Search Engine

**ไฟล์:** `packages/Webkul/Product/src/Repositories/ProductRepository.php`

Method `searchFromDatabase()` (บรรทัด 246–488):

```
ปัญหา:
├── Self-join product_attribute_values หลายครั้ง (ต่อ attribute ที่ filter)
├── Self-join variants table
├── DISTINCT + GROUP BY
├── LIKE '%term%' สำหรับค้นหาชื่อ (full table scan)
└── Eager load 12+ relations (ดี แต่ query หลักหนักมาก)
```

Method `searchFromElastic()` (บรรทัด 495–552):

```
ข้อดี:
├── ค้นหาจาก Elasticsearch ได้เฉพาะ product IDs
├── Hydrate จาก DB พร้อม eager load ชุดเดียวกัน
└── หลีกเลี่ยง EAV join ทั้งหมด
```

**แนะนำ:** ตั้งค่า search engine เป็น `elastic` ใน Admin → Configuration → Catalog → Products เมื่อพร้อม deploy Elasticsearch

### 4.2 Bottleneck: N+1 ใน Admin Order DataGrid

**ไฟล์:** `packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php` (บรรทัด 193–203)

```php
// ปัญหา: query + render view ต่อ 1 row
'closure' => function ($value) {
    $order = app(OrderRepository::class)->with('items')->find($value->id);
    return view('admin::sales.orders.items', compact('order'))->render();
},
```

**ผลกระทบ:** หน้า Order list 20 แถว = 20 queries เพิ่ม + 20 Blade renders

**แนวทางแก้:**

1. รวม item data ใน base query ด้วย `GROUP_CONCAT` (เหมือน column `method` ที่บรรทัด 33)
2. หรือ eager-load items ทั้งหมดครั้งเดียว แล้ว map ตาม `order_id`

### 4.3 Queue Jobs ที่รัน Sync

**ไฟล์:** `packages/Webkul/Product/src/Jobs/`

| Job | ทำงาน | ผลเมื่อ `QUEUE_CONNECTION=sync` |
|-----|-------|----------------------------------|
| `UpdateCreatePriceIndex` | สร้าง/อัปเดต price index | บล็อก request ตอน save product |
| `UpdateCreateInventoryIndex` | สร้าง/อัปเดต inventory index | บล็อก request ตอน save product |
| `ElasticSearch\UpdateCreateIndex` | Index product ใน ES | บล็อก request ตอน save product |
| `ElasticSearch\DeleteIndex` | ลบ index ใน ES | บล็อก request ตอน delete product |

**แนะนำ:** เปลี่ยน `QUEUE_CONNECTION=redis` (หรือ `database` ถ้ายังไม่มี Redis) แล้วรัน `php artisan queue:work`

### 4.4 Eager Loading ที่ทำได้ดีแล้ว

`ProductRepository` ทั้ง `searchFromDatabase` และ `searchFromElastic` ใช้ `with([...])` ชุดเดียวกัน:

```php
'attribute_family', 'images', 'videos', 'attribute_values',
'price_indices', 'inventory_indices', 'reviews',
'variants', 'variants.attribute_family', 'variants.attribute_values',
'variants.price_indices', 'variants.inventory_indices'
```

ป้องกัน N+1 ระหว่าง hydrate product listing — **ควรคง pattern นี้ไว้** เมื่อเพิ่ม feature ใหม่

### 4.5 Search Term Tracking

**ไฟล์:** `packages/Webkul/Shop/src/Http/Controllers/API/ProductController.php`

ทุกครั้งที่ลูกค้าค้นหา จะ dispatch job `UpdateCreateSearchTerm` — ด้วย `sync` queue จะรันทันทีใน request

---

## 5. Frontend & Assets

### 5.1 Build System

- Root `vite.config.js` — minimal (app.css/app.js)
- Admin/Shop มี Vite config แยกในแต่ละ package
- Production build: `npm run build` (root) + build per package

### 5.5 แนะนำ

| รายการ | สถานะ | แนะนำ |
|--------|-------|-------|
| Vite production build | ทำแล้ว | ใช้ hashed filenames สำหรับ long-cache |
| Image optimization | ไม่มี built-in | ใช้ WebP, lazy loading, CDN |
| CSS/JS minification | Vite ทำให้ | ตรวจสอบว่า deploy ใช้ `build` ไม่ใช่ `dev` |
| Static file caching | ขึ้นกับ Apache config | ตั้ง `Cache-Control` สำหรับ `/public/build/` |

---

## 6. แผนปรับปรุงตามลำดับความสำคัญ

### 🔴 HIGH — ทำก่อน, ผลลัพธ์ชัดเจน

| # | รายการ | วิธีทำ | ผลที่คาดหวัง |
|---|--------|-------|-------------|
| 1 | เปลี่ยน Queue ออกจาก `sync` | `QUEUE_CONNECTION=redis` + `php artisan queue:work` | ลด response time ตอน save product/search ลง 50–80% |
| 2 | ย้าย Cache + Session ไป Redis | `CACHE_STORE=redis`, `SESSION_DRIVER=redis`, `RESPONSE_CACHE_DRIVER=redis` | ลด DB load, เพิ่ม throughput |
| 3 | ปิด Debug ใน production | `APP_DEBUG=false`, `LOG_LEVEL=warning` | เปิดใช้ config/route/view cache |
| 4 | รัน Laravel optimize | `php artisan optimize` | Cache config, routes, views, events |
| 5 | แก้ N+1 Order DataGrid | Refactor `OrderDataGrid.php` items column | ลด query ในหน้า Order list จาก N+1 เหลือ 1–2 |
| 6 | เปิด Elasticsearch | ติดตั้ง ES + ตั้งค่าใน Admin config | หลีกเลี่ยง EAV full scan ในการค้นหา |

### 🟡 MEDIUM — ทำเมื่อเริ่มมีข้อมูลจริง

| # | รายการ | วิธีทำ | ผลที่คาดหวัง |
|---|--------|-------|-------------|
| 7 | เพิ่ม DB indexes (Sales + product_flat) | สร้าง migration ใหม่ | เร่ง Admin order list และ storefront listing |
| 8 | เปิด Laravel Octane | `php artisan octane:start` (ต้องมี Swoole/RoadRunner) | ลด bootstrap overhead ต่อ request |
| 9 | FULLTEXT index บน EAV | ถ้ายังใช้ database search | เร่ง text search บน `product_attribute_values` |
| 10 | ตั้งค่า OPcache สำหรับ production | `opcache.memory_consumption=256`, `validate_timestamps=0` | ลด PHP compilation overhead |

### 🟢 LOW — ทำเมื่อ optimize รอบสอง

| # | รายการ | วิธีทำ |
|---|--------|-------|
| 11 | ตั้ง `cache_tag` ใน responsecache.php | เมื่อใช้ Redis (รองรับ tags) |
| 12 | CDN สำหรับ static assets + product images | CloudFlare, AWS CloudFront |
| 13 | Database read replica | เมื่อ traffic สูง |
| 14 | Monitor ด้วย Laravel Telescope/Debugbar | ตรวจ N+1 และ slow queries ใน dev |
| 15 | Index `order_items.product_id` | สำหรับ sales reporting |

---

## 7. แผนสำหรับเครื่อง Dev (Windows/XAMPP)

สภาพแวดล้อมปัจจุบันไม่มี Redis — สำหรับ development สามารถใช้ทางเลือกนี้:

### ทางเลือก A: ใช้ Database Queue (ไม่ต้องติดตั้ง Redis)

```env
QUEUE_CONNECTION=database
CACHE_STORE=file
SESSION_DRIVER=database
```

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

### ทางเลือก B: ติดตั้ง Redis ผ่าน Docker

```bash
docker run -d --name redis -p 6379:6379 redis
```

```env
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
RESPONSE_CACHE_DRIVER=redis
```

### ทางเลือก C: Memurai (Redis-compatible สำหรับ Windows)

ดาวน์โหลดจาก https://www.memurai.com/ — ใช้แทน Redis บน Windows ได้โดยตรง

---

## 8. Checklist ก่อน Production

```
Infrastructure
  [ ] APP_DEBUG=false
  [ ] LOG_LEVEL=warning หรือ error
  [ ] Redis ติดตั้งและทำงาน
  [ ] QUEUE_CONNECTION=redis + supervisor สำหรับ queue:work
  [ ] Elasticsearch ติดตั้ง (ถ้ามีสินค้า > 1,000 SKU)

Laravel Optimize
  [ ] php artisan config:cache
  [ ] php artisan route:cache
  [ ] php artisan view:cache
  [ ] php artisan event:cache
  [ ] php artisan optimize

Database
  [ ] Migration indexes สำหรับ orders, product_flat
  [ ] ตรวจ slow query log
  [ ] ตั้ง MySQL innodb_buffer_pool_size >= 1GB

Cache
  [ ] CACHE_STORE=redis
  [ ] SESSION_DRIVER=redis
  [ ] RESPONSE_CACHE_DRIVER=redis
  [ ] RESPONSE_CACHE_ENABLED=true

Frontend
  [ ] npm run build (production assets)
  [ ] Apache/Nginx gzip + static file caching
  [ ] Product images: WebP + lazy load

Monitoring
  [ ] ตั้ง slow query log (MySQL long_query_time=1)
  [ ] ตรวจ response time ด้วย `Bagisto-FPC` header
  [ ] Queue monitoring (Horizon หรือ supervisor logs)
```

---

## 9. ไฟล์อ้างอิงสำคัญ

| หัวข้อ | Path |
|--------|------|
| Product Search (DB) | `packages/Webkul/Product/src/Repositories/ProductRepository.php` |
| Product Search (ES) | `packages/Webkul/Product/src/Repositories/ElasticSearchRepository.php` |
| Catalog API Cache | `packages/Webkul/Shop/src/Helpers/CatalogApiCache.php` |
| FPC Listeners | `packages/Webkul/FPC/src/Listeners/` |
| Storefront Routes (FPC) | `packages/Webkul/Shop/src/Routes/store-front-routes.php` |
| Order DataGrid (N+1) | `packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php` |
| Index Jobs | `packages/Webkul/Product/src/Jobs/` |
| Response Cache Config | `config/responsecache.php` |
| Elasticsearch Config | `config/elasticsearch.php` |
| Index Migrations | `packages/Webkul/*/src/Database/Migrations/2025_09_05_*` |

---

## 10. สรุป

Bagisto มี foundation ด้าน performance ที่ดี — FPC, versioned API cache, index migrations, Elasticsearch support, และ eager loading patterns ถูกออกแบบมาอย่างรอบคอบ

จุดอ่อนหลักอยู่ที่ **การตั้งค่าสภาพแวดล้อม** (sync queue, file cache, database session, debug mode) และ **database search engine** ที่ไม่เหมาะกับ catalog ขนาดใหญ่

สำหรับเครื่อง dev ปัจจุบัน: ใช้งานได้ปกติ แต่ควรวางแผนติดตั้ง Redis + เปลี่ยน queue driver ก่อนเริ่มเพิ่มสินค้าจริง เพื่อไม่ให้เจอปัญหา performance ทีหลัง
