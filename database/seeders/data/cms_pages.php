<?php

/**
 * CMS page copy for Beyondary (en + th).
 *
 * @return array<string, array<string, array{page_title: string, meta_title: string, meta_description: string, meta_keywords: string, html_content: string}>>
 */
return [
    'about-us' => [
        'en' => [
            'page_title' => 'About Us',
            'meta_title' => 'About Beyondary — Authentic Thai Handmade Crafts',
            'meta_description' => 'Discover Beyondary: curated Thai handmade products crafted by skilled artisans and shipped worldwide.',
            'meta_keywords' => 'beyondary, thai handmade, artisan, about us',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">About Beyondary</h1>
    <p class="mb-4 text-brand-earth">Beyondary is a premium destination for authentic Thai handmade crafts — from home décor and textiles to wellness goods and collectible art. Every piece is selected for its craftsmanship, cultural story, and quality you can trust.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Our Mission</h2>
    <p class="mb-4 text-brand-earth">We connect skilled Thai artisans with customers around the world, preserving traditional techniques while offering a seamless global shopping experience.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">What We Stand For</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>100% Authentic</strong> — Sourced directly from makers and trusted partners across Thailand.</li>
        <li><strong>Worldwide Shipping</strong> — Carefully packed and delivered to your door, wherever you are.</li>
        <li><strong>Secure Payment</strong> — Safe checkout with leading payment providers.</li>
        <li><strong>Thoughtful Curation</strong> — Each collection reflects premium Thai craft and modern living.</li>
    </ul>
    <p class="text-brand-earth">Thank you for supporting Thai craftsmanship. We are honoured to share these stories with you.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'เกี่ยวกับเรา',
            'meta_title' => 'เกี่ยวกับ Beyondary — งานหัตถกรรมไทยแท้',
            'meta_description' => 'รู้จัก Beyondary คัดสรรงานหัตถกรรมไทยจากช่างฝีมือทั่วประเทศ ส่งถึงมือคุณทั่วโลก',
            'meta_keywords' => 'beyondary, งานหัตถกรรมไทย, เกี่ยวกับเรา',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">เกี่ยวกับ Beyondary</h1>
    <p class="mb-4 text-brand-earth">Beyondary คือจุดหมายสำหรับงานหัตถกรรมไทยแท้ระดับพรีเมียม — ตั้งแต่ของตกแต่งบ้าน สิ่งทอ สปาและสุขภาพ ไปจนถึงงานศิลปะสะสม ทุกชิ้นคัดเลือกจากฝีมือ เรื่องราวท้องถิ่น และคุณภาพที่เชื่อถือได้</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">พันธกิจของเรา</h2>
    <p class="mb-4 text-brand-earth">เราเชื่อมช่างฝีมือไทยกับลูกค้าทั่วโลก อนุรักษ์เทคนิคดั้งเดิม พร้อมประสบการณ์ช้อปปิ้งออนไลน์ที่ราบรื่น</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สิ่งที่เรายึดมั่น</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>ของแท้ 100%</strong> — จากผู้ผลิตและพาร์ทเนอร์ที่ไว้ใจได้ทั่วประเทศไทย</li>
        <li><strong>จัดส่งทั่วโลก</strong> — บรรจุอย่างระมัดระวัง ส่งถึงคุณทุกที่</li>
        <li><strong>ชำระเงินปลอดภัย</strong> — รองรับช่องทางชำระเงินที่เชื่อถือได้</li>
        <li><strong>คัดสรรอย่างใส่ใจ</strong> — ทุกคอลเลกชันสะท้อนงานคราฟต์ไทยและไลฟ์สไตล์ร่วมสมัย</li>
    </ul>
    <p class="text-brand-earth">ขอบคุณที่สนับสนุนงานหัตถกรรมไทย เรายินดีที่ได้แบ่งปันเรื่องราวเหล่านี้กับคุณ</p>
</div>
HTML,
        ],
    ],

    'return-policy' => [
        'en' => [
            'page_title' => 'Return Policy',
            'meta_title' => 'Return Policy — Beyondary',
            'meta_description' => 'How to return items to Beyondary within 14 days of delivery.',
            'meta_keywords' => 'return policy, returns, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Return Policy</h1>
    <p class="mb-4 text-brand-earth">We want you to love every Beyondary purchase. If something is not right, you may return eligible items within <strong>14 days</strong> of delivery.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Eligible Items</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Unused, in original condition with all tags and packaging.</li>
        <li>Not personalised, made-to-order, or marked final sale.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">How to Start a Return</h2>
    <ol class="mb-4 list-decimal space-y-2 pl-6 text-brand-earth">
        <li>Log in to your account and open <strong>My Orders</strong>.</li>
        <li>Select the order and choose <strong>Return</strong> (or contact Customer Service).</li>
        <li>Pack items securely in original packaging where possible.</li>
        <li>Ship to the address we provide. Return shipping may apply unless the item is defective or incorrect.</li>
    </ol>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Processing Time</h2>
    <p class="text-brand-earth">Once we receive and inspect your return, we will notify you by email. Approved returns are refunded according to our <a href="/page/refund-policy" class="text-brand-gold underline">Refund Policy</a>.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'นโยบายการคืนสินค้า',
            'meta_title' => 'นโยบายการคืนสินค้า — Beyondary',
            'meta_description' => 'วิธีคืนสินค้า Beyondary ภายใน 14 วันหลังได้รับสินค้า',
            'meta_keywords' => 'คืนสินค้า, นโยบายคืนสินค้า, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">นโยบายการคืนสินค้า</h1>
    <p class="mb-4 text-brand-earth">เราต้องการให้คุณพอใจกับทุกการสั่งซื้อ หากสินค้าไม่ตรงความคาดหวัง คุณสามารถคืนสินค้าที่เข้าเงื่อนไขภายใน <strong>14 วัน</strong> หลังได้รับสินค้า</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สินค้าที่เข้าเงื่อนไข</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>ยังไม่ได้ใช้งาน สภาพเดิม ครบแท็กและบรรจุภัณฑ์</li>
        <li>ไม่ใช่สินค้าสั่งทำพิเศษ สินค้าสั่งผลิต หรือลดราคาสุดท้าย</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ขั้นตอนการคืนสินค้า</h2>
    <ol class="mb-4 list-decimal space-y-2 pl-6 text-brand-earth">
        <li>เข้าสู่ระบบและเปิด <strong>คำสั่งซื้อของฉัน</strong></li>
        <li>เลือกคำสั่งซื้อและกด <strong>คืนสินค้า</strong> (หรือติดต่อฝ่ายบริการลูกค้า)</li>
        <li>บรรจุสินค้าให้ปลอดภัย ใช้กล่องเดิมถ้าเป็นไปได้</li>
        <li>จัดส่งตามที่อยู่ที่เราแจ้ง ค่าส่งคืนอาจเป็นภาระของลูกค้า เว้นแต่สินค้าชำรุดหรือส่งผิด</li>
    </ol>
    <p class="text-brand-earth">เมื่อได้รับและตรวจสอบแล้ว เราจะแจ้งทางอีเมล การคืนเงินดำเนินการตาม <a href="/page/refund-policy" class="text-brand-gold underline">นโยบายการคืนเงิน</a></p>
</div>
HTML,
        ],
    ],

    'refund-policy' => [
        'en' => [
            'page_title' => 'Refund Policy',
            'meta_title' => 'Refund Policy — Beyondary',
            'meta_description' => 'Refund timelines and methods for Beyondary orders.',
            'meta_keywords' => 'refund policy, refunds, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Refund Policy</h1>
    <p class="mb-4 text-brand-earth">After your return is approved or a cancellation is confirmed, we process refunds to your original payment method.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Refund Timeline</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>Credit / debit card:</strong> 5–10 business days after processing (depending on your bank).</li>
        <li><strong>Other methods:</strong> As per provider guidelines.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Partial Refunds</h2>
    <p class="mb-4 text-brand-earth">Partial refunds may apply for items not in original condition, missing parts, or late returns beyond the 14-day window.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Shipping Costs</h2>
    <p class="text-brand-earth">Original shipping fees are non-refundable unless the return is due to our error (wrong or damaged item). Return shipping is the customer&apos;s responsibility unless otherwise stated.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'นโยบายการคืนเงิน',
            'meta_title' => 'นโยบายการคืนเงิน — Beyondary',
            'meta_description' => 'ระยะเวลาและวิธีคืนเงินสำหรับคำสั่งซื้อ Beyondary',
            'meta_keywords' => 'คืนเงิน, นโยบายคืนเงิน, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">นโยบายการคืนเงิน</h1>
    <p class="mb-4 text-brand-earth">เมื่อการคืนสินค้าได้รับการอนุมัติ หรือยกเลิกคำสั่งซื้อเรียบร้อย เราจะคืนเงินผ่านช่องทางที่คุณชำระเดิม</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ระยะเวลาคืนเงิน</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>บัตรเครดิต/เดบิต:</strong> 5–10 วันทำการหลังดำเนินการ (ขึ้นกับธนาคาร)</li>
        <li><strong>ช่องทางอื่น:</strong> ตามเงื่อนไขของผู้ให้บริการชำระเงิน</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">คืนเงินบางส่วน</h2>
    <p class="mb-4 text-brand-earth">อาจคืนเงินบางส่วนหากสินค้าไม่ครบ สภาพไม่ตรงเงื่อนไข หรือคืนหลัง 14 วัน</p>
    <p class="text-brand-earth">ค่าจัดส่งเดิมไม่คืน เว้นแต่เกิดจากความผิดพลาดของเรา ค่าส่งคืนเป็นภาระลูกค้า เว้นแต่ระบุไว้เป็นอย่างอื่น</p>
</div>
HTML,
        ],
    ],

    'terms-conditions' => [
        'en' => [
            'page_title' => 'Terms & Conditions',
            'meta_title' => 'Terms & Conditions — Beyondary',
            'meta_description' => 'Terms and conditions for shopping at Beyondary.',
            'meta_keywords' => 'terms, conditions, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Terms &amp; Conditions</h1>
    <p class="mb-4 text-brand-earth">By accessing and placing orders on Beyondary, you agree to these terms. Please read them carefully.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Orders &amp; Pricing</h2>
    <p class="mb-4 text-brand-earth">All prices are shown in the currency selected at checkout. We reserve the right to correct pricing errors and to limit quantities. An order is confirmed only after you receive an order confirmation email.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Product Descriptions</h2>
    <p class="mb-4 text-brand-earth">Handmade items may vary slightly in colour, size, or finish — this is part of their authentic character. We strive for accurate descriptions and images.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Limitation of Liability</h2>
    <p class="mb-4 text-brand-earth">Beyondary is not liable for indirect or consequential damages arising from use of our products or website, to the fullest extent permitted by law.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Governing Law</h2>
    <p class="text-brand-earth">These terms are governed by the laws of Thailand. Disputes shall be subject to the exclusive jurisdiction of Thai courts unless otherwise required by consumer protection law in your country.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'ข้อกำหนดและเงื่อนไข',
            'meta_title' => 'ข้อกำหนดและเงื่อนไข — Beyondary',
            'meta_description' => 'ข้อกำหนดและเงื่อนไขการใช้บริการร้าน Beyondary',
            'meta_keywords' => 'ข้อกำหนด, เงื่อนไข, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">ข้อกำหนดและเงื่อนไข</h1>
    <p class="mb-4 text-brand-earth">การเข้าใช้และสั่งซื้อที่ Beyondary ถือว่าคุณยอมรับข้อกำหนดเหล่านี้ โปรดอ่านอย่างละเอียด</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">คำสั่งซื้อและราคา</h2>
    <p class="mb-4 text-brand-earth">ราคาแสดงตามสกุลเงินที่เลือกตอนชำระเงิน เราสงวนสิทธิ์แก้ไขราคาที่ผิดพลาดและจำกัดจำนวน คำสั่งซื้อยืนยันเมื่อได้รับอีเมลยืนยันเท่านั้น</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">รายละเอียดสินค้า</h2>
    <p class="mb-4 text-brand-earth">งานทำมืออาจต่างกันเล็กน้อยในเรื่องสี ขนาด หรือพื้นผิว ซึ่งเป็นส่วนหนึ่งของความเป็นงานแท้ เราพยายามให้คำอธิบายและรูปภาพตรงที่สุด</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">กฎหมายที่ใช้บังคับ</h2>
    <p class="text-brand-earth">ข้อกำหนดนี้อยู่ภายใต้กฎหมายไทย เว้นแต่กฎหมายคุ้มครองผู้บริโภคในประเทศของคุณกำหนดเป็นอย่างอื่น</p>
</div>
HTML,
        ],
    ],

    'terms-of-use' => [
        'en' => [
            'page_title' => 'Terms of Use',
            'meta_title' => 'Terms of Use — Beyondary Website',
            'meta_description' => 'Rules for using the Beyondary website and services.',
            'meta_keywords' => 'terms of use, website, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Terms of Use</h1>
    <p class="mb-4 text-brand-earth">This website is operated by Beyondary. By using our site, you agree to use it lawfully and respectfully.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Permitted Use</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Browse products and place orders for personal or commercial use as allowed.</li>
        <li>Create an account with accurate information.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Prohibited Use</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Scraping, automated abuse, or attempts to disrupt the site.</li>
        <li>Uploading malicious code or infringing intellectual property.</li>
        <li>Reselling content or images without permission.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Intellectual Property</h2>
    <p class="text-brand-earth">All site content, logos, and product photography are owned by Beyondary or licensors. Unauthorised reproduction is prohibited.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'เงื่อนไขการใช้งาน',
            'meta_title' => 'เงื่อนไขการใช้งานเว็บไซต์ — Beyondary',
            'meta_description' => 'กฎการใช้งานเว็บไซต์และบริการ Beyondary',
            'meta_keywords' => 'เงื่อนไขการใช้งาน, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">เงื่อนไขการใช้งาน</h1>
    <p class="mb-4 text-brand-earth">เว็บไซต์นี้ดำเนินการโดย Beyondary การใช้งานถือว่าคุณยอมรับที่จะใช้อย่างถูกกฎหมายและสุภาพ</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">การใช้งานที่อนุญาต</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>เลือกชมและสั่งซื้อสินค้าตามวัตถุประสงค์ที่เหมาะสม</li>
        <li>สร้างบัญชีด้วยข้อมูลที่ถูกต้อง</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สิทธิ์ในทรัพย์สินทางปัญญา</h2>
    <p class="text-brand-earth">เนื้อหา โลโก้ และภาพสินค้าบนเว็บไซต์เป็นของ Beyondary หรือผู้ให้อนุญาต ห้ามคัดลอกโดยไม่ได้รับอนุญาต</p>
</div>
HTML,
        ],
    ],

    'customer-service' => [
        'en' => [
            'page_title' => 'Customer Service',
            'meta_title' => 'Customer Service — Beyondary',
            'meta_description' => 'Contact Beyondary for orders, shipping, returns, and product questions.',
            'meta_keywords' => 'customer service, contact, support, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Customer Service</h1>
    <p class="mb-4 text-brand-earth">We are here to help with orders, shipping, returns, and product questions. Our team typically responds within <strong>1–2 business days</strong>.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">How to Reach Us</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>Email:</strong> support@beyondary.com (replace with your live address in Admin settings).</li>
        <li><strong>Order help:</strong> Log in → My Account → My Orders → select your order.</li>
        <li><strong>Returns:</strong> See our <a href="/page/return-policy" class="text-brand-gold underline">Return Policy</a>.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Before You Write</h2>
    <p class="mb-4 text-brand-earth">Please include your order number, photos (if damaged), and a brief description so we can assist you faster.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Hours</h2>
    <p class="text-brand-earth">Monday – Friday, 9:00–18:00 (ICT, GMT+7). Messages received outside hours are answered on the next business day.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'บริการลูกค้า',
            'meta_title' => 'บริการลูกค้า — Beyondary',
            'meta_description' => 'ติดต่อ Beyondary เรื่องคำสั่งซื้อ การจัดส่ง การคืนสินค้า และสินค้า',
            'meta_keywords' => 'บริการลูกค้า, ติดต่อ, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">บริการลูกค้า</h1>
    <p class="mb-4 text-brand-earth">เราพร้อมช่วยเรื่องคำสั่งซื้อ การจัดส่ง การคืนสินค้า และคำถามเกี่ยวกับสินค้า โดยทั่วไปตอบกลับภายใน <strong>1–2 วันทำการ</strong></p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ช่องทางติดต่อ</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>อีเมล:</strong> support@beyondary.com (อัปเดตอีเมลจริงใน Admin)</li>
        <li><strong>คำสั่งซื้อ:</strong> เข้าสู่ระบบ → บัญชีของฉัน → คำสั่งซื้อ</li>
        <li><strong>การคืนสินค้า:</strong> ดู <a href="/page/return-policy" class="text-brand-gold underline">นโยบายการคืนสินค้า</a></li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">เวลาทำการ</h2>
    <p class="text-brand-earth">จันทร์–ศุกร์ 09:00–18:00 น. (เวลาประเทศไทย) ข้อความนอกเวลาจะได้รับการตอบในวันทำการถัดไป</p>
</div>
HTML,
        ],
    ],

    'whats-new' => [
        'en' => [
            'page_title' => "What's New",
            'meta_title' => "What's New — Beyondary",
            'meta_description' => 'Latest collections, arrivals, and updates from Beyondary.',
            'meta_keywords' => 'new arrivals, collections, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">What&apos;s New</h1>
    <p class="mb-4 text-brand-earth">Discover our latest handmade arrivals and seasonal highlights from artisans across Thailand.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">New Arrivals</h2>
    <p class="mb-4 text-brand-earth">Browse the <a href="/" class="text-brand-gold underline">homepage</a> and <strong>New Arrivals</strong> section for freshly added pieces — textiles, ceramics, spa essentials, and art &amp; collectibles.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Collections</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Home &amp; Living — handwoven décor and tableware.</li>
        <li>Fashion — artisan accessories and natural fabrics.</li>
        <li>Wellness &amp; Spa — botanical and traditional wellness goods.</li>
        <li>Art &amp; Collectibles — limited pieces for discerning collectors.</li>
    </ul>
    <p class="text-brand-earth">Subscribe to our newsletter on the homepage for launch alerts and exclusive offers.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'มีอะไรใหม่',
            'meta_title' => 'มีอะไรใหม่ — Beyondary',
            'meta_description' => 'คอลเลกชันและสินค้ามาใหม่ล่าสุดจาก Beyondary',
            'meta_keywords' => 'สินค้าใหม่, คอลเลกชัน, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">มีอะไรใหม่</h1>
    <p class="mb-4 text-brand-earth">พบกับสินค้ามาใหม่และไฮไลต์ตามฤดูกาลจากช่างฝีมือทั่วประเทศไทย</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สินค้ามาใหม่</h2>
    <p class="mb-4 text-brand-earth">ชมที่ <a href="/" class="text-brand-gold underline">หน้าแรก</a> และส่วน <strong>New Arrivals</strong> — สิ่งทอ เซรามิก สปา และงานศิลปะสะสม</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">หมวดหมู่เด่น</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Home &amp; Living — ของตกแต่งและเครื่องใช้บนโต๊ะ</li>
        <li>Fashion — เครื่องประดับและผ้าธรรมชาติ</li>
        <li>Wellness &amp; Spa — สินค้าสุขภาพและสมุนไพร</li>
        <li>Art &amp; Collectibles — งานลิมิเต็ดสำหรับนักสะสม</li>
    </ul>
    <p class="text-brand-earth">สมัครรับข่าวสารที่หน้าแรกเพื่อรับข้อมูลเปิดตัวสินค้าและโปรโมชัน</p>
</div>
HTML,
        ],
    ],

    'payment-policy' => [
        'en' => [
            'page_title' => 'Payment Policy',
            'meta_title' => 'Payment Policy — Beyondary',
            'meta_description' => 'Accepted payment methods and security at Beyondary.',
            'meta_keywords' => 'payment, checkout, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Payment Policy</h1>
    <p class="mb-4 text-brand-earth">We offer secure checkout with trusted payment partners. All transactions are encrypted (SSL/TLS).</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Accepted Methods</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Credit and debit cards (Visa, Mastercard, and others as enabled in checkout).</li>
        <li>Digital wallets and regional methods shown at payment step.</li>
        <li>PayPal / Stripe / other gateways configured in your store.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Currency</h2>
    <p class="mb-4 text-brand-earth">Prices may be displayed in THB or your selected currency. Your bank may apply conversion fees for international cards.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Failed or Duplicate Charges</h2>
    <p class="text-brand-earth">If a payment fails, your order will not be processed. Pending authorisations are released by your bank according to their policy. Contact us with your order reference if you see an unexpected charge.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'นโยบายการชำระเงิน',
            'meta_title' => 'นโยบายการชำระเงิน — Beyondary',
            'meta_description' => 'ช่องทางชำระเงินและความปลอดภัยที่ Beyondary',
            'meta_keywords' => 'ชำระเงิน, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">นโยบายการชำระเงิน</h1>
    <p class="mb-4 text-brand-earth">ชำระเงินอย่างปลอดภัยผ่านพาร์ทเนอร์ที่เชื่อถือได้ ทุกธุรกรรมเข้ารหัส (SSL/TLS)</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ช่องทางที่รองรับ</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>บัตรเครดิตและเดบิต (Visa, Mastercard และอื่นๆ ตามที่เปิดใน checkout)</li>
        <li>วอลเล็ตและช่องทางในประเทศตามที่แสดงขั้นตอนชำระเงิน</li>
        <li>PayPal / Stripe / เกตเวย์อื่นที่ตั้งค่าในร้าน</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สกุลเงิน</h2>
    <p class="text-brand-earth">ราคาอาจแสดงเป็น THB หรือสกุลที่เลือก ธนาคารอาจคิดค่าธรรมเนียมแปลงสกุลเงินสำหรับบัตรต่างประเทศ</p>
</div>
HTML,
        ],
    ],

    'shipping-policy' => [
        'en' => [
            'page_title' => 'Shipping Policy',
            'meta_title' => 'Shipping Policy — Worldwide Delivery',
            'meta_description' => 'Beyondary shipping rates, delivery times, and packaging for handmade goods.',
            'meta_keywords' => 'shipping, delivery, worldwide, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Shipping Policy</h1>
    <p class="mb-4 text-brand-earth">We ship handmade treasures worldwide with care. Free or promotional shipping may apply — see the announcement bar and checkout for current offers.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Processing Time</h2>
    <p class="mb-4 text-brand-earth">Orders are typically processed within <strong>1–3 business days</strong>. Made-to-order or fragile items may require additional handling time (noted on the product page).</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Delivery Estimates</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>Thailand domestic:</strong> 2–5 business days after dispatch.</li>
        <li><strong>Asia-Pacific:</strong> 5–12 business days.</li>
        <li><strong>Europe / Americas:</strong> 10–20 business days (customs may add delay).</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Packaging</h2>
    <p class="mb-4 text-brand-earth">Fragile crafts are packed with protective materials to minimise damage in transit.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Tracking</h2>
    <p class="text-brand-earth">You will receive a tracking number by email when your order ships. Contact Customer Service if tracking does not update within 48 hours of the ship notification.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'นโยบายการจัดส่ง',
            'meta_title' => 'นโยบายการจัดส่ง — ส่งทั่วโลก',
            'meta_description' => 'อัตราค่าจัดส่ง ระยะเวลา และการบรรจุของ Beyondary',
            'meta_keywords' => 'จัดส่ง, ส่งทั่วโลก, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">นโยบายการจัดส่ง</h1>
    <p class="mb-4 text-brand-earth">เราจัดส่งงานหัตถกรรมทั่วโลกอย่างระมัดระวัง อาจมีโปรจัดส่งฟรี — ดูแถบประกาศและหน้า checkout</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ระยะเวลาจัดเตรียม</h2>
    <p class="mb-4 text-brand-earth">โดยทั่วไป <strong>1–3 วันทำการ</strong> สินค้าสั่งทำหรือของเปราะอาจใช้เวลานานขึ้น (ระบุในหน้าสินค้า)</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ระยะเวลาจัดส่งโดยประมาณ</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li><strong>ในประเทศไทย:</strong> 2–5 วันทำการหลังส่งออก</li>
        <li><strong>เอเชียแปซิฟิก:</strong> 5–12 วันทำการ</li>
        <li><strong>ยุโรป / อเมริกา:</strong> 10–20 วันทำการ (ศุลกากรอาจล่าช้า)</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">การติดตามพัสดุ</h2>
    <p class="text-brand-earth">ได้รับเลขพัสดุทางอีเมลเมื่อจัดส่งแล้ว หากสถานะไม่อัปเดตภายใน 48 ชม. หลังแจ้งส่ง กรุณาติดต่อฝ่ายบริการลูกค้า</p>
</div>
HTML,
        ],
    ],

    'privacy-policy' => [
        'en' => [
            'page_title' => 'Privacy Policy',
            'meta_title' => 'Privacy Policy — Beyondary',
            'meta_description' => 'How Beyondary collects, uses, and protects your personal data.',
            'meta_keywords' => 'privacy policy, personal data, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">Privacy Policy</h1>
    <p class="mb-4 text-brand-earth">Beyondary respects your privacy. This policy explains what we collect, why, and your rights.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Information We Collect</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Account details: name, email, phone (optional).</li>
        <li>Order and shipping addresses.</li>
        <li>Payment information processed by secure payment providers (we do not store full card numbers).</li>
        <li>Usage data: cookies, device type, pages visited (see cookie settings in your browser).</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">How We Use It</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>Fulfil orders and provide customer support.</li>
        <li>Send order updates and, with consent, marketing emails.</li>
        <li>Improve our website and prevent fraud.</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Sharing</h2>
    <p class="mb-4 text-brand-earth">We share data only with service providers (shipping, payment, email) as needed to operate the store. We do not sell your personal information.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Your Rights</h2>
    <p class="mb-4 text-brand-earth">You may request access, correction, or deletion of your data by contacting Customer Service. You may unsubscribe from marketing at any time.</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">Contact</h2>
    <p class="text-brand-earth">For privacy enquiries: privacy@beyondary.com (update in Admin). Last updated: 2026.</p>
</div>
HTML,
        ],
        'th' => [
            'page_title' => 'นโยบายความเป็นส่วนตัว',
            'meta_title' => 'นโยบายความเป็นส่วนตัว — Beyondary',
            'meta_description' => 'การเก็บ ใช้ และปกป้องข้อมูลส่วนบุคคลของ Beyondary',
            'meta_keywords' => 'ความเป็นส่วนตัว, ข้อมูลส่วนบุคคล, beyondary',
            'html_content' => <<<'HTML'
<div class="static-container">
    <h1 class="mb-4 text-2xl font-semibold text-brand-dark">นโยบายความเป็นส่วนตัว</h1>
    <p class="mb-4 text-brand-earth">Beyondary เคารพความเป็นส่วนตัวของคุณ นโยบายนี้อธิบายข้อมูลที่เก็บ วัตถุประสงค์ และสิทธิของคุณ</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">ข้อมูลที่เราเก็บ</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>บัญชี: ชื่อ อีเมล เบอร์โทร (ถ้ามี)</li>
        <li>ที่อยู่จัดส่งและออกใบกำกับ</li>
        <li>การชำระเงินผ่านผู้ให้บริการที่ปลอดภัย (เราไม่เก็บเลขบัตรเต็ม)</li>
        <li>ข้อมูลการใช้งาน: คุกกี้ ประเภทอุปกรณ์ หน้าที่เข้าชม</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">การใช้ข้อมูล</h2>
    <ul class="mb-4 list-disc space-y-2 pl-6 text-brand-earth">
        <li>ดำเนินการคำสั่งซื้อและบริการลูกค้า</li>
        <li>แจ้งสถานะคำสั่งซื้อ และการตลาด (เมื่อได้รับความยินยอม)</li>
        <li>ปรับปรุงเว็บไซต์และป้องกันการฉ้อโกง</li>
    </ul>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">การเปิดเผย</h2>
    <p class="mb-4 text-brand-earth">แชร์เฉพาะกับผู้ให้บริการที่จำเป็น (ขนส่ง ชำระเงิน อีเมล) เราไม่ขายข้อมูลส่วนบุคคลของคุณ</p>
    <h2 class="mb-2 text-xl font-semibold text-brand-dark">สิทธิของคุณ</h2>
    <p class="text-brand-earth">ขอเข้าถึง แก้ไข หรือลบข้อมูลได้โดยติดต่อฝ่ายบริการลูกค้า ยกเลิกรับการตลาดได้ทุกเมื่อ — privacy@beyondary.com (อัปเดตใน Admin)</p>
</div>
HTML,
        ],
    ],
];
