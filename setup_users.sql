-- ================================================
-- Setup restricted users for Laravel multi-auth
-- ================================================

-- Drop old users if they exist (optional, safe re-run)
DROP USER IF EXISTS 'laravel_admin'@'%';
DROP USER IF EXISTS 'laravel_regular'@'%';

-- Create users with strong passwords
CREATE USER 'laravel_admin'@'%' IDENTIFIED BY 'AdminPass123!';
CREATE USER 'laravel_regular'@'%' IDENTIFIED BY 'RegularPass123!';

-- =================================================
-- Grants for laravel_admin
-- Admins can only access the admins_view + tokens
-- =================================================
GRANT SELECT, INSERT, UPDATE, DELETE
    ON gulfvision.admins_view
    TO 'laravel_admin'@'%';

GRANT SELECT, INSERT, UPDATE, DELETE
    ON gulfvision.personal_access_tokens
    TO 'laravel_admin'@'%';

-- =================================================
-- Grants for laravel_regular
-- Regular users can only access the regular_users_view + tokens
-- =================================================
GRANT SELECT, INSERT, UPDATE, DELETE
    ON gulfvision.regular_users_view
    TO 'laravel_regular'@'%';

GRANT SELECT, INSERT, UPDATE, DELETE
    ON gulfvision.personal_access_tokens
    TO 'laravel_regular'@'%';

-- Apply changes
FLUSH PRIVILEGES;
