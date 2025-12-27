# Online Bookstore System - Order Processing System

## Project Overview
This project implements a simplified **Online Bookstore System** that supports two types of users:
- **Administrators** (with privileged operations)
- **Customers** (registered users who can browse, shop, and manage orders)

The system manages books, publishers, stock levels, replenishment orders from publishers, customer accounts, shopping carts, and sales transactions.

The implementation covers **Part 1 (Core System Requirements)** and **Part 2 (Customer Account & Online Shopping Features)** as specified in the project description.

## Features Implemented

### Administrator Features
1. **Add New Books**  
   - Add book details (ISBN, title, authors, publisher, year, price, category, threshold).  
   - Full integrity validation on insertion.

2. **Modify Existing Books**  
   - Search and update book information.  
   - Update stock quantity on sales (prevent negative stock via trigger).

3. **Automatic Replenishment Orders**  
   - Trigger fires when stock drops below threshold after an update.  
   - Places a fixed-quantity order to the publisher automatically.

4. **Confirm Orders**  
   - Admin confirms received orders → stock updated and order status changed to "Confirmed".

5. **Search for Books**  
   - By ISBN, title, category, author, or publisher.  
   - Displays availability and full details.

6. **System Reports**  
   - Total sales for the previous month.  
   - Total sales on a specific date.  
   - Top 5 customers (by total purchase amount) in the last 3 months.  
   - Top 10 best-selling books (by copies sold) in the last 3 months.  
   - Total number of replenishment orders placed for a specific book.

### Customer Features
1. **Registration & Login**  
   - New customers can sign up with username, password, name, email, phone, and shipping address.  
   - Registered users can log in.

2. **Edit Personal Information**  
   - Update profile details, including password.

3. **Book Search**  
   - Same search capabilities as administrators.

4. **Shopping Cart Management**  
   - Add/remove books.  
   - View cart contents with individual and total prices.

5. **Checkout**  
   - Provide credit card number and expiry date.  
   - Validation of card information (simulated).  
   - On success: deduct quantities from stock, create sales transaction, record order.

6. **View Past Orders**  
   - Detailed view of all previous orders (order number, date, books, quantities, total price).

7. **Logout**  
   - Clears the current shopping cart.

## Database Design

### Entity-Relationship Diagram (ERD)
*(Include the ERD image here in your actual submission - e.g., `ERD.png`)*

### Key Relations (Relational Schema)
- **Books** (ISBN PK, Title, PublicationYear, Price, Category, PublisherID FK, Threshold, QuantityInStock)
- **Authors** (AuthorID PK, AuthorName)
- **BookAuthors** (ISBN FK, AuthorID FK) — many-to-many relationship
- **Publishers** (PublisherID PK, Name, Address, Phone)
- **Customers** (CustomerID PK, Username UNIQUE, Password, FirstName, LastName, Email, Phone, ShippingAddress)
- **OrdersFromPublishers** (OrderID PK, ISBN FK, OrderDate, QuantityOrdered, Status, ConfirmDate)
- **Carts** (CustomerID FK, ISBN FK, Quantity) — current cart items
- **SalesOrders** (SalesOrderID PK, CustomerID FK, OrderDate, TotalPrice, CreditCardNumber, ExpiryDate)
- **SalesOrderItems** (SalesOrderID FK, ISBN FK, Quantity, UnitPrice)
- Additional tables/views as needed for reports

### Integrity Constraints & Triggers
- Primary keys, foreign keys, UNIQUE constraints enforced.
- CHECK constraints (e.g., Category values, QuantityInStock ≥ 0, Price > 0).
- Trigger to prevent negative stock updates.
- Trigger to automatically place replenishment order when stock falls below threshold.
- All constraints and triggers documented in SQL scripts.

## Technologies Used
- **Database**: [ MySQL cloud database]
- **Backend/Application Layer**: [ PHP ]
- **Frontend**: [ HTML/CSS/JavaScript ]
- **Development Tools**: [ VS Code ]

## Sample Data
The database is populated with:
- Multiple publishers
- 50+ books across all categories with varying authors
- Sample customers
- Historical sales data covering multiple months
- Pending and confirmed replenishment orders

This allows full testing of all features and reports.

## Project Report Contents
- List of implemented features (as above)
- ERD diagram
- Complete relational schema with constraints
- Description of each user interface screen and its logic
- SQL scripts for schema, triggers, and sample data

