-- =========================================================
-- Database: db_finalcut
-- Platform film review + booking tiket bioskop
-- Import langsung lewat phpMyAdmin: menu Import > pilih file ini
-- =========================================================

CREATE DATABASE IF NOT EXISTS `db_finalcut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_finalcut`;

-- ---------------------------------------------------------
-- Tabel users
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel genres
-- ---------------------------------------------------------
CREATE TABLE `genres` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel movies
-- ---------------------------------------------------------
CREATE TABLE `movies` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `sinopsis` TEXT,
  `durasi` INT UNSIGNED COMMENT 'dalam menit',
  `rating_umur` VARCHAR(10) DEFAULT 'SU',
  `poster` VARCHAR(255) DEFAULT NULL,
  `trailer_url` VARCHAR(255) DEFAULT NULL,
  `release_date` DATE,
  `director` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel movie_genre (pivot: satu film bisa banyak genre)
-- ---------------------------------------------------------
CREATE TABLE `movie_genre` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `movie_id` INT UNSIGNED NOT NULL,
  `genre_id` INT UNSIGNED NOT NULL,
  UNIQUE KEY `uniq_movie_genre` (`movie_id`, `genre_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`genre_id`) REFERENCES `genres`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel reviews
-- ---------------------------------------------------------
CREATE TABLE `reviews` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `movie_id` INT UNSIGNED NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL,
  `review_text` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel watchlist (film yang "mau ditonton")
-- ---------------------------------------------------------
CREATE TABLE `watchlist` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `movie_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_user_movie_watchlist` (`user_id`, `movie_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel watched_diary (catatan film yang sudah ditonton)
-- ---------------------------------------------------------
CREATE TABLE `watched_diary` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `movie_id` INT UNSIGNED NOT NULL,
  `watched_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel cinemas
-- ---------------------------------------------------------
CREATE TABLE `cinemas` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `address` VARCHAR(255),
  `city` VARCHAR(100),
  `latitude` DECIMAL(10,8) DEFAULT NULL,
  `longitude` DECIMAL(11,8) DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel studios (ruang/studio di tiap bioskop)
-- ---------------------------------------------------------
CREATE TABLE `studios` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `cinema_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `capacity` INT UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (`cinema_id`) REFERENCES `cinemas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel showtimes (jadwal tayang)
-- ---------------------------------------------------------
CREATE TABLE `showtimes` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `movie_id` INT UNSIGNED NOT NULL,
  `studio_id` INT UNSIGNED NOT NULL,
  `show_date` DATE NOT NULL,
  `show_time` TIME NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`studio_id`) REFERENCES `studios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel seats (kursi per studio)
-- ---------------------------------------------------------
CREATE TABLE `seats` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `studio_id` INT UNSIGNED NOT NULL,
  `seat_number` VARCHAR(10) NOT NULL,
  `seat_type` ENUM('reguler','vip') NOT NULL DEFAULT 'reguler',
  UNIQUE KEY `uniq_studio_seat` (`studio_id`, `seat_number`),
  FOREIGN KEY (`studio_id`) REFERENCES `studios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel bookings (transaksi pemesanan tiket)
-- ---------------------------------------------------------
CREATE TABLE `bookings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `showtime_id` INT UNSIGNED NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `booking_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`showtime_id`) REFERENCES `showtimes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel booking_seats (pivot: kursi mana saja yang dipesan)
-- ---------------------------------------------------------
CREATE TABLE `booking_seats` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT UNSIGNED NOT NULL,
  `seat_id` INT UNSIGNED NOT NULL,
  UNIQUE KEY `uniq_booking_seat` (`booking_id`, `seat_id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`seat_id`) REFERENCES `seats`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel payments
-- ---------------------------------------------------------
CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `method` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
  `payment_date` TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- Data awal (seed) supaya bisa langsung dites setelah import
-- Password akun di bawah = "password" (sudah di-hash bcrypt)
-- Ganti/hash ulang sesuai kebutuhan aplikasi kamu nanti
-- =========================================================

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin FinalCut', 'admin@finalcut.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('User Percobaan', 'user@finalcut.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO `genres` (`name`) VALUES
('Action'), ('Drama'), ('Horror'), ('Comedy'), ('Sci-Fi');

INSERT INTO `cinemas` (`name`, `address`, `city`) VALUES
('FinalCut Cineplex - Paskal', 'Jl. Pasirkaliki No. 25', 'Bandung');

INSERT INTO `studios` (`cinema_id`, `name`, `capacity`) VALUES
(1, 'Studio 1', 40);

INSERT INTO `seats` (`studio_id`, `seat_number`, `seat_type`) VALUES
(1, 'A1', 'reguler'), (1, 'A2', 'reguler'), (1, 'B1', 'vip'), (1, 'B2', 'vip');
