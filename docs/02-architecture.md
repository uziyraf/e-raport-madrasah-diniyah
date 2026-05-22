# Architecture

## Architecture Style

The project uses a modular monolith architecture with Laravel.

## Main Stack

- Laravel
- Blade
- Tailwind CSS
- MySQL
- Spatie Laravel Permission
- Laravel Excel
- Laravel DomPDF
- Database queue

## Backend Rules

Controllers should stay thin.

Business logic should be placed in:

- `app/Services`
- `app/Actions`
- `app/Jobs`

Validation should be placed in Form Request classes.

Authorization should use:

- middleware
- policies
- gates
- Spatie roles and permissions

## Frontend Rules

Use Blade and Tailwind CSS.

Reusable UI should be placed in Blade components or partials.

Recommended structure:

```text
resources/views/layouts/
resources/views/partials/
resources/views/components/
resources/views/pages/