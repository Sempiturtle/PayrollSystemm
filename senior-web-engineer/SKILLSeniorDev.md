---
name: senior-web-engineer
description: Make web app look premium (Stripe, Linear, Vercel). Clean code, good UI.
---

# Senior Web Engineer

Make website look premium. No look like AI write.

## Design

### 1. Visuals
- **Vibe**: Stripe, Linear, Vercel, Notion.
- **Color**: Tailored HSL. No basic red/blue/green. Deep gray, clean gradients, nice accent.
- **Font**: Inter, Outfit, system-sans. Strong size hierarchy (`text-xs` to `text-6xl`). Tight letter spacing.

### 2. Spacing (8px rule)
- Padding, margin, size must be multiply of 8px (4, 8, 16, 24, 32, 48, 64). Keep breathing room.

### 3. Polish
- Glassmorphism: `border-white/10`, `backdrop-blur-md`.
- Layered shadow (`shadow-sm`, `shadow-xl`) for depth.

---

## Page Layout

### Sections
1. **Hero**: Big hook, subtext, primary CTA.
2. **Features**: Card grid.
3. **Demo**: Product preview/mockup.
4. **Social**: Testimonials.
5. **CTA**: Bottom action.
6. **Footer**: Clean links, copyright.

### Interaction
- Mobile first always. Responsive.
- Micro-animations: hover scale, color shift, transition.

---

## Code Standard

### Clean Code
- Modular components (Header, Hero, FeatureCard). No god file.
- Clean Tailwind. Group classes: Layout -> Size -> Colors -> Hover -> Responsive.

---

## Anti-AI Rules

- No "Lorem Ipsum". Use real names, text.
- Standard file/variable names (`primaryActionBtn`, `DashboardLayout.tsx`).
- Little details: custom scroll, focus ring, small imperfections for human feel.

---

## Examples

### Nav Bar (Bad vs Good)
Bad: `<nav class="bg-blue-500 p-4"><div class="container mx-auto"><a href="#" class="text-white">Home</a></div></nav>`

Good: `<nav class="sticky top-0 z-50 w-full border-b border-white/10 bg-slate-950/80 backdrop-blur-xl px-6 py-4"><div class="max-w-7xl mx-auto flex items-center justify-between"><span class="text-xl font-bold tracking-tighter text-white">Logo</span><button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full shadow-[0_0_20px_-5px_rgba(79,70,229,0.5)] transition-all">Start</button></div></nav>`

---

## Rules
- No generic template.
- Check accessibility (contrast, aria).
- Fast load, no console errors.
