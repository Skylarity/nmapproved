DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS businessCategory;
DROP TABLE IF EXISTS subcategory;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS description;
DROP TABLE IF EXISTS business;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
	userId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	hash   CHAR(128),
	salt   CHAR(64),
	email  VARCHAR(64)                 NOT NULL,
	INDEX (userId),
	PRIMARY KEY (userId),
	UNIQUE (email)
);

CREATE TABLE business (
	businessId  INT UNSIGNED AUTO_INCREMENT NOT NULL,
	name        VARCHAR(128)                NOT NULL,
	location    VARCHAR(128),
	phone       VARCHAR(64),
	website     VARCHAR(64),
	email       VARCHAR(64),
	category    VARCHAR(64),
	subcategory VARCHAR(64),
	INDEX (businessId),
	PRIMARY KEY (businessId)
);

CREATE TABLE description (
	businessId  INT UNSIGNED NOT NULL,
	description TEXT,
	FOREIGN KEY (businessId) REFERENCES business (businessId)
);

CREATE TABLE category (
	categoryId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	name       VARCHAR(64),
	INDEX (categoryId),
	PRIMARY KEY (categoryId)
);

CREATE TABLE subcategory (
	subcategoryId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	name          VARCHAR(64),
	INDEX (subcategoryId),
	PRIMARY KEY (subcategoryId)
);

CREATE TABLE businessCategory (
	businessId    INT UNSIGNED NOT NULL,
	categoryId    INT UNSIGNED NOT NULL,
	subcategoryId INT UNSIGNED NOT NULL,
	FOREIGN KEY (businessId) REFERENCES business (businessId),
	FOREIGN KEY (categoryId) REFERENCES category (categoryId),
	FOREIGN KEY (subcategoryId) REFERENCES subcategory (subcategoryId)
);

CREATE TABLE image (
	businessId INT UNSIGNED                NOT NULL,
	imageId    INT UNSIGNED AUTO_INCREMENT NOT NULL,
	type       VARCHAR(16),
	path       VARCHAR(256),
	FOREIGN KEY (businessId) REFERENCES business (businessId),
	INDEX (imageId),
	PRIMARY KEY (imageId)
);