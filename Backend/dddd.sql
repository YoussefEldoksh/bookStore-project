-- =====================================================
-- PUBLISHER DATA
-- =====================================================
INSERT INTO publisher (name, address, phone) VALUES
('Penguin Books', '80 Strand, London, UK', '+44-20-7139-3000'),
('Simon & Schuster', '1230 Avenue of the Americas, New York, USA', '+1-212-698-7000'),
('HarperCollins', '195 Broadway, New York, USA', '+1-212-207-7000'),
('Oxford University Press', 'Great Clarendon Street, Oxford, UK', '+44-1865-556767'),
('Cambridge University Press', 'University Avenue, Cambridge, UK', '+44-1223-358331'),
('Bloomsbury Publishing', '50 Bedford Square, London, UK', '+44-20-7631-5600'),
('W. W. Norton', '500 Fifth Avenue, New York, USA', '+1-212-790-4456'),
('Macmillan Publishers', '175 Fifth Avenue, New York, USA', '+1-646-307-5151'),
('Scholastic Press', '557 Broadway, New York, USA', '+1-212-343-6100'),
('Random House', '1745 Broadway, New York, USA', '+1-212-782-9000');

-- =====================================================
-- AUTHOR DATA
-- =====================================================
INSERT INTO author (name) VALUES
('J.R.R. Tolkien'),
('George R.R. Martin'),
('Stephen King'),
('Isaac Asimov'),
('Carl Sagan'),
('Richard Dawkins'),
('Karl Popper'),
('Albert Einstein'),
('Albert Camus'),
('Jean-Paul Sartre'),
('Simone de Beauvoir'),
('Hannah Arendt'),
('Michel Foucault'),
('Gilles Deleuze'),
('Ludwig Wittgenstein'),
('Bertrand Russell'),
('Ludwig Feuerbach'),
('Karl Marx'),
('Friedrich Nietzsche'),
('Arthur Schopenhauer'),
('René Descartes'),
('Immanuel Kant'),
('Georg Wilhelm Friedrich Hegel'),
('David Hume'),
('John Locke'),
('Thomas Hobbes'),
('Plato'),
('Aristotle'),
('Socrates'),
('Confucius'),
('Laozi'),
('Buddha'),
('Muhammad'),
('Jesus Christ'),
('Moses'),
('Vincent van Gogh'),
('Leonardo da Vinci'),
('Michelangelo'),
('Pablo Picasso'),
('Frida Kahlo'),
('Salvador Dalí'),
('Andy Warhol'),
('Jackson Pollock'),
('Mark Rothko'),
('Joan Mitchell'),
('Barbara Hepworth'),
('Alexander the Great'),
('Julius Caesar'),
('Napoleon Bonaparte'),
('Adolf Hitler'),
('Winston Churchill'),
('Franklin D. Roosevelt'),
('Joseph Stalin'),
('Mao Zedong'),
('Gandhi'),
('Che Guevara'),
('Malcolm X'),
('Martin Luther King Jr.'),
('Nelson Mandela'),
('Isaac Newton'),
('Charles Darwin'),
('Marie Curie'),
('Stephen Hawking'),
('Nikola Tesla'),
('Thomas Edison'),
('Alexander Graham Bell'),
('Guglielmo Marconi'),
('Henry Ford'),
('Steve Jobs'),
('Bill Gates'),
('Mark Zuckerberg'),
('Elon Musk');

-- =====================================================
-- CATEGORY DATA (Already created via enum)
-- =====================================================
INSERT INTO category (category_name) VALUES
('Science'),
('Art'),
('Religion'),
('History'),
('Geography');

-- =====================================================
-- BOOK DATA
-- =====================================================
INSERT INTO book (book_isbn, title, pub_id, pub_year, selling_price, quantity_in_stock, stock_threshold, category_id, last_updated) VALUES
-- Science Books
('9780486284629', 'A Brief History of Time', 7, 1988, 18.99, 45, 5, 1, CURRENT_TIMESTAMP),
('9780385333313', 'Cosmos', 2, 1980, 22.50, 38, 5, 1, CURRENT_TIMESTAMP),
('9780553296129', 'The Selfish Gene', 3, 1976, 16.99, 52, 5, 1, CURRENT_TIMESTAMP),
('9780486268651', 'Relativity: The Special and General Theory', 7, 1916, 12.99, 28, 5, 1, CURRENT_TIMESTAMP),
('9780553345285', 'Foundation', 2, 1951, 15.99, 41, 5, 1, CURRENT_TIMESTAMP),
('9780374157338', 'The Demon-Haunted World', 7, 1996, 19.99, 35, 5, 1, CURRENT_TIMESTAMP),
('9781491927398', 'The Structure of Scientific Revolutions', 8, 1962, 17.99, 30, 5, 1, CURRENT_TIMESTAMP),
('9780691029558', 'Gödel, Escher, Bach', 4, 1979, 24.99, 22, 5, 1, CURRENT_TIMESTAMP),
('9780262720151', 'Introduction to Algorithms', 5, 2009, 89.99, 15, 5, 1, CURRENT_TIMESTAMP),
('9780486431178', 'The Republic', 7, 1957, 14.99, 42, 5, 1, CURRENT_TIMESTAMP),

-- Art Books
('9780714837543', 'The Story of Painting', 1, 2000, 45.99, 18, 5, 2, CURRENT_TIMESTAMP),
('9780714847030', 'The Art of the Renaissance', 1, 2005, 55.99, 12, 5, 2, CURRENT_TIMESTAMP),
('9780500015032', 'Art History', 1, 2010, 65.00, 8, 5, 2, CURRENT_TIMESTAMP),
('9780789212352', 'The Impressionists', 9, 2001, 35.99, 25, 5, 2, CURRENT_TIMESTAMP),
('9780393046679', 'Ways of Seeing', 7, 1972, 22.99, 31, 5, 2, CURRENT_TIMESTAMP),
('9780141192154', 'The Letters of Van Gogh', 1, 2008, 29.99, 19, 5, 2, CURRENT_TIMESTAMP),
('9780714833446', 'Leonardo da Vinci: Notebooks', 1, 2006, 49.99, 9, 5, 2, CURRENT_TIMESTAMP),
('9780393345308', 'Michelangelo and the Pope\'s Ceiling', 7, 2002, 28.99, 21, 5, 2, CURRENT_TIMESTAMP),
('9780500284529', 'Picasso: The Cubist Revolution', 1, 2011, 42.99, 14, 5, 2, CURRENT_TIMESTAMP),
('9780393308716', 'Sister Wendy\'s Story of Paintings', 7, 1994, 24.99, 27, 5, 2, CURRENT_TIMESTAMP),

-- Religion Books
('9780140262094', 'The Bible', 1, 2000, 19.99, 78, 5, 3, CURRENT_TIMESTAMP),
('9780553213980', 'The Qur\'an', 2, 2004, 22.99, 65, 5, 3, CURRENT_TIMESTAMP),
('9781473663688', 'Bhagavad Gita', 6, 2015, 14.99, 44, 5, 3, CURRENT_TIMESTAMP),
('9780199535293', 'The Dhammapada', 4, 2008, 13.99, 38, 5, 3, CURRENT_TIMESTAMP),
('9780140197532', 'The I Ching', 1, 1995, 18.99, 32, 5, 3, CURRENT_TIMESTAMP),
('9780199221868', 'The Complete Works of Confucius', 4, 2009, 24.99, 17, 5, 3, CURRENT_TIMESTAMP),
('9780140195683', 'The Essential Rumi', 1, 1995, 16.99, 29, 5, 3, CURRENT_TIMESTAMP),
('9780393307634', 'A History of God', 7, 1993, 20.99, 25, 5, 3, CURRENT_TIMESTAMP),
('9780199219797', 'The Problem of Religious Diversity', 4, 2011, 28.99, 11, 5, 3, CURRENT_TIMESTAMP),
('9780553382532', 'Why I Am Not a Christian', 2, 1957, 12.99, 33, 5, 3, CURRENT_TIMESTAMP),

-- History Books
('9780345539571', 'The Guns of August', 2, 1962, 18.99, 36, 5, 4, CURRENT_TIMESTAMP),
('9780394747980', 'A Tale of Two Cities', 10, 1859, 14.99, 52, 5, 4, CURRENT_TIMESTAMP),
('9780671656775', 'The Rise and Fall of the Third Reich', 2, 1960, 35.99, 19, 5, 4, CURRENT_TIMESTAMP),
('9780679436232', '1491: New Revelations of the Americas Before Columbus', 10, 2011, 20.00, 28, 5, 4, CURRENT_TIMESTAMP),
('9780393079517', 'The Code Breaker', 7, 2021, 19.99, 41, 5, 4, CURRENT_TIMESTAMP),
('9780684801222', 'A Brief History of Nearly Everything', 8, 2003, 18.99, 45, 5, 4, CURRENT_TIMESTAMP),
('9780199213949', 'The Oxford History of the British Empire', 4, 1998, 85.00, 5, 5, 4, CURRENT_TIMESTAMP),
('9780345383715', 'The Splendid Century', 2, 1953, 16.99, 19, 5, 4, CURRENT_TIMESTAMP),
('9780374514693', 'The End of the Old Order in Rural Europe', 7, 1989, 25.99, 12, 5, 4, CURRENT_TIMESTAMP),

-- Geography Books
('9780241233269', 'Sapiens: A Brief History of Humankind', 6, 2014, 20.00, 67, 5, 5, CURRENT_TIMESTAMP),
('9780062356529', 'Educated', 3, 2018, 18.99, 54, 5, 5, CURRENT_TIMESTAMP),
('9780374248689', 'The Great Railway Bazaar', 7, 1975, 17.99, 22, 5, 5, CURRENT_TIMESTAMP),
('9780393080322', 'The Geography of Genius', 7, 2016, 19.99, 31, 5, 5, CURRENT_TIMESTAMP),
('9780143117032', 'Prisoners of Geography', 1, 2015, 18.99, 38, 5, 5, CURRENT_TIMESTAMP),
('9780393315509', 'The Way of the Wanderer', 7, 2017, 16.99, 25, 5, 5, CURRENT_TIMESTAMP),
('9781451628654', 'In a Sunburned Country', 2, 2000, 17.99, 28, 5, 5, CURRENT_TIMESTAMP),
('9780307277671', 'Travel Writing', 10, 2003, 19.99, 20, 5, 5, CURRENT_TIMESTAMP),
('9780141039991', 'The Atlas Obscura', 1, 2016, 24.99, 15, 5, 5, CURRENT_TIMESTAMP),
('9780393078848', 'A Walk Around the Lakes', 7, 1978, 14.99, 21, 5, 5, CURRENT_TIMESTAMP),

-- Additional Science Books
('9780553395419', 'Thinking, Fast and Slow', 2, 2011, 18.99, 49, 5, 1, CURRENT_TIMESTAMP),
('9780141039328', 'On the Origin of Species', 1, 2008, 12.99, 61, 5, 1, CURRENT_TIMESTAMP),
('9780374226275', 'The Righteous Mind', 7, 2012, 19.99, 37, 5, 1, CURRENT_TIMESTAMP),

-- Additional Art Books
('9780500015026', 'The Story of Western Art', 1, 2008, 48.99, 10, 5, 2, CURRENT_TIMESTAMP),
('9780714830086', 'Modernism 1890-1930', 1, 2006, 52.99, 7, 5, 2, CURRENT_TIMESTAMP),

-- Additional Religion Books
('9780140194679', 'The Torah', 1, 2004, 18.99, 35, 5, 3, CURRENT_TIMESTAMP),
('9780199537730', 'Eastern Philosophy', 4, 2012, 22.99, 16, 5, 3, CURRENT_TIMESTAMP),

-- Additional History Books
('9780679736691', 'The Fall of the Roman Empire', 10, 2005, 22.99, 18, 5, 4, CURRENT_TIMESTAMP),
('9780393252850', 'The Silk Roads', 7, 2015, 20.00, 32, 5, 4, CURRENT_TIMESTAMP),

-- Additional Geography Books
('9780393344530', 'The Lost City of Z', 7, 2009, 18.99, 26, 5, 5, CURRENT_TIMESTAMP),
('9780241023532', 'Slow Travel', 6, 2016, 16.99, 23, 5, 5, CURRENT_TIMESTAMP);

-- =====================================================
-- BOOK_AUTHOR DATA (Many-to-Many relationships)
-- =====================================================
INSERT INTO book_author (book_isbn, author_id) VALUES
-- Science Books
('9780486284629', 8),  -- A Brief History of Time - Albert Einstein
('9780385333313', 5),  -- Cosmos - Carl Sagan
('9780553296129', 6),  -- The Selfish Gene - Richard Dawkins
('9780486268651', 8),  -- Relativity - Albert Einstein
('9780553345285', 4),  -- Foundation - Isaac Asimov
('9780374157338', 5),  -- The Demon-Haunted World - Carl Sagan
('9781491927398', 7),  -- The Structure of Scientific Revolutions - Karl Popper
('9780691029558', 1),  -- Gödel, Escher, Bach - J.R.R. Tolkien
('9780262720151', 4),  -- Introduction to Algorithms - Isaac Asimov
('9780486431178', 27), -- The Republic - Plato
('9780553395419', 2),  -- Thinking, Fast and Slow - George R.R. Martin
('9780141039328', 3),  -- On the Origin of Species - Stephen King
('9780374226275', 5),  -- The Righteous Mind - Carl Sagan

-- Art Books
('9780714837543', 36), -- The Story of Painting - Vincent van Gogh
('9780714847030', 37), -- The Art of the Renaissance - Leonardo da Vinci
('9780500015032', 38), -- Art History - Michelangelo
('9780789212352', 36), -- The Impressionists - Vincent van Gogh
('9780393046679', 40), -- Ways of Seeing - Frida Kahlo
('9780141192154', 36), -- The Letters of Van Gogh - Vincent van Gogh
('9780714833446', 37), -- Leonardo da Vinci: Notebooks - Leonardo da Vinci
('9780393345308', 38), -- Michelangelo and the Pope's Ceiling - Michelangelo
('9780500284529', 39), -- Picasso: The Cubist Revolution - Pablo Picasso
('9780393308716', 41), -- Sister Wendy's Story of Paintings - Salvador Dalí
('9780500015026', 37), -- The Story of Western Art - Leonardo da Vinci
('9780714830086', 39), -- Modernism 1890-1930 - Pablo Picasso

-- Religion Books
('9780140262094', 34), -- The Bible - Jesus Christ
('9780553213980', 33), -- The Qur'an - Muhammad
('9781473663688', 32), -- Bhagavad Gita - Buddha
('9780199535293', 32), -- The Dhammapada - Buddha
('9780140197532', 31), -- The I Ching - Laozi
('9780199221868', 30), -- The Complete Works of Confucius - Confucius
('9780140195683', 30), -- The Essential Rumi - Confucius
('9780393307634', 17), -- A History of God - Ludwig Feuerbach
('9780199219797', 15), -- The Problem of Religious Diversity - Ludwig Wittgenstein
('9780553382532', 16), -- Why I Am Not a Christian - Bertrand Russell
('9780140194679', 35), -- The Torah - Moses
('9780199537730', 30), -- Eastern Philosophy - Confucius

-- History Books
('9780345539571', 48), -- The Guns of August - Alexander the Great
('9780394747980', 49), -- A Tale of Two Cities - Julius Caesar
('9780671656775', 50), -- The Rise and Fall of the Third Reich - Napoleon Bonaparte
('9780679436232', 48), -- 1491 - Alexander the Great
('9780393079517', 64), -- The Code Breaker - Marie Curie
('9780684801222', 5),  -- A Brief History of Nearly Everything - Carl Sagan
('9780199213949', 52), -- The Oxford History of the British Empire - Winston Churchill
('9780345383715', 50), -- The Splendid Century - Napoleon Bonaparte
('9780374514693', 18), -- The End of the Old Order - Karl Marx
('9780679736691', 49), -- The Fall of the Roman Empire - Julius Caesar
('9780393252850', 5),  -- The Silk Roads - Carl Sagan

-- Geography Books
('9780241233269', 29), -- Sapiens - Socrates
('9780062356529', 64), -- Educated - Marie Curie
('9780374248689', 5),  -- The Great Railway Bazaar - Carl Sagan
('9780393080322', 5),  -- The Geography of Genius - Carl Sagan
('9780143117032', 18), -- Prisoners of Geography - Karl Marx
('9780393315509', 5),  -- The Way of the Wanderer - Carl Sagan
('9781451628654', 5),  -- In a Sunburned Country - Carl Sagan
('9780307277671', 5),  -- Travel Writing - Carl Sagan
('9780393078848', 5),  -- A Walk Around the Lakes - Carl Sagan
('9780393344530', 69), -- The Lost City of Z - Elon Musk
('9780241023532', 62); -- Slow Travel - Steve Jobs