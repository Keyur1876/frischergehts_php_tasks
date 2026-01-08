-- Task 2 Schema: Artikelverwaltung (MySQL)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- Categories
CREATE TABLE IF NOT EXISTS category (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_category_name (name)
) ENGINE=InnoDB;

-- Articles
CREATE TABLE IF NOT EXISTS article (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_article_name (name)
) ENGINE=InnoDB;

-- Article <-> Category (many-to-many)
CREATE TABLE IF NOT EXISTS article_category (
  article_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,

  PRIMARY KEY (article_id, category_id),

  CONSTRAINT fk_ac_article
    FOREIGN KEY (article_id) REFERENCES article(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_ac_category
    FOREIGN KEY (category_id) REFERENCES category(id)
    ON DELETE CASCADE,

  KEY idx_ac_category (category_id),
  KEY idx_ac_article (article_id)
) ENGINE=InnoDB;

-- Category compatibility:
-- base_category can accept addon_category
-- Example: Pizza (base) can accept Topping (addon)
CREATE TABLE IF NOT EXISTS category_compatibility (
  base_category_id INT UNSIGNED NOT NULL,
  addon_category_id INT UNSIGNED NOT NULL,

  PRIMARY KEY (base_category_id, addon_category_id),

  CONSTRAINT fk_cc_base
    FOREIGN KEY (base_category_id) REFERENCES category(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_cc_addon
    FOREIGN KEY (addon_category_id) REFERENCES category(id)
    ON DELETE CASCADE,

  -- Prevent -- like "Pizza can add Pizza"
  CONSTRAINT chk_cc_not_same CHECK (base_category_id <> addon_category_id),

  KEY idx_cc_addon (addon_category_id),
  KEY idx_cc_base (base_category_id)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- mysql -h interchange.proxy.rlwy.net -P 46998 -u root -p railway < ./sql/artikel_verwaltung_schema.sql