CREATE DATABASE tp5_db;

USE tp5_db;

CREATE TABLE `users`
(
    `id`         bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `name`       varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email`      varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp                               NULL DEFAULT NULL,
    `updated_at` timestamp                               NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
