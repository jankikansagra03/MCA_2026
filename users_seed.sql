-- =======================================================
-- users_seed.sql
-- Seed script to insert initial users into the `users` table
-- Default password for all seed accounts: Password@123 (except admin: Admin@123)
-- =======================================================

INSERT INTO `users` (`fullname`, `email`, `phone`, `password_hash`, `role`, `status`, `created_at`, `updated_at`)
VALUES
(
    'Admin User',
    'admin@trendcart.com',
    '+91 98765 00001',
    '$2y$10$yIA39yuNYSD.5abceMCkleeiaM.wy8xr9pzgrUjMXn3O.40s7tZTG', -- Admin@123
    'admin',
    'active',
    NOW(),
    NOW()
),
(
    'Janki Kansagra',
    'kansagrajanki@gmail.com',
    '+91 98765 43210',
    '$2y$10$ksWMqR4ALGAvlAem/hVd1OC2zsrrKei.azWWbjgYA6pf2inTGGwi2', -- Password@123
    'customer',
    'active',
    NOW(),
    NOW()
),
(
    'Aarav Patel',
    'aarav.patel@example.com',
    '+91 98765 12345',
    '$2y$10$ksWMqR4ALGAvlAem/hVd1OC2zsrrKei.azWWbjgYA6pf2inTGGwi2', -- Password@123
    'customer',
    'active',
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `fullname` = VALUES(`fullname`),
    `phone`    = VALUES(`phone`),
    `role`     = VALUES(`role`),
    `status`   = VALUES(`status`);
