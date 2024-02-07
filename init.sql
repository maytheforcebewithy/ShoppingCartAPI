CREATE TABLE products (
    id        SERIAL PRIMARY KEY,
    name      TEXT,
    price     DECIMAL,
    quantity  INT
);

CREATE TABLE users (
    id    SERIAL PRIMARY KEY,
    name  TEXT,
    email TEXT
);

CREATE TABLE cart_items (
    id SERIAL PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);