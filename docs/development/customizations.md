# Customizations & Upgrade Inventory

> ดัชนี: [../README.md](../README.md) · กฎ: `.cursor/rules/bagisto-customization.mdc` · คู่มืออัปเกรด: [../upgrade.md](../upgrade.md)

เอกสารนี้บันทึก **ทุกอย่างที่เราแก้นอก upstream Bagisto** เพื่อให้อัปเกรด core ได้โดยไม่พลาด

**Remote (โปรเจกต์เรา):** [github.com/BukHUM/bagisto](https://github.com/BukHUM/bagisto)  
**Upstream อ้างอิงอัปเกรด:** [github.com/bagisto/bagisto](https://github.com/bagisto/bagisto) (ไม่ push ตรงไปที่นี่)

---

## สรุปชั้นการแก้ไข (Upgrade-Safe Layers)

| Layer | ปลอดภัยต่ออัปเกรด | ตัวอย่างในโปรเจกต์นี้ |
|-------|-------------------|----------------------|
| 1 Theme | สูง | `beyondary` theme |
| 2 App/Config/Lang | สูง | `config/themes.php`, `resources/lang/*/beyondary.php` |
| 3 App migrations | สูง | `database/migrations/2026_06_08_*` |
| 4 Custom package | สูง (ถ้าออกแบบถูก) | *(ยังไม่มี — เป้าหมายสำหรับ logic ด้านล่าง)* |
| 5 Core patch | ต่ำ — โดน overwrite | `OrderDataGrid`, `ProductRepository` |

---

## Layer 1–3: ปลอดภัย (ไม่โดน composer ทับ)

### Theme `beyondary`

| รายการ | Path |
|--------|------|
| Theme config | `config/themes.php`, `config/bagisto-vite.php` |
| Blade overrides | `resources/themes/beyondary/views/` |
| Vite/Tailwind | `resources/themes/beyondary/` |
| Build output | `public/themes/shop/beyondary/build/` |
| Static images | `public/themes/shop/beyondary/images/` |
| ภาษา theme | `resources/lang/th/beyondary.php`, `resources/lang/en/beyondary.php` |

**หลังอัปเกรด:** ไม่ต้องทำอะไรกับ theme นอกจากทดสอบ storefront + `npm run build` ใน `resources/themes/beyondary/` ถ้าแก้ assets

### Performance indexes (DB)

| ไฟล์ | หมายเหตุ |
|------|----------|
| `database/migrations/2026_06_08_000001_add_performance_indexes_to_sales_and_product_flat.php` | Index sales + product_flat |
| `database/migrations/2026_06_08_000002_add_performance_indexes_phase2.php` | Index phase 2 |

**หลังอัปเกรด:** `php artisan migrate` — migration ของเราไม่ถูกลบ

### เอกสาร & Cursor rules

- `docs/performance-analysis.md`, `.cursor/rules/*.mdc`

---

## Layer 5: แก้ Core โดยตรง (เสี่ยง — ต้อง re-apply หลังอัปเกรด)

| ไฟล์ | เหตุผล | แผนย้าย (แนะนำ) |
|------|--------|------------------|
| `packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php` | แก้ N+1 ใน `formatRecords()` | สร้าง `Beyondary\Admin\DataGrids\Sales\OrderDataGrid` extend + override route/controller หรือ bind ผ่าน custom package |
| `packages/Webkul/Product/src/Repositories/ProductRepository.php` | FULLTEXT/LIKE search, `url_key` exact | สร้าง `Beyondary\Product\Repositories\ProductRepository` extend + `bind()` ใน `AppServiceProvider` |

### วิธีเก็บ patch ชั่วคราว (ก่อนมี custom package)

เมื่อมี git แล้ว หลังแก้ core:

```bash
git diff packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php > docs/patches/order-datagrid-n1.patch
git diff packages/Webkul/Product/src/Repositories/ProductRepository.php > docs/patches/product-repository-search.patch
```

หลัง `composer update` ที่ทับไฟล์ core:

```bash
git checkout packages/Webkul/Admin/src/DataGrids/Sales/OrderDataGrid.php
git checkout packages/Webkul/Product/src/Repositories/ProductRepository.php
# แล้ว apply patch หรือย้ายไป custom package แทน
```

---

## ขั้นตอนอัปเกรด Bagisto (Playbook)

### ก่อนอัปเกรด

1. **Backup:** DB + `.env` + `storage/`
2. **Git:** init/commit หรือ export patch จากตาราง Layer 5
3. อ่าน [upgrade.md](../upgrade.md) ของเวอร์ชันเป้าหมาย
4. บันทึกเวอร์ชันปัจจุบัน: `php artisan bagisto:version` (ถ้ามี)

### ระหว่างอัปเกรด

1. อัปเดต `composer.json` / `composer update bagisto/bagisto` ตามคู่มือ upstream
2. ตรวจ conflict ใน `packages/Webkul/*` — ไฟล์ใน Layer 5 มักถูกทับ
3. `php artisan migrate`
4. `npm run build` (root และ/หรือ `resources/themes/beyondary/`)

### หลังอัปเกรด

1. Re-apply patch Layer 5 **หรือ** ย้าย logic ไป custom package แล้ว bind
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

## แนวทางระยะยาว (แนะนำ)

สร้าง **`packages/Beyondary/`** เป็น custom module เดียว:

```
packages/Beyondary/
├── Performance/          # ProductRepository extend, OrderDataGrid extend
├── Theme/                # (optional) theme-related PHP ถ้ามี
└── src/Providers/BeyondaryServiceProvider.php
```

ลงทะเบียนใน `bootstrap/providers.php` — logic ทั้งหมดอยู่นอก `packages/Webkul/*` ทำให้อัปเกรด core แล้ว merge conflict น้อยลงมาก

เมื่อสร้าง custom package แล้ว ให้ **revert** ไฟล์ Layer 5 กลับเป็น upstream และลบแถวออกจากตาราง Layer 5 ในเอกสารนี้
