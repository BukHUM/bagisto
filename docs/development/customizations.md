# Customizations & Upgrade Inventory

> ดัชนี: [../README.md](../README.md) · กฎ: `.cursor/rules/bagisto-customization.mdc` · คู่มืออัปเกรด: [../upgrade.md](../upgrade.md)

เอกสารนี้บันทึก **ทุกอย่างที่เราแก้นอก upstream Bagisto** เพื่อให้อัปเกรด core ได้โดยไม่พลาด

**Remote (โปรเจกต์เรา):** [github.com/BukHUM/bagisto](https://github.com/BukHUM/bagisto)  
**Upstream อ้างอิงอัปเกรด:** [github.com/bagisto/bagisto](https://github.com/bagisto/bagisto) (ไม่ push ตรงไปที่นี่)

```text
origin   → BukHUM/bagisto    (งานของเรา)
upstream → bagisto/bagisto     (อ้างอิง core — ห้าม push)
```

---

## สิ่งที่ต้องทำต่อ (ก่อนอัปเกรดครั้งถัดไป)

| ลำดับ | งาน | สถานะ | หมายเหตุ |
|-------|-----|--------|----------|
| 1 | ตั้ง Git + push ขึ้น BukHUM | ✅ เสร็จ | branch `main` |
| 2 | สร้าง `packages/Beyondary/Performance/` | ✅ เสร็จ | bind ผ่าน `PerformanceServiceProvider` |
| 3 | Revert 2 ไฟล์ใน `packages/Webkul/` กลับ upstream | ✅ เสร็จ | ตรงกับ `upstream/2.4` |
| 4 | เก็บ patch ใน `docs/patches/` | ✅ เสร็จ | สำรองก่อนย้าย package |

**พร้อมอัปเกรด:** core ใน `packages/Webkul/` สะอาด — performance logic อยู่ใน Beyondary package + theme/migration ของเรา

---

## สรุปชั้นการแก้ไข (Upgrade-Safe Layers)

| Layer | ปลอดภัยต่ออัปเกรด | ตัวอย่างในโปรเจกต์นี้ |
|-------|-------------------|----------------------|
| 1 Theme | สูง | shop `beyondary` + admin `beyondary-admin` |
| 2 App/Config/Lang | สูง | `config/themes.php`, `resources/lang/*/beyondary.php` |
| 3 App migrations | สูง | `database/migrations/2026_06_08_*` |
| 4 Custom package | สูง | `packages/Beyondary/Performance/` |
| 5 Core patch | ต่ำ — โดน overwrite | *(ไม่มีแล้ว — ย้ายไป Layer 4)* |

---

## Layer 1–3: ปลอดภัย (ไม่โดน composer ทับ)

### Theme `beyondary` (storefront)

| รายการ | Path |
|--------|------|
| Theme config | `config/themes.php`, `config/bagisto-vite.php` |
| Blade overrides | `resources/themes/beyondary/views/` |
| Vite/Tailwind | `resources/themes/beyondary/` |
| Build output | `public/themes/shop/beyondary/build/` |
| Static images | `public/themes/shop/beyondary/images/` |
| ภาษา theme | `resources/lang/th/beyondary.php`, `resources/lang/en/beyondary.php` |

**หลังอัปเกรด:** ไม่ต้องทำอะไรกับ theme นอกจากทดสอบ storefront + `npm run build` ใน `resources/themes/beyondary/` ถ้าแก้ assets

### Theme `beyondary-admin` (หลังบ้าน)

| รายการ | Path |
|--------|------|
| แผน / mockup | `docs/development/admin-theme-plan.md`, `docs/mockup/beyondary_admin_dashboard.html` |
| Theme config | `config/themes.php` (`admin-default` → `beyondary-admin`), `config/bagisto-vite.php` |
| Blade overrides (Phase 2) | `resources/admin-themes/beyondary-admin/views/components/layouts/` |
| Vite/Tailwind | `resources/admin-themes/beyondary-admin/` |
| Build output | `public/themes/admin/beyondary-admin/build/` |
| Admin เดิม | ยังอยู่ใน `packages/Webkul/Admin/` — สลับกลับด้วย `admin-default` => `default` |

**Build:** `cd resources/admin-themes/beyondary-admin && npm run build`

### Performance indexes (DB)

| ไฟล์ | หมายเหตุ |
|------|----------|
| `database/migrations/2026_06_08_000001_add_performance_indexes_to_sales_and_product_flat.php` | Index sales + product_flat |
| `database/migrations/2026_06_08_000002_add_performance_indexes_phase2.php` | Index phase 2 |

**หลังอัปเกรด:** `php artisan migrate` — migration ของเราไม่ถูกลบ

### เอกสาร & Cursor rules

- `docs/performance-analysis.md`, `.cursor/rules/*.mdc`

---

## Layer 4: Custom package `Beyondary/Performance`

| รายการ | Path / การทำงาน |
|--------|------------------|
| Service provider | `packages/Beyondary/Performance/src/Providers/PerformanceServiceProvider.php` |
| Autoload | `composer.json` → `Beyondary\\Performance\\` |
| Bootstrap | `bootstrap/providers.php` |
| Order N+1 fix | `DataGrids/Admin/Sales/OrderDataGrid.php` — `bind(OrderDataGrid::class, …)` |
| Product search | `Repositories/ProductRepository.php` + trait `Concerns/ImprovesProductDatabaseSearch` — `bind(ProductRepository::class, …)` |
| Patch สำรอง | `docs/patches/order-datagrid-n1.patch`, `docs/patches/product-repository-search.patch` |

**หลังอัปเกรด:** ถ้า upstream แก้ `searchFromDatabase()` หรือ `OrderDataGrid` ให้ merge logic ใน Beyondary package แล้วทดสอบ — **ไม่ต้องแก้** `packages/Webkul/*`

---

## Layer 5: Core patch (ย้ายแล้ว)

เดิมแก้ `OrderDataGrid` และ `ProductRepository` โดยตรง — ย้ายไป Layer 4 แล้ว ไฟล์ core กลับตรง `upstream/2.4`

---

## ขั้นตอนอัปเกรด Bagisto (Playbook)

### ก่อนอัปเกรด

1. **Backup:** DB + `.env` + `storage/`
2. **Git:** `git status` สะอาด → `git commit` → `git push origin main`
3. อ่าน [upgrade.md](../upgrade.md) ของเวอร์ชันเป้าหมาย
4. ตรวจ `packages/Beyondary/Performance/` — merge ถ้า upstream เปลี่ยน API ที่เรา extend

### ระหว่างอัปเกรด

1. อัปเดต `composer.json` / `composer update bagisto/bagisto` ตามคู่มือ upstream
2. ตรวจ conflict ใน `packages/Webkul/*` — ไฟล์ใน Layer 5 มักถูกทับ
3. `php artisan migrate`
4. `npm run build` (root และ/หรือ `resources/themes/beyondary/`)

### หลังอัปเกรด

1. ทดสอบ Beyondary Performance bindings ยังทำงาน (`composer dump-autoload`, admin orders, product search)
2. `php artisan optimize:clear`
3. `php artisan responsecache:clear`
4. ทดสอบตาม checklist ด้านล่าง

---

## Post-Upgrade Test Checklist

- [ ] Storefront `GET /` → HTTP 200, theme `beyondary`
- [ ] Admin login + Sales → Orders (ตรวจ N+1 / โหลดเร็ว)
- [ ] ค้นหาสินค้า (EN FULLTEXT + ไทย LIKE + `url_key`)
- [ ] Add to cart / mini-cart
- [ ] Channel locale/currency (th, THB)
- [ ] `php artisan migrate:status` — migration ของเราครบ

---

## แนวทางระยะยาว

ขยาย **`packages/Beyondary/`** เมื่อมี customization เพิ่ม (เช่น `Theme/` สำหรับ PHP helpers ของ theme) — ใช้ pattern เดียวกับ Performance: extend + `bind()` ใน provider แทนแก้ `packages/Webkul/*`
