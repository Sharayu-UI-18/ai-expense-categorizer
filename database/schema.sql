-- schema.sql
-- Database and table creation for AI Expense Categorizer

CREATE DATABASE IF NOT EXISTS `ai_expense_categorizer` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ai_expense_categorizer`;

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_text` TEXT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `category` VARCHAR(128) NOT NULL,
  `subcategory` VARCHAR(128) NOT NULL,
  `spending_type` VARCHAR(128) NOT NULL,
  `reasoning` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
