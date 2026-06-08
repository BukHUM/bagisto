# Bagisto — Project Documentation

เอกสารภายในโปรเจกต์ Beyondary/Bagisto local instance

## โครงสร้าง

| เอกสาร | คำอธิบาย |
|--------|----------|
| [performance-analysis.md](performance-analysis.md) | วิเคราะห์ performance, indexes, สิ่งที่ดำเนินการแล้ว |
| [upgrade.md](upgrade.md) | คู่มืออัปเกรด Bagisto (v2.3 → v2.4 ฯลฯ) |
| [development/agents.md](development/agents.md) | คำแนะนำสำหรับ AI agents (โครงสร้าง repo, commands) |
| [development/customizations.md](development/customizations.md) | สิ่งที่แก้นอก core + แผนอัปเกรด Bagisto |
| [development/admin-theme-plan.md](development/admin-theme-plan.md) | แผนพัฒนา Beyondary Admin theme (semi-dark UX/UI) |
| [development/claude.md](development/claude.md) | คำแนะนำสำหรับ Claude Code |
| [mockup/thai_handmade_global_store.html](mockup/thai_handmade_global_store.html) | HTML mockup ต้นฉบับ — Beyondary storefront |

## Cursor Rules

กฎการพัฒนาที่บังคับใช้ทุก session อยู่ที่:

- `.cursor/rules/bagisto-customization.mdc` — แก้ไขแบบ upgrade-safe (ห้ามแตะ core โดยไม่จำเป็น)
- `.cursor/rules/bagisto-workflow.mdc` — workflow ทั้งโปรเจกต์ (todo list, ทดสอบทีละ phase)
- `.cursor/rules/bagisto-performance.mdc` — performance
- `.cursor/rules/bagisto-security.mdc` — security
- `.cursor/rules/theme.mdc` — Beyondary theme (อ้างอิง `docs/mockup/`)

## เอกสารที่ root (มาตรฐาน upstream)

ไฟล์เหล่านี้คงไว้ที่ root ตาม convention ของ GitHub / Bagisto:

- `README.md` — ภาพรวมโปรเจกต์
- `SECURITY.md` — รายงานช่องโหว่
- `CONTRIBUTING.md` — แนวทาง contribute
- `CODE_OF_CONDUCT.md` — จรรยาบรรณ

ไฟล์ `UPGRADE.md`, `AGENTS.md`, `CLAUDE.md` ที่ root เป็น stub ชี้มาที่โฟลเดอร์นี้
