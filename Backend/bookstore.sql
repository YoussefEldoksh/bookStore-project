-- =====================================================
    -- ONLINE BOOKSTORE SYSTEM - COMPLETE DATABASE SCHEMA
    -- Database Systems Project Fall 2025
    -- =====================================================

    -- Create Database
    CREATE DATABASE IF NOT EXISTS bookstore;
    USE bookstore;

    -- =====================================================
    -- 1. USER TABLE (Admin & Customer accounts)
    -- =====================================================
    CREATE TABLE `user` (
        user_id INT NOT NULL AUTO_INCREMENT,
        email VARCHAR(225) NOT NULL UNIQUE,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        `type` ENUM('Admin', 'Customer') NOT NULL DEFAULT 'Customer',
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        city VARCHAR(100) NOT NULL,
        street VARCHAR(100) NOT NULL,
        apartment VARCHAR(100) NOT NULL,
        PRIMARY KEY (user_id)
    );

    -- =====================================================
    -- 2. PUBLISHER TABLE
    -- =====================================================
    CREATE TABLE publisher (
        pub_id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(225) NOT NULL,
        address VARCHAR(500),
        phone VARCHAR(20),
        PRIMARY KEY (pub_id)
    );

    -- =====================================================
    -- 3. AUTHOR TABLE
    -- =====================================================
    CREATE TABLE author (
        author_id INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(225) NOT NULL,
        PRIMARY KEY (author_id)
    );
  -- =====================================================
    --  CATEGORY TABLE
    -- =====================================================

    CREATE TABLE category(
        category_id INT NOT NULL AUTO_INCREMENT,
        category_name ENUM('Science', 'Art', 'Religion', 'History', 'Geography') NOT NULL,
        PRIMARY KEY (category_id)
    );
 -- =====================================================
    -- 4. BOOK TABLE (Core book information with stock)
    -- =====================================================
    CREATE TABLE book (
        book_isbn VARCHAR(13) NOT NULL,
        title VARCHAR(225) NOT NULL,
        pub_id INT NOT NULL,
        pub_year YEAR NOT NULL,
        selling_price DECIMAL(10, 2) NOT NULL,
        quantity_in_stock INT NOT NULL DEFAULT 0,
        stock_threshold INT NOT NULL DEFAULT 5,
        category_id INT NOT NULL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (book_isbn),
        FOREIGN KEY (pub_id) REFERENCES publisher(pub_id)
            ON DELETE RESTRICT ON UPDATE CASCADE,
        FOREIGN KEY (category_id) REFERENCES category(category_id)

    );
    ALTER TABLE book
ADD CONSTRAINT chk_quantity_non_negative
CHECK (quantity_in_stock >= 0);

    DELIMITER $$

CREATE TRIGGER auto_order_when_stock_low
AFTER UPDATE ON book
FOR EACH ROW
BEGIN
    DECLARE fixed_order_qty INT DEFAULT 10;

    IF NEW.quantity_in_stock < NEW.stock_threshold AND OLD.quantity_in_stock >= OLD.stock_threshold THEN
        INSERT INTO publisher_order (pub_id, order_date, status, created_by)
        VALUES (NEW.pub_id, CURDATE(), 'Pending', NULL);

        SET @last_order_id = LAST_INSERT_ID();

        INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
        VALUES (@last_order_id, NEW.book_isbn, fixed_order_qty, 0);
    END IF;
END$$

DELIMITER ;

-- =====================================================
    -- 5. BOOK_AUTHOR TABLE (Many-to-Many relationship)
    -- =====================================================
    CREATE TABLE book_author (
        book_isbn VARCHAR(13) NOT NULL,
        author_id INT NOT NULL,
        PRIMARY KEY (book_isbn, author_id),
        FOREIGN KEY (book_isbn) REFERENCES book(book_isbn)
            ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (author_id) REFERENCES author(author_id)
            ON DELETE CASCADE ON UPDATE CASCADE
    );

    -- =====================================================
    -- 6. PUBLISHER_ORDER TABLE (Replenishment orders from publishers)
    -- =====================================================
<<<<<<< HEAD
    CREATE TABLE publisher_order_item (
    order_id INT NOT NULL AUTO_INCREMENT,
    pub_id INT NOT NULL,
    book_isbn VARCHAR(13) NOT NULL,
    order_date DATE NOT NULL,  -- Remove DEFAULT CURDATE()
    delivery_date DATE,
    status ENUM('Pending', 'Confirmed') DEFAULT 'Pending',
    confirmed_by INT,
    PRIMARY KEY (order_id),
    FOREIGN KEY (pub_id) REFERENCES publisher(pub_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (book_isbn) REFERENCES book(book_isbn)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (confirmed_by) REFERENCES user(user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
=======
    CREATE TABLE publisher_order (
    order_id INT NOT NULL AUTO_INCREMENT,
    pub_id INT NOT NULL,
    order_date DATE NOT NULL DEFAULT CURDATE(),
    expected_delivery_date DATE,
    actual_delivery_date DATE,
    total_amount DECIMAL(12, 2),
    status ENUM('Pending', 'Confirmed') DEFAULT 'Pending',
    created_by INT,

    PRIMARY KEY (order_id),

    FOREIGN KEY (pub_id)
        REFERENCES publisher(pub_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    FOREIGN KEY (created_by)
        REFERENCES user(user_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
>>>>>>> b24c0f1b267ae706ac7725c85af8b1b942f92edc
);

    -- =====================================================
    -- 8. SHOPPING_CART TABLE (Customer shopping carts)
    -- =====================================================
    CREATE TABLE shopping_cart (
    cart_item_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    book_isbn VARCHAR(13) NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (cart_item_id),
    UNIQUE KEY unique_user_book (user_id, book_isbn),
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (book_isbn) REFERENCES book(book_isbn) ON DELETE CASCADE ON UPDATE CASCADE
);

    -- =====================================================
    -- 9. CUSTOMER_ORDER TABLE (Placed orders)
    -- =====================================================
    CREATE TABLE customer_order (
        order_id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(12, 2) NOT NULL,
        status ENUM('Pending', 'Confirmed') DEFAULT 'Pending',
        apartment int NOT NULL,
        street_name VARCHAR(60) NOT NULL,
        City varchar(40) NOT NULL,
        payment_method ENUM('Credit Card', 'Debit Card', 'PayPal', 'Bank Transfer', 'Apple Pay') NOT NULL,
        credit_card_last4 VARCHAR(4),
        PRIMARY KEY (order_id),
        FOREIGN KEY (user_id) REFERENCES user(user_id)
            ON DELETE RESTRICT ON UPDATE CASCADE
    );

    -- =====================================================
    -- 10. CUSTOMER_ORDER_ITEM TABLE (Items in customer orders)
    -- =====================================================
    CREATE TABLE customer_order_item (
        order_id INT NOT NULL,
        book_isbn VARCHAR(13) NOT NULL,
        quantity INT NOT NULL,  
        unit_price DECIMAL(10, 2) NOT NULL,
        subtotal DECIMAL(12, 2) NOT NULL,
        PRIMARY KEY (order_id, book_isbn),
        FOREIGN KEY (order_id) REFERENCES customer_order(order_id)
            ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (book_isbn) REFERENCES book(book_isbn)
            ON DELETE RESTRICT ON UPDATE CASCADE
    );

    -- =====================================================
    -- 11. SAVED_BOOKS TABLE (Wishlist/Bookmarks)
    -- =====================================================
    CREATE TABLE saved_books (
        saved_id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        book_isbn VARCHAR(13) NOT NULL,
        saved_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (saved_id),
        UNIQUE KEY unique_user_saved_book (user_id, book_isbn),
        FOREIGN KEY (user_id) REFERENCES user(user_id)
            ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (book_isbn) REFERENCES book(book_isbn)
            ON DELETE CASCADE ON UPDATE CASCADE,
    );

    -- =====================================================
    -- 12. CREDIT_CARD TABLE (Store credit card details securely)
    -- =====================================================
    CREATE TABLE credit_card (
        card_id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        card_number_encrypted VARCHAR(255) NOT NULL,
        card_holder_name VARCHAR(100) NOT NULL,
        expiry_date DATE NOT NULL,
        cvv_encrypted VARCHAR(255) NOT NULL,
        is_default BOOLEAN DEFAULT FALSE,
        added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (card_id),
        FOREIGN KEY (user_id) REFERENCES user(user_id)
            ON DELETE CASCADE ON UPDATE CASCADE,
    );

    -- =====================================================
    -- 13. SALES_TRANSACTION TABLE (Track all sales for reporting)
    -- =====================================================
    CREATE TABLE sales_transaction (
        transaction_id INT NOT NULL AUTO_INCREMENT,
        order_id INT NOT NULL,
        book_isbn VARCHAR(13) NOT NULL,
        quantity_sold INT NOT NULL,
        sale_amount DECIMAL(12, 2) NOT NULL,
        sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (transaction_id),
        FOREIGN KEY (order_id) REFERENCES customer_order(order_id)
            ON DELETE RESTRICT ON UPDATE CASCADE,
        FOREIGN KEY (book_isbn) REFERENCES book(book_isbn)
            ON DELETE RESTRICT ON UPDATE CASCADE,

    );




