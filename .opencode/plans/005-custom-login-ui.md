# Plan: Custom Login UI

## Goal

Customize the Breeze login page to match SIMADU UI guidelines.

## Scope

Customize:

- guest layout
- login page
- login branding
- basic color, typography, and layout

## Rules

- Do not change authentication logic.
- Do not change login controller.
- Do not change routes.
- Do not enable public registration.
- Do not implement guardian registration.
- Do not create academic features.
- Keep login using email and password for now.
- Follow docs/05-ui-guidelines.md.
- Use SIMADU branding.
- Use teal/emerald/orange earthy pesantren color direction.

## Expected Files

Possible files:

- resources/views/layouts/guest.blade.php
- resources/views/auth/login.blade.php
- resources/views/components/application-logo.blade.php
- tailwind.config.js if needed

## Acceptance Criteria

- Login page looks closer to SIMADU design.
- Login still works.
- Register link is not shown.
- Forgot password link may stay.
- No auth logic is broken.