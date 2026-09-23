# Sirkelku UI Theme Specification & Rules (PERMANENT LOCK)

> **CRITICAL INSTRUCTION**: 
> Never revert, overwrite, or change this UI color palette unless the user EXPLICITLY requests a color theme change in a new prompt. Do not introduce neon gradients, overly saturated orange/amber colors, or unstyled dark-mode contrast glitches.

## 1. Color Palette: 'Charcoal & Sage Green'
- **Background Utama (Page Background)**:
  - Token: `#F8FAFC` (`slate-50`)
  - Class: `bg-[#F8FAFC]` or `bg-slate-50`
- **Warna Struktur & Teks Dominan (Structure & Dominant Text)**:
  - Dark Charcoal: `#1E293B` (`slate-800`) or `#0F172A` (`slate-900`) for headers, body text, sidebar titles, and structural borders (`#E2E8F0` / `slate-200`).
- **Warna Aksen & CTA (Primary Accent & Buttons)**:
  - Muted Sage: `#588157` (Solid emerald-700/green-700 solid)
  - Hover: `#476A46`
  - Active: `#385437`
  - Light Sage Tints: `#EAF0EA` or `bg-emerald-50` for hobby pills, subtle highlights, active states.
  - Text on Light Sage: `#2D472C` or `text-emerald-800`.
- **Surface / Cards**:
  - Pure White `#FFFFFF` with thin neutral border `#E2E8F0` (`border-slate-200`), without heavy drop shadows (`shadow-xs` / `shadow-sm`).
- **Room Chat**:
  - Sender Bubble: Muted Sage `#588157` with white text.
  - Receiver Bubble: Light Neutral `#F1F5F9` with Dark Charcoal `#1E293B` text.

## 2. Predefined CSS Classes (`resources/css/sirkel-theme.css`)
- `.sk-card` & `.sk-card-compact`: White background, `border-slate-200`, `shadow-xs`.
- `.sk-btn-primary`: Background `#588157`, hover `#476A46`, active `#385437`, text white.
- `.sk-btn-outline`: Border `slate-200`, text `slate-700`, hover `#588157` and border `emerald-300`.
- `.sk-pill`: Pill with `bg-emerald-50 text-emerald-800 border-emerald-100`.
- `.sk-badge-sage`: Sage badge with `bg-emerald-100 text-emerald-800 border-emerald-200`.
- `.sk-input`: Clean input with `border-slate-300` and focus ring `#588157`.
- `.sk-chat-sender` & `.sk-chat-receiver`: Consistent chat bubble styling.

## 3. Pagination Styling
- Never use `dark:bg-gray-800` or OS-triggered dark blocks.
- Active page must use `#588157` with white text.
- Inactive pages use white background with `border-slate-200` and `text-slate-700`.
