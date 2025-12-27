{"book_isbn":"9780140194679","pub_id":"1"},
{"book_isbn":"9780140195683","pub_id":"1"},
{"book_isbn":"9780140197532","pub_id":"1"},
{"book_isbn":"9780140262094","pub_id":"1"},
{"book_isbn":"9780141039328","pub_id":"1"},
{"book_isbn":"9780141192154","pub_id":"1"},
{"book_isbn":"9780500015026","pub_id":"1"},
{"book_isbn":"9780500015032","pub_id":"1"},
{"book_isbn":"9780500284529","pub_id":"1"},
{"book_isbn":"9780714830086","pub_id":"1"},
{"book_isbn":"9780714833446","pub_id":"1"},
{"book_isbn":"9780714837543","pub_id":"1"},
{"book_isbn":"9780714847030","pub_id":"1"},
{"book_isbn":"9780345383715","pub_id":"2"},
{"book_isbn":"9780345539571","pub_id":"2"},
{"book_isbn":"9780385333313","pub_id":"2"},
{"book_isbn":"9780553345285","pub_id":"2"},
{"book_isbn":"9780553382532","pub_id":"2"},
{"book_isbn":"9780553395419","pub_id":"2"},
{"book_isbn":"9780671656775","pub_id":"2"},
{"book_isbn":"9780553296129","pub_id":"3"},
{"book_isbn":"9780199213949","pub_id":"4"},
{"book_isbn":"9780199219797","pub_id":"4"},
{"book_isbn":"9780199221868","pub_id":"4"},
{"book_isbn":"9780199535293","pub_id":"4"},
{"book_isbn":"9780199537730","pub_id":"4"},
{"book_isbn":"9780691029558","pub_id":"4"},
{"book_isbn":"9780262720151","pub_id":"5"},
{"book_isbn":"9780241023532","pub_id":"6"},
{"book_isbn":"9781473663688","pub_id":"6"},
{"book_isbn":"9780374157338","pub_id":"7"},
{"book_isbn":"9780374226275","pub_id":"7"},
{"book_isbn":"9780374514693","pub_id":"7"},
{"book_isbn":"9780393046679","pub_id":"7"},
{"book_isbn":"9780393252850","pub_id":"7"},
{"book_isbn":"9780393307634","pub_id":"7"},
{"book_isbn":"9780393308716","pub_id":"7"},
{"book_isbn":"9780393344530","pub_id":"7"},
{"book_isbn":"9780393345308","pub_id":"7"},
{"book_isbn":"9780486268651","pub_id":"7"},
{"book_isbn":"9780486284629","pub_id":"7"},
{"book_isbn":"9780486431178","pub_id":"7"},
{"book_isbn":"9780684801222","pub_id":"8"},
{"book_isbn":"9781491927398","pub_id":"8"},
{"book_isbn":"9780789212352","pub_id":"9"},
{"book_isbn":"9780394747980","pub_id":"10"},
{"book_isbn":"9780679436232","pub_id":"10"},
{"book_isbn":"9780679736691","pub_id":"10"}




-- Publisher Order 1
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (1, CURRENT_TIMESTAMP, '2025-01-15', 3500.00, 'Confirmed', 1);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (1, '9780140194679', 50, 25.00),
       (1, '9780140195683', 30, 35.50),
       (1, '9780140197532', 25, 40.00);

-- Publisher Order 2
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (2, CURRENT_TIMESTAMP, '2025-01-20', 3200.50, 'Pending', 2);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (2, '9780345383715', 20, 60.00),
       (2, '9780385333313', 40, 45.50);

-- Publisher Order 3
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (3, CURRENT_TIMESTAMP, '2025-02-05', 5500.75, 'Confirmed', 1);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (3, '9780500015026', 60, 50.00),
       (3, '9780500015032', 35, 55.00),
       (3, '9780500284529', 45, 25.00);

-- Publisher Order 4
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (4, CURRENT_TIMESTAMP, '2025-01-28', 4100.00, 'Pending', 3);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (4, '9780714830086', 50, 35.00),
       (4, '9780714833446', 25, 60.00);

-- Publisher Order 5
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (5, CURRENT_TIMESTAMP, '2025-02-10', 6200.25, 'Confirmed', 2);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (5, '9780714837543', 40, 35.50),
       (5, '9780714847030', 55, 40.00),
       (5, '9780345383715', 30, 50.00);

-- Publisher Order 6
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (1, CURRENT_TIMESTAMP, '2025-02-15', 4800.00, 'Confirmed', 1);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (6, '9780345539571', 45, 32.00),
       (6, '9780385333313', 35, 45.00),
       (6, '9780553345285', 28, 55.00);

-- Publisher Order 7
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (2, CURRENT_TIMESTAMP, '2025-02-20', 3600.50, 'Pending', 3);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (7, '9780553382532', 50, 28.00),
       (7, '9780553395419', 40, 32.50);

-- Publisher Order 8
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (3, CURRENT_TIMESTAMP, '2025-03-01', 5200.75, 'Confirmed', 2);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (8, '9780671656775', 35, 38.00),
       (8, '9780553296129', 45, 42.00),
       (8, '9780199213949', 30, 50.00);

-- Publisher Order 9
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (4, CURRENT_TIMESTAMP, '2025-03-05', 4900.00, 'Pending', 1);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (9, '9780199219797', 40, 35.00),
       (9, '9780199221868', 50, 42.00);

-- Publisher Order 10
INSERT INTO publisher_order (pub_id, order_date, expected_delivery_date, total_amount, status, created_by)
VALUES (5, CURRENT_TIMESTAMP, '2025-03-10', 6800.25, 'Confirmed', 3);

INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
VALUES (10, '9780394747980', 45, 38.00),
       (10, '9780679436232', 55, 40.00),
       (10, '9780691029558', 35, 55.00);