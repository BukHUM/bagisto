# Beyondary Admin Theme — แผนพัฒนา UX/UI

> ดัชนี: [../README.md](../README.md) · Shop theme: [../../.cursor/rules/theme.mdc](../../.cursor/rules/theme.mdc) · Upgrade-safe: [customizations.md](customizations.md)

เอกสารนี้เป็น **แผนก่อนลงมือพัฒนา** สำหรับ admin theme ใหม่ของ Beyondary — ไม่แก้ core Bagisto

---

## 1. เป้าหมาย

| หัวข้อ | รายละเอียด |
|--------|-------------|
| **ชื่อ theme** | `beyondary-admin` (แยกจาก shop `beyondary`) |
| **แนวทาง** | Child theme ของ admin `default` — คัดลอกเฉพาะไฟล์ที่ต้องปรับ ที่เหลือ fallback จาก package |
| **เก็บของเดิม** | `admin-default` ยังเป็น `default` จนกว่า theme ใหม่จะพร้อม — สลับได้ด้วย config เดียว |
| **สไตล์** | Semi-dark คงที่ — sidebar โทนเข้มอุ่น (ไม่เข้มเกิน) พื้นที่เนื้อหาสว่างอ่านง่าย |
| **ไม่ทำ** | ปุ่มสลับ Dark/Light mode (ลบ `v-dark` ออกจาก header override) |
| **แบรนด์** | สอดคล้อง shop Beyondary (ทองบรอนซ์ + น้ำตาลอุ่น + ฟอนต์ Prompt) |

---

## 2. สถาปัตยกรรม Bagisto Admin Theme (สรุป)

```
config/themes.php          → ลงทะเบียน admin.beyondary-admin + parent: default
config/bagisto-vite.php    → viter สำหรับ build assets
resources/admin-themes/beyondary-admin/
  ├── views/               → Blade override (mirror path จาก package)
  ├── assets/css|js/       → Vite source
  └── vite.config.js, tailwind.config.cjs, package.json
public/themes/admin/beyondary-admin/build/  → output หลัง npm run build
```

**การเปิดใช้:** ไม่มีเมนูหลังบ้าน — เปลี่ยน `themes.admin-default` ใน `config/themes.php` เป็น `beyondary-admin` เมื่อพร้อม

**Activation:** `ThemeViewFinder` ตรวจ URL ว่าเป็น admin (`APP_ADMIN_URL`) แล้วโหลด theme จาก config

**ห้ามแก้:** `packages/Webkul/Admin/src/Http/Controllers`, DataGrids, Routes, Config menu/ACL

**อ้างอิงที่ทำสำเร็จแล้ว:** shop theme `beyondary` ใน `resources/themes/beyondary/` (pattern เดียวกัน)

---

## 3. Design System — Beyondary Admin (Semi-Dark)

### 3.1 โทนสี (เสนอ — ปรับได้ตอน mockup)

| Token | Hex | ใช้กับ |
|-------|-----|--------|
| `admin-sidebar` | `#2E2720` | พื้นหลัง sidebar (warm brown-gray — ไม่ใช่ `#111` / `gray-950`) |
| `admin-sidebar-hover` | `#3A3229` | hover รายการเมนู |
| `admin-sidebar-active` | `#B88B54` | รายการ active (brand-gold จาก shop) |
| `admin-sidebar-text` | `#E8E2D9` | ข้อความเมนูหลัก |
| `admin-sidebar-muted` | `#A89B8C` | submenu / inactive |
| `admin-surface` | `#F8F6F0` | พื้นหลัง main (brand-light จาก shop) |
| `admin-card` | `#FFFFFF` | การ์ด / panel |
| `admin-border` | `#E5DFD4` | เส้นแบ่งอ่อน |
| `admin-text` | `#3A2618` | ข้อความหลัก (brand-dark) |
| `admin-text-muted` | `#6B4A31` | ข้อความรอง (brand-earth) |
| `admin-primary` | `#B88B54` | ปุ่มหลัก / link active (แทน `blue-600`) |
| `admin-primary-hover` | `#9A7345` | hover ปุ่มหลัก |
| `admin-danger` | `#C45C5C` | ลบ / error (คง contrast) |
| `admin-success` | `#40994A` | success (อ้างอิง `darkGreen` เดิม) |

### 3.2 Typography

| บทบาท | ฟอนต์ |
|--------|-------|
| UI ทั่วไป | **Prompt** (รองรับไทย — สอดคล้อง storefront) |
| หัวข้อหน้า / Dashboard | **Playfair Display** (optional เฉพาะ `<h1>` ระดับ page) |
| ตัวเลข / ตาราง | Prompt |

> Admin เดิมใช้ Poppins + DM Serif — theme ใหม่เปลี่ยนเป็น Prompt เพื่อความต่อเนื่องของแบรนด์

### 3.3 Layout & UX หลัก

```
┌─────────────────────────────────────────────────────────────┐
│ Header (สว่าง) — logo, search, notifications, profile      │
│ ไม่มี dark mode toggle                                      │
├──────────┬──────────────────────────────────────────────────┤
│ Sidebar  │ Main content (สว่าง brand-light)                   │
│ semi-dark│ ┌─ page title + breadcrumb/actions ─────────────┐ │
│ 270px    │ │ cards / datagrid / forms                       │ │
│          │ └────────────────────────────────────────────────┘ │
│ collapse │ footer เบาๆ                                       │
└──────────┴──────────────────────────────────────────────────┘
```

**UX ที่จะปรับจากของเดิม**

| พื้นที่ | ปัญหาเดิม | แนวทาง Beyondary |
|---------|-----------|------------------|
| Sidebar | สีขาว/เทา + `blue-600` active + พึ่ง `dark:` toggle | พื้น `admin-sidebar` คงที่, active เป็น gold pill |
| Header | ปุ่ม dark mode + contrast กระโดดเมื่อสลับโหมด | header สว่างเสมอ, ลบ `v-dark` |
| Main | `dark:bg-gray-950` ทำให้ทั้งจอมืด | พื้น `admin-surface` เสมอ |
| Primary CTA | น้ำเงิน Bagisto ทั่วระบบ | ทองบรอนซ์ — สอดคล้องร้านค้า |
| Datagrid | เส้นคม โทนเย็น | มุม `rounded-lg`, border อุ่น, header ตารางพื้น `#F5F3EE` |
| Form | label จางใน dark mode | label `admin-text` ชัดบนพื้นสว่าง |
| Spacing | แน่นบน mobile | คง responsive เดิม + เพิ่ม padding การ์ดบน `sm+` |
| Login | anonymous layout แยก | ปรับให้โทนเดียวกัน (sidebar ไม่มี — full bleed brand) |

**ไม่เปลี่ยนในเฟสแรก (ลดความเสี่ยง)**

- Logic Vue (`v-datagrid`, `v-modal`, validation)
- โครงสร้าง route / menu / ACL
- พฤติกรรม collapse sidebar (คง `v-sidebar-collapse`)

---

## 4. กลยุทธ์ Override (ไม่ copy 324 ไฟล์)

### ชั้นที่ 1 — Shell (บังคับ)

Override เฉพาะ layout ก่อน ได้ผล ~70% ของ “look & feel”:

```
resources/admin-themes/beyondary-admin/views/components/layouts/
├── index.blade.php          # ลบ cookie dark_mode, โหลด vite theme, class พื้นหลัง
├── anonymous.blade.php      # หน้า login
├── header/index.blade.php     # ลบ v-dark, ปรับสี header
├── sidebar/index.blade.php    # semi-dark sidebar + gold active
└── tabs.blade.php             # tab สี primary ใหม่
```

### ชั้นที่ 2 — Component ที่กระทบทุกหน้า

```
components/
├── button/index.blade.php
├── flash-group/index.blade.php
├── form/control-group/control.blade.php   # input, select focus ring
├── datagrid/index.blade.php               # wrapper ถ้ามี
├── datagrid/table.blade.php               # หัวตาราง / แถว
└── modal/index.blade.php                  # overlay + radius
```

### ชั้นที่ 3 — CSS global ใน theme

`assets/css/app.css` — import Tailwind + **@layer components** สำหรับ:

- `.label-processing`, `.label-active` (status badges ใน orders)
- datagrid checkbox, pagination
- แทนที่สี `blue-600` ที่ยังเหลือใน blade จาก parent ด้วย selector `[class*="text-blue"]` เฉพาะที่จำเป็น (ใช้เท่าที่ต้อง — ลด !important)

**Tailwind `content` paths** ต้อง scan ทั้ง:

- `resources/admin-themes/beyondary-admin/views/**`
- `packages/Webkul/Admin/src/Resources/**/*.blade.php` (class จาก parent ยัง render อยู่)

### ชั้นที่ 4 — หน้าสำคัญ (polish ทีหลัง)

| ลำดับ | หน้า | เหตุผล |
|-------|------|--------|
| 1 | `dashboard/index.blade.php` | หน้าแรกหลัง login |
| 2 | `sales/orders/index.blade.php` | datagrid หนัก |
| 3 | `catalog/products/index.blade.php` | ใช้บ่อย |
| 4 | `customers/customers/index.blade.php` | datagrid |
| 5 | `configuration/index.blade.php` | settings |

---

## 5. แผนพัฒนาเป็นหลาย Phase

ทุก phase จบด้วย **ทดสอบตาม `bagisto-workflow.mdc`** ก่อนเริ่ม phase ถัดไป

### Phase 0 — Scaffold & Git (0.5–1 วัน)

- [ ] สร้าง `resources/admin-themes/beyondary-admin/` (vite, tailwind, package.json)
- [ ] ลงทะเบียน `config/themes.php` → `admin.beyondary-admin` (`parent => 'default'`)
- [ ] ลงทะเบียน `config/bagisto-vite.php` → key `beyondary-admin`
- [ ] อัปเดต `resources/admin-themes/.gitignore` ให้ track `beyondary-admin/` (แบบเดียวกับ shop)
- [ ] **`admin-default` ยังเป็น `default`** — ยังไม่สลับ
- [ ] เอกสาร: อัปเดต `customizations.md` (Layer 1 admin theme)

**ทดสอบ:** `npm run build` สำเร็จ, `php artisan view:clear`, เข้า admin ยังเป็น theme เดิม

### Phase 1 — Design Spec & Mockup (1–2 วัน)

- [ ] สร้าง `docs/mockup/beyondary_admin_dashboard.html` (static HTML 1 หน้า — sidebar + header + datagrid ตัวอย่าง)
- [ ] กำหนด tokens สุดท้ายใน `tailwind.config.cjs` ของ theme
- [ ] Screenshot admin เดิมเป็น reference (before)
- [ ] Checklist component: sidebar states, buttons, table, form field, modal, login

**ทดสอบ:** review mockup กับ stakeholder — ไม่เขียน Blade จนกว่าจะ lock สี/โครง

### Phase 2 — Layout Shell (2–3 วัน)

- [ ] Override `index`, `anonymous`, `header`, `sidebar`, `tabs`
- [ ] ลบการอ่าน `request()->cookie('dark_mode')` และ class `dark` บน `<html>`
- [ ] ลบ `<v-dark>` จาก header
- [ ] โหลด `@bagistoVite` จาก theme (ไม่ใช่ path package เดิม)
- [ ] ตั้ง `admin-default` → `beyondary-admin` ใน **environment dev เท่านั้น** (หรือสลับชั่วคราวเพื่อทดสอบ)

**ทดสอบ:**

- [ ] Login / logout
- [ ] ทุกเมนูหลักเปิดได้ (ไม่ broken layout)
- [ ] Sidebar collapse / mobile drawer
- [ ] Mega search, notifications ทำงาน
- [ ] ไม่มี flash สีขาวบนพื้นขาว (contrast)

### Phase 3 — Shared Components (3–4 วัน)

- [ ] Button, form controls, flash, datagrid chrome, modal
- [ ] CSS layer สำหรับ status labels + pagination
- [ ] แทน primary blue → gold ในส่วนที่ override แล้ว

**ทดสอบ:**

- [ ] สร้าง/แก้สินค้า (form ยาว)
- [ ] Orders list + filter + pagination
- [ ] Modal confirm ลบ
- [ ] Validation errors อ่านง่าย

### Phase 4 — High-Traffic Pages (2–3 วัน)

- [ ] Dashboard cards + charts สีใหม่
- [ ] Polish หน้า catalog / customers index ถ้ายังไม่สวยพอ

**ทดสอบ:** workflow จริง — เพิ่มสินค้า → สั่งซื้อทดสอบ → ดู order ใน admin

### Phase 5 — Hardening & Rollout (1–2 วัน)

- [ ] เปรียบเทียบกับ admin `default` (side-by-side screenshot)
- [ ] อัปเดต `docs/development/customizations.md` + `docs/README.md`
- [ ] (ทางเลือก) Cursor rule `admin-theme.mdc` สั้นๆ ชี้ mockup + tokens
- [ ] ตั้ง `admin-default` → `beyondary-admin` อย่างเป็นทางการ
- [ ] `git commit` + push BukHUM

**ทดสอบสุดท้าย (checklist)**

- [ ] Chrome + Firefox, ความกว้าง 1280 / 768 / 375
- [ ] Locale `th` — ข้อความยาวไม่ล้น sidebar
- [ ] RTL ไม่จำเป็นถ้าใช้แค่ th/en แต่ไม่ทำพัง `dir=rtl`
- [ ] `php artisan optimize:clear` หลัง deploy

---

## 6. Config ตัวอย่าง (เมื่อ implement)

```php
// config/themes.php — เพิ่มใน 'admin' (เก็บ default ไว้)
'beyondary-admin' => [
    'name' => 'Beyondary Admin',
    'assets_path' => 'public/themes/admin/beyondary-admin',
    'views_path' => 'resources/admin-themes/beyondary-admin/views',
    'parent' => 'default',
    'vite' => [
        'hot_file' => 'admin-beyondary-vite.hot',
        'build_directory' => 'themes/admin/beyondary-admin/build',
        'package_assets_directory' => 'assets',
    ],
],
```

```php
// config/bagisto-vite.php
'beyondary-admin' => [
    'hot_file' => 'admin-beyondary-vite.hot',
    'build_directory' => 'themes/admin/beyondary-admin/build',
    'package_assets_directory' => 'assets',
],
```

**สลับ theme:** `'admin-default' => 'beyondary-admin'` (เมื่อพร้อม)

---

## 7. ความเสี่ยง & การลดความเสี่ยง

| ความเสี่ยง | การป้องกัน |
|-----------|------------|
| Upstream อัปเดต layout package | ใช้ `parent => 'default'` — merge เฉพาะไฟล์ที่ override |
| Tailwind ไม่จับ class ใน parent views | scan path รวม package Admin blades |
| Vue component พังเพราะแก้ DOM | อย่าเปลี่ยน `#app` structure / ชื่อ component |
| Asset 404 | ใช้ `bagisto_asset(..., 'admin')` หรือ viter ที่ลงทะเบียนถูกต้อง |
| สลับ theme แล้วเสีย | เก็บ `default` ไว้ + revert `admin-default` ได้ทันที |

---

## 8. สิ่งที่ไม่ทำใน scope นี้

- Redesign โครงสร้าง menu / เพิ่มหน้าใหม่
- เปลี่ยน DataGrid เป็น component อื่น
- SPA rewrite (ยังคง Blade + Vue hybrid ของ Bagisto)
- Admin theme selector ใน UI (ใช้ config พอ)
- Light/Dark toggle

---

## 9. ลำดับงานถัดไป (เมื่อ approve แผน)

1. สร้าง mockup HTML `docs/mockup/beyondary_admin_dashboard.html`
2. Phase 0 scaffold
3. Phase 2 layout (ข้าม implement จนกว่า mockup approve ได้ ถ้าต้องการ)

---

## 10. อ้างอิงไฟล์สำคัญ

| ไฟล์ | หมายเหตุ |
|------|----------|
| `config/themes.php` | ลงทะเบียน admin theme |
| `packages/Webkul/Theme/src/ThemeViewFinder.php` | activation |
| `packages/Webkul/Admin/src/Resources/views/components/layouts/` | ต้นฉบับ copy |
| `packages/Webkul/Admin/vite.config.js` | ต้นแบบ Vite |
| `resources/themes/beyondary/` | ต้นแบบ child theme + gitignore pattern |
| `.cursor/rules/bagisto-workflow.mdc` | ทดสอบทุก phase |
