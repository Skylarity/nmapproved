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
	businessId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	name       VARCHAR(128)                NOT NULL,
	location   VARCHAR(128),
	phone      VARCHAR(64),
	website    VARCHAR(64),
	email      VARCHAR(64),
	INDEX (businessId),
	PRIMARY KEY (businessId)
);

CREATE TABLE description (
	businessId  INT UNSIGNED NOT NULL,
	description TEXT,
	FOREIGN KEY (businessId) REFERENCES business (businessId)
);