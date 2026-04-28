CREATE TABLE products (
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(100) NOT NULL,
    price     NUMERIC(10,2) NOT NULL,
    stock     INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE orders (
    id           SERIAL PRIMARY KEY,
    confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total        NUMERIC(10,2) NOT NULL
);

CREATE TABLE order_items (
    id         SERIAL PRIMARY KEY,
    order_id   INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id),
    quantity   INTEGER NOT NULL,
    unit_price NUMERIC(10,2) NOT NULL
);

INSERT INTO products (name, price, stock) VALUES
('Laptop',   899.99, 10),
('Mouse',     25.00, 50),
('Keyboard',  45.00, 30),
('Monitor',  299.99, 15);