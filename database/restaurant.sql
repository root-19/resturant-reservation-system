-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add category_id to menu_items table
ALTER TABLE menu_items ADD COLUMN category_id INT;
ALTER TABLE menu_items ADD FOREIGN KEY (category_id) REFERENCES categories(id);

-- Insert some default categories
INSERT INTO categories (name) VALUES 
('Appetizers'),
('Main Course'),
('Desserts'),
('Beverages'),
('Specialties'); 