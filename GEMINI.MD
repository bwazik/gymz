# GymZ - Product Requirements Document (PRD) & Architecture

## 1. Project Overview
GymZ is a PWA (Progressive Web App) fitness companion platform designed to connect gym-goers in real-time. It solves the "Cold Start" and "Disintermediation" problems by focusing on a rolling 24-hour intent feed, temporary connections, and strict in-person verification.
* **Initial Test Bed (Soft Launch):** Banha, Egypt.

## 2. Tech Stack
* **Backend Core:** Laravel 12
* **Frontend:** TALL Stack (Tailwind CSS, Alpine.js, Laravel Livewire 4)
* **Auth:** Google Socialite + Custom Onboarding Wizard
* **PWA:** Fully configured for standalone mode (iOS/Android) with offline fallback.

## 3. UI/UX Design System (STRICT RULES - LIQUID GLASS)
The application strictly follows an "Apple Minimalist Liquid Glass" aesthetic.
* **DO NOT** use generic Tailwind or default Laravel Breeze UI.
* **Glass Panels (Cards/Modals):** Must use `bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10`.
* **Border Radius:** Use heavily rounded corners. `rounded-[2rem]` or `rounded-3xl` for main cards, `rounded-2xl` for inputs/buttons.
* **Buttons:** Solid accent colors without gradients. e.g., `bg-gymz-accent text-white font-bold rounded-2xl py-3.5 active:scale-95 transition-all`.
* **Inputs:** Native iOS Settings style. Grouped inputs separated by `border-b` without individual outer borders. Naked inputs: `bg-transparent border-0 focus:ring-0`. Fix autofill using: `[&:-webkit-autofill]:[transition:background-color_999999s]`.
* **Feedback:** NO native browser alerts or standard Laravel session flashes. Use the custom global `<x-toaster>` for success/info, and `<x-ios-alert>` for destructive confirmations.
* **Modals:** Must slide up from the bottom (Bottom Sheets) honoring `env(safe-area-inset-bottom)` or appear as centered iOS Prompt alerts.

## 4. Core Business Logic & Constraints
* **Gender Separation:** Strict isolation. Males only see male feeds/requests; Females only see female feeds/requests (`where('gender', auth()->user()->gender)`).
* **The 24-Hour Rolling Feed:** Users can only see workout intents scheduled within the next 24 hours. Past intents are hidden.
* **No Solo Workouts:** The platform strictly tracks *shared* workouts.
* **Verification (Anti-Cheat):** Workouts are only "Verified" if a Dynamic QR Code is scanned between users inside the gym. Sessions MUST be at least 30-90 minutes long to claim rewards.
* **Gamification:** "Glutes" points are awarded for verified workouts.
* **Guest Access:** Non-authenticated users can view the Feed (`/`) but must log in to interact.

## 5. App Navigation & Structure (Bottom Nav)
1. **Feed (Home ` / `):** The 24-hour rolling intent list.
2. **Requests:** Incoming/Outgoing connection requests with Tabbed Segmented Controls.
3. **Active Session:** QR Code scanner/generator and 30-min Anti-Cheat timer.
4. **Profile:** User's "Fitness CV", Settings, and Wallet.

## 6. Development CLI Instructions (System Prompt)
* **Role:** This CLI acts as the executor (Muscle). Do not invent new features or modify this architecture without explicit instructions from the user.
* **Context Restoration:** You MUST read this `GEMINI.MD` file at the start of every session or terminal restart to understand the project's boundaries, UI guidelines, and logic.
* **Clean Code:** Always use Livewire Form Objects for complex forms. Extract logic into small, private methods. Keep components strictly typed. Use `RateLimiter` on all public actions.

## 7. Git & Workflow Automation (Strict Rules for CLI)
* **Version Control Mandate:** Handle Git automations directly on the **CURRENT BRANCH**.
    1. DO NOT create new branches automatically. Stay on the current branch (e.g., `main`).
    2. Stage changes: `git add .`
    3. Commit using Conventional Commits: `git commit -m "feat: description"`
    4. Push to origin: `git push`
