# Add challenge timeout
ALTER TABLE ladders ADD challenge_timeout INT NOT NULL DEFAULT 0
ALTER TABLE users ADD pw_reset INT NOT NULL DEFAULT 0, ADD pw_reset_expire INT NOT NULL DEFAULT 0
