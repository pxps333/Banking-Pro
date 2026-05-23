-- PostgreSQL-compatible schema converted from MySQL dump

CREATE TABLE IF NOT EXISTS admin (
  id SERIAL PRIMARY KEY,
  firstname VARCHAR(200) NOT NULL,
  lastname VARCHAR(200) NOT NULL,
  image TEXT DEFAULT NULL,
  admin_email VARCHAR(200) NOT NULL,
  admin_password VARCHAR(200) NOT NULL
);

INSERT INTO admin (id, firstname, lastname, image, admin_email, admin_password) VALUES
(1, 'Admin', 'Admin', 'jamesavatar.png', 'support@dirtyscripts.shop', '$2y$10$Ysp7iiUXB4O.p.vm/H.V5eya846d4sHiBlpkc23JPMCbwEnFVddIC')
ON CONFLICT (id) DO NOTHING;

SELECT setval('admin_id_seq', 3);

CREATE TABLE IF NOT EXISTS audit_logs (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  device TEXT NOT NULL,
  "ipAddress" VARCHAR(200) NOT NULL,
  datenow TIMESTAMP DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS card (
  id SERIAL PRIMARY KEY,
  seria_key TEXT NOT NULL,
  user_id INT NOT NULL,
  card_number TEXT NOT NULL,
  card_name TEXT NOT NULL,
  card_expiration VARCHAR(50) NOT NULL,
  card_security TEXT NOT NULL,
  card_limit DOUBLE PRECISION NOT NULL DEFAULT 5000,
  card_limit_remain DOUBLE PRECISION NOT NULL DEFAULT 5000,
  card_status INT NOT NULL DEFAULT 2,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS card_request (
  id SERIAL PRIMARY KEY,
  reference_id TEXT NOT NULL,
  user_id INT NOT NULL,
  card_type TEXT NOT NULL,
  card_reason TEXT NOT NULL,
  card_request_status INT NOT NULL DEFAULT 2,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS crypto_currency (
  id SERIAL PRIMARY KEY,
  crypto_name VARCHAR(200) NOT NULL,
  wallet_address TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO crypto_currency (id, crypto_name, wallet_address, created_at) VALUES
(1, 'Bitcoin', '8rtknjbhgfgvsnkjhgxfgxjhkx', '2022-10-21 17:33:59')
ON CONFLICT (id) DO NOTHING;

SELECT setval('crypto_currency_id_seq', 56);

CREATE TABLE IF NOT EXISTS deposit (
  d_id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  refrence_id TEXT NOT NULL,
  image TEXT DEFAULT NULL,
  amount DOUBLE PRECISION NOT NULL,
  wallet_address TEXT NOT NULL,
  crypto_id INT NOT NULL,
  crypto_status INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS domestic_transfer (
  dom_id SERIAL PRIMARY KEY,
  acct_id INT DEFAULT NULL,
  refrence_id TEXT NOT NULL,
  amount DOUBLE PRECISION NOT NULL DEFAULT 0,
  bank_name TEXT DEFAULT NULL,
  acct_name TEXT DEFAULT NULL,
  acct_number BIGINT NOT NULL,
  trans_type VARCHAR(50) NOT NULL DEFAULT 'domestic transfer',
  acct_type VARCHAR(50) NOT NULL,
  acct_remarks TEXT DEFAULT NULL,
  dom_status INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS loan (
  loan_id SERIAL PRIMARY KEY,
  loan_reference_id TEXT DEFAULT NULL,
  acct_id INT NOT NULL,
  amount DOUBLE PRECISION DEFAULT 0,
  loan_remarks TEXT NOT NULL,
  loan_status INT NOT NULL DEFAULT 0,
  loan_message TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
  id SERIAL PRIMARY KEY,
  "FullName" TEXT NOT NULL,
  "PhoneNumber" TEXT NOT NULL,
  locationcus TEXT NOT NULL,
  "Addresscus" TEXT NOT NULL,
  "City" TEXT NOT NULL,
  "Customer" TEXT NOT NULL,
  "Messagecus" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS settings (
  id SERIAL PRIMARY KEY,
  image TEXT NOT NULL,
  about_us TEXT NOT NULL,
  url_name TEXT NOT NULL,
  url_tel VARCHAR(15) DEFAULT NULL,
  url_email VARCHAR(100) NOT NULL,
  trans_limit_min DOUBLE PRECISION DEFAULT NULL,
  trans_limit_max DOUBLE PRECISION DEFAULT NULL,
  livechat TEXT NOT NULL DEFAULT '',
  twillio_status INT NOT NULL DEFAULT 0,
  billing_code INT NOT NULL DEFAULT 0,
  transfer INT NOT NULL DEFAULT 1,
  bank_deposit INT NOT NULL DEFAULT 0
);

INSERT INTO settings (id, image, about_us, url_name, url_tel, url_email, trans_limit_min, trans_limit_max, livechat, twillio_status, billing_code, transfer, bank_deposit) VALUES
(1, 'logo.png', 'Online Banking Script', 'Bankpro Banking', '2348114313795', 'support@bankpro.com', 500, 500000, '', 0, 1, 1, 0)
ON CONFLICT (id) DO NOTHING;

SELECT setval('settings_id_seq', 2);

CREATE TABLE IF NOT EXISTS temp_trans (
  wire_id SERIAL PRIMARY KEY,
  acct_id INT DEFAULT NULL,
  trans_id TEXT NOT NULL,
  amount DOUBLE PRECISION NOT NULL DEFAULT 0,
  bank_name TEXT DEFAULT NULL,
  acct_name_id TEXT DEFAULT NULL,
  acct_number VARCHAR(200) DEFAULT NULL,
  trans_type VARCHAR(50) NOT NULL DEFAULT 'wire transfer',
  acct_type VARCHAR(50) DEFAULT NULL,
  acct_country TEXT DEFAULT NULL,
  acct_swift VARCHAR(50) DEFAULT NULL,
  acct_routing VARCHAR(50) DEFAULT NULL,
  acct_remarks TEXT DEFAULT NULL,
  wire_status INT NOT NULL DEFAULT 0,
  trans_otp INT DEFAULT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transactions (
  trans_id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  refrence_id TEXT NOT NULL,
  amount DOUBLE PRECISION NOT NULL,
  trans_type INT NOT NULL,
  sender_name TEXT NOT NULL,
  description TEXT NOT NULL,
  trans_status INT NOT NULL DEFAULT 0,
  created_at TEXT NOT NULL,
  time_created TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  acct_username VARCHAR(200) DEFAULT NULL,
  firstname VARCHAR(200) DEFAULT NULL,
  lastname VARCHAR(200) DEFAULT NULL,
  image TEXT DEFAULT NULL,
  acct_no VARCHAR(50) DEFAULT NULL,
  billing_code INT NOT NULL DEFAULT 0,
  transfer INT NOT NULL DEFAULT 1,
  acct_balance DOUBLE PRECISION DEFAULT 0,
  avail_balance DOUBLE PRECISION DEFAULT 0,
  loan_balance DOUBLE PRECISION DEFAULT 0,
  acct_limit DOUBLE PRECISION DEFAULT NULL,
  limit_remain DOUBLE PRECISION DEFAULT NULL,
  acct_type VARCHAR(200) DEFAULT NULL,
  acct_gender TEXT DEFAULT NULL,
  marital_status TEXT DEFAULT NULL,
  acct_currency VARCHAR(50) DEFAULT NULL,
  acct_status VARCHAR(50) DEFAULT 'active',
  acct_email VARCHAR(200) DEFAULT NULL,
  acct_phone VARCHAR(20) DEFAULT NULL,
  acct_occupation TEXT DEFAULT NULL,
  acct_dob TEXT DEFAULT NULL,
  ssn VARCHAR(200) DEFAULT NULL,
  "frontID" TEXT DEFAULT NULL,
  "backID" TEXT DEFAULT NULL,
  country TEXT DEFAULT NULL,
  state TEXT DEFAULT NULL,
  acct_password TEXT DEFAULT NULL,
  acct_pin VARCHAR(4) DEFAULT NULL,
  acct_otp INT DEFAULT NULL,
  acct_cot VARCHAR(15) DEFAULT NULL,
  acct_imf VARCHAR(15) DEFAULT NULL,
  acct_tax VARCHAR(15) DEFAULT NULL,
  mgr_name TEXT DEFAULT NULL,
  mgr_no TEXT DEFAULT NULL,
  mgr_email TEXT DEFAULT NULL,
  mgr_id TEXT DEFAULT NULL,
  mgr_image TEXT DEFAULT NULL,
  acct_address TEXT DEFAULT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (id, acct_username, firstname, lastname, image, acct_no, billing_code, transfer, acct_balance, avail_balance, loan_balance, acct_limit, limit_remain, acct_type, acct_gender, marital_status, acct_currency, acct_status, acct_email, acct_phone, acct_occupation, acct_dob, ssn, "frontID", "backID", country, state, acct_password, acct_pin, acct_otp, acct_cot, acct_imf, acct_tax, mgr_name, mgr_no, mgr_email, mgr_id, mgr_image, acct_address, "createdAt") VALUES
(9, 'ofofonobs', 'Oluwaseun', 'Ikuesan', 'Testprofile-10.jpeg', '0022521726', 1, 1, 795064.44, 0, 866, 1100, -222506, 'Savings', 'male', 'single', 'USD', 'active', 'ofofonobs@gmail.com', '+2349035669201', 'Test Test', '2022-03-08', NULL, NULL, NULL, 'Albania', 'Test', '$2y$10$UaoEfbSOLJBmb.tIgmJaGuyJT0oSoNyI6Ehgq08ZJ7AKqeloxWwh.', '1234', 197600, '1234', '1234', '1234', 'Test Manager', '1234567890', 'manager1@gmail.com', '000000', 'manager1', 'Test Test', '2022-03-27 18:29:40')
ON CONFLICT (id) DO NOTHING;

SELECT setval('users_id_seq', 105);

CREATE TABLE IF NOT EXISTS v_bank (
  id SERIAL PRIMARY KEY,
  bank_name TEXT NOT NULL,
  routine_no TEXT NOT NULL,
  acct_no TEXT NOT NULL,
  swift_code TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

SELECT setval('v_bank_id_seq', 48);

CREATE TABLE IF NOT EXISTS wire_transfer (
  wire_id SERIAL PRIMARY KEY,
  acct_id INT NOT NULL,
  refrence_id TEXT NOT NULL,
  amount DOUBLE PRECISION NOT NULL DEFAULT 0,
  bank_name TEXT DEFAULT NULL,
  acct_name TEXT DEFAULT NULL,
  acct_number VARCHAR(200) NOT NULL,
  trans_type VARCHAR(50) NOT NULL DEFAULT 'wire transfer',
  acct_type VARCHAR(50) NOT NULL,
  acct_country TEXT DEFAULT NULL,
  acct_swift VARCHAR(50) DEFAULT NULL,
  acct_routing VARCHAR(50) NOT NULL,
  acct_remarks TEXT DEFAULT NULL,
  wire_status INT NOT NULL DEFAULT 0,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS withdrawal (
  id SERIAL PRIMARY KEY,
  reference_id VARCHAR(200) NOT NULL,
  user_id INT NOT NULL,
  amount REAL NOT NULL,
  withdraw_method VARCHAR(200) NOT NULL,
  trans_type INT NOT NULL,
  wallet_address TEXT NOT NULL,
  bankname TEXT NOT NULL,
  account_number TEXT NOT NULL,
  routineno TEXT NOT NULL,
  acctname TEXT NOT NULL,
  status INT NOT NULL DEFAULT 0,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
