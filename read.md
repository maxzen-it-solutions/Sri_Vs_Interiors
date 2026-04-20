ALTER TABLE products
ADD COLUMN project_phase ENUM('past','present','future')
NOT NULL DEFAULT 'present'
AFTER category;

-- Completed projects
UPDATE products
SET project_phase = 'past'
WHERE id IN (1,3,4,5);

-- Ongoing projects
UPDATE products
SET project_phase = 'present'
WHERE id IN (6,7,8,9,10);

-- Upcoming projects
UPDATE products
SET project_phase = 'future'
WHERE id IN (11,12,13);

-- Add media_type to project_images to support video/image classification
ALTER TABLE project_images 
ADD COLUMN media_type ENUM('image', 'video') NOT NULL DEFAULT 'image' AFTER order_index;
