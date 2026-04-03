---
name: senior-web-engineer
description: Guarantees website design and code quality that matches 10+ year senior developer standards. Use this skill whenever building or refining web applications, landing pages, or UI components to ensure premium SaaS aesthetics (Stripe, Linear, Vercel), modular code architecture, and high-quality production patterns.
---

# Senior Web Engineer

A professional-grade skill for building high-quality, production-ready web applications. This skill forces the AI to output code and designs that look "handcrafted" and reflect a deep understanding of modern web engineering principles.

## Design Quality Standards

### 1. Visual Aesthetics
- **Inspiration**: Draw direct inspiration from Stripe, Linear, Vercel, and Notion.
- **Color Palettes**: Avoid generic colors. Use HSL-tailored, harmonious color schemes. Use deep grays, subtle accents, and curated gradients (avoid heavy or outdated ones).
- **Typography**: 
  - Use high-quality fonts (e.g., Inter, Outfit, Roboto, or system sans-serif font families).
  - Define a clear hierarchy: `text-xs`, `text-sm`, `text-base`, `text-lg`, `text-xl`, `text-2xl`, up to `text-6xl` for impact.
  - Pay attention to line height (`leading-relaxed`) and letter spacing (`tracking-tight`).

### 2. Spacing and Grid (The 8px Rule)
- All dimensions, padding, and margins MUST follow a strict 8px logic (e.g., 4px, 8px, 16px, 24px, 32px, 48px, 64px).
- Maintain consistent white space to avoid cluttered layouts. Use "breathing room" strategically.

### 3. Glassmorphism and Depth
- Use subtle borders (`border-white/10`) and background blurs (`backdrop-blur-md`) on cards and navigation overlays.
- Use layered shadows (`shadow-sm`, `shadow-md`, `shadow-xl`) to create a sense of depth and hierarchy.

---

## UI/UX Blueprint Patterns

### Required Sections for Landing Pages
1. **Hero**: High-impact headline, clear subtext, and a primary call-to-action (CTA).
2. **Features Grid**: Modular cards explaining key value propositions.
3. **Product Section**: A demonstration or visual mockup of the product in action.
4. **Testimonials**: Social proof with professional profiles.
5. **CTA Section**: A final, high-converting prompt to action.
6. **Footer**: Well-structured navigation with necessary links and branding.

### Functional Principles
- **Mobile-First**: Always design for small screens first, then scale up using Tailwind's responsive breakpoints.
- **Strong Alignment**: Center content properly or use precise grid/flexbox positioning.
- **Interactive Elements**: Add subtle micro-animations (hover transitions, scaling, color shifts) to make the UI feel alive.

---

## Code Standards

### 1. Clean and Modular Architecture
- Organize code into logical components (e.g., `Header`, `Hero`, `FeatureCard`, `Button`).
- Avoid "God components" that do too much. Keep logic and style separated where appropriate.
- Follow the DRY (Don't Repeat Yourself) principle, but prioritize readability over over-abstraction.

### 2. Tailwind CSS Standards
- Use Tailwind utility classes correctly. 
- **Avoid Long Class Chains**: For highly reused styles, prefer extracting components or using a simple `@apply` in CSS only if necessary.
- **Group Related Classes**: List layout first (flex, grid), then size, then colors/typography, then interaction.
- **Responsive Utilities**: Group responsive classes together at the end of the class list.

---

## Anti-AI Detection Rules

To ensure the design feels handcrafted and intentional:
1. **Avoid Generic Placeholders**: Do not use "Lorem Ipsum". Use realistic data, names, and scenarios.
2. **Realistic Naming**: Use industry-standard variable and file naming conventions (e.g., `primaryActionBtn`, `useAuth`, `DashboardLayout.tsx`).
3. **Micro-details**: Add small, deliberate details like custom scrollbars, subtle hover effects, and precise focus rings.
4. **Intentional Imperfection**: Slight variations in positioning or unique grid layouts can often feel more "human" than perfectly symmetrical, sterile blocks.

---

## Examples

### Example 1: Navigation Bar (AI vs Senior)
**AI Pattern (Generic):**
```html
<nav class="bg-blue-500 p-4">
  <div class="container mx-auto">
    <a href="#" class="text-white">Home</a>
    <a href="#" class="text-white">About</a>
  </div>
</nav>
```

**Senior Developer Pattern (Premium):**
```html
<nav class="sticky top-0 z-50 w-full border-b border-white/10 bg-slate-950/80 backdrop-blur-xl px-6 py-4">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <div class="flex items-center gap-8">
      <span class="text-xl font-bold tracking-tighter text-white uppercase italic">Logo</span>
      <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-400">
        <a href="#" class="hover:text-white transition-colors duration-200">Resources</a>
        <a href="#" class="hover:text-white transition-colors duration-200">Pricing</a>
      </div>
    </div>
    <button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-full shadow-[0_0_20px_-5px_rgba(79,70,229,0.5)] transition-all">
      Get Started
    </button>
  </div>
</nav>
```

---

## Constraints
- Never use generic, low-quality templates.
- Always verify accessibility (color contrast, alt tags, keyboard navigation).
- Ensure fast performance and clean, error-free logs.
- Prioritize high-level UI/UX principles over simple functionality.
