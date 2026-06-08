<?php

return [
    'menu' => [
        'beyondary' => 'Beyondary',
        'homepage' => 'หน้าร้าน',
    ],

    'acl' => [
        'beyondary' => 'Beyondary',
        'homepage' => 'หน้าแรกหน้าร้าน',
        'edit' => 'แก้ section หน้าร้าน',
        'export' => 'ส่งออกหน้าร้าน',
        'import' => 'นำเข้าหน้าร้าน',
    ],

    'home' => [
        'title' => 'จัดการหน้าแรกหน้าร้าน',
        'subtitle' => 'แก้ section หน้าแรก beyondary สำหรับ :channel (theme: :theme)',
        'preview' => 'ดูหน้าร้าน',
        'active' => 'เปิดใช้งาน',
        'missing' => 'ยังไม่ตั้งค่า',
        'edit-section' => 'แก้ไข section',
        'footer-note' => 'แก้เมนูและ footer ได้จากด้านล่าง · CLI: export (ZIP), import, install-preset',
    ],

    'edit' => [
        'title' => 'แก้ไข section',
        'subtitle' => 'Channel: :channel',
        'back' => 'กลับ',
        'save' => 'บันทึก',
        'saved' => 'บันทึก section เรียบร้อย',
        'enabled' => 'แสดงบนหน้าแรก',
        'enabled-layout' => 'แสดงบนหน้าร้าน',
    ],

    'sections' => [
        'hero' => 'Hero / สไลด์รูปด้านบน',
        'trust' => 'Trust badges (จัดส่ง, ของแท้, ชำระเงิน)',
        'categories' => 'กริดหมวดหมู่สินค้า',
        'products' => 'คารูเซลสินค้า',
        'our_story' => 'บล็อก Our Story (รูป + ข้อความ)',
        'menu' => 'แถบประกาศและลิงก์เมนูหลัก',
        'footer' => 'ข้อความเกี่ยวกับเรา โซเชียล และคอลัมน์ Support',
    ],

    'forms' => [
        'common' => [
            'sort' => 'เรียงลำดับ',
            'limit' => 'จำนวนรายการ',
            'add_link' => 'เพิ่มลิงก์',
            'remove_link' => 'ลบ',
        ],
        'hero' => [
            'help' => 'อัปโหลดสไลด์ได้หลายรูป — ว่างรูปไว้เพื่อคงไฟล์เดิม',
            'slide' => 'สไลด์ :n',
            'title' => 'หัวข้อ',
            'link' => 'ลิงก์',
            'image' => 'รูปภาพ',
        ],
        'trust' => [
            'help' => 'แสดงได้สูงสุด 3 badges ใต้ hero',
            'badge' => 'Badge :n',
            'title' => 'หัวข้อ',
            'description' => 'คำอธิบาย',
            'icon' => 'ไอคอน',
        ],
        'categories' => [
            'title' => 'หัวข้อ section',
        ],
        'products' => [
            'title' => 'หัวข้อ section',
            'category_id' => 'Category ID (ไม่บังคับ)',
            'category_help' => 'ว่างไว้ = สินค้าทุกหมวด',
        ],
        'our_story' => [
            'title' => 'หัวข้อ',
            'highlight' => 'ข้อความเน้น (สีทอง)',
            'p1' => 'ย่อหน้า 1',
            'p2' => 'ย่อหน้า 2',
            'cta' => 'ข้อความปุ่ม',
            'cta_link' => 'ลิงก์ปุ่ม',
            'image' => 'รูป Our Story',
        ],
        'menu' => [
            'announcement' => 'ข้อความแถบประกาศด้านบน',
            'help' => 'ลิงก์เมนูหลักได้สูงสุด 5 รายการ (desktop และมือถือ)',
            'link' => 'ลิงก์ :n',
            'title' => 'ชื่อเมนู',
            'url' => 'URL',
        ],
        'footer' => [
            'about' => 'ข้อความเกี่ยวกับเรา',
            'facebook' => 'ลิงก์ Facebook',
            'instagram' => 'ลิงก์ Instagram',
            'pinterest' => 'ลิงก์ Pinterest',
            'support_links' => 'ลิงก์ Support',
            'support_help' => 'ลิงก์ในคอลัมน์ Support (column 2) — กดเพิ่มลิงก์หรือลบเพื่อปรับรายการ',
            'link_row' => 'ลิงก์ :n',
            'link_title' => 'ชื่อลิงก์',
            'link_url' => 'URL',
        ],
    ],

    'transfer' => [
        'export-zip' => 'ส่งออก ZIP',
        'export-json' => 'JSON อย่างเดียว',
        'import' => 'นำเข้า',
        'import-title' => 'นำเข้า archive',
        'import-help' => 'อัปโหลด ZIP (มีรูปใน storage/theme) หรือ JSON สำหรับ channel / instance อื่น',
        'preset-title' => 'Preset เริ่มต้น',
        'preset-help' => 'รีเซ็ตหน้าร้านเป็น layout beyondary มาตรฐาน (หน้าแรก, เมนู, footer)',
        'install-preset' => 'ติดตั้ง preset',
        'preset-installed' => 'ติดตั้ง preset แล้ว (:count sections)',
        'channel' => 'Channel ปลายทาง',
        'file' => 'ไฟล์ ZIP หรือ JSON',
        'replace' => 'แทนที่ section หน้าแรกเดิม',
        'imported' => 'นำเข้า :count section เรียบร้อย',
    ],
];
