# GymZ - Product Requirements Document (PRD) & Architecture

## 1. Project Overview
GymZ is a PWA (Progressive Web App) fitness companion platform designed to connect gym-goers in real-time. It solves the "Cold Start" and "Disintermediation" problems by focusing on a rolling 24-hour intent feed, temporary connections, and strict in-person verification.
* **Initial Test Bed (Soft Launch):** Banha, Egypt.

## 2. Tech Stack
* **Backend Core:** Laravel 12
* **Frontend:** TALL Stack (Tailwind CSS, Alpine.js, Laravel Livewire 4)
* **Admin Panel:** FilamentPHP 5
* **PWA:** Fully configured for standalone mode (iOS/Android) with offline fallback.
* **UI/UX Design Language:** Glassmorphism (Liquid Glass) with a strict Native Mobile feel (Bottom Navigation Bar).

## 3. Core Business Logic & Constraints
* **Gender Separation:** Strict isolation. Males only see male feeds/requests; Females only see female feeds/requests (`where('gender', auth()->user()->gender)`).
* **The 24-Hour Rolling Feed:** Users can only see workout intents scheduled within the next 24 hours. Past intents are automatically hidden.
* **No Solo Workouts:** The platform strictly tracks *shared* workouts. Solo hours cannot be logged to maintain the integrity of the core value proposition.
* **Verification (Anti-Cheat):** Workouts are only considered "Verified" and added to a user's total hours if a QR Code is scanned between both users inside the gym.
* **Gamification:** The platform utilizes a custom points system called "Glutes". Points are awarded for verified workouts and high reliability scores.
* **Guest Passes:** A core USP. Users with gym guest passes can explicitly tag their intents to invite others.

## 4. App Navigation & Structure (Bottom Nav)
1. **Feed (Home):** The 24-hour rolling intent list with quick filters (Soon, Tonight, Tomorrow) and blurred info for guests.
2. **Requests:** Incoming/Outgoing connection requests.
3. **Active Session / Chat:** Temporary coordination area (disappears 24 hours post-workout). Holds the QR Code scanner/generator.
4. **Profile:** The user's "Fitness CV" displaying Verified Hours, Top Muscles, Reliability Score, and badges.

## 5. Development CLI Instructions (System Prompt)
* **Role:** This CLI acts as the executor (Muscle). Do not invent new features or modify this architecture without explicit instructions from the user.
* **Context Restoration:** You MUST read this `GEMINI.MD` file at the start of every session or terminal restart to understand the project's boundaries, tech stack, and logic.

## 6. Git & Workflow Automation (Strict Rules for CLI)
* **Version Control Mandate:** Upon successfully implementing and verifying a new feature, fix, or refactor based on user instructions, you MUST automatically handle the Git workflow using the terminal:
    1. Create/Checkout a descriptive branch (e.g., `feature/add-glutes-system`).
    2. Stage all changes: `git add .`
    3. Commit using Conventional Commits formatting: `git commit -m "feat: added glutes gamification system"`
    4. Push the changes: `git push origin <branch-name>`
