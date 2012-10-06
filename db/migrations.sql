# Add challenge timeout of 2 weeks
ALTER TABLE ladders ADD challenge_timeout INT NOT NULL DEFAULT 1209600

# Add user inactive timeout of 6 weeks
ALTER TABLE ladders ADD inactive_timeout INT NOT NULL DEFAULT 3628800

ALTER TABLE users ADD pw_reset INT NOT NULL DEFAULT 0, ADD pw_reset_expire INT NOT NULL DEFAULT 0
ALTER TABLE users ADD last_visit INT UNSIGNED NOT NULL DEFAULT 0
