-- Fix admin registration by making roll_no nullable for admin users
-- This allows admins to register without a roll number

-- Step 1: Modify the users table to allow NULL roll_no
ALTER TABLE `users` 
MODIFY COLUMN `roll_no` varchar(30) NULL DEFAULT NULL;

-- Step 2: Update the unique constraint to allow multiple NULL values
-- First, drop the existing unique constraint on roll_no
ALTER TABLE `users` 
DROP INDEX `roll_no`;

-- Step 3: Add a new unique index that allows NULL values
-- MySQL treats NULL values as distinct, so multiple NULL roll_no values are allowed
ALTER TABLE `users` 
ADD UNIQUE INDEX `roll_no` (`roll_no`);

-- Step 4: Remove foreign key constraints that reference roll_no (if any issues)
-- This ensures we can have NULL roll_no for admins
-- Note: Foreign keys will still work, they just won't reference admin rows

-- Update existing admin users with empty roll_no to NULL
UPDATE `users` 
SET `roll_no` = NULL 
WHERE `usertype` = 'admin' AND (`roll_no` = '' OR `roll_no` LIKE 'ADMIN_%');

SELECT 'Admin roll_no fix completed successfully!' as status;
