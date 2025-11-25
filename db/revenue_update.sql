-- Revenue Tracking System - Database Update
-- The total_cost column already exists in the booking table
-- This file is for reference only

-- Verify the column exists:
-- SHOW COLUMNS FROM booking LIKE 'total_cost';

-- If for some reason the column doesn't exist, run this:
-- ALTER TABLE `booking` ADD COLUMN `total_cost` DECIMAL(10,2) DEFAULT NULL AFTER `repair_status`;

-- Update existing completed bookings without cost (optional - set to 0 or leave as NULL):
-- UPDATE booking SET total_cost = 0 WHERE status = 'completed' AND total_cost IS NULL;

-- Sample query to check revenue:
SELECT 
    COUNT(*) as total_bookings,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
    COALESCE(SUM(CASE WHEN status = 'completed' THEN total_cost ELSE 0 END), 0) as total_revenue
FROM booking
WHERE shop = 1; -- Replace with actual shop_id

-- Revenue by date range:
SELECT 
    DATE(createdAt) as date,
    COUNT(*) as bookings,
    COALESCE(SUM(total_cost), 0) as daily_revenue
FROM booking
WHERE status = 'completed'
    AND shop = 1 -- Replace with actual shop_id
    AND createdAt BETWEEN '2025-01-01' AND '2025-12-31'
GROUP BY DATE(createdAt)
ORDER BY date DESC;
