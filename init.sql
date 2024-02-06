CREATE TABLE products (
    id        SERIAL PRIMARY KEY,
    name      TEXT,
    price     DECIMAL,
    quantity  INT
);

CREATE TABLE users {
  id    SERIAL PRIMARY KEY,
  name  TEXT, 
  email TEXT
}

CREATE TABLE cart_items {
  user_id     INT REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
  product_id  INT REFERENCES products (id) ON UPDATE CASCADE
  quantity    INT,
  CONSTRAINT user_product_pkey PRIMARY KEY(user_id, product_id)
}