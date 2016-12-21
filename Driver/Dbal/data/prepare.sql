CREATE TABLE IF NOT EXISTS `__PREFIX__requests` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`happened` INTEGER NOT NULL,
	`ip` TEXT NOT NULL,
	`url` TEXT NOT NULL,
	`method` TEXT,
	`path` TEXT,
	`route` TEXT,
	`username` TEXT,
	`session_key` TEXT
)