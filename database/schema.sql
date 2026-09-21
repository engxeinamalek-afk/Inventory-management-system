-- CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

-- //جدول البرودكتس
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price Decimal(10, 2) NOT NULL,

    CONSTRAINT chk_product_price
        CHECK (price >= 0)
);

-- //جدول المصادر
CREATE TABLE suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL
);


-- جدول وسيط علاقة m2n
CREATE TABLE product_suppliers (
    product_id INT UNSIGNED NOT NULL,
    supplier_id INT UNSIGNED NOT NULL,
    supplier_price Decimal(10, 2) NOT NULL,

    PRIMARY KEY (product_id, supplier_id),

    CONSTRAINT fk_product_suppliers_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_product_suppliers_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT chk_supplier_price
        CHECK (supplier_price >= 0)
);

-- جدول المخزون لكل منتج
CREATE TABLE inventory (
    product_id INT UNSIGNED PRIMARY KEY,
    current_quantity INT NOT NULL DEFAULT 0,

    CONSTRAINT fk_inventory_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT chk_inventory_quantity
        CHECK (current_quantity >= 0)
);


-- عمليات البيع والشراء
CREATE TABLE transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    supplier_id INT UNSIGNED NULL, -- يقبل NULL في حالة البيع بشكل سليم
    type ENUM('sale', 'purchase') NOT NULL,
    quantity INT NOT NULL,
    unit_price Decimal(10, 2) NOT NULL,
    total_price Decimal(12, 2) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- مفتاح أجنبي فردي للمنتج (يضمن وجوده في جدول المنتجات دائماً)
    CONSTRAINT fk_transactions_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    -- مفتاح أجنبي فردي للمورد (يضمن وجوده في جدول الموردين)
    CONSTRAINT fk_transactions_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_transaction_quantity
        CHECK (quantity >= 0),

    CONSTRAINT chk_transaction_unit_price
        CHECK (unit_price >= 0),

    CONSTRAINT chk_transaction_total_price
        CHECK (total_price >= 0),

    -- المصدر مطلوب إجبارياً في حالة الشراء وممنوع في حالة البيع لضمان نظافة البيانات
    CONSTRAINT chk_purchase_supplier
        CHECK (
            (type = 'sale' AND supplier_id IS NULL)
            OR 
            (type = 'purchase' AND supplier_id IS NOT NULL)
        )
);

DELIMITER $$

CREATE TRIGGER before_insert_transaction
BEFORE INSERT ON transactions
FOR EACH ROW
BEGIN
    -- الفحص يتم فقط في حالة كانت العملية شراء (purchase)
    IF NEW.type = 'purchase' THEN
        
        -- التحقق من وجود المنتج والمورد معاً في جدول product_suppliers
        IF NOT EXISTS (
            SELECT 1 
            FROM product_suppliers 
            WHERE product_id = NEW.product_id 
              AND supplier_id = NEW.supplier_id
        ) THEN
            -- إلغاء العملية وإظهار رسالة خطأ تمنع الإدخال
            SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'خطأ: لا يمكن إتمام الشراء لأن هذا المورد غير مرتبط بهذا المنتج في جدول product_suppliers';
        END IF;
        
    END IF;
END$$

DELIMITER ;


