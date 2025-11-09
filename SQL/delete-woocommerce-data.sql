-- ===============================
-- ⚡ Set table prefix variable
-- ===============================
SET @prefix = 'wpstg0_';

-- ===============================
-- 1️⃣ Delete product meta
-- ===============================
SET @sql = CONCAT(
    'DELETE pm FROM ', @prefix, 'postmeta pm ',
    'JOIN ', @prefix, 'posts p ON pm.post_id = p.ID ',
    'WHERE p.post_type IN (''product'', ''product_variation'')'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ===============================
-- 2️⃣ Delete product-to-term relationships
-- ===============================
SET @sql = CONCAT(
    'DELETE tr FROM ', @prefix, 'term_relationships tr ',
    'JOIN ', @prefix, 'posts p ON tr.object_id = p.ID ',
    'WHERE p.post_type IN (''product'', ''product_variation'')'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ===============================
-- 3️⃣ Delete products and variations
-- ===============================
SET @sql = CONCAT(
    'DELETE FROM ', @prefix, 'posts ',
    'WHERE post_type IN (''product'', ''product_variation'')'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ===============================
-- 4️⃣ Delete product-related terms
-- ===============================
SET @sql = CONCAT(
    'DELETE tt, t FROM ', @prefix, 'term_taxonomy tt ',
    'JOIN ', @prefix, 'terms t ON tt.term_id = t.term_id ',
    'WHERE tt.taxonomy IN (''product_cat'', ''product_tag'', ''product_brand'')'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ===============================
-- 5️⃣ Delete product attachments (images)
-- ===============================
SET @sql = CONCAT(
    'DELETE a, am FROM ', @prefix, 'posts a ',
    'LEFT JOIN ', @prefix, 'postmeta am ON am.post_id = a.ID ',
    'WHERE a.post_type = ''attachment'' ',
    'AND a.ID IN (',
        'SELECT meta_value FROM ', @prefix, 'postmeta WHERE meta_key = ''_thumbnail_id'' ',
        'UNION ',
        'SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, '','', n.n), '','', -1)) ',
        'FROM ', @prefix, 'postmeta ',
        'CROSS JOIN (',
            'SELECT a.N + b.N * 10 + 1 n ',
            'FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 ',
            'UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a, ',
            '(SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 ',
            'UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b',
        ') n ',
        'WHERE meta_key = ''_product_image_gallery'' ',
        'AND n.n <= 1 + (LENGTH(meta_value) - LENGTH(REPLACE(meta_value, '','', '''')))',
    ')'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
