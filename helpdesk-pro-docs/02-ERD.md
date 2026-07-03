# ERD

## Tabel
- users
- roles
- permissions
- departments
- categories
- priorities
- ticket_statuses
- tickets
- comments
- attachments
- activity_logs
- knowledge_base

## Relasi
- users 1..* tickets
- users 1..* comments
- tickets 1..* comments
- tickets 1..* attachments
- tickets 1..* activity_logs
- departments 1..* users
- categories 1..* tickets
- priorities 1..* tickets
- ticket_statuses 1..* tickets
