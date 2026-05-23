-- ============================================================
--  Northwest Registered Online Banking — MySQL / phpMyAdmin
--  Import this file via: phpMyAdmin → your database → Import
--  Tested against MySQL 5.7+ and MariaDB 10.3+
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
--  TABLE: admin
-- ============================================================
CREATE TABLE `admin` (
  `id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `firstname`      VARCHAR(200) NOT NULL,
  `lastname`       VARCHAR(200) NOT NULL,
  `image`          TEXT         DEFAULT NULL,
  `admin_email`    VARCHAR(200) NOT NULL,
  `admin_password` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: email = admin@northwestregisteredonlinebanking.com
-- Default admin password = Admin@1234  (change this immediately after first login)
INSERT INTO `admin` (`id`, `firstname`, `lastname`, `image`, `admin_email`, `admin_password`) VALUES
(1, 'Admin', 'Admin', 'jamesavatar.png',
 'admin@northwestregisteredonlinebanking.com',
 '$2y$10$Ysp7iiUXB4O.p.vm/H.V5eya846d4sHiBlpkc23JPMCbwEnFVddIC');

-- ============================================================
--  TABLE: audit_logs
--  (tracks user logins; flagged column added for suspicious sessions)
-- ============================================================
CREATE TABLE `audit_logs` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id`       INT(11)      NOT NULL,
  `device`        TEXT         NOT NULL,
  `ipAddress`     VARCHAR(200) NOT NULL,
  `datenow`       TIMESTAMP    NULL DEFAULT NULL,
  `flagged`       TINYINT(1)   NOT NULL DEFAULT 0,
  `location_city` TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: card
-- ============================================================
CREATE TABLE `card` (
  `id`               INT(11)     NOT NULL AUTO_INCREMENT,
  `seria_key`        TEXT        NOT NULL,
  `user_id`          INT(11)     NOT NULL,
  `card_number`      TEXT        NOT NULL,
  `card_name`        TEXT        NOT NULL,
  `card_expiration`  VARCHAR(50) NOT NULL,
  `card_security`    TEXT        NOT NULL,
  `card_limit`       DOUBLE      NOT NULL DEFAULT 5000,
  `card_limit_remain` DOUBLE     NOT NULL DEFAULT 5000,
  `card_status`      INT(11)     NOT NULL DEFAULT 2 COMMENT '1=Active,2=Process,3=Hold,4=Paused',
  `createdAt`        DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: card_request
-- ============================================================
CREATE TABLE `card_request` (
  `id`                  INT(11)  NOT NULL AUTO_INCREMENT,
  `reference_id`        TEXT     NOT NULL,
  `user_id`             INT(11)  NOT NULL,
  `card_type`           TEXT     NOT NULL,
  `card_reason`         TEXT     NOT NULL,
  `card_request_status` INT(11)  NOT NULL DEFAULT 2,
  `createdAt`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: crypto_currency
-- ============================================================
CREATE TABLE `crypto_currency` (
  `id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `crypto_name`    VARCHAR(200) NOT NULL,
  `wallet_address` TEXT         NOT NULL,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `crypto_currency` (`id`, `crypto_name`, `wallet_address`, `created_at`) VALUES
(1, 'Bitcoin', 'YOUR_BITCOIN_WALLET_ADDRESS_HERE', NOW());

-- ============================================================
--  TABLE: deposit
-- ============================================================
CREATE TABLE `deposit` (
  `d_id`          INT(11)  NOT NULL AUTO_INCREMENT,
  `user_id`       INT(11)  NOT NULL,
  `refrence_id`   TEXT     NOT NULL,
  `image`         TEXT     DEFAULT NULL,
  `amount`        DOUBLE   NOT NULL,
  `wallet_address` TEXT    NOT NULL,
  `crypto_id`     INT(11)  NOT NULL,
  `crypto_status` INT(11)  NOT NULL DEFAULT 0,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: domestic_transfer
-- ============================================================
CREATE TABLE `domestic_transfer` (
  `dom_id`       INT(11)     NOT NULL AUTO_INCREMENT,
  `acct_id`      INT(11)     DEFAULT NULL,
  `refrence_id`  TEXT        NOT NULL,
  `amount`       DOUBLE      NOT NULL DEFAULT 0,
  `bank_name`    TEXT        DEFAULT NULL,
  `acct_name`    TEXT        DEFAULT NULL,
  `acct_number`  BIGINT(15)  NOT NULL,
  `trans_type`   VARCHAR(50) NOT NULL DEFAULT 'domestic transfer',
  `acct_type`    VARCHAR(50) NOT NULL,
  `acct_remarks` TEXT        DEFAULT NULL,
  `dom_status`   INT(11)     NOT NULL DEFAULT 0,
  `created_at`   DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: loan
-- ============================================================
CREATE TABLE `loan` (
  `loan_id`           INT(11)  NOT NULL AUTO_INCREMENT,
  `loan_reference_id` TEXT     DEFAULT NULL,
  `acct_id`           INT(11)  NOT NULL,
  `amount`            DOUBLE   DEFAULT 0,
  `loan_remarks`      TEXT     NOT NULL,
  `loan_status`       INT(11)  NOT NULL DEFAULT 0,
  `loan_message`      TEXT     DEFAULT NULL,
  `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: messages  (contact form submissions)
-- ============================================================
CREATE TABLE `messages` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `FullName`    TEXT    NOT NULL,
  `PhoneNumber` TEXT    NOT NULL,
  `locationcus` TEXT    NOT NULL,
  `Addresscus`  TEXT    NOT NULL,
  `City`        TEXT    NOT NULL,
  `Customer`    TEXT    NOT NULL,
  `Messagecus`  TEXT    NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: settings
-- ============================================================
CREATE TABLE `settings` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `image`           TEXT         NOT NULL,
  `about_us`        TEXT         NOT NULL,
  `url_name`        TEXT         NOT NULL,
  `url_tel`         VARCHAR(15)  DEFAULT NULL,
  `url_email`       VARCHAR(100) NOT NULL,
  `trans_limit_min` DOUBLE       DEFAULT NULL,
  `trans_limit_max` DOUBLE       DEFAULT NULL,
  `livechat`        TEXT         NOT NULL,
  `twillio_status`  INT(11)      NOT NULL DEFAULT 0  COMMENT '0=off, 1=on',
  `billing_code`    INT(11)      NOT NULL DEFAULT 0  COMMENT '0=off, 1=on',
  `transfer`        INT(11)      NOT NULL DEFAULT 1  COMMENT '0=off, 1=on',
  `bank_deposit`    INT(11)      NOT NULL DEFAULT 0  COMMENT '0=off, 1=on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update url_tel and url_email to your real phone number / support email after import
INSERT INTO `settings`
  (`id`, `image`, `about_us`, `url_name`, `url_tel`, `url_email`,
   `trans_limit_min`, `trans_limit_max`, `livechat`,
   `twillio_status`, `billing_code`, `transfer`, `bank_deposit`)
VALUES
  (1, 'logo.png', 'Northwest Registered Online Banking',
   'Northwest Registered Online Banking',
   'YOUR_PHONE_NUMBER',
   'support@northwestregisteredonlinebanking.com',
   500, 500000, '', 0, 1, 1, 0);

-- ============================================================
--  TABLE: temp_trans  (holds transfers awaiting OTP confirmation)
-- ============================================================
CREATE TABLE `temp_trans` (
  `wire_id`     INT(11)     NOT NULL AUTO_INCREMENT,
  `acct_id`     INT(11)     DEFAULT NULL,
  `trans_id`    TEXT        NOT NULL,
  `amount`      DOUBLE      NOT NULL DEFAULT 0,
  `bank_name`   TEXT        DEFAULT NULL,
  `acct_name_id` TEXT       DEFAULT NULL,
  `acct_number` VARCHAR(200) DEFAULT NULL,
  `trans_type`  VARCHAR(50) NOT NULL DEFAULT 'wire transfer',
  `acct_type`   VARCHAR(50) DEFAULT NULL,
  `acct_country` TEXT       DEFAULT NULL,
  `acct_swift`  VARCHAR(50) DEFAULT NULL,
  `acct_routing` VARCHAR(50) DEFAULT NULL,
  `acct_remarks` TEXT       DEFAULT NULL,
  `wire_status` INT(11)     NOT NULL DEFAULT 0,
  `trans_otp`   INT(11)     DEFAULT NULL,
  `createdAt`   DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: transactions  (credit / debit history)
-- ============================================================
CREATE TABLE `transactions` (
  `trans_id`     INT(11)  NOT NULL AUTO_INCREMENT,
  `user_id`      INT(11)  NOT NULL,
  `refrence_id`  TEXT     NOT NULL,
  `amount`       DOUBLE   NOT NULL,
  `trans_type`   INT(11)  NOT NULL COMMENT '1=Credit, 0=Debit',
  `sender_name`  TEXT     NOT NULL,
  `description`  TEXT     NOT NULL,
  `trans_status` INT(11)  NOT NULL DEFAULT 0,
  `created_at`   TEXT     NOT NULL,
  `time_created` TEXT     NOT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: users
-- ============================================================
CREATE TABLE `users` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `acct_username`   VARCHAR(200) DEFAULT NULL,
  `firstname`       VARCHAR(200) DEFAULT NULL,
  `lastname`        VARCHAR(200) DEFAULT NULL,
  `image`           TEXT         DEFAULT NULL,
  `acct_no`         VARCHAR(50)  DEFAULT NULL,
  `billing_code`    INT(11)      NOT NULL DEFAULT 0  COMMENT '0=deactivated, 1=active',
  `transfer`        INT(11)      NOT NULL DEFAULT 1  COMMENT '0=deactivated, 1=active',
  `acct_balance`    DOUBLE       DEFAULT 0,
  `avail_balance`   DOUBLE       DEFAULT 0,
  `loan_balance`    DOUBLE       DEFAULT 0,
  `acct_limit`      DOUBLE       DEFAULT NULL,
  `limit_remain`    DOUBLE       DEFAULT NULL,
  `acct_type`       VARCHAR(200) DEFAULT NULL,
  `acct_gender`     TEXT         DEFAULT NULL,
  `marital_status`  TEXT         DEFAULT NULL,
  `acct_currency`   VARCHAR(50)  DEFAULT NULL,
  `acct_status`     VARCHAR(50)  DEFAULT 'active'  COMMENT 'active, hold',
  `acct_email`      VARCHAR(200) DEFAULT NULL,
  `acct_phone`      VARCHAR(20)  DEFAULT NULL,
  `acct_occupation` TEXT         DEFAULT NULL,
  `acct_dob`        TEXT         DEFAULT NULL,
  `ssn`             VARCHAR(200) DEFAULT NULL,
  `frontID`         TEXT         DEFAULT NULL,
  `backID`          TEXT         DEFAULT NULL,
  `country`         TEXT         DEFAULT NULL,
  `state`           TEXT         DEFAULT NULL,
  `acct_password`   TEXT         DEFAULT NULL,
  `acct_pin`        VARCHAR(4)   DEFAULT NULL,
  `acct_otp`        INT(11)      DEFAULT NULL,
  `acct_cot`        VARCHAR(15)  DEFAULT NULL,
  `acct_imf`        VARCHAR(15)  DEFAULT NULL,
  `acct_tax`        VARCHAR(15)  DEFAULT NULL,
  `mgr_name`        TEXT         DEFAULT NULL,
  `mgr_no`          TEXT         DEFAULT NULL,
  `mgr_email`       TEXT         DEFAULT NULL,
  `mgr_id`          TEXT         DEFAULT NULL,
  `mgr_image`       TEXT         DEFAULT NULL,
  `acct_address`    TEXT         DEFAULT NULL,
  `createdAt`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: v_bank  (bank deposit receiving details)
-- ============================================================
CREATE TABLE `v_bank` (
  `id`         INT(11)  NOT NULL AUTO_INCREMENT,
  `bank_name`  TEXT     NOT NULL,
  `routine_no` TEXT     NOT NULL,
  `acct_no`    TEXT     NOT NULL,
  `swift_code` TEXT     NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: wire_transfer
-- ============================================================
CREATE TABLE `wire_transfer` (
  `wire_id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `acct_id`      INT(11)      NOT NULL,
  `refrence_id`  TEXT         NOT NULL,
  `amount`       DOUBLE       NOT NULL DEFAULT 0,
  `bank_name`    TEXT         DEFAULT NULL,
  `acct_name`    TEXT         DEFAULT NULL,
  `acct_number`  VARCHAR(200) NOT NULL,
  `trans_type`   VARCHAR(50)  NOT NULL DEFAULT 'wire transfer',
  `acct_type`    VARCHAR(50)  NOT NULL,
  `acct_country` TEXT         DEFAULT NULL,
  `acct_swift`   VARCHAR(50)  DEFAULT NULL,
  `acct_routing` VARCHAR(50)  NOT NULL,
  `acct_remarks` TEXT         DEFAULT NULL,
  `wire_status`  INT(11)      NOT NULL DEFAULT 0,
  `createdAt`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TABLE: withdrawal
-- ============================================================
CREATE TABLE `withdrawal` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `reference_id`    VARCHAR(200) NOT NULL,
  `user_id`         INT(11)      NOT NULL,
  `amount`          FLOAT        NOT NULL,
  `withdraw_method` VARCHAR(200) NOT NULL,
  `trans_type`      INT(11)      NOT NULL,
  `wallet_address`  TEXT         NOT NULL,
  `bankname`        TEXT         NOT NULL,
  `account_number`  TEXT         NOT NULL,
  `routineno`       TEXT         NOT NULL,
  `acctname`        TEXT         NOT NULL,
  `status`          INT(11)      NOT NULL DEFAULT 0,
  `createdAt`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Commit
-- ============================================================
COMMIT;
