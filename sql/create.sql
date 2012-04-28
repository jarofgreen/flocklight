CREATE TABLE twitter_account (
  id BIGINT  UNSIGNED NOT NULL,
  user_name VARCHAR(100),
  profile_image_url VARCHAR(250),
  oauth_token VARCHAR(250),
  oauth_secret VARCHAR(250),
  write_oauth_token VARCHAR(250),
  write_oauth_secret VARCHAR(250),
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE tweet (
  id BIGINT UNSIGNED NOT NULL,
  text VARCHAR(250) NOT NULL,
  created_at DATETIME NOT NULL,
  actually_created_at DATETIME NULL,
  account_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tweet ADD CONSTRAINT tweet_account_id  FOREIGN KEY (account_id) REFERENCES twitter_account(id);

CREATE TABLE tweet_conversation (
  from_account_id  BIGINT UNSIGNED NOT NULL,
  to_account_id  BIGINT UNSIGNED NOT NULL,
  tweet_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (from_account_id, to_account_id, tweet_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tweet_conversation ADD CONSTRAINT tweet_conversation_from_account_id  FOREIGN KEY (from_account_id) REFERENCES twitter_account(id);
ALTER TABLE tweet_conversation ADD CONSTRAINT tweet_conversation_to_ccount_id  FOREIGN KEY (to_account_id) REFERENCES twitter_account(id);
ALTER TABLE tweet_conversation ADD CONSTRAINT tweet_conversation_tweet_id  FOREIGN KEY (tweet_id) REFERENCES tweet(id);

CREATE TABLE follows (
  account_id BIGINT UNSIGNED NOT NULL,
  follows_account_id BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (account_id, follows_account_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE follows ADD CONSTRAINT follows_account_id  FOREIGN KEY (account_id) REFERENCES twitter_account(id);
ALTER TABLE follows ADD CONSTRAINT follows_follows_ccount_id  FOREIGN KEY (follows_account_id) REFERENCES twitter_account(id);


