# Sarthi Enterprise Platform

Sarthi is an enterprise multi-role service management platform built with Laravel 12 + Inertia React.

## Architecture Highlights

- Domain-driven service layer in `app/Domain/Services`
- Thin controllers delegating to domain services
- Role based access controls at middleware + policy + domain layer
- Strict service request lifecycle transitions
- Real-time transport-ready request chat event broadcasting

## Roles

- `admin`
- `staff`
- `customer`
- `affiliate`

## Request Lifecycle

`pending -> assigned -> in_progress -> needs_info -> reviewing -> completed`

`flagged_revoke` is terminal for operational revocation.

## Core Modules

- Service requests and assignment flow
- Staff/customer request chat with read receipts in DB
- Invoice generation workflow
- Support ticket system
- Staff todo operations

## Development Setup

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

## Broadcasting

Set the Pusher/Soketi variables in `.env`:

- `BROADCAST_CONNECTION=pusher`
- `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`
- `PUSHER_HOST`, `PUSHER_PORT`, `PUSHER_SCHEME`

Presence authorization lives in `routes/channels.php`.

## Production Readiness Notes

- Switch to MySQL in production with identical schema and FK enforcement.
- Use queue workers for broadcast/event scaling.
- Keep websocket infra (Soketi/Pusher) horizontally scalable.
