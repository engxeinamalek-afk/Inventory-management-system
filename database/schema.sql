CREATE DATABASE IF NOT EXISTS store_db
USE store_db;

-- //جدول البرودكتس
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price INT(10, 2) NOT NULL,

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
    supplier_price INT(10, 2) NOT NULL,

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

    supplier_id INT UNSIGNED NULL,

    type ENUM('sale', 'purchase') NOT NULL,

    quantity INT NOT NULL,

    unit_price INT(10, 2) NOT NULL,

    total_price INT(12, 2) NOT NULL,

    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_transactions_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_transactions_product_supplier
        FOREIGN KEY (product_id, supplier_id)
        REFERENCES product_suppliers(product_id, supplier_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_transaction_quantity
        CHECK (quantity >= 0),


    CONSTRAINT chk_transaction_unit_price
        CHECK (unit_price >= 0),

    CONSTRAINT chk_transaction_total_price
        CHECK (total_price >= 0),

-- المصدر مطلوب بحالة الشراء بس
    CONSTRAINT chk_purchase_supplier
        CHECK (
            type = 'sale'
            OR supplier_id IS NOT NULL
        )
);