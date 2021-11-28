-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2021 at 08:36 AM
-- Server version: 10.3.32-MariaDB
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `designhr_travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activations`
--

CREATE TABLE `activations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'YnWaPujuAsxKFPcAqE9ISIQlCYAEnQO2', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(2, 2, '2Y3BF2XFwoHi84agjCckMUCmNz3PUe9Z', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(3, 3, '8wnyjHloyg5AAstHPIT3PPNfGGgdmN6t', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(4, 4, 'HYueZVMoiZansAmDquhkKPXKzgJha7Gd', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(5, 5, 'Pb6H3NitfSDEh3hsxgJUfHpwOLD03s71', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(6, 6, 'An6JLmxkb56xLsqGsB9IFyFut1wfKp9h', 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47', '2021-11-16 11:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) NOT NULL,
  `booking_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint(20) NOT NULL,
  `total` double(16,5) NOT NULL,
  `number_of_guest` int(11) NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer` bigint(20) NOT NULL,
  `owner` bigint(20) NOT NULL,
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkout_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` bigint(20) NOT NULL,
  `end_date` bigint(20) NOT NULL,
  `start_time` bigint(20) NOT NULL,
  `end_time` bigint(20) NOT NULL,
  `created_date` bigint(20) NOT NULL,
  `total_minutes` bigint(20) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirm` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` bigint(20) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_lat` double(10,6) DEFAULT 48.856613,
  `location_lng` double(10,6) DEFAULT 2.352222,
  `location_address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_zoom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '12',
  `location_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_postcode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_country` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_city` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `equipments` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_form` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'instant',
  `base_price` double(16,5) DEFAULT NULL,
  `enable_cancellation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_before` int(11) DEFAULT NULL,
  `cancellation_detail` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double(8,1) DEFAULT NULL,
  `is_featured` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `passenger` int(11) DEFAULT 1,
  `gear_shift` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baggage` int(11) DEFAULT NULL,
  `door` int(11) DEFAULT NULL,
  `video` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'car',
  `discount_by_day` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_plan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_external` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `use_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `text_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `quantity`, `equipments`, `car_type`, `features`, `booking_form`, `base_price`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `rating`, `is_featured`, `passenger`, `gear_shift`, `baggage`, `door`, `video`, `status`, `post_type`, `discount_by_day`, `insurance_plan`, `enable_external`, `use_external_link`, `text_external_link`) VALUES
(2, '2020 Nissan Titan Pro-4X', '2020-nissan-titan-pro-4x', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The production model of the Countach was codenamed LP 400 because its V12 – positioned longitudinally behind the cockpit – was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.', 1, '1586244027', 41.875600, -87.624400, '[:en]Chicago, United States of America[:vi][:]', '12.084602103345945', '[:en][:vi][:]', NULL, '[:en]United States of America[:vi]United States of', '[:en]Chicago[:vi][:]', '289,288,291,290,284,285,286,287,282,283', '289', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '61', '81,82,83,84,85,86', 'instant', 55.00000, 'on', 5, '[:en]The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together.[:vi][:]', NULL, 'on', 4, '[:en]Auto[:vi][:]', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(4, 'Honda Civic', 'honda-civic', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car’s performance but to improve its overall quality, which had been neglected in the haste to start production.', 1, '1586245093', 48.856580, 2.351830, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '294,297,292,295,293,296', '294', 5, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '60', '81,82,83,84,85,86', 'instant_enquiry', 60.00000, 'on', 4, 'The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together.', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(5, 'VinFast Fadil', 'vinfast-fadil', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; color: #5e6d77; font-family: Poppins, sans-serif; font-size: 14px; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', NULL, 1, '1586247821', 48.856580, 2.351830, 'Paris, France', '12.459371419731522', NULL, NULL, 'France', 'Paris', '298,299,300,301,304,302,303', '298', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '60', '81,82,83,84,85,86', 'instant', 75.00000, 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class', NULL, 'off', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(6, 'Vinfast Lux A2.0', 'vinfast-lux-a20', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car’s performance but to improve its overall quality, which had been neglected in the haste to start production.', 1, '1586266193', 48.856580, 2.351830, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '329,330,325,326,327,328', '329', 10, 'a:5:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:2:\"10\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:1:\"7\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:1:\"4\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:2:\"10\";s:6:\"custom\";b:0;}i:95;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:1:\"5\";s:6:\"custom\";b:0;}}', '59', '81,82,83,84,85,86', 'instant_enquiry', 50.00000, 'on', 5, 'The production model of the Countach was codenamed LP 400 because its V12 – positioned longitudinally behind the cockpit – was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:2:{i:0;a:4:{s:4:\"name\";s:8:\"3-5 days\";s:4:\"from\";s:1:\"3\";s:2:\"to\";s:1:\"5\";s:5:\"price\";s:2:\"40\";}i:1;a:4:{s:4:\"name\";s:8:\"6-7 days\";s:4:\"from\";s:1:\"6\";s:2:\"to\";s:1:\"7\";s:5:\"price\";s:2:\"35\";}}', 'a:3:{i:0;a:5:{s:4:\"name\";s:16:\"Insurance Plan 1\";s:11:\"name_unique\";s:16:\"insurance-plan-1\";s:11:\"description\";s:74:\"Lorem Ipsum is simply dummy text of the printing and typesetting industry.\";s:5:\"price\";s:2:\"20\";s:5:\"fixed\";s:2:\"on\";}i:1;a:5:{s:4:\"name\";s:16:\"Insurance Plan 2\";s:11:\"name_unique\";s:16:\"insurance-plan-2\";s:11:\"description\";s:74:\"Lorem Ipsum is simply dummy text of the printing and typesetting industry.\";s:5:\"price\";s:2:\"25\";s:5:\"fixed\";s:2:\"on\";}i:2;a:5:{s:4:\"name\";s:16:\"Insurance Plan 3\";s:11:\"name_unique\";s:16:\"insurance-plan-3\";s:11:\"description\";s:74:\"Lorem Ipsum is simply dummy text of the printing and typesetting industry.\";s:5:\"price\";s:1:\"5\";s:5:\"fixed\";s:0:\"\";}}', 'off', '', ''),
(7, 'Honda Accord 2020', 'honda-accord-2020', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car’s performance but to improve its overall quality, which had been neglected in the haste to start production.', 1, '1586267383', 48.856580, 2.351830, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '338,335,336,337,331,332,333,334', '338', 10, 'a:5:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:2:\"10\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:1:\"7\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:1:\"4\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:2:\"10\";s:6:\"custom\";b:0;}i:95;a:3:{s:6:\"choose\";s:2:\"no\";s:5:\"price\";s:1:\"5\";s:6:\"custom\";b:0;}}', '58', '81,82,83,84,85,86', 'instant', 45.00000, 'on', 5, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', 'a:0:{}', 'off', '', ''),
(9, 'Toyota Camry 2020', 'toyota-camry-2020', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 1, '1586268028', 48.852941, 2.397578, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '341,342,340,339', '341', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '59', '81,82,83,84,85,86', 'instant_enquiry', 45.00000, 'on', 5, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', NULL, 'off', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(10, 'BMW M6 Gran Coupe', 'bmw-m6-gran-coupe', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 1, '1586270320', 48.856580, 2.351830, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '345,346,347,343,344', '345', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '61', '81,82,83,84,85,86', 'instant_enquiry', 100.00000, 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(11, '2019 Honda Clarity', '2019-honda-clarity', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car’s performance but to improve its overall quality, which had been neglected in the haste to start production.', 1, '1586271419', 41.874171, -87.645307, 'Chicago, United States of America', '11.267200527152067', NULL, NULL, 'United States of America', 'Chicago', '351,350,348,349,353,352', '351', 15, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '63', '81,82,83,84,85,86', 'instant', 50.00000, 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(12, '2019 Audi S3', '2019-audi-s3', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 1, '1586272186', 48.848649, 2.338870, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '357,359,358,356,354,355', '357', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '58', '81,82,83,84,85,86', 'instant', 80.00000, 'on', 5, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(13, 'Lamborghini', 'lamborghini', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 1, '1586272775', 48.869316, 2.315009, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '364,360,361,363,362', '364', 10, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '63', '81,82,83,84,85,86', 'enquiry', 200.00000, 'on', 7, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', NULL, 'off', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(14, 'Nissan GT R', 'nissan-gt-r', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 1, '1586273018', 48.857798, 2.289259, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '367,365,366,368', '367', 18, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '61', '81,82,83,84,85,86', 'instant_enquiry', 45.00000, 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class', NULL, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(15, 'Mercedes Benz', 'mercedes-benz', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The production model of the Countach was codenamed LP 400 because its V12 – positioned longitudinally behind the cockpit – was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.', 1, '1586274135', 48.882266, 2.351977, 'Paris, France', '12', NULL, NULL, 'France', 'Paris', '372,369,370,371', '372', 12, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '60', '81,82,83,84,85,86', 'instant', 100.00000, 'on', 3, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', NULL, 'off', 4, 'Auto', 4, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(16, 'Honda CR-V', 'honda-cr-v', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'The production model of the Countach was codenamed LP 400 because its V12 – positioned longitudinally behind the cockpit – was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.', 1, '1586274342', 48.826786, 2.431882, 'Paris, France', '11.71473381971117', NULL, NULL, 'France', 'Paris', '374,375,376,371,373', '374', 18, 'a:4:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}}', '59', '81,82,83,84,85,86', 'instant', 60.00000, 'on', 2, 'The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.', 5.0, 'on', 4, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', ''),
(17, 'Toyota Fortuner 2019', 'toyota-fortuner-2019', '<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The company continued to work at its regular pace. In 1972, the P250 Urraco, the 400 GT Jarama, the 400 GT Espada and the P400 Miura SV were in full production. That year, in an attempt to improve sales that were frankly quite disappointing until then, the Jarama hand a 365-hp engine and was dubbed the Jarama S.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car&rsquo;s performance but to improve its overall quality, which had been neglected in the haste to start production.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; font-family: Poppins, sans-serif; font-size: 14px; color: #5e6d77; background-color: #ffffff;\">The production model of the Countach was codenamed LP 400 because its V12 &ndash; positioned longitudinally behind the cockpit &ndash; was increased to an ideal displacement of 4 litres (3929 cc). This model debuted at the 1973 Geneva Motor Show.</p>', 'In 1972, the Urraco, which had experienced several initial slowdowns, was finally put into production. Almost inevitably, the S version also arrived in October of that year. In this case, the goal was not to enhance the car’s performance but to improve its overall quality, which had been neglected in the haste to start production.', 1, '1586274639', 48.865310, 2.371228, 'Paris, France', '11.956034298658611', NULL, NULL, 'France', 'Paris', '377,379,380,378', '377', 10, 'a:5:{i:90;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:93;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:89;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.2\";s:6:\"custom\";b:0;}i:88;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";s:3:\"0.5\";s:6:\"custom\";b:0;}i:95;a:3:{s:6:\"choose\";s:3:\"yes\";s:5:\"price\";d:0.5;s:6:\"custom\";b:1;}}', '62', '81,82,83,84,85,86', 'instant', 30.00000, 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class', NULL, 'on', 7, 'Auto', 8, 4, 'https://www.youtube.com/watch?v=2obL1oPpEPI', 'publish', 'car', 'a:0:{}', NULL, 'off', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `car_price`
--

CREATE TABLE `car_price` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) NOT NULL,
  `start_date` bigint(20) NOT NULL,
  `end_date` bigint(20) NOT NULL,
  `price` double(16,5) NOT NULL,
  `available` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author` int(11) NOT NULL,
  `comment_rate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_title`, `comment_content`, `comment_name`, `comment_email`, `comment_author`, `comment_rate`, `post_type`, `parent`, `status`, `created_at`) VALUES
(1, 1, 'We had an excellent stay at the trullo', 'We had an excellent stay at the trullo - everything was perfect, starting with Anna’s generosity to meet us in town so we wouldn’t lose our way, to the beautiful setting of the trullo, to the fresh eggs and tomatoes for our use, to Anna’s tips and suggestions for local restaurants and eateries! A superb and memorable time away for us! So happy we discovered this place and highly recommend it!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1578558848'),
(2, 13, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579010928'),
(3, 20, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579010948'),
(4, 21, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579010971'),
(5, 19, 'We absolutely loved Mar\'s apartmentv', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautifulvvvv', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579011007'),
(6, 12, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579011030'),
(7, 25, 'Stewart is a wonderful host', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579233103'),
(8, 15, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270806'),
(9, 4, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270823'),
(10, 3, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270835'),
(11, 2, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '4', 'home', 0, 'publish', '1579270860'),
(12, 5, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270880'),
(13, 6, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270886'),
(14, 6, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270897'),
(15, 7, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270911'),
(16, 8, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270921'),
(17, 8, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270931'),
(18, 9, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270942'),
(19, 10, 'We absolutely loved Mar\'s apartment', 'This apartment was perfect. If you have a large group (4-8 people) this listing should be your choice. It has tons of room, 2 bathrooms, and 2 showers, and it is beautiful', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579270953'),
(20, 29, 'We absolutely loved Mar\'s apartment', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579524690'),
(21, 27, 'We absolutely loved Mar\'s apartment', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579524696'),
(22, 26, 'We absolutely loved Mar\'s apartment', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579524714'),
(23, 28, 'We absolutely loved Mar\'s apartment', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579524720'),
(24, 26, 'We absolutely loved Mar\'s apartment', 'The house is great, in a peaceful neighborhood about 20 minutes walking distance from downtown, and right next to the Boulder creek and trailheads in the mountains. Stewart himself is extremely kind, willing to help and provides a lot of tips. An amazing experience', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1579526895'),
(32, 1, 'A wonderful experience.', 'A wonderful experience. Everything from the market tour to the actual cooking was very well planned and chef helped us execute at our own pace. Learned a lot about the culture and met some nice people as well. Also, got a recipe book as homework :)', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586239647'),
(33, 4, 'Amazing experience', 'Amazing experience. Fantastic host, expert knowledge, amazing location. On top of that the lesson and the food was superb. Highly recommend this experience for all.', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586242808'),
(34, 5, 'Amazing experience', 'Great experience to learn more about farm to table. Elizabeth had great knowledge about different types of Vegetables, grains and it was nice to learn much about where food comes from and enjoying the process of cooking with a group.', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586243199'),
(35, 6, 'Happy time', 'What an incredible, once in a lifetime experience. Jerusa could not have been kinder or more generous. Her home is idyllic, my family and I felt truly relaxed and peaceful there despite being in the middle of a busy trip. Her farmland was idyllic, we truly enjoyed walking along her crops, meeting her adorable dogs, donkey, chickens, and more, she taught us many things about Catalan culture that we would have never known, and the paella we made was delicious. I will be recommending this experience to EVERYONE I know, and truly feel like Jerusa is now a part of our family. We will never forget the memories we made with her and the hospitality she showed us. We hope very much to come back one day.', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586243613'),
(36, 9, 'The delicious food', 'Eleonora was wonderful! The location, the delicious food, and the overall experience was something we will not forget. You will not be disappointed with booking this experience. Thank you for opening your home to us, and for such a lovely evening!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586245149'),
(37, 10, 'Amazing time', 'This was absolutly amazing time spent on making paella, torrilla and drinking great wine! They have made our evening really special! We love paella but this one was extraordinary, make with love and pasion. Amazing people! When we will be again in Barcelona asap we will repeat!! Again thanks!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586245459'),
(38, 12, 'Unforgettable time', 'This activity was more than expected. Nyoman and Wayan, two guides, were very knowledgeable about the local culture and showed genuine interest to introduce the culture. Especially the visit to local house compound and preparing and sharing the local food was extremely good. I was very impressed by the Bali culture and by the two guides. I recommend this activity to anyone interested in learning Bali culture. The guides deserve high commendations. thanks much!!!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586246121'),
(39, 4, 'A wonderful experience.', 'Realmente vale realizar está experiencia para todos aquellos que quieran conocer tips de preparación del chocolate. Bernardo se toma el tiempo de explicar las veces que sea necesario y es muy apasionado en lo que hace.', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586246682'),
(40, 23, 'Amazing experience', 'Amazing experience, Ibiza (Horse) was wonderful, Antonia taught us a lot about horse and helped us understand how to bond with horse. Strongly recommend this experience.', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586250395'),
(41, 35, 'A wonderful experience', 'DJ Jigüe fue más allá de lo esperado para hacernos sentir a mi novia y yo que estábamos en Cuba. En todo mi viaje no aprendí y me divertí tanto como este día. Saludos y gracias hermano!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586401374'),
(42, 16, 'Best', 'I love this car!!!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'car', 0, 'publish', '1586703688'),
(43, 19, 'I love this experience!', 'This is the best of VietNam!!!', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'experience', 0, 'publish', '1586704698'),
(44, 16, 'Easy way to discover the city', 'Varius massa maecenas et id dictumst mattis. Donec fringilla ac parturient posuere id phasellus erat elementum nullam lacus cursus rhoncus parturient vitae praesent quisque nascetur molestie quis', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'car', 0, 'publish', '1586706464'),
(45, 16, 'Very Good', 'Buses don\'t come often and drivers don\'t have this information, there is a site that shows you the GPS location of the bus', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'car', 0, 'publish', '1586706475'),
(46, 16, 'Good service', 'Varius massa maecenas et id dictumst mattis. Donec fringilla ac parturient posuere id phasellus erat elementum nullam lacus cursus rhoncus parturient vitae praesent quisque nascetur molestie quis', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'car', 0, 'publish', '1586706499'),
(49, 37, 'I love this home', 'I love this home@@@', 'Awe Booking', 'admin@awebooking.org', 1, '5', 'home', 0, 'publish', '1586755429'),
(50, 1, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757380'),
(51, 2, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757500'),
(52, 3, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757530'),
(53, 4, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757547'),
(54, 5, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757562'),
(55, 6, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757578'),
(56, 7, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757597'),
(57, 8, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757613'),
(58, 11, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757653'),
(59, 14, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757671'),
(60, 11, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757684'),
(61, 16, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757697'),
(62, 17, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757710'),
(63, 18, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757722'),
(64, 19, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757734'),
(65, 20, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757756'),
(66, 21, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757769'),
(67, 22, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757780'),
(68, 24, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757807'),
(69, 25, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757822'),
(70, 26, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757839'),
(71, 27, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757851'),
(72, 28, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757866'),
(73, 29, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757878'),
(74, 30, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757891'),
(75, 31, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757907'),
(76, 32, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757920'),
(77, 33, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757932'),
(78, 34, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757945'),
(79, 36, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757971'),
(80, 37, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757983'),
(81, 41, 'My family and I loved this experience.', 'My family and I loved this experience. My daughter and I love to bake, but my husband and son rarely step into the kitchen to cook, so watching them enjoy themselves was very special. Emma was a kind and patient teacher. She made the experience fun with her humor and stories. We shared the class with a mother and daughter who were fun as well. Not only did we learn how to make a baguette, but we also had a great time doing it as a family. This experience was a great value as well.', 'Jaclyn K ', 'partner@awebooking.org', 2, '5', 'experience', 0, 'publish', '1586757997');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_price` double(16,5) NOT NULL,
  `price_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `earning`
--

CREATE TABLE `earning` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `amount` double(15,6) NOT NULL,
  `net_amount` double(15,6) NOT NULL,
  `balance` double(15,6) NOT NULL,
  `payout` double(15,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` bigint(20) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_lat` double(10,6) DEFAULT 48.856613,
  `location_lng` double(10,6) DEFAULT 2.352222,
  `location_address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_zoom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '12',
  `location_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_postcode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_country` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_city` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_guest` int(11) DEFAULT NULL,
  `booking_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_form` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'instant',
  `base_price` double(16,5) DEFAULT NULL,
  `adult_price` double(16,5) DEFAULT NULL,
  `child_price` double(16,5) DEFAULT NULL,
  `infant_price` double(16,5) DEFAULT NULL,
  `languages` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inclusions` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclusions` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itinerary` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_categories` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_primary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_services` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_points` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_cancellation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_before` int(11) DEFAULT NULL,
  `cancellation_detail` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double(8,1) DEFAULT NULL,
  `is_featured` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_ical_url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'experience',
  `tour_packages` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `use_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `text_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `booking_type`, `booking_form`, `base_price`, `adult_price`, `child_price`, `infant_price`, `languages`, `durations`, `inclusions`, `exclusions`, `itinerary`, `price_categories`, `price_primary`, `video`, `extra_services`, `meeting_points`, `amenities`, `experience_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `rating`, `is_featured`, `status`, `import_ical_url`, `post_type`, `tour_packages`, `use_external_link`, `text_external_link`) VALUES
(1, 'Cooking Class in Local Villa', 'cooking-class-in-local-villa', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'To begin, you will be picked up from Hanoi Opera House. \r\nWe will welcome you to our kitchen and the cooking time may take around 3 hours and after that you will enjoy the work of our labor.', 1, '1586238077', 21.016233, 105.805275, '[:en]Ha Noi, Viet Nam[:vi]Ha Noi, Viet Nam[:]', '11.732399161829072', '[:en][:vi][:]', NULL, '[:en]Vietnam[:vi]Vietnam[:]', '[:en]Ha Noi[:vi][:]', '281,268,246,237,228,239', '237', 5, 'date_time', 'instant', 250.00000, 250.00000, 100.00000, 0.00000, '66,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults,enable_children', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 2, '[:en]Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class. However, if you do not cancel prior to the 48 hours, you will lose the payment for the class. The owner has the only right to be flexible here.[:vi][:]', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(2, 'Handmade pasta with grandma', 'handmade-pasta-with-grandma', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586240768', 21.010862, 105.758411, 'Ha Noi, Viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '206,255,264,237,225,216', '206', 8, 'date_time', 'instant_enquiry', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '6 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:32:\"rekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(3, 'Ancient México Cooking Experience', 'ancient-mexico-cooking-experience', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586241892', 21.000000, 105.750000, 'Ha Noi Viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '199,206,210,216,225,255', '199', 3, 'date_time', 'instant_enquiry', 200.00000, 200.00000, 0.00000, 0.00000, '66,65', '6 hours', '74,70,73,71,75,72', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(4, 'Boring Indian Food Workshop', 'boring-indian-food-workshop', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586242268', 20.986663, 105.781328, 'Ha Noi Viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '234,218,225,216,209', '209', 4, 'date_time', 'instant', 250.00000, 250.00000, 0.00000, 0.00000, '69,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(5, 'On the farm cooking class', 'on-the-farm-cooking-class', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586242827', 20.975123, 105.785620, 'Ha Noi Viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '210,209,219,229,226', '210', 5, 'date_time', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '69,68', '3 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:32:\"rekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(6, 'Lunch in my Rural Orchard', 'lunch-in-my-rural-orchard', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586243246', 20.972077, 105.800039, 'Ha Noi Viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '216,225,234,237,210', '237', 3, 'date_time', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '69,64', '2 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(7, 'Pizza & Bakery Tour in Goheung', 'pizza-amp-bakery-tour-in-goheung', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586243623', 42.927007, -75.006874, 'New York, USA', '7.771567559128908', NULL, NULL, 'USA', 'New York', '267,263,255,258,250,237', '267', 5, 'date_time', 'instant', 300.00000, 300.00000, 0.00000, 0.00000, '66,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(8, 'Kebab from a true master', 'kebab-from-a-true-master', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586244429', 43.078397, -75.600657, 'New York, USA', '7.422115207877448', NULL, NULL, 'USA', 'New York', '210,216,206,218,225', '210', 5, 'date_time', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '66,64', '6 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(9, 'Cooking in a tuscan organic farm!', 'cooking-in-a-tuscan-organic-farm', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586244862', 48.884480, 2.297523, 'Paris, France', '10.30120509646626', NULL, NULL, 'France', 'Paris', '195,196,216,210,222', '195', 5, 'date_time', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '66,64', '4 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(10, 'Paella in the most beautiful garden', 'paella-in-the-most-beautiful-garden', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245179', 48.849841, 2.382201, 'Paris, France', '12.428130444425362', NULL, NULL, 'France', 'Paris', '197,196,216,222,225', '197', 5, 'date_time', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '66,64', '4 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '53', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(11, 'Active Adventure & Stay with Locals', 'active-adventure-amp-stay-with-locals', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245473', 20.995065, 105.862788, 'Ha Noi Viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '271,259,262,274,273', '271', 4, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(12, 'Backroads - beautiful Bali home stay', 'backroads-beautiful-bali-home-stay', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.010518, 105.856425, 'Ha Noi viet Nam', '12.449746708201321', NULL, NULL, 'Vietnam', 'Ha Noi', '276,273,262,212,211,207', '276', 3, 'just_date', 'instant', 250.00000, 250.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', '');
INSERT INTO `experience` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `booking_type`, `booking_form`, `base_price`, `adult_price`, `child_price`, `infant_price`, `languages`, `durations`, `inclusions`, `exclusions`, `itinerary`, `price_categories`, `price_primary`, `video`, `extra_services`, `meeting_points`, `amenities`, `experience_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `rating`, `is_featured`, `status`, `import_ical_url`, `post_type`, `tour_packages`, `use_external_link`, `text_external_link`) VALUES
(14, 'Rainbow Mountain Adventure', 'rainbow-mountain-adventure', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.010518, 105.856425, 'Ha Noi viet Nam', '12.449746708201321', NULL, NULL, 'Vietnam', 'Ha Noi', '185,180,179,181,188', '185', 3, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(15, 'Active Adventure & Stay with Locals', 'active-adventure-amp-stay-with-locals', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.005774, 105.859111, 'Ha Noi viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '108,113,101,95,105,114', '180', 3, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(16, '2 Nights PACKAGE All Inclusive', '2-nights-package-all-inclusive', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.010518, 105.856425, 'Ha Noi viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '108,113,101,95,105,114', '105', 3, 'just_date', 'instant', 350.00000, 350.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(17, 'Ghost Towns and Saloons', 'ghost-towns-and-saloons', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.017167, 105.809395, 'Ha Noi Viet Nam', '10', NULL, NULL, 'Vietnam', 'Ha Noi', '108,113,101,95,105,114', '114', 3, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(18, 'Snorkeling and hiking in West Sumatra', 'snorkeling-and-hiking-in-west-sumatra', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.030657, 105.793105, 'Ha Noi Viet Nam', '11.940448984600446', NULL, NULL, 'Vietnam', 'Ha Noi', '192,188,202,203,211,207', '192', 5, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:32:\"rekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(19, 'Taste local wines and stargaze in Baja', 'taste-local-wines-and-stargaze-in-baja', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.051764, 105.871156, 'Ha Noi Viet Nam', '9.996408310933422', NULL, NULL, 'Vietnam', 'Ha Noi', '192,188,202,203,211,207', '211', 4, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(20, '2-Day Mui Ne Beach Luxury Vacation', '2-day-mui-ne-beach-luxury-vacation', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.002850, 105.783903, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '235,240,208,214,232', '235', 4, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(21, 'Nepal trek to Annapurna Base Camp', 'nepal-trek-to-annapurna-base-camp', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.007555, 105.822470, 'Ha Noi Viet Nam', '10.999422771882607', NULL, NULL, 'Vietnam', 'Ha Noi', '235,240,208,214,232', '232', 4, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:32:\"rekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '54', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(22, 'Tea with Naughty Sheep', 'tea-with-naughty-sheep', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.003323, 105.834238, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '323,324,319,311,312', '232', 6, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(23, 'Horse Whispering with an Equine Therapist', 'horse-whispering-with-an-equine-therapist', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.013294, 105.856217, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '323,324,319,311,312', '324', 5, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(24, 'Discover the arctic foxes', 'discover-the-arctic-foxes', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 20.987785, 105.794374, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '323,324,319,311,312', '319', 4, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(25, 'SUP PUP Paddleboard Tour', 'sup-pup-paddleboard-tour', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 20.977046, 105.776007, 'Ha Noi viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '323,324,319,311,312', '312', 6, 'just_date', 'instant', 100.00000, 100.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', '');
INSERT INTO `experience` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `booking_type`, `booking_form`, `base_price`, `adult_price`, `child_price`, `infant_price`, `languages`, `durations`, `inclusions`, `exclusions`, `itinerary`, `price_categories`, `price_primary`, `video`, `extra_services`, `meeting_points`, `amenities`, `experience_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `rating`, `is_featured`, `status`, `import_ical_url`, `post_type`, `tour_packages`, `use_external_link`, `text_external_link`) VALUES
(26, 'Visit Chimpanzees in the Mountains', 's', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 20.977171, 105.814030, 'Ha Noi Viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '323,324,319,311,312', '311', 3, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(27, 'Meet over 200 rescued animals!', 'meet-over-200-rescued-animals', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.008138, 105.781157, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '322,316,318,313,314', '318', 6, 'just_date', 'instant', 250.00000, 250.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(28, 'Meet Our Big Cat Residents', 'meet-our-big-cat-residents', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.010653, 105.919205, 'Ha Noi Viet Nam', '10.268398452555498', NULL, NULL, 'Viet Nam', 'Ha Noi', '322,316,318,313,314', '316', 5, 'just_date', 'instant', 100.00000, 100.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(29, 'Vegan picnic with rescued animals', 'vegan-picnic-with-rescued-animals', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.008193, 105.838234, 'Ha Noi  Viet Nam', '11', NULL, NULL, 'Vietnam', 'Ha Noi', '322,316,318,313,314', '322', 5, 'just_date', 'instant', 300.00000, 300.00000, 0.00000, 0.00000, '66,64', '4 days 3 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(30, 'Play with Rescued Horses', 'play-with-rescued-horses', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.002850, 105.795404, 'Ha Noi viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '309,308,307,306,310', '313', 4, 'just_date', 'instant', 250.00000, 250.00000, 0.00000, 0.00000, '66,64', '2 days 1 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(31, 'Inside Look with Dolphins', 'inside-look-with-dolphins', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.048150, 105.809052, 'Ha Noi Viet Nam', '9', NULL, NULL, 'Vietnam', 'Ha Noi', '309,308,307,306,310', '309', 5, 'just_date', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '2 days 1 night', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '55', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(32, 'Write to heal', 'write-to-heal', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.009695, 105.842010, 'Ha Noi Viet Nam', '9', NULL, NULL, 'Vietnam', 'Ha Noi', '398,397,387,388,384,396', '384', 5, 'just_date', 'instant', 100.00000, 100.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(33, 'Pronunciation Workshop', 'pronunciation-workshop', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.031207, 105.871536, 'Ha Noi Viet Nam', '10', NULL, NULL, 'Vietnam', 'Ha Noi', '398,397,387,388,384,396', '396', 5, 'just_date', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(34, 'Happiness Charity Event', 'happiness-charity-event', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.024658, 105.887672, 'Ha Noi Viet Nam', '10', NULL, NULL, 'Vietnam', 'Ha Noi', '388,387,394,401,397,398', '397', 6, 'date_time', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '66,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(35, 'Formal Event Makeup', 'formal-event-makeup', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.031247, 105.873939, 'Ha Noi Viet Nam', '11', NULL, NULL, 'Vietnam', 'Ha Noi', '386,385,391,396,395,400', '395', 4, 'date_time', 'instant', 350.00000, 350.00000, 0.00000, 0.00000, '66,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'on', 'publish', NULL, 'experience', '', '', ''),
(36, 'Prom 2019', 'prom-2019', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.035663, 105.846988, 'Ha Noi Viet Nam', '12', NULL, NULL, 'Vietnam', 'Ha Noi', '385,399,398,397,401,394', '398', 30, 'just_date', 'instant', 50.00000, 50.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(37, 'BIG BANG world tour', 'big-bang-world-tour', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.042678, 105.865442, 'Ha Noi Viet Nam', '13', NULL, NULL, 'Vietnam', 'Ha Noi', '385,399,398,397,401,394', '401', 20, 'just_date', 'instant', 350.00000, 350.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(38, '[:en]43 rd Tokyo Motor show[:vi]43 rd Tokyo Motor show[:]', '43-rd-tokyo-motor-show', '[:en]<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>[:vi]<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>[:]', '[:en]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus[:vi]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus[:]', 1, '1586245766', 21.043702, 105.856129, 'Ha Noi Viet Nam', '14', NULL, NULL, 'Vietnam', 'Ha Noi', '395,396,391,384,383', '395', 200, 'date_time', 'instant', 200.00000, 200.00000, 0.00000, 0.00000, '66,64', '[:en]5 hours[:vi]5 hours[:]', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', '[:en]https://www.youtube.com/watch?v=3M0TmN2TsK4[:vi]https://www.youtube.com/watch?v=3M0TmN2TsK4[:]', 'a:4:{i:0;a:4:{s:4:\"name\";s:39:\"[:en]Food & drinks[:vi]Food & drinks[:]\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:0:\"\";}i:1;a:4:{s:4:\"name\";s:37:\"[:en]Hiking shoes[:vi]Hiking shoes[:]\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:2;a:4:{s:4:\"name\";s:21:\"[:en]Tent[:vi]Tent[:]\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:41:\"[:en]Backup charger[:vi]Backup charger[:]\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', '');
INSERT INTO `experience` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `booking_type`, `booking_form`, `base_price`, `adult_price`, `child_price`, `infant_price`, `languages`, `durations`, `inclusions`, `exclusions`, `itinerary`, `price_categories`, `price_primary`, `video`, `extra_services`, `meeting_points`, `amenities`, `experience_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `rating`, `is_featured`, `status`, `import_ical_url`, `post_type`, `tour_packages`, `use_external_link`, `text_external_link`) VALUES
(39, 'Milan Fashion Week 2020', 'milan-fashion-week-2020', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.041450, 105.850314, 'Ha Noi Viet Nam', '15', NULL, NULL, 'Vietnam', 'Ha Noi', '386,393,392,391,396,395', '393', 40, 'date_time', 'instant', 100.00000, 100.00000, 0.00000, 0.00000, '66,64', '3 days 2 nights', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(40, '[:en]Paris Fashion Week 2019[:vi]Paris Fashion Week 2019[:]', 'paris-fashion-week-2019', '[:en]<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>[:vi]<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>[:]', '[:en]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus[:vi]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus[:]', 1, '1586245766', 21.041085, 105.846870, 'Ha Noi Viet Nam', '16', NULL, NULL, 'Vietnam', 'Ha Noi', '392,387,397,401,398,399', '392', 50, 'just_date', 'instant', 350.00000, 350.00000, 0.00000, 0.00000, '66,64', '[:en]3 days 2 nights[:vi]3 days 2 nights[:]', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:39:\"[:en]Food & drinks[:vi]Food & drinks[:]\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:0:\"\";}i:1;a:4:{s:4:\"name\";s:37:\"[:en]Hiking shoes[:vi]Hiking shoes[:]\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:2;a:4:{s:4:\"name\";s:21:\"[:en]Tent[:vi]Tent[:]\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:41:\"[:en]Backup charger[:vi]Backup charger[:]\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', ''),
(41, 'Exchange your waste paper with plants', 'exchange-your-waste-paper-with-plants', '<p>You can be a Valdivian artisan chocolate maker!</p>\r\n<p>You will learn the process of melting, tempering, enrobing, and molding chocolate; all techniques that you can replicate at home in the future. We will prepare some chocolates and chocolate bars using the highest quality chocolates and local ingredients. Then, we will finish with a chocolate tasting, along with a coffee or beverage of your choice.</p>\r\n<p>Throughout the activity, I will tell you about the history of chocolate as well as important events, places, and life in Valdivia!</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus', 1, '1586245766', 21.041203, 105.845802, 'Ha Noi Viet Nam', '17', NULL, NULL, 'Vietnam', 'Ha Noi', '385,384,383,388,387,391', '385', 30, 'date_time', 'instant', 150.00000, 150.00000, 0.00000, 0.00000, '66,64', '5 hours', '74,70,73,71,75,72,76', '80,78,77,79', 'a:3:{i:0;a:4:{s:9:\"sub_title\";s:5:\"Day 1\";s:5:\"title\";s:39:\"Camp & dine 2000 meters above sea level\";s:11:\"description\";s:427:\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dapibus ultrices in iaculis nunc sed augue lacus. Quam nulla porttitor massa id neque aliquam. Ultrices mi tempus imperdiet nulla malesuada. Eros in cursus turpis massa tincidunt dui ut ornare lectus. Egestas sed sed risus pretium. Lorem dolor sed viverra ipsum. Gravida rutrum quisque non tellus</p>\";s:5:\"image\";s:3:\"271\";}i:1;a:4:{s:9:\"sub_title\";s:5:\"Day 2\";s:5:\"title\";s:33:\"Trekking between Ancient Villages\";s:11:\"description\";s:413:\"<p>1) We recommend you wake up before sunrise to enjoy watching the stunning view of the sunrise.</p>\r\n<p>2) By 7:30 am, relax and enjoy locally prepared breakfast cooked at spot.</p>\r\n<p>3) By 9 am will head towards Al Sahrija ancient village where we start our trekking tour. Will pass through old stone/mud houses, terrace farms of roses, pomegranates, walnuts &amp; grapes farms with stunning cliff views.</p>\";s:5:\"image\";s:0:\"\";}i:2;a:4:{s:9:\"sub_title\";s:5:\"Day 3\";s:5:\"title\";s:38:\"Experience tradition & taste Oman food\";s:11:\"description\";s:326:\"<p>1) 7 am Will start the day with a local breakfast in the heritage hotel</p>\r\n<p>2) 9 am Visit Honey Exhibition. Learn about the local Omani bees and the different type of honey produced in Oman</p>\r\n<p>3) 10 am Trekking between old stone houses, farms, and the terraces. Beautiful landscape and interaction with locals.</p>\";s:5:\"image\";s:3:\"267\";}}', 'enable_adults', 'adult_price', 'https://www.youtube.com/watch?v=3M0TmN2TsK4', 'a:4:{i:0;a:4:{s:4:\"name\";s:13:\"Food & drinks\";s:11:\"name_unique\";s:17:\"food-_and_-drinks\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:12:\"Hiking shoes\";s:11:\"name_unique\";s:12:\"hiking-shoes\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:4:\"Tent\";s:11:\"name_unique\";s:4:\"tent\";s:5:\"price\";s:2:\"15\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:14:\"Backup charger\";s:11:\"name_unique\";s:14:\"backup-charger\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', NULL, NULL, '92', 'on', 1, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here.', 5.0, 'off', 'publish', NULL, 'experience', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `experience_availability`
--

CREATE TABLE `experience_availability` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `experience_id` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experience_price`
--

CREATE TABLE `experience_price` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `experience_id` bigint(20) NOT NULL,
  `start_time` bigint(20) NOT NULL,
  `start_date` bigint(20) NOT NULL,
  `end_time` bigint(20) NOT NULL,
  `end_date` bigint(20) NOT NULL,
  `max_people` int(11) NOT NULL,
  `adult_price` double(16,5) NOT NULL,
  `child_price` double(16,5) NOT NULL,
  `infant_price` double(16,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE `home` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` bigint(20) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_lat` double DEFAULT 48.856613,
  `location_lng` double DEFAULT 2.352222,
  `location_address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_zoom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '13',
  `location_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_postcode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_country` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_city` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_guest` int(11) DEFAULT NULL,
  `number_of_bedrooms` int(11) DEFAULT NULL,
  `number_of_bathrooms` int(11) DEFAULT NULL,
  `size` double(8,2) DEFAULT NULL,
  `min_stay` int(11) DEFAULT NULL,
  `max_stay` int(11) DEFAULT NULL,
  `booking_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_price` double(16,5) DEFAULT NULL,
  `weekend_price` double(16,5) DEFAULT NULL,
  `weekend_to_apply` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_services` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_cancellation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_before` int(11) DEFAULT NULL,
  `cancellation_detail` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkin_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double(8,1) DEFAULT NULL,
  `is_featured` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_form` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'instant',
  `import_ical_url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_7_day` double(15,6) NOT NULL DEFAULT 0.000000,
  `price_14_day` double(15,6) NOT NULL DEFAULT 0.000000,
  `price_30_day` double(15,6) NOT NULL DEFAULT 0.000000,
  `use_long_price` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `use_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `text_external_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `enable_extra_guest` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `extra_guest_price` double(15,6) NOT NULL DEFAULT 0.000000,
  `apply_to_guest` int(11) NOT NULL DEFAULT 1,
  `video` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `distance` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home`
--

INSERT INTO `home` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `number_of_bedrooms`, `number_of_bathrooms`, `size`, `min_stay`, `max_stay`, `booking_type`, `base_price`, `weekend_price`, `weekend_to_apply`, `extra_services`, `amenities`, `facilities`, `home_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `checkin_time`, `checkout_time`, `rating`, `is_featured`, `status`, `booking_form`, `import_ical_url`, `price_7_day`, `price_14_day`, `price_30_day`, `use_long_price`, `use_external_link`, `text_external_link`, `post_type`, `enable_extra_guest`, `extra_guest_price`, `apply_to_guest`, `video`, `distance`) VALUES
(1, 'Independent cottage, mountain view', 'independent-cottage-mountain-view', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578298658', 41.889061, -87.644465, 'Chicago, Illinois, United States of America', '13.168262671413748', NULL, NULL, 'United States of America', 'Chicago', '59,58,62,61,60,65', '237', 2, 1, 1, 75.00, 1, -1, 'per_night', 50.00000, NULL, 'sun', 'a:0:{}', '39,44,43,40,42,36,38,4', NULL, '25', 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class. However, if you do not cancel prior to the 48 hours, you will lose the payment for the class. The owner has the only right to be flexible here.', '11:30 AM', '10:30 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(2, 'Lussuoso. Vista incantevole.', 'lussuoso-vista-incantevole', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578400078', 41.79, -87.74, 'Chicago, United States of America', '12.791617024347332', NULL, NULL, 'United States of America', 'Chicago', '58,62,61,60,65', '58', 4, 3, 2, 110.00, 1, -1, 'per_night', 120.00000, NULL, 'sun', 'a:0:{}', '41,39,44,43,40,37,42,36,38,4', NULL, '22', 'off', NULL, NULL, '01:30 PM', '01:00 PM', 4.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(3, 'Hector Cave House', 'hector-cave-house', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578400590', 40.729364, -73.995956, '44 W 4th St, New York City, United States of America', '16', NULL, NULL, 'United States of America', 'New York', '60,65,64,63,68', '60', 2, 1, 1, 75.00, 1, -1, 'per_night', 60.00000, NULL, 'sun', 'a:0:{}', '41,39,43,40,37,42,36,38,4', NULL, '23', 'off', NULL, NULL, '09:30 AM', '09:00 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(4, 'Joshua Tree Homesteader Cabin', 'joshua-tree-homesteader-cabin', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578400733', 40.743687, -73.974175, '550 1st Ave, New York City United States of America', '16', NULL, NULL, 'United States of America', 'New York City', '64,63,68,67,66', '64', 4, 2, 1, 95.00, 1, -1, 'per_night', 100.00000, NULL, 'sun', 'a:0:{}', '41,40,37,42,36,38,4', NULL, '24', 'on', 2, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class. However, if you do not cancel prior to the 48 hours, you will lose the payment for the class. The owner has the only right to be flexible here.', '02:00 PM', '01:30 PM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(5, 'Luxurious stone villa in Crete', 'luxurious-stone-villa-in-crete', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578400915', 37.775762, -122.41881, 'San Fransico, United States of America', '12.505358490267144', NULL, NULL, 'United States of America', 'San Fransico', '67,66,72,71,70', '210', 1, 1, 1, 50.00, 1, -1, 'per_night', 80.00000, NULL, 'sun', 'a:0:{}', '41,43,40,37,36,38,4', NULL, '25', 'off', NULL, NULL, '07:00 PM', '06:30 PM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(6, 'Perfectly located Castro', 'perfectly-located-castro', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578401093', 37.734318, -122.471512, 'San Fransico Tower,, United States of America', '12.684931690872864', NULL, NULL, 'United States of America', 'San Fransico', '74,68,67,66', '74', 2, 1, 1, 75.00, 1, -1, 'per_night', 75.00000, NULL, 'sun', 'a:0:{}', '41,39,44,40,37,42', NULL, '26', 'off', NULL, NULL, '12:00 AM', '11:00 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(7, 'Bamboo eco cottage', 'bamboo-eco-cottage', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578401248', 36.974303, -120.0566, 'California, United States of America', '12.229707263520488', NULL, NULL, 'United States of America', 'California', '75,64,63,68,65', '68', 2, 1, 1, 75.00, 1, -1, 'per_night', 125.00000, NULL, 'sun', 'a:0:{}', '41,44,43,38', NULL, '22', 'off', NULL, NULL, '00:00 AM', '00:00 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(8, 'The best position in Hvar', 'the-best-position-in-hvar', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578401492', 36.786652, -119.771663, 'California City, California, United States of America', '11.851809526979102', NULL, NULL, 'United States of America', NULL, '72,71,70,69,68,67', '72', 4, 2, 1, 120.00, 1, -1, 'per_night', 90.00000, NULL, 'sun', 'a:0:{}', '44,40,37,38,4', NULL, '27', 'off', NULL, NULL, '01:30 PM', '02:00 PM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(9, 'Villa San Gennariello B&B', 'villa-san-gennariello-bb', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578402000', 36.1663, -115.1492, 'Las Vegas, Nevada, United States of America', '14.304351861567246', NULL, NULL, 'United States of America', 'Las Vegas', '67,68,63,64,65', '67', 2, 1, 1, 60.00, 1, -1, 'per_night', 75.00000, NULL, 'sun', 'a:0:{}', '39,40,37,42,38', NULL, '22', 'off', NULL, NULL, '09:00 AM', '08:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(10, 'Ocean View Malibu Hideaway', 'ocean-view-malibu-hideaway', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. r.', 1, '1578402293', 36.19495, -115.214793, 'Las Vegas, Nevada, United States of America', '11.581941944414277', NULL, NULL, 'United States of America', 'Las Vegas', '74,63,68,67,66', '345', 2, 1, 1, 75.00, 1, -1, 'per_night', 85.00000, NULL, 'sun', 'a:0:{}', '43,40,37,42,4', NULL, '27', 'off', NULL, NULL, '11:00 AM', '10:30 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(11, 'Twins Apartment in Center', 'twins-apartment-in-center', '<p><span class=\"_czm8crp\">Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport –the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578403092', 36.173826, -115.116132, 'Las Vegas, Nevada, United States of America', '12.478531993424209', NULL, NULL, 'United States of America', 'Las Vegas', '85,79,80,66,72', '85', 1, 1, 1, 90.00, 1, -1, 'per_night', 55.00000, NULL, 'sun', 'a:0:{}', '41,40,36,4', NULL, '28', 'off', NULL, NULL, '03:00 PM', '01:30 PM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(12, 'Charming SF 1911 Studio', 'charming-sf-1911-studio', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578403229', 40.723, -73.998805, 'New York, United States of America', '13.854006459247062', NULL, NULL, 'United States of America', 'New York', '67,93,79,85,69,77', '67', 2, 1, 1, 70.00, 1, -1, 'per_night', 135.00000, NULL, 'sun', 'a:0:{}', '41,44,40,37,42,36', NULL, '26', 'off', NULL, NULL, '10:00 AM', '09:00 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(13, 'Garden Suite Efficiency', 'garden-suite-efficiency', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578403371', 40.721383, -73.998848, 'New York, United States of America', '13', NULL, NULL, 'United States of America', 'New York', '83,73,78,68', '83', 3, 2, 1, 110.00, 1, -1, 'per_night', 115.00000, NULL, 'sun', 'a:0:{}', '41,39,44,43,40,37,42,36,38', NULL, '25', 'on', 5, 'Due to limited seating, we request that you cancel at least 48 hours before a scheduled class. This gives us the opportunity to fill the class. You may cancel by phone or online here. If you have to cancel your class, we offer you a credit to your account if you cancel before the 48 hours, but do not offer refunds. You may use these credits towards any future class. However, if you do not cancel prior to the 48 hours, you will lose the payment for the class. The owner has the only right to be flexible here.', '02:00 PM', '01:00 PM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(14, 'Ocean View Malibu Hideaway', 'new-home-1578573749', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578573749', 40.749304, -73.936825, 'New York, United States of America', '12.36220611022574', NULL, NULL, 'United States of America', 'New York', '120,119,123,122', '120', 4, 2, 2, 110.00, 1, -1, 'per_night', 120.00000, 10.00000, 'sat_sun', 'a:0:{}', '41,39,44,43,40,37,42,36,38,4', NULL, '22', 'off', NULL, NULL, '12:30 AM', '11:00 AM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(15, 'Romantic Luxury Escape Seminyak', 'new-home-1578573878', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1578573878', 40.724863, -73.892636, 'New York, United States of America', '12.108528217369493', NULL, NULL, 'United States of America', 'New York City', '119,123,122,120,121', '113', 2, 1, 1, 55.00, 1, -1, 'per_night', 40.00000, NULL, 'sun', 'a:0:{}', '41,39,40,42,36,38,4', NULL, '23', 'off', NULL, NULL, '12:30 AM', '12:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(16, 'Apartamento junto Gran Vía', 'new-home-1578574163', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578574163', 41.87511, -87.682987, 'Chicago,United States of America', '12.554162423327455', NULL, NULL, 'United States of America', 'Chicago', '121,126,122,123', '374', 2, 1, 1, 75.00, 1, -1, 'per_night', 50.00000, NULL, 'sun', 'a:0:{}', '41,37,42,36,38,4', NULL, '22', 'off', NULL, NULL, '11:30 AM', '11:00 AM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(17, 'Garden Suite Efficiency', 'new-home-1578574289', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578574289', 41.975337, -87.681032, 'Chicago, United States of America', '11.755527994639213', NULL, NULL, 'United States of America', 'Chicago', '125,126,120,123', '380', 2, 1, 1, 80.00, 1, -1, 'per_night', 90.00000, NULL, 'sun', 'a:0:{}', '41,39,43,40,37,42,36', NULL, '26', 'off', NULL, NULL, '05:00 PM', '04:30 PM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(18, '1885 Victorian Suit', 'new-home-1578574613', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578574613', 41.820229, -87.628054, 'Chicago, Illinois, United States of America', '11.552103865962389', NULL, NULL, 'United States of America', 'Chicago', '124,129,125,126', '192', 2, 1, 1, 55.00, 1, -1, 'per_night', 35.00000, NULL, 'sun', 'a:0:{}', '41,39,44,37,42,36,38,4', NULL, '27', 'off', NULL, NULL, '11:30 AM', '10:30 AM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(19, 'Cozy and charming cottage', 'new-home-1578574885', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578574885', 40.736016, -74.001168, 'New York, United States of America', '12.56690619292122', NULL, NULL, 'United States of America', 'New York', '128,129,120,126', '120', 1, 1, 1, 80.00, 1, -1, 'per_night', 75.00000, NULL, 'sun', 'a:0:{}', '41,44,40,37,42,36,38,4', NULL, '28', 'off', NULL, NULL, '01:00 PM', '12:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(20, 'Suite Tower Rialto', 'new-home-1578575550', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1578575550', 40.756996, -73.919628, 'New York, United States of America', '11.464340107197204', NULL, NULL, 'United States of America', 'New York City', '124,125,129,128,122', '128', 2, 1, 1, 60.00, 1, -1, 'per_night', 45.00000, NULL, 'sun', 'a:0:{}', '39,44,40,37,36', NULL, '24', 'off', NULL, NULL, '12:30 AM', '11:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(21, '[:en]Chiado Loft 7 with Patio[:vi]Chiado Loft 7 with Patio[:]', 'new-home-1578575660', '[:en]<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes.&nbsp;</span></p>[:vi]<p><span class=\"_czm8crp\">Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport –the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. </span></p>[:]', '[:en]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.[:vi]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.[:]', 1, '1578575660', 40.732586, -73.920384, 'New York, United States of America', '11.246520357848738', NULL, NULL, 'United States of America', 'New York City', '90,102,103,92,91', '90', 2, 1, 1, 30.00, 1, -1, 'per_night', 55.00000, NULL, 'sun', 'a:4:{i:0;a:4:{s:4:\"name\";s:4:\"Iron\";s:11:\"name_unique\";s:4:\"iron\";s:5:\"price\";s:1:\"5\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:10:\"Hair dryer\";s:11:\"name_unique\";s:10:\"hair-dryer\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:2;a:4:{s:4:\"name\";s:10:\"Mobile USB\";s:11:\"name_unique\";s:10:\"mobile-usb\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:3;a:4:{s:4:\"name\";s:10:\"Television\";s:11:\"name_unique\";s:10:\"television\";s:5:\"price\";s:1:\"5\";s:8:\"required\";s:0:\"\";}}', '41,39,40,37,42,36', NULL, '28', 'off', NULL, NULL, '11:30 AM', '11:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(25, 'Twins Apartment', 'new-home-1579158855', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\r\n<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\r\n<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1, '1579158855', 48.84133, 2.30029, '15th arrondissement of Paris, 75015, France', '12.381751490472599', NULL, '10000', 'France', '75015, Paris', '124,129,128,125', '128', 4, 2, 2, 125.00, 2, -1, 'per_night', 50.00000, 60.00000, 'sat_sun', 'a:3:{i:0;a:4:{s:4:\"name\";s:4:\"Iron\";s:11:\"name_unique\";s:4:\"iron\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:9:\"USB Cable\";s:11:\"name_unique\";s:9:\"usb-cable\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:2;a:4:{s:4:\"name\";s:5:\"Table\";s:11:\"name_unique\";s:5:\"table\";s:5:\"price\";s:1:\"3\";s:8:\"required\";s:0:\"\";}}', '41,39,43,40,37,36,4', NULL, '25', 'on', 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '01:00 PM', '12:30 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(26, 'BAWhome - Komorebi', 'bawhome-komorebi', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1579524174', 21.02889, 105.8525, 'Hoan Kiem, Hanoi, Vietnam', '14.181171556712725', 'Ha Noi', NULL, 'Vietnam', 'Ha Noi', '129,124,125', '115', 4, 2, 1, 60.00, 1, -1, 'per_night', 70.00000, NULL, 'sun', 'a:3:{i:0;a:4:{s:4:\"name\";s:9:\"Breakfast\";s:11:\"name_unique\";s:9:\"breakfast\";s:5:\"price\";s:2:\"10\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:9:\"USB Cable\";s:11:\"name_unique\";s:9:\"usb-cable\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:2;a:4:{s:4:\"name\";s:5:\"Table\";s:11:\"name_unique\";s:5:\"table\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', '41,48,39,44,43,40,37,42,38,4', NULL, '23', 'off', NULL, NULL, '11:30 AM', '11:00 AM', 5.0, 'on', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(27, 'Trần’s home-Lovely Balc', 'trans-home-lovely-balc', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1579524317', 21.02722, 105.83028, 'Quốc Tử Giám, Dong Da, Hanoi, Vietnam', '15.811280916972196', 'Ha Noi', NULL, 'Vietnam', 'Ha Noi', '124,125,126', '318', 2, 1, 1, 50.00, 1, -1, 'per_night', 40.00000, NULL, 'sun', 'a:0:{}', '39,44,43,42,36,4', NULL, '26', 'off', NULL, NULL, '09:30 AM', '08:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(28, 'Aimee #2', 'aimee-2', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1579524419', 21.03667, 105.83611, 'Ba Dinh, Hanoi, Vietnam', '12.677567974801612', 'Hanoi', NULL, 'Vietnam', 'Hanoi', '85,83,87,79', '85', 2, 1, 1, 50.00, 1, -1, 'per_night', 45.00000, NULL, 'sun', 'a:0:{}', '48,40,37,42,38,4', NULL, '25', 'off', NULL, NULL, '11:30 AM', '11:00 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', '');
INSERT INTO `home` (`post_id`, `post_title`, `post_slug`, `post_content`, `post_description`, `author`, `created_at`, `location_lat`, `location_lng`, `location_address`, `location_zoom`, `location_state`, `location_postcode`, `location_country`, `location_city`, `gallery`, `thumbnail_id`, `number_of_guest`, `number_of_bedrooms`, `number_of_bathrooms`, `size`, `min_stay`, `max_stay`, `booking_type`, `base_price`, `weekend_price`, `weekend_to_apply`, `extra_services`, `amenities`, `facilities`, `home_type`, `enable_cancellation`, `cancel_before`, `cancellation_detail`, `checkin_time`, `checkout_time`, `rating`, `is_featured`, `status`, `booking_form`, `import_ical_url`, `price_7_day`, `price_14_day`, `price_30_day`, `use_long_price`, `use_external_link`, `text_external_link`, `post_type`, `enable_extra_guest`, `extra_guest_price`, `apply_to_guest`, `video`, `distance`) VALUES
(29, 'The Autumn Homestay', 'the-autumn-homestay', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1579524497', 21.04, 105.83, 'Ngọc Hà, 118300, Ba Dinh, Hanoi, Vietnam', '15', 'Hanoi', NULL, 'Vietnam', 'Hanoi', '126,121,123,119', '93', 2, 1, 1, 45.00, 1, -1, 'per_night', 55.00000, NULL, 'sun', 'a:0:{}', '41,39,44,43,40,37,36,38,4', NULL, '28', 'off', NULL, NULL, '11:00 AM', '10:30 AM', 5.0, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(31, 'Bright & Airy in Highland Park', 'enbright-airy-in-highland-parkvinew-home-1582010499', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1582010499', 21.03667, 105.83611, '[:en]Ba Dinh, Ha Noi, Viet Nam[:vi]Ba Dinh, Ha Noi, Viet Nam[:]', '11.820426799394294', '[:en][:vi][:]', NULL, '[:en]Vietnam[:vi]Vietnam[:]', '[:en][:vi][:]', '58,71,62,61,70', '58', 1, 1, 1, 35.00, 1, -1, 'per_hour', 5.00000, NULL, 'sun', 'a:4:{i:0;a:4:{s:4:\"name\";s:21:\"[:en]Wifi[:vi]Wifi[:]\";s:11:\"name_unique\";s:4:\"wifi\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:2:\"on\";}i:1;a:4:{s:4:\"name\";s:21:\"[:en]Iron[:vi]Iron[:]\";s:11:\"name_unique\";s:4:\"iron\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:2;a:4:{s:4:\"name\";s:19:\"[:en]Bed[:vi]Bed[:]\";s:11:\"name_unique\";s:3:\"bed\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}i:3;a:4:{s:4:\"name\";s:21:\"[:en]Pets[:vi]Pets[:]\";s:11:\"name_unique\";s:4:\"pets\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', '41,48,44,43,37,42,38,4', NULL, '24', 'off', NULL, '[:en][:vi][:]', '06:00 AM', '11:30 PM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(32, 'Uptown Chic in Hoboken', 'enuptown-chic-in-hobokenvi', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.', 1, '1582012786', 21.0333, 105.8, '[:en]Cau Giay, Ha Noi, Vietnam[:vi][:]', '12.058695710524699', '[:en][:vi][:]', NULL, '[:en]Vietnam[:vi]Vietnam[:]', '[:en]Ha Noi[:vi][:]', '86,89,94,90,83', '388', 2, 1, 1, 35.00, 1, -1, 'per_hour', 3.00000, NULL, 'sun', 'a:0:{}', '41,43,40,36,38,4', NULL, '26', 'off', NULL, '[:en][:vi][:]', '01:00 AM', '11:30 PM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(33, 'Midtown Manhattan Hideaway', 'enmidtown-manhattan-hideaway', '<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>', 'Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO', 1, '1582013035', 41.88149861803839, -87.63829080286165, '[:en]Chicago, USA[:vi][:]', '12.703647761847193', '[:en][:vi][:]', NULL, '[:en]United States of America[:vi]United States of', '[:en]Chicago[:vi][:]', '93,92,88,91', '93', 2, 1, 1, 45.00, 1, -1, 'per_hour', 6.00000, NULL, 'sun', 'a:0:{}', '48,43,40,37,36,38,4', NULL, '24', 'off', NULL, '[:en][:vi][:]', '01:00 AM', '11:00 PM', NULL, 'off', 'publish', 'instant', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(34, '[:en]Private room in apartment[:ar]Private room in apartment[:]', 'private-room-in-apartment', '[:en]<p>Long-term Pricing</p><p>- 7+ days: <strong>$90</strong>/night</p><p>- 14+ days:<strong> $80</strong>/night</p><p>- 30+ days: <strong>$50</strong>/night</p><p>Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes.<br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation.<br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together.<br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower.</p>[:ar]<p>Long-term Pricing</p><p>- 7+ days: <strong>$90</strong>/night</p><p>- 14+ days:<strong> $80</strong>/night</p><p>- 30+ days: <strong>$50</strong>/night</p><p>Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes.<br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation.<br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together.<br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower.</p>[:]', '[:en]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.[:ar]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO.[:]', 1, '1582013643', 41.846788632766, -87.624674504347, '[:en]Chicago, USA[:ar]Chicago, USA[:]', '11', '[:en][:ar][:]', NULL, '[:en]USA[:ar][:en]United States of America[:vi]United States of[:]', '[:en]Split[:ar][:]', '92,93,91,85', '388', 8, 8, 7, 30.00, 1, -1, 'per_night', 100.00000, NULL, 'sun', 'a:1:{i:0;a:4:{s:4:\"name\";s:4:\"Wifi\";s:11:\"name_unique\";s:4:\"wifi\";s:5:\"price\";s:1:\"2\";s:8:\"required\";s:0:\"\";}}', '4', '{\"BATHROM\":null,\"BEDROOM 1\":null,\"BEDROOM 2\":null,\"BEDROOM 3\":null,\"BEDROOM 4\":null,\"BEDROOM 5\":null,\"BEDROOM 6\":null,\"BEDROOM 7\":null,\"COOLING &amp; HEATING\":null,\"GARDEN\":null,\"Kitchen\":null,\"LAUNDRY\":null,\"LIVING ROOM # 2\":null,\"LIVING ROOM # 3\":null,\"LIVING ROOM\":null,\"OUTDOORS\":null,\"WELLNES\":null}', '28', 'off', NULL, '[:en][:vi][:]', '11:00 AM', '09:00 PM', NULL, 'off', 'publish', 'instant', NULL, 90.000000, 80.000000, 50.000000, 'on', '', '', 'home', 'on', 5.000000, 2, '', '{\"Airport\":null,\"ATM\":null,\"Cafe bar\":null,\"Doctor\":null,\"Marina\":null,\"Market\":null,\"Restaurant\":null,\"Sea\":null,\"Town center\":null}'),
(37, '[:en]Desirable Deal on The Park[:vi]Desirable Deal on The Park[:ja]Desirable Deal on The Park[:]', 'desirable-deal-on-the-park', '[:en]<p><span class=\"_czm8crp\">Son Marimon, this B&amp;B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport &ndash;the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>[:vi]<p><span class=\"_czm8crp\">Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport –the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>[:ja]<p><span class=\"_czm8crp\">Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO. It is five minutes away from Lloseta, a village that offers the travellers everything they need: gastronomy, theatre, concerts, shows, historical neighbourhood, library and public transport –the train will allow the travellers reach Palma, Ciutat de Mallorca, in 25 minutes and Inca, where one of the most important markets of the island take place every Thursday, in 5 minutes. <br /><br />Wonderfully secluded, and still well connected, this 40m2 refugee also counts with 3600m2 of private land rich in autochthonous vegetation. <br /><br />The main characteristic of the house is how little it impacts on the surroundings in order to preserve the nature. Simple designs, hand crafted furniture, clay tiles (typical Majorcan material that tells the stories lived there) and beams create a space where comfort and tradition go together. <br /><br />The inner space comprises a sleeping room area, a sitting room with fireplace, a kitchen with oven, a microwave and fridge and an independent bathroom with shower. </span></p>[:]', '[:en]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO[:vi]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO[:ja]Son Marimon, this B&B, is located in the municipality of Selva, at the feet of Serra de Tramuntana, recently declared World Heritage Site by UNESCO[:]', 1, '1582016678', 34.04712686803, -118.24238092594, '[:en]Los Angeles, California USA[:vi]Los Angeles, California USA[:ja]Los Angeles, California USA[:]', '12', '[:en][:vi][:ja][:]', NULL, '[:en]United States of America[:vi]United States of', '[:en]Los Angeles[:vi]Los Angeles[:ja]Los Angeles[:', '68,67,66,64', '68', 1, 1, 1, 35.00, 1, -1, 'per_hour', 5.00000, NULL, 'sun', 'a:0:{}', '41,48,43,40,37,42,36,38,4', NULL, '24', 'off', NULL, '[:en][:vi][:]', '01:00 AM', '02:30 PM', 5.0, 'off', 'publish', 'enquiry', NULL, 0.000000, 0.000000, 0.000000, 'off', '', '', 'home', 'off', 0.000000, 1, '', ''),
(38, '[:en]New Home - 1637066224[:ar]New Home - 1637066224[:]', 'new-home-1637066224', '[:en]<p>test</p>[:ar]<p>test</p>[:]', '[:en]test[:ar]test[:]', 2, '1637066224', 48.856613, 2.352222, '[:en]Chornovola Viacheslava 26/2 fl 49[:ar]Chornovola Viacheslava 26/2 fl 49[:]', '12', '[:en]Test[:ar]Kiev[:]', '02000', '[:en]Ukraine[:ar]Ukraine[:]', '[:en]Porec[:ar]Kyiv[:]', '57,60,59,58,56,55', '57', 10, 1, 1, NULL, 1, -1, 'per_night', 500.00000, NULL, 'sun', 'a:0:{}', '41,48,39,44,43,40,37,42,36', '{\"Bed room\":null,\"Test 1\":null,\"Test Field 1\":null,\"Test field 2\":null,\"Test field 3\":null}', '22', 'off', NULL, '[:en][:ar][:]', '12:00 AM', '12:00 AM', NULL, 'off', 'publish', 'instant_enquiry', 'a:0:{}', 0.000000, 0.000000, 0.000000, 'off', '#', '[:en]Check Out[:ar]Check Out[:]', 'home', 'off', 0.000000, 1, '', '{\"to ATM\":null,\"To beach\":null,\"To sea\":null}');

-- --------------------------------------------------------

--
-- Table structure for table `home_availability`
--

CREATE TABLE `home_availability` (
  `home_id` bigint(20) NOT NULL,
  `booking_id` bigint(20) NOT NULL,
  `start_time` bigint(20) NOT NULL,
  `end_time` bigint(20) NOT NULL,
  `start_date` bigint(20) NOT NULL,
  `end_date` bigint(20) NOT NULL,
  `total_minutes` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_price`
--

CREATE TABLE `home_price` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `home_id` bigint(20) NOT NULL,
  `start_time` bigint(20) NOT NULL,
  `end_time` bigint(20) NOT NULL,
  `price` double(16,5) NOT NULL,
  `available` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_night` double NOT NULL DEFAULT 0,
  `stay_min_date` int(11) NOT NULL DEFAULT 1,
  `discount_percent` int(11) NOT NULL DEFAULT 0,
  `first_minute` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `last_minute` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_price`
--

INSERT INTO `home_price` (`ID`, `home_id`, `start_time`, `end_time`, `price`, `available`, `price_per_night`, `stay_min_date`, `discount_percent`, `first_minute`, `last_minute`) VALUES
(15, 38, 1640995200, 1646006400, 0.00000, 'on', 500, 3, 0, 'off', 'off'),
(16, 38, 1646092800, 1651273200, 0.00000, 'on', 600, 4, 0, 'off', 'off'),
(17, 38, 1651359600, 1655247600, 0.00000, 'on', 800, 4, 0, 'off', 'off'),
(18, 38, 1655334000, 1656543600, 0.00000, 'on', 1000, 4, 0, 'off', 'off'),
(19, 38, 1656630000, 1659222000, 0.00000, 'on', 1500, 4, 0, 'off', 'off'),
(20, 38, 1642204800, 1643587200, 0.00000, 'on', 0, 1, 50, 'on', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(11) DEFAULT 0,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rtl` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `code`, `name`, `flag_code`, `flag_name`, `priority`, `status`, `created_at`, `updated_at`, `rtl`) VALUES
(1, 'ar', 'Arabia', 'sa', 'Saudi Arabia', 2, 'on', '2020-08-24 09:34:43', '2020-08-25 01:37:05', 'on'),
(2, 'en', 'English', 'gb', 'United Kingdom of Great Britain and Northern Ireland', 1, 'on', '2020-08-25 01:36:33', '2020-08-25 01:37:05', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` bigint(20) UNSIGNED NOT NULL,
  `media_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` bigint(20) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`media_id`, `media_title`, `media_name`, `media_url`, `media_path`, `media_description`, `media_size`, `media_type`, `author`, `created_at`) VALUES
(53, 'logo-sm-1578387303', 'logo-sm-1578387303', 'storage/u-1/2021/02/27/logo-sm-1578387303-1614442416.png', 'storage/app/public/u-1/2021/02/27/logo-sm-1578387303-1614442416.png', 'logo-sm-1578387303', '8699', 'png', 1, '1614442416'),
(55, '1-1', '1-1', 'storage/u-2/2021/11/16/1-1-1637065256.jpg', 'storage/app/public/u-2/2021/11/16/1-1-1637065256.jpg', '1-1', '130310', 'jpg', 2, '1637065256'),
(56, '1-2', '1-2', 'storage/u-2/2021/11/16/1-2-1637065475.jpg', 'storage/app/public/u-2/2021/11/16/1-2-1637065475.jpg', '1-2', '112376', 'jpg', 2, '1637065475'),
(57, '1-3', '1-3', 'storage/u-2/2021/11/16/1-3-1637065796.jpg', 'storage/app/public/u-2/2021/11/16/1-3-1637065796.jpg', '1-3', '395456', 'jpg', 2, '1637065796'),
(58, '1-4', '1-4', 'storage/u-2/2021/11/16/1-4-1637065839.jpg', 'storage/app/public/u-2/2021/11/16/1-4-1637065839.jpg', '1-4', '176210', 'jpg', 2, '1637065839'),
(59, '1-5', '1-5', 'storage/u-2/2021/11/16/1-5-1637065894.jpg', 'storage/app/public/u-2/2021/11/16/1-5-1637065894.jpg', '1-5', '125264', 'jpg', 2, '1637065894'),
(60, '1-6', '1-6', 'storage/u-2/2021/11/16/1-6-1637065938.jpg', 'storage/app/public/u-2/2021/11/16/1-6-1637065938.jpg', '1-6', '280386', 'jpg', 2, '1637065938');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `menu_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_title`, `menu_position`, `created_at`) VALUES
(1, 'Main menu', 'primary', '1577381924'),
(2, 'Footer 1', NULL, '1578043825'),
(3, 'Footer 2', NULL, '1578043880');

-- --------------------------------------------------------

--
-- Table structure for table `menu_structure`
--

CREATE TABLE `menu_structure` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `depth` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `left` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `right` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_lang` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `target_blank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_structure`
--

INSERT INTO `menu_structure` (`id`, `item_id`, `parent_id`, `depth`, `left`, `right`, `name`, `type`, `post_id`, `post_title`, `url`, `class`, `menu_id`, `created_at`, `menu_lang`, `target_blank`) VALUES
(364, NULL, NULL, '0', '1', '10', '', '', '', '', '', '', '3', '1582012110', 'vi', NULL),
(365, '1', NULL, '1', '2', '3', 'Chicago', 'custom', '0', 'Chicago', '#', '', '3', '1582012110', 'vi', NULL),
(366, '2', NULL, '1', '4', '5', 'New York', 'custom', '0', 'New York', '#', '', '3', '1582012110', 'vi', NULL),
(367, '3', NULL, '1', '6', '7', 'San Fancisco', 'custom', '0', 'San Fancisco', '#', '', '3', '1582012110', 'vi', NULL),
(368, '4', NULL, '1', '8', '9', 'California', 'custom', '0', 'California', '#', '', '3', '1582012110', 'vi', NULL),
(369, NULL, NULL, '0', '1', '10', '', '', '', '', '', '', '3', '1582012146', 'en', NULL),
(370, '1', NULL, '1', '2', '3', 'Chicago', 'custom', '0', 'Chicago', '#', '', '3', '1582012146', 'en', NULL),
(371, '2', NULL, '1', '4', '5', 'New York', 'custom', '0', 'New York', '#', '', '3', '1582012146', 'en', NULL),
(372, '3', NULL, '1', '6', '7', 'San Fancisco', 'custom', '0', 'San Fancisco', '#', '', '3', '1582012146', 'en', NULL),
(373, '4', NULL, '1', '8', '9', 'California', 'custom', '0', 'California', '#', '', '3', '1582012146', 'en', NULL),
(517, NULL, NULL, '0', '1', '10', '', '', '', '', '', '', '2', '1582041085', 'en', NULL),
(518, '1', NULL, '1', '2', '3', 'About us', 'custom', '0', 'About us', '#', '', '2', '1582041085', 'en', NULL),
(519, '2', NULL, '1', '4', '5', 'Become a host', 'custom', '0', 'Become a host', '#', '', '2', '1582041085', 'en', NULL),
(520, '3', NULL, '1', '6', '7', 'Blog', 'custom', '0', 'Blog', '#', '', '2', '1582041085', 'en', NULL),
(521, '4', NULL, '1', '8', '9', 'Contact Us', 'custom', '0', 'Contact Us', '#', '', '2', '1582041085', 'en', NULL),
(527, NULL, NULL, '0', '1', '10', '', '', '', '', '', '', '2', '1582041133', 'vi', NULL),
(528, '1', NULL, '1', '2', '3', 'Giới thiệu', 'custom', '0', 'Giới thiệu', '#', '', '2', '1582041133', 'vi', NULL),
(529, '2', NULL, '1', '4', '5', 'Trở thành Host', 'custom', '0', 'Trở thành Host', '#', '', '2', '1582041133', 'vi', NULL),
(530, '3', NULL, '1', '6', '7', 'Blog', 'custom', '0', 'Blog', '#', '', '2', '1582041133', 'vi', NULL),
(531, '4', NULL, '1', '8', '9', 'Liên hệ', 'custom', '0', 'Liên hệ', '#', '', '2', '1582041133', 'vi', NULL),
(816, NULL, NULL, '0', '1', '56', '', '', '', '', '', '', '1', '1589614490', 'en', 0),
(817, '1', NULL, '1', '2', '9', 'Home Page ', 'custom', '0', 'Home', 'http://123design.co/', '', '1', '1589614490', 'en', 0),
(818, '2', '1', '2', '3', '4', 'Home', 'custom', '0', 'Home Demo', 'http://123design.co/home', '', '1', '1589614490', 'en', 0),
(819, '3', '1', '2', '5', '6', 'Experience', 'custom', '0', 'Experience', 'http://123design.co/experience', '', '1', '1589614490', 'en', 0),
(820, '4', '1', '2', '7', '8', 'Car', 'custom', '0', 'Car', 'http://123design.co/car', '', '1', '1589614490', 'en', 0),
(821, '5', NULL, '1', '10', '21', 'Listing', 'custom', '0', 'Listing', '#', '', '1', '1589614490', 'en', 0),
(822, '6', '5', '2', '11', '12', 'Home - per Hour', 'custom', '0', 'Listing - per Hour', 'http://123design.co/home-search-result?bookingType=per_hour&checkInTime=2020-04-30&page=1&checkOutTime=2020-04-30&checkInOutTime=2020-04-30%2B12%3A00%2Bam-2020-04-30%2B11%3A59%2Bpm', '', '1', '1589614490', 'en', 0),
(823, '7', '5', '2', '13', '14', 'Home - per Night', 'custom', '0', 'Listing - per Night', 'http://123design.co/home-search-result?bookingType=per_night', '', '1', '1589614490', 'en', 0),
(824, '8', '5', '2', '15', '16', 'Experience', 'custom', '0', 'Experience Listings', 'http://123design.co/experience-search-result', '', '1', '1589614490', 'en', 0),
(825, '9', '5', '2', '17', '18', 'Car - Grid Layout', 'custom', '0', 'Car - Grid Layout', 'http://123design.co/car-search-result?layout=grid', '', '1', '1589614490', 'en', 0),
(826, '10', '5', '2', '19', '20', 'Car - List Layout', 'custom', '0', 'Car - List Layout', 'http://123design.co/car-search-result?layout=list', '', '1', '1589614490', 'en', 0),
(827, '11', NULL, '1', '22', '39', 'Single ', 'custom', '0', 'Single ', '#', '', '1', '1589614490', 'en', 0),
(828, '12', '11', '2', '23', '30', 'Home Single', 'custom', '0', 'Home Single', '#', '', '1', '1589614490', 'en', 0),
(829, '13', '12', '3', '24', '25', 'Hourly Booking', 'home', '31', 'Bright & Airy in Highland Park', 'http://123design.co/home/31/enbright-airy-in-highland-parkvinew-home-1582010499', '', '1', '1589614490', 'en', 0),
(830, '14', '12', '3', '26', '27', 'Nightly Booking', 'home', '21', 'Chiado Loft 7 with Patio', 'http://123design.co/home/21/new-home-1578575660', '', '1', '1589614490', 'en', 0),
(831, '15', '12', '3', '28', '29', 'Long-term Pricing', 'home', '34', 'Private room in apartment', 'http://123design.co/home/34/private-room-in-apartment', '', '1', '1589614490', 'en', 0),
(832, '16', '11', '2', '31', '36', 'Experience Single', 'custom', '0', 'Experience Single', '#', '', '1', '1589614490', 'en', 0),
(833, '17', '16', '3', '32', '33', 'Daily Booking', 'experience', '40', 'Paris Fashion Week 2019', 'http://123design.co/experience/40/paris-fashion-week-2019', '', '1', '1589614490', 'en', 0),
(834, '18', '16', '3', '34', '35', 'Hourly Booking', 'experience', '38', '43 rd Tokyo Motor show', 'http://123design.co/experience/38/43-rd-tokyo-motor-show', '', '1', '1589614490', 'en', 0),
(835, '19', '11', '2', '37', '38', 'Car', 'car', '6', 'Vinfast Lux A2.0', 'http://123design.co/car/6/vinfast-lux-a20', '', '1', '1589614490', 'en', 0),
(836, '20', NULL, '1', '40', '49', 'Pages', 'custom', '0', 'Pages', '#', '', '1', '1589614490', 'en', 0),
(837, '21', '20', '2', '41', '42', 'Blog', 'custom', '0', 'Blog', 'http://123design.co/blog', '', '1', '1589614490', 'en', 0),
(838, '22', '20', '2', '43', '44', 'Category', 'category', '29', 'Beauty beaches', 'http://123design.co/category/beauty-beaches', '', '1', '1589614490', 'en', 0),
(839, '23', '20', '2', '45', '46', 'Single Blog', 'post', '23', 'A Seaside Reset in Laguna Beach', 'http://123design.co/post/23/a-seaside-reset-in-laguna-beach', '', '1', '1589614490', 'en', 0),
(840, '24', '20', '2', '47', '48', 'Contact Us', 'custom', '0', 'Contact Us', 'http://123design.co/contact-us', '', '1', '1589614490', 'en', 0),
(841, '25', NULL, '1', '50', '55', 'Extra Pages', 'custom', '0', 'Extra Pages', 'http://123design.co/', '', '1', '1589614490', 'en', 0),
(842, '26', '25', '2', '51', '52', 'Coming Soon', 'custom', '0', 'Coming Soon', 'http://123design.co/coming-soon', '', '1', '1589614490', 'en', 0),
(843, '27', '25', '2', '53', '54', '404 Page', 'custom', '0', '404 Page', 'http://123design.co/not-found', '', '1', '1589614490', 'en', 0);

-- --------------------------------------------------------

--
-- Table structure for table `messenger`
--

CREATE TABLE `messenger` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `channel_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_channel`
--

CREATE TABLE `messenger_channel` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `channel_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_created` bigint(20) NOT NULL,
  `user_joined` bigint(20) NOT NULL,
  `last_user` bigint(20) NOT NULL,
  `last_time` int(11) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_channel_checking`
--

CREATE TABLE `messenger_channel_checking` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `channel_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `last_time_check` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_07_02_230147_migration_cartalyst_sentinel', 1),
(2, '2019_10_16_230147_migration_media', 1),
(3, '2019_10_22_230147_migration_options', 1),
(4, '2019_10_23_230149_migration_home', 1),
(5, '2019_10_24_230147_migration_taxonomy', 1),
(6, '2019_10_24_230147_migration_term', 1),
(7, '2019_10_24_230147_migration_term_1_2', 1),
(8, '2019_10_29_230147_migration_coupon', 1),
(9, '2019_10_30_230147_migration_page', 1),
(10, '2019_10_30_230147_migration_page_1_2_2', 1),
(11, '2019_10_30_230147_migration_session', 1),
(12, '2019_10_30_230147_migration_term_relationship', 1),
(13, '2019_11_01_230147_migration_post', 1),
(14, '2019_11_01_230147_migration_post_1_2_2', 1),
(15, '2019_11_04_230147_migration_price_home', 1),
(16, '2019_11_07_230147_migration_menu', 1),
(17, '2019_11_07_230149_migration_menu_structure', 1),
(18, '2019_11_07_230152_migration_menu_structure_1_1', 1),
(19, '2019_11_07_230152_migration_menu_structure_1_2', 1),
(20, '2019_11_11_230147_migration_home_booking', 1),
(21, '2019_11_19_230147_migration_availability_home', 1),
(22, '2019_11_20_230147_migration_earning', 1),
(23, '2019_11_21_230147_migration_notification', 1),
(24, '2019_11_27_230147_migration_usermeta', 1),
(25, '2019_11_27_230152_migration_comment', 1),
(26, '2020_02_02_230149_migration_home_1_1', 1),
(27, '2020_02_02_230149_migration_home_1_2', 1),
(28, '2020_02_02_230149_migration_user_1_1', 1),
(29, '2020_02_02_230151_migration_language', 1),
(30, '2020_02_19_230147_migration_home_booking_rename', 1),
(31, '2020_02_19_230147_migration_taxonomy_1_1', 1),
(32, '2020_02_19_230149_migration_experience', 1),
(33, '2020_02_26_230147_migration_car_price', 1),
(34, '2020_02_26_230147_migration_price_experience', 1),
(35, '2020_02_26_230150_migration_car', 1),
(36, '2020_02_26_230150_migration_car_1_2_2', 1),
(37, '2020_03_25_230150_migration_experience_availability', 1),
(38, '2020_04_15_230150_migration_payout', 1),
(39, '2020_04_16_230149_migration_experience_1_2_2', 1),
(40, '2020_04_16_230149_migration_home_1_2_2', 1),
(41, '2020_04_16_230149_migration_home_1_4', 1),
(42, '2020_04_22_230149_migration_experience_availability_1_2_2', 1),
(43, '2020_04_22_230149_migration_home_availability_1_2_2', 1),
(44, '2020_05_01_230149_migration_experience_1_2_3', 1),
(45, '2020_05_01_230149_migration_home_1_2_3', 1),
(46, '2020_05_01_230150_migration_car_1_2_3', 1),
(47, '2020_05_01_230150_migration_car_1_3_3', 1),
(48, '2020_05_01_230150_migration_car_1_4', 1),
(49, '2020_05_21_230149_migration_home_1_3', 1),
(50, '2020_06_01_230149_migration_home_1_3_1', 1),
(51, '2020_07_02_230149_migration_experience_1_3_2', 1),
(52, '2020_07_02_230149_migration_experience_1_4', 1),
(53, '2020_07_08_230149_migration_booking_1_3_3', 1),
(54, '2020_08_06_230149_migration_booking_1_4', 1),
(55, '2020_08_06_230149_migration_language_1_3_5', 1),
(56, '2020_08_28_230149_migration_messenger', 1),
(57, '2020_08_28_230149_migration_messenger_channel', 1),
(58, '2020_08_28_230149_migration_messenger_channel_checking', 1),
(59, '2021_02_20_230149_migration_user_1_4', 1),
(60, '2021_02_21_230147_migration_page_1_4', 1),
(61, '2021_02_21_230147_migration_seo_1_4', 1),
(62, '2021_02_21_230147_migration_seo_1_5_1', 1),
(63, '2021_02_22_230147_migration_post_1_4', 1),
(64, '2021_02_25_230147_migration_term_relationship_1_4', 1),
(65, '2021_03_17_230147_migration_term_relationship_1_4_3', 1),
(66, '2021_03_17_230147_migration_usermeta_1_4_3', 1),
(67, '2021_03_17_230149_migration_car_1_4_3', 1),
(68, '2021_03_17_230149_migration_experience_1_4_3', 1),
(69, '2021_03_17_230149_migration_home_1_4_3', 1),
(70, '2021_03_17_230149_migration_home_availability_1_4_3', 1),
(71, '2021_03_17_230150_migration_experience_availability_1_4_3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_from` bigint(20) NOT NULL,
  `user_to` bigint(20) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'hh_theme_options', 'a:147:{s:9:\"site_name\";s:39:\"[:en]Travel-agency[:ar]Travel-agency[:]\";s:16:\"site_description\";s:57:\"[:en]Awesome Booking System[:ar]Awesome Booking System[:]\";s:10:\"main_color\";s:7:\"#F1556C\";s:12:\"header_items\";s:6:\"a:0:{}\";s:15:\"unit_of_measure\";s:2:\"m2\";s:10:\"user_admin\";s:1:\"1\";s:13:\"enable_review\";s:2:\"on\";s:15:\"review_approval\";s:3:\"off\";s:15:\"top_destination\";s:363:\"a:3:{i:0;a:5:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"115\";s:7:\"service\";s:0:\"\";}i:1;a:5:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"116\";s:7:\"service\";s:0:\"\";}i:2;a:5:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"114\";s:7:\"service\";s:0:\"\";}}\";s:11:\"testimonial\";s:2002:\"a:4:{i:0;a:5:{s:11:\"author_name\";s:13:\"[:en][:ar][:]\";s:13:\"author_avatar\";s:3:\"109\";s:14:\"author_comment\";s:343:\"[:en]Needless to say we are extremely satisfied with the results. Home is awesome! Thanks guys, keep up the good work! I have gotten at least 50 times the value from home[:ar]Needless to say we are extremely satisfied with the results. Home is awesome! Thanks guys, keep up the good work! I have gotten at least 50 times the value from home[:]\";s:11:\"author_rate\";s:1:\"5\";s:4:\"date\";s:10:\"2020-01-26\";}i:1;a:5:{s:11:\"author_name\";s:13:\"[:en][:ar][:]\";s:13:\"author_avatar\";s:3:\"110\";s:14:\"author_comment\";s:345:\"[:en]I can\'t say enough about home. Home is the most valuable business resource we have ever purchased. Home is the most valuable business resource we have ever purchased.[:ar]I can\'t say enough about home. Home is the most valuable business resource we have ever purchased. Home is the most valuable business resource we have ever purchased.[:]\";s:11:\"author_rate\";s:1:\"5\";s:4:\"date\";s:10:\"2020-01-31\";}i:2;a:5:{s:11:\"author_name\";s:13:\"[:en][:ar][:]\";s:13:\"author_avatar\";s:3:\"111\";s:14:\"author_comment\";s:325:\"[:en]If you want real marketing that works and effective implementation - home\'s got you covered. Your company is truly upstanding and is behind its product 100%[:ar]If you want real marketing that works and effective implementation - home\'s got you covered. Your company is truly upstanding and is behind its product 100%[:]\";s:11:\"author_rate\";s:1:\"5\";s:4:\"date\";s:10:\"2020-01-30\";}i:3;a:5:{s:11:\"author_name\";s:13:\"[:en][:ar][:]\";s:13:\"author_avatar\";s:3:\"112\";s:14:\"author_comment\";s:311:\"[:en]Thanks to home, we\'ve just launched our 5th website! I am so pleased with this product. I couldn\'t have asked for more than this. We have no regrets![:ar]Thanks to home, we\'ve just launched our 5th website! I am so pleased with this product. I couldn\'t have asked for more than this. We have no regrets![:]\";s:11:\"author_rate\";s:1:\"5\";s:4:\"date\";s:10:\"2020-01-22\";}}\";s:13:\"checkout_page\";s:1:\"3\";s:22:\"redirect_checkout_page\";s:1:\"3\";s:19:\"term_condition_page\";s:1:\"4\";s:19:\"call_to_action_page\";s:1:\"4\";s:10:\"currencies\";s:234:\"a:1:{i:0;a:8:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:6:\"symbol\";s:0:\"\";s:4:\"unit\";s:3:\"EUR\";s:8:\"exchange\";s:0:\"\";s:8:\"position\";s:4:\"left\";s:18:\"thousand_separator\";s:0:\"\";s:17:\"decimal_separator\";s:0:\"\";s:16:\"currency_decimal\";s:1:\"2\";}}\";s:12:\"included_tax\";s:2:\"on\";s:3:\"tax\";s:2:\"10\";s:16:\"paypal_test_mode\";s:2:\"on\";s:14:\"type_encrytion\";s:3:\"ssl\";s:18:\"send_enquire_email\";s:22:\"admin_partner_customer\";s:11:\"payout_date\";s:2:\"26\";s:11:\"min_balance\";s:3:\"100\";s:16:\"partner_approval\";s:2:\"on\";s:11:\"list_social\";s:121:\"a:1:{i:0;a:3:{s:11:\"social_name\";s:13:\"[:en][:ar][:]\";s:11:\"social_icon\";s:12:\"001_facebook\";s:11:\"social_link\";s:0:\"\";}}\";s:12:\"footer_menu1\";s:1:\"2\";s:12:\"footer_menu2\";s:1:\"3\";s:10:\"mapbox_key\";s:90:\"pk.eyJ1Ijoib2h0ZWFtdm4iLCJhIjoiY2p6eTc0d3RlMGF2eDNucnU0dmQ0dTE3aiJ9.be2cd5WfYsffjhRxwV5gxQ\";s:11:\"home_slider\";s:11:\"52,56,55,54\";s:16:\"primary_currency\";s:3:\"EUR\";s:4:\"logo\";s:2:\"53\";s:18:\"footer_menu1_label\";s:23:\"[:en]About[:ar]About[:]\";s:18:\"footer_menu2_label\";s:33:\"[:en]Top Cities[:ar]Top Cities[:]\";s:22:\"footer_subscribe_label\";s:67:\"[:en]Subscribe to Our Newsletter[:ar]Subscribe to Our Newsletter[:]\";s:28:\"footer_subscribe_description\";s:195:\"[:en]A bi-weekly newsletter on the future of work, travel resources, and updates to our product![:ar]A bi-weekly newsletter on the future of work, travel resources, and updates to our product![:]\";s:10:\"copy_right\";s:75:\"[:en]Copyright © 2020 by AweBooking[:ar]Copyright © 2020 by AweBooking[:]\";s:13:\"featured_text\";s:29:\"[:en]Featured[:ar]Featured[:]\";s:14:\"facebook_login\";s:2:\"on\";s:12:\"facebook_api\";s:15:\"685947245135628\";s:15:\"facebook_secret\";s:32:\"351c801b6a63b6ba50c9b3ce9e393d01\";s:12:\"google_login\";s:2:\"on\";s:16:\"google_client_id\";s:72:\"724455793386-mept1m2oq4njhabppisrdi8dr7hng5p9.apps.googleusercontent.com\";s:20:\"google_client_secret\";s:24:\"sV11n1adirqndzdX83rSUaMX\";s:17:\"mailchimp_api_key\";s:3:\"111\";s:14:\"mailchimp_list\";s:3:\"222\";s:13:\"search_radius\";s:2:\"20\";s:14:\"dashboard_logo\";s:2:\"53\";s:20:\"dashboard_logo_short\";s:2:\"53\";s:7:\"favicon\";s:2:\"53\";s:10:\"blog_image\";s:2:\"57\";s:13:\"sidebar_image\";s:2:\"52\";s:7:\"use_ssl\";s:3:\"off\";s:15:\"contact_map_lat\";s:9:\"48.856613\";s:15:\"contact_map_lng\";s:8:\"2.352222\";s:14:\"contact_detail\";s:239:\"[:en]<p>Address: 123, ABC street, Paris, France</p><p>Phone: (+03) 123 456 789</p><p>Email: support@awebooking.org</p>[:ar]<p>Address: 123, ABC street, Paris, France</p><p>Phone: (+03) 123 456 789</p><p>Email: support@awebooking.org</p>[:]\";s:11:\"footer_logo\";s:2:\"49\";s:18:\"use_google_captcha\";s:3:\"off\";s:23:\"google_captcha_site_key\";s:40:\"6LehLcgUAAAAABfvsxwlijKd-wvBs9TadnMxyvCK\";s:25:\"google_captcha_secret_key\";s:40:\"6LehLcgUAAAAABST4xwlnqAAlxu8KwKbaZyxmUZF\";s:9:\"smtp_port\";s:3:\"465\";s:18:\"email_from_address\";s:15:\"admin@gmail.com\";s:10:\"email_from\";s:33:\"[:en]Awebooking[:ar]Awebooking[:]\";s:18:\"sidebar_image_link\";s:22:\"https://awebooking.org\";s:18:\"bank_transfer_logo\";s:3:\"147\";s:13:\"enable_paypal\";s:2:\"on\";s:11:\"paypal_logo\";s:3:\"145\";s:16:\"paypal_client_id\";s:80:\"AfjS5FFdBpUdnKbURZ9eJ4PBN4no7i0NFQ2PQJVL_IcxvcVlof1Rfjyu5UMngz_Rjvoxk4nmt4qTIx9w\";s:20:\"paypal_client_secret\";s:80:\"EHZaSyS92N-kV3UdiNL9vtA2LRpr3ymDiufqHjcGiCwEAbgyJvxkR7Edn6fyJNHw3p6KCAX-vUqIWWR6\";s:18:\"paypal_description\";s:95:\"[:en]Payments will be redirected to Paypal.com[:ar]Payments will be redirected to Paypal.com[:]\";s:13:\"enable_stripe\";s:2:\"on\";s:11:\"stripe_logo\";s:3:\"146\";s:22:\"stripe_publishable_key\";s:32:\"pk_test_z8WaU3Hj8N4tdohBe6B6Whhc\";s:17:\"stripe_secret_key\";s:32:\"sk_test_7BkhYi1U0SQ4U17vNYmbspn5\";s:18:\"stripe_description\";s:77:\"[:en]Payment method using credit card[:ar]Payment method using credit card[:]\";s:25:\"bank_transfer_description\";s:463:\"[:en]<strong>Payment via account number:</strong>\r\n\r\nCard Number: <strong>4221 2135 001 1324</strong>\r\nCard Name: <strong>AweBooking</strong>\r\nBank Name: <strong>Asia Commercial Bank</strong>\r\nSwift Code: <strong>ASCBVNVX</strong>[:ar]<strong>Payment via account number:</strong>\r\n\r\nCard Number: <strong>4221 2135 001 1324</strong>\r\nCard Name: <strong>AweBooking</strong>\r\nBank Name: <strong>Asia Commercial Bank</strong>\r\nSwift Code: <strong>ASCBVNVX</strong>[:]\";s:9:\"smtp_host\";s:14:\"smtp.gmail.com\";s:18:\"partner_commission\";s:2:\"10\";s:13:\"site_language\";s:2:\"en\";s:14:\"multi_language\";s:2:\"on\";s:11:\"google_font\";s:39:\"poppins;300,regular,500;latin,latin-ext\";s:11:\"enable_home\";s:2:\"on\";s:19:\"home_featured_image\";s:3:\"157\";s:18:\"home_search_radius\";s:2:\"25\";s:20:\"home_top_destination\";s:201:\"a:2:{i:0;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"116\";}i:1;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:2:\"62\";}}\";s:17:\"enable_experience\";s:2:\"on\";s:25:\"experience_featured_image\";s:3:\"159\";s:24:\"experience_search_radius\";s:2:\"25\";s:26:\"experience_top_destination\";s:202:\"a:2:{i:0;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"381\";}i:1;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"116\";}}\";s:10:\"enable_car\";s:2:\"on\";s:18:\"car_featured_image\";s:3:\"158\";s:17:\"car_search_radius\";s:2:\"25\";s:19:\"car_top_destination\";s:201:\"a:2:{i:0;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:2:\"62\";}i:1;a:4:{s:4:\"name\";s:13:\"[:en][:ar][:]\";s:3:\"lat\";s:0:\"\";s:3:\"lng\";s:0:\"\";s:5:\"image\";s:3:\"116\";}}\";s:15:\"google_font_key\";s:39:\"AIzaSyDPG7nZZoCpIP9wsi5S3AvavW4Jtvs1UxY\";s:8:\"timezone\";s:13:\"Europe/London\";s:13:\"enable_sticky\";s:3:\"off\";s:20:\"enable_bank_transfer\";s:2:\"on\";s:24:\"become_a_host_background\";s:3:\"422\";s:31:\"become_a_host_background_footer\";s:3:\"421\";s:22:\"become_a_host_features\";s:1548:\"a:3:{i:0;a:3:{s:5:\"title\";s:13:\"[:en][:ar][:]\";s:6:\"detail\";s:383:\"[:en]AweBooking connects people with places and things to experience around the world. This community is supported by homeowners who offer guests unique opportunities to travel like a local.[:ar]AweBooking connects people with places and things to experience around the world. This community is supported by homeowners who offer guests unique opportunities to travel like a local.[:]\";s:5:\"image\";s:3:\"425\";}i:1;a:3:{s:5:\"title\";s:13:\"[:en][:ar][:]\";s:6:\"detail\";s:423:\"[:en]No matter what type of house or room you share, we will help you to receive guests simply and securely. You have complete control over room availability, rental rates, house rules, and how guests interact.[:ar]No matter what type of house or room you share, we will help you to receive guests simply and securely. You have complete control over room availability, rental rates, house rules, and how guests interact.[:]\";s:5:\"image\";s:3:\"423\";}i:2;a:3:{s:5:\"title\";s:13:\"[:en][:ar][:]\";s:6:\"detail\";s:475:\"[:en]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.[:ar]Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.[:]\";s:5:\"image\";s:3:\"424\";}}\";s:24:\"home_call_to_action_page\";s:1:\"4\";s:30:\"experience_call_to_action_page\";s:1:\"4\";s:23:\"car_call_to_action_page\";s:1:\"4\";s:33:\"paypal_enable_currency_conversion\";s:3:\"off\";s:23:\"paypal_currency_convert\";s:3:\"USD\";s:11:\"payout_time\";s:5:\"15:00\";s:14:\"ical_time_type\";s:4:\"hour\";s:9:\"ical_hour\";s:1:\"1\";s:11:\"ical_minute\";s:2:\"30\";s:11:\"enable_gdpr\";s:2:\"on\";s:9:\"gdpr_page\";s:1:\"4\";s:16:\"car_booking_type\";s:3:\"day\";s:22:\"testimonial_background\";s:7:\"#DD556A\";s:20:\"review_after_booking\";s:3:\"off\";s:27:\"enable_partner_registration\";s:2:\"on\";s:13:\"optimize_site\";s:3:\"off\";s:21:\"enable_lazyload_image\";s:3:\"off\";s:13:\"right_to_left\";s:3:\"off\";s:15:\"enable_lazyload\";s:3:\"off\";s:7:\"car_tax\";s:2:\"10\";s:8:\"home_tax\";s:2:\"10\";s:14:\"experience_tax\";s:2:\"10\";s:16:\"included_car_tax\";s:2:\"on\";s:17:\"included_home_tax\";s:2:\"on\";s:23:\"included_experience_tax\";s:2:\"on\";s:20:\"create_user_checkout\";s:3:\"off\";s:25:\"enable_email_confirmation\";s:2:\"on\";s:16:\"account_approval\";s:3:\"off\";s:10:\"enable_seo\";s:3:\"off\";s:10:\"custom_css\";N;s:18:\"custom_header_code\";N;s:18:\"custom_footer_code\";N;s:16:\"sort_search_form\";s:201:\"a:2:{i:0;a:3:{s:2:\"id\";s:4:\"home\";s:16:\"only_search_form\";s:0:\"\";s:5:\"label\";s:13:\"[:en][:ar][:]\";}i:1;a:3:{s:2:\"id\";s:10:\"experience\";s:16:\"only_search_form\";s:0:\"\";s:5:\"label\";s:13:\"[:en][:ar][:]\";}}\";s:25:\"call_to_action_background\";N;s:16:\"coming_soon_date\";N;s:22:\"coming_soon_background\";N;s:30:\"home_call_to_action_background\";N;s:36:\"experience_call_to_action_background\";N;s:29:\"car_call_to_action_background\";N;s:29:\"alert_paypal_convert_currency\";s:0:\"\";s:13:\"smtp_username\";N;s:13:\"smtp_password\";N;s:11:\"email_alert\";s:0:\"\";s:10:\"email_logo\";N;s:12:\"alert_payout\";s:0:\"\";s:12:\"ical_heading\";s:0:\"\";s:10:\"ical_alert\";s:0:\"\";s:11:\"time_format\";s:5:\"h:i A\";s:11:\"date_format\";s:6:\"d.m.Y.\";}'),
(2, 'awebooking_version_1_1', 'updated'),
(3, 'awebooking_version_1_2', 'updated'),
(4, 'awebooking_version_1_2_1', 'updated'),
(5, 'awebooking_version_1_2_2', 'updated'),
(6, 'awebooking_version_1_2_3', 'updated'),
(7, 'awebooking_version_1_3', 'updated'),
(8, 'awebooking_version_1_3_1', 'updated'),
(9, 'awebooking_version_1_3_2', 'updated'),
(10, 'awebooking_version_1_3_3', 'updated'),
(11, 'awebooking_version_1_3_5', 'updated'),
(12, 'awebooking_version_1_4', 'updated'),
(13, 'awbooking_image_sizes', 'a:9:{i:0;s:7:\"130-130\";i:1;s:7:\"800-600\";i:2;s:7:\"450-320\";i:3;s:5:\"75-75\";i:4;s:7:\"650-550\";i:5;s:7:\"400-300\";i:6;s:7:\"550-270\";i:7;s:7:\"400-400\";i:8;s:5:\"70-70\";}'),
(14, 'messenger_refresh_time', '5'),
(15, 'awebooking_version_1_4_3', 'updated');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_template` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` int(11) NOT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`post_id`, `post_title`, `post_slug`, `post_content`, `thumbnail_id`, `page_template`, `status`, `created_at`, `author`, `post_type`) VALUES
(4, 'Terms & Conditions', 'terms-conditions', '<p>Help protect your website and its users with clear and fair website terms and conditions.</p>\r\n<p>These terms and conditions for a website set out key issues such as acceptable use, privacy, cookies, registration and passwords, intellectual property, links to other sites, termination and disclaimers of responsibility. Terms and conditions are used and necessary to protect a website owner from liability of a user relying on the information or the goods provided from the site then suffering a loss.</p>\r\n<p>Making your own terms and conditions for your website is hard, not impossible, to do. It can take a few hours to few days for a person with no legal background to make. But worry no more; we are here to help you out.</p>\r\n<p>All you need to do is fill up the blank spaces and then you will receive an email with your personalized terms and conditions.</p>', '57', 'default', 'publish', '1578392345', 0, 'page');

-- --------------------------------------------------------

--
-- Table structure for table `payout`
--

CREATE TABLE `payout` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `payout_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(15,6) NOT NULL DEFAULT 0.000000,
  `created` bigint(20) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `persistences`
--

CREATE TABLE `persistences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `persistences`
--

INSERT INTO `persistences` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(2, 2, 'XxPf4r2zxuJCOSU7NyB7F7IGOcXpQwnF', '2021-11-16 11:15:01', '2021-11-16 11:15:01'),
(3, 2, 'XhK3oXay8ZpFRlgf3iotJF9UqvyAl8Al', '2021-11-16 11:50:29', '2021-11-16 11:50:29'),
(4, 1, 'p822VPF7BzaPnkq3ci3BriPgoK1wqG7l', '2021-11-16 12:00:20', '2021-11-16 12:00:20'),
(5, 1, 'ClFmGSkXzYaWFqccUNDHcZVCcxx2mGrJ', '2021-11-16 12:53:22', '2021-11-16 12:53:22'),
(6, 1, 'CsoCpTwfmHNc2P7qhq6DZhd9vOxBDT4L', '2021-11-18 07:17:17', '2021-11-18 07:17:17'),
(7, 1, '1xiUfRGmV2f4Z420JlC82j74sI5IiscK', '2021-11-19 06:36:00', '2021-11-19 06:36:00'),
(8, 1, 'SZQMd1Oz5UI8nI6eEFDRszA3zIYOlaOa', '2021-11-20 07:28:44', '2021-11-20 07:28:44'),
(9, 1, 'etGRsSAHtIw5c3Q0MHzz0QLsCCrjIDQi', '2021-11-21 07:37:11', '2021-11-21 07:37:11'),
(10, 1, 'AI73xkCn64EJDZfSzJRCkWJwheM5G6c3', '2021-11-21 13:21:48', '2021-11-21 13:21:48'),
(11, 1, 'SmHIDtPXjphHgToiirPH0fXkcsbhseJ1', '2021-11-21 14:32:50', '2021-11-21 14:32:50'),
(12, 1, 'Ho9HClbBGxAGq270dpW9lkXysw5Ckusv', '2021-11-22 05:45:05', '2021-11-22 05:45:05'),
(13, 1, 'rwyX25zYtZO58Pu0uSmiw2f1poFOYgSD', '2021-11-22 10:59:33', '2021-11-22 10:59:33'),
(14, 1, 'jx4lQ4IsBpbvNgpsqpkofF3lbEoIoxVO', '2021-11-22 13:34:53', '2021-11-22 13:34:53'),
(15, 1, 'DsPPyVvVTZaEGwIJZUZDkq86BMfFwNxh', '2021-11-23 07:26:01', '2021-11-23 07:26:01'),
(16, 1, 'lx5w6mIjPPzZiytYr4vP4HO8Z4tDEzJZ', '2021-11-24 04:38:27', '2021-11-24 04:38:27'),
(18, 1, 'verAJnTpRUr5KezYXF2o9HQnL5ruev7K', '2021-11-24 06:20:14', '2021-11-24 06:20:14'),
(19, 1, 'VyqHnhcVHiYoIfWG17zB186e9QRHDLFx', '2021-11-24 06:53:10', '2021-11-24 06:53:10'),
(20, 1, '3hHWH59GoVKQmYz8Bm8GnwKBuSLwq9Iz', '2021-11-24 07:48:06', '2021-11-24 07:48:06'),
(21, 1, 'sNulau8onuZy1GUgHiiMD9yZEkxAXC2o', '2021-11-24 11:45:37', '2021-11-24 11:45:37'),
(22, 1, '0G1dHhHP2RSzW0gm8eh3MyEe3u1C2xhd', '2021-11-25 09:03:02', '2021-11-25 09:03:02'),
(23, 1, '7OR2uHMXnnaOjasPw2mAQ2g8hFB4bOAS', '2021-11-26 07:55:13', '2021-11-26 07:55:13'),
(27, 1, '7bNDw1E2Y075vdr4ojPoyi2t4HklE8fO', '2021-11-26 11:30:39', '2021-11-26 11:30:39'),
(28, 1, 'YZSShnhULgX0On2EsZQMU86dPhMTPrYz', '2021-11-27 18:12:15', '2021-11-27 18:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` int(11) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `post_title`, `post_slug`, `post_content`, `thumbnail_id`, `status`, `author`, `created_at`, `post_type`) VALUES
(16, '1914 translation by H. Rackham', '1914-translation-by-h-rackham', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>&nbsp;</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '56', 'publish', 1, '1578042978', 'post'),
(17, 'Where can I get some?', 'where-can-i-get-some', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>&nbsp;</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '95', 'publish', 1, '1578043091', 'post'),
(18, 'What Does Travel Mean to You?', 'what-does-travel-mean-to-you', '<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing! It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<h2>Where does it come from?</h2>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &ldquo;de Finibus Bonorum et Malorum&rdquo; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &ldquo;Lorem ipsum dolor sit amet..&rdquo;, comes from a line in section 1.10.32.</p>\r\n<p><!-- wp:paragraph --> <!-- /wp:paragraph --> <!-- wp:paragraph --> <!-- /wp:paragraph --> <!-- wp:heading --> <!-- /wp:heading --> <!-- wp:paragraph --> <!-- /wp:paragraph --> <!-- wp:paragraph --> <!-- /wp:paragraph --></p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &ldquo;de Finibus Bonorum et Malorum&rdquo; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '97', 'publish', 1, '1578043129', 'post'),
(19, 'My 12 Favorite Cities in the World', 'my-12-favorite-cities-in-the-world', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<h2>Where does it come from?</h2>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p><!-- wp:paragraph --> <!-- /wp:paragraph --> <!-- wp:heading --> <!-- /wp:heading --> <!-- wp:paragraph --> <!-- /wp:paragraph --> <!-- wp:paragraph --> <!-- /wp:paragraph --></p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '96', 'publish', 1, '1578043156', 'post'),
(20, 'A Seaside Reset in Laguna Beach', 'a-seaside-reset-in-laguna-beach', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>&nbsp;</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '101', 'publish', 1, '1578043193', 'post'),
(21, 'All Aboard the Rocky Mountaineer', 'all-aboard-the-rocky-mountaineer', '<p><span style=\"color: #191e23; font-family: \'Noto Serif\'; font-size: 16px; white-space: pre-wrap; background-color: #ffffff;\">Welcome to WordPress. This is your first post. Edit or delete it, then start writing! It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</span></p>', '100', 'publish', 1, '1578043221', 'post'),
(22, 'City Spotlight: Philadelphia', 'city-spotlight-philadelphia', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>&nbsp;</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '249', 'publish', 1, '1578043248', 'post'),
(23, 'A Seaside Reset in Laguna Beach', 'a-seaside-reset-in-laguna-beach', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>&nbsp;</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '395', 'publish', 1, '1578043277', 'post');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'Administrator', '{\"admin\":true}', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(2, 'partner', 'Partner', '{\"partner\":true}', '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(3, 'customer', 'Customer', '{\"customer\":true}', '2021-11-16 11:12:47', '2021-11-16 11:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(2, 2, '2021-11-26 08:23:13', '2021-11-26 08:23:13'),
(3, 2, '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(4, 2, '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(5, 2, '2021-11-16 11:12:47', '2021-11-16 11:12:47'),
(6, 3, '2021-11-16 11:12:47', '2021-11-16 11:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `seo`
--

CREATE TABLE `seo` (
  `seo_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_image` int(11) DEFAULT NULL,
  `facebook_title` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` int(11) DEFAULT NULL,
  `twitter_title` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy`
--

CREATE TABLE `taxonomy` (
  `taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `taxonomy_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxonomy_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxonomy_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxonomy`
--

INSERT INTO `taxonomy` (`taxonomy_id`, `taxonomy_title`, `taxonomy_name`, `taxonomy_description`, `taxonomy_icon`, `taxonomy_image`, `created_at`, `post_type`) VALUES
(1, 'Home Type', 'home-type', NULL, NULL, NULL, '1637064767', 'home'),
(2, 'Home Amenity', 'home-amenity', NULL, NULL, NULL, '1637064767', 'home'),
(3, 'Categories', 'post-category', NULL, NULL, NULL, '1637064767', 'post'),
(4, 'Tags', 'post-tag', NULL, NULL, NULL, '1637064767', 'post'),
(5, 'Languages', 'experience-languages', NULL, NULL, NULL, '1637064767', 'experience'),
(6, 'Inclusions', 'experience-inclusions', NULL, NULL, NULL, '1637064767', 'experience'),
(7, 'Exclusions', 'experience-exclusions', NULL, NULL, NULL, '1637064767', 'experience'),
(8, 'Experience Types', 'experience-type', NULL, NULL, NULL, '1637064767', 'experience'),
(9, 'Car Types', 'car-type', NULL, NULL, NULL, '1637064767', 'car'),
(10, 'Car Equipments', 'car-equipment', NULL, NULL, NULL, '1637064767', 'car'),
(11, 'Car Features', 'car-feature', NULL, NULL, NULL, '1637064767', 'car'),
(12, 'Home Facilities Fields', 'home-facilities', NULL, NULL, NULL, '1637064767', 'home'),
(13, 'Distance', 'home-distance', NULL, NULL, NULL, '1637064767', 'home'),
(14, 'Advanced Facilities', 'home-advanced', NULL, NULL, NULL, '1637064767', 'home');

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE `term` (
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `term_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_id` bigint(20) NOT NULL,
  `author` bigint(20) NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `term_select` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `term`
--

INSERT INTO `term` (`term_id`, `term_title`, `term_name`, `term_description`, `term_icon`, `term_image`, `taxonomy_id`, `author`, `created_at`, `term_price`, `term_select`) VALUES
(4, '[:en]Internet[:ar]Wifi[:]', 'internet', '[:en]Wifi Free[:ar]Wifi Free[:]', '001_wifi', NULL, 2, 0, '1577367452', '0', '[]'),
(29, 'Beauty beaches', 'beauty-beaches', NULL, '', '', 3, 1, '1578042629', '0', NULL),
(30, 'Cultural events', 'cultural-events', NULL, '', '', 3, 1, '1578042637', '0', NULL),
(31, 'Mountains', 'mountains', NULL, '', '', 3, 1, '1578042642', '0', NULL),
(32, 'Museums', 'museums', NULL, '', '', 3, 1, '1578042649', '0', NULL),
(33, 'Beauty', 'beauty', '', '', '', 4, 0, '1578042978', '0', NULL),
(34, 'Event', 'event', '', '', '', 4, 0, '1578042978', '0', NULL),
(35, 'Mountain', 'mountain', '', '', '', 4, 0, '1578042978', '0', NULL),
(45, 'Travel', 'travel', 'Travel Blog', '', '', 3, 1, '1579145691', '0', NULL),
(46, 'Travel', 'travel', '', '', '', 4, 0, '1579145733', '0', NULL),
(53, '[:en]Cooking[:ar]Cooking[:]', 'cooking', '[:en][:ar][:]', NULL, '155', 8, 1, '1586180766', '0', NULL),
(54, '[:en]Adventures[:ar]Adventures[:]', 'adventures', '[:en][:ar][:]', NULL, '156', 8, 1, '1586180775', '0', NULL),
(55, '[:en]Animals[:ar]Animals[:]', 'animals', '[:en][:ar][:]', NULL, '316', 8, 1, '1586180783', '0', NULL),
(58, 'Minivans', 'minivans', NULL, NULL, '416', 9, 1, '1586187989', '0', NULL),
(59, 'Sedan', 'sedan', NULL, NULL, '417', 9, 1, '1586188022', '0', NULL),
(60, 'SUVs', 'suvs', NULL, NULL, '418', 9, 1, '1586188034', '0', NULL),
(61, 'Coupes', 'coupes', NULL, NULL, '414', 9, 1, '1586188054', '0', NULL),
(62, 'Convertibles', 'convertibles', NULL, NULL, '415', 9, 1, '1586188068', '0', NULL),
(63, 'Wagons', 'wagons', NULL, NULL, '420', 9, 1, '1586188080', '0', NULL),
(64, '[:en]Vienamese[:vi][:]', 'vienamese', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237776', '0', NULL),
(65, '[:en]French[:vi][:]', 'french', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237784', '0', NULL),
(66, '[:en]English - US[:vi][:]', 'english-us', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237795', '0', NULL),
(67, '[:en]Portuguese[:vi][:]', 'portuguese', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237827', '0', NULL),
(68, '[:en]Spanish[:vi][:]', 'spanish', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237851', '0', NULL),
(69, '[:en]Chinese[:vi][:]', 'chinese', '[:en][:vi][:]', NULL, NULL, 5, 1, '1586237865', '0', NULL),
(70, '[:en]Entry or admission fee[:vi][:]', 'entry-or-admission-fee', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240611', '0', NULL),
(71, '[:en]Landing & facility fees[:vi][:]', 'landing-facility-fees', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240622', '0', NULL),
(72, '[:en]Parking fees[:vi][:]', 'parking-fees', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240654', '0', NULL),
(73, '[:en]Entry tax[:vi][:]', 'entry-tax', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240682', '0', NULL),
(74, '[:en]Departure tax[:vi][:]', 'departure-tax', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240692', '0', NULL),
(75, '[:en]National park entrance fee[:vi][:]', 'national-park-entrance-fee', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240702', '0', NULL),
(76, '[:en]Tip or gratuity[:vi][:]', 'tip-or-gratuity', '[:en][:vi][:]', NULL, NULL, 6, 1, '1586240718', '0', NULL),
(77, '[:en]Fuel surcharge[:vi][:]', 'fuel-surcharge', '[:en][:vi][:]', NULL, NULL, 7, 1, '1586240772', '0', NULL),
(78, '[:en]Food & drinks[:vi][:]', 'food-drinks', '[:en][:vi][:]', NULL, NULL, 7, 1, '1586240784', '0', NULL),
(79, '[:en]Wifi[:vi][:]', 'wifi', '[:en][:vi][:]', NULL, NULL, 7, 1, '1586240795', '0', NULL),
(80, '[:en]Bus fare[:vi][:]', 'bus-fare', '[:en][:vi][:]', NULL, NULL, 7, 1, '1586240800', '0', NULL),
(81, 'Airbag', 'airbag', NULL, '006_airbag', NULL, 11, 1, '1586243733', '0', NULL),
(82, 'FM Radio', 'fm-radio', NULL, '005_radio', NULL, 11, 1, '1586243743', '0', NULL),
(83, 'Power Windows', 'power-windows', NULL, '004_car_door', NULL, 11, 1, '1586243750', '0', NULL),
(84, 'Sensor', 'sensor', NULL, '003_car', NULL, 11, 1, '1586243757', '0', NULL),
(85, 'Speed Km', 'speed-km', NULL, '002_speedometer', NULL, 11, 1, '1586243769', '0', NULL),
(86, 'Steering Wheel', 'steering-wheel', NULL, '001_steering_wheel', NULL, 11, 1, '1586243776', '0', NULL),
(88, 'Ski Rack', 'ski-rack', NULL, '005_skiing', NULL, 10, 1, '1586243897', '10', NULL),
(89, 'Infant Child Seat', 'infant-child-seat', NULL, '004_baby_car_seat', NULL, 10, 1, '1586243909', '4', NULL),
(90, 'GPS Satellite', 'gps-satellite', NULL, '003_satellite', NULL, 10, 1, '1586243920', '10', NULL),
(92, '[:en]Event[:ar]Event[:]', 'event', '[:en][:ar][:]', NULL, '396', 8, 1, '1586343677', '0', NULL),
(93, 'Hand tool', 'hand-tool', NULL, '001_repair', NULL, 10, 1, '1586679023', '7', NULL),
(95, 'Wifi', 'wifi', NULL, '001_wifi', NULL, 10, 1, '1586724252', '5', NULL),
(112, '[:en]Air conditioning[:ar][:]', 'air-conditioning', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917453', '0', '[]'),
(113, '[:en]Swimming pool[:ar][:]', 'swimming-pool', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917472', '0', '[]'),
(114, '[:en]Heated pool[:ar][:]', 'heated-pool', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917478', '0', '[]'),
(115, '[:en]Wellness[:ar][:]', 'wellness', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917486', '0', '[]'),
(116, '[:en]Garden[:ar][:]', 'garden', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917491', '0', '[]'),
(117, '[:en]Pets allowed[:ar][:]', 'pets-allowed', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917502', '0', '[]'),
(120, '[:en]Near the sea[:ar][:]', 'near-the-sea', '[:en][:ar][:]', NULL, NULL, 2, 1, '1637917521', '0', '[]'),
(128, '[:en]OUTDOORS[:ar]OUTDOORS[:]', 'outdoors', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637917836', '0', '[\"Private pool\",\"Garden\",\"Quiet area\",\"Outdoor furniture\",\"Beachfront\",\"Sun deck\",\"Terrace\",\"Garden\",\"Outdoor shower\",\"BBQ\",\"Pizza oven\",\"Parking\",\"Private parking\",\"Fenced yard\",\"Children playground\",\"Tennis court\",\"Volleyball court\",\"Bowling alley\"]'),
(129, '[:en]COOLING & HEATING[:ar]COOLING & HEATING[:]', 'cooling-heating', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918101', '0', '[\"Complete cooling\\/heating solution\",\"AC\",\"Heating with A\\/C units\"]'),
(130, '[:en]LIVING ROOM[:ar]LIVING ROOM[:]', 'living-room', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918142', '0', '[\"SAT TV\",\"TV\",\"SAT\",\"AC\",\"Couch\",\"Sofa\",\"Pull of sofa\",\"Corner sofa\",\"Balcony\",\"Table and chairs\",\"Sofa bed\",\"Additional bed for 1 or 2 persons\",\"Exit to the terrace\",\"Laminate flooring\",\"Bed\",\"Fireplace\",\"Stereo system\",\"Armchairs\"]'),
(131, '[:en]LIVING ROOM # 2[:ar]LIVING ROOM # 2[:]', 'living-room-2', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918242', '0', '[\"SAT TV\",\"TV\",\"SAT\",\"AC\",\"Couch\",\"Sofa\",\"Pull of sofa\",\"Corner sofa\",\"Balcony\",\"Table and chairs\",\"Sofa bed\",\"Additional bed for 1 or 2 persons\",\"Exit to the terrace\",\"Laminate flooring\",\"Bed\",\"Fireplace\",\"Stereo system\",\"Armchairs\"]'),
(132, '[:en]LIVING ROOM # 3[:ar]LIVING ROOM # 3[:]', 'living-room-3', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918341', '0', '[\"SAT TV\",\"TV\",\"SAT\",\"AC\",\"Couch\",\"Sofa\",\"Pull of sofa\",\"Corner sofa\",\"Balcony\",\"Table and chairs\",\"Sofa bed\",\"Additional bed for 1 or 2 persons\",\"Exit to the terrace\",\"Laminate flooring\",\"Bed\",\"Fireplace\",\"Stereo system\",\"Armchairs\"]'),
(133, '[:en]Kitchen[:ar]Kitchen[:]', 'kitchen', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918458', '0', '[\"Fully equipped kitchen\",\"Coffee machine\",\"Oven\",\"Dishwasher\",\"Ice maker\",\"Blender\",\"Wine cooler\",\"Toaster\",\"Microwave\",\"Fridge with freezer\",\"Dishwasher\",\"BBQ gas charcoal\",\"Indoor dining area for: 4\",\"Indoor dining area for: 6\",\"Indoor dining area for: 8\",\"Indoor dining area for: 10\",\"Indoor dining area for: 12\",\"Indoor dining area for: 14\",\"Child\'s high chair: 1\",\"Child\'s high chair: 2\",\"Child\'s high chair: 3\",\"Child\'s high chair: 4\",\"Outdoor dining area for: 4\",\"Outdoor dining area for: 6\",\"Outdoor dining area for: 8\",\"Outdoor dining area for: 10\",\"Outdoor dining area for: 12\",\"Outdoor dining area for: 14\"]'),
(134, '[:en]BEDROOM 1[:ar]BEDROOM 1[:]', 'bedroom-1', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918595', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(135, '[:en]BEDROOM 2[:ar]BEDROOM 2[:]', 'bedroom-2', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918647', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(136, '[:en]BEDROOM 3[:ar][:]', 'bedroom-3', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918799', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(137, '[:en]BEDROOM 4[:ar][:]', 'bedroom-4', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918848', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(138, '[:en]BEDROOM 5[:ar][:]', 'bedroom-5', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918859', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(139, '[:en]BEDROOM 6[:ar][:]', 'bedroom-6', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918868', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(140, '[:en]BEDROOM 7[:ar][:]', 'bedroom-7', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918877', '0', '[\"King size bed\",\"En suit\",\"Terrace\",\"Sea view\",\"TV\",\"Air condition\",\"Baby cot\",\"Sofa bed\"]'),
(141, '[:en]BATHROM[:ar]BATHROM[:]', 'bathrom', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637918914', '0', '[\"Bathroom\",\"Toilet\",\"Shower\",\"Towels\",\"Bathtub\",\"Private bathroom\",\"Shower cabin\",\"shower enclosure\",\"Hairdryer\",\"Bath tub\",\"Bidet\",\"Sink\",\"Toilet\",\"1 bathroom\",\"2 bathrooms\",\"3 bathrooms\",\"4 bathrooms\",\"5 bathrooms\",\"6 bathrooms\",\"7 bathrooms\",\"8 bathrooms\"]'),
(142, '[:en]WELLNES[:ar]WELLNES[:]', 'wellnes', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637919001', '0', '[\"Pool\",\"Sun loungers\",\"Sun umbrellas\",\"Sauna\",\"Garden furniture\",\"Panoramic view\",\"Complete privacy\"]'),
(143, '[:en]GARDEN[:ar]GARDEN[:]', 'garden', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637919041', '0', '[\"Sun loungers\",\"Sun umbrellas\",\"Garden furniture\",\"Panoramic view\",\"Lounge set\",\"Dining set\",\"Grill\",\"Outside kitchen\"]'),
(144, '[:en]LAUNDRY[:ar]LAUNDRY[:]', 'laundry', '[:en][:ar][:]', NULL, NULL, 12, 1, '1637919079', '0', '[\"Washing machine\",\"Iron and board\"]'),
(145, '[:en]Sea[:ar][:]', 'sea', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919112', '0', '[]'),
(146, '[:en]Restaurant[:ar][:]', 'restaurant', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919117', '0', '[]'),
(147, '[:en]Town center[:ar][:]', 'town-center', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919124', '0', '[]'),
(148, '[:en]Cafe bar[:ar][:]', 'cafe-bar', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919130', '0', '[]'),
(149, '[:en]Doctor[:ar][:]', 'doctor', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919135', '0', '[]'),
(150, '[:en]Marina[:ar][:]', 'marina', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919140', '0', '[]'),
(151, '[:en]Market[:ar][:]', 'market', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919146', '0', '[]'),
(152, '[:en]Airport[:ar][:]', 'airport', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919151', '0', '[]'),
(153, '[:en]ATM[:ar][:]', 'atm', '[:en][:ar][:]', NULL, NULL, 13, 1, '1637919157', '0', '[]'),
(154, '[:en]Frontline and near the Beach Villas[:ar][:]', 'frontline-and-near-the-beach-villas', '[:en][:ar][:]', NULL, NULL, 1, 1, '1637942460', '0', '[]'),
(155, '[:en]Family Villas for Rent[:ar][:]', 'family-villas-for-rent', '[:en][:ar][:]', NULL, NULL, 1, 1, '1637942471', '0', '[]'),
(156, '[:en]Villas with extra services[:ar][:]', 'villas-with-extra-services', '[:en][:ar][:]', NULL, NULL, 1, 1, '1637942479', '0', '[]'),
(157, '[:en]Countryside Villas[:ar][:]', 'countryside-villas', '[:en][:ar][:]', NULL, NULL, 1, 1, '1637942486', '0', '[]'),
(158, '[:en]Small Villas for Rent[:ar][:]', 'small-villas-for-rent', '[:en][:ar][:]', NULL, NULL, 1, 1, '1637942494', '0', '[]'),
(159, '[:en]Theme[:ar][:]', 'theme', '[:en][:ar][:]', NULL, NULL, 14, 1, '1638040384', '0', '{\"BEDROOM 1\":[\"TV\"],\"BEDROOM 4\":[\"Terrace\"],\"BEDROOM 7\":[\"Sea view\"]}');

-- --------------------------------------------------------

--
-- Table structure for table `term_relation`
--

CREATE TABLE `term_relation` (
  `term_id` bigint(20) NOT NULL,
  `service_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `ID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `term_relation`
--

INSERT INTO `term_relation` (`term_id`, `service_id`, `post_type`, `ID`) VALUES
(22, '22', 'post', 1),
(29, '21', 'post', 12),
(74, '7', 'experience', 17),
(70, '7', 'experience', 18),
(73, '7', 'experience', 19),
(71, '7', 'experience', 20),
(75, '7', 'experience', 21),
(72, '7', 'experience', 22),
(76, '7', 'experience', 23),
(80, '7', 'experience', 24),
(78, '7', 'experience', 25),
(77, '7', 'experience', 26),
(79, '7', 'experience', 27),
(74, '8', 'experience', 31),
(70, '8', 'experience', 32),
(73, '8', 'experience', 33),
(71, '8', 'experience', 34),
(75, '8', 'experience', 35),
(72, '8', 'experience', 36),
(76, '8', 'experience', 37),
(80, '8', 'experience', 38),
(78, '8', 'experience', 39),
(77, '8', 'experience', 40),
(79, '8', 'experience', 41),
(74, '1', 'experience', 44),
(70, '1', 'experience', 45),
(73, '1', 'experience', 46),
(71, '1', 'experience', 47),
(75, '1', 'experience', 48),
(72, '1', 'experience', 49),
(76, '1', 'experience', 50),
(80, '1', 'experience', 51),
(78, '1', 'experience', 52),
(77, '1', 'experience', 53),
(79, '1', 'experience', 54),
(74, '9', 'experience', 57),
(70, '9', 'experience', 58),
(73, '9', 'experience', 59),
(71, '9', 'experience', 60),
(75, '9', 'experience', 61),
(72, '9', 'experience', 62),
(76, '9', 'experience', 63),
(80, '9', 'experience', 64),
(78, '9', 'experience', 65),
(77, '9', 'experience', 66),
(79, '9', 'experience', 67),
(74, '10', 'experience', 70),
(70, '10', 'experience', 71),
(73, '10', 'experience', 72),
(71, '10', 'experience', 73),
(75, '10', 'experience', 74),
(72, '10', 'experience', 75),
(76, '10', 'experience', 76),
(80, '10', 'experience', 77),
(78, '10', 'experience', 78),
(77, '10', 'experience', 79),
(79, '10', 'experience', 80),
(74, '2', 'experience', 83),
(70, '2', 'experience', 84),
(73, '2', 'experience', 85),
(71, '2', 'experience', 86),
(75, '2', 'experience', 87),
(72, '2', 'experience', 88),
(76, '2', 'experience', 89),
(80, '2', 'experience', 90),
(78, '2', 'experience', 91),
(77, '2', 'experience', 92),
(79, '2', 'experience', 93),
(74, '11', 'experience', 96),
(70, '11', 'experience', 97),
(73, '11', 'experience', 98),
(71, '11', 'experience', 99),
(75, '11', 'experience', 100),
(72, '11', 'experience', 101),
(76, '11', 'experience', 102),
(80, '11', 'experience', 103),
(78, '11', 'experience', 104),
(77, '11', 'experience', 105),
(79, '11', 'experience', 106),
(74, '3', 'experience', 109),
(70, '3', 'experience', 110),
(73, '3', 'experience', 111),
(71, '3', 'experience', 112),
(75, '3', 'experience', 113),
(72, '3', 'experience', 114),
(80, '3', 'experience', 115),
(78, '3', 'experience', 116),
(77, '3', 'experience', 117),
(79, '3', 'experience', 118),
(74, '12', 'experience', 121),
(70, '12', 'experience', 122),
(73, '12', 'experience', 123),
(71, '12', 'experience', 124),
(75, '12', 'experience', 125),
(72, '12', 'experience', 126),
(76, '12', 'experience', 127),
(80, '12', 'experience', 128),
(78, '12', 'experience', 129),
(77, '12', 'experience', 130),
(79, '12', 'experience', 131),
(53, '2', 'experience', 132),
(74, '4', 'experience', 135),
(70, '4', 'experience', 136),
(73, '4', 'experience', 137),
(71, '4', 'experience', 138),
(75, '4', 'experience', 139),
(72, '4', 'experience', 140),
(76, '4', 'experience', 141),
(80, '4', 'experience', 142),
(78, '4', 'experience', 143),
(77, '4', 'experience', 144),
(79, '4', 'experience', 145),
(53, '3', 'experience', 146),
(74, '5', 'experience', 149),
(70, '5', 'experience', 150),
(73, '5', 'experience', 151),
(71, '5', 'experience', 152),
(75, '5', 'experience', 153),
(72, '5', 'experience', 154),
(76, '5', 'experience', 155),
(80, '5', 'experience', 156),
(78, '5', 'experience', 157),
(77, '5', 'experience', 158),
(79, '5', 'experience', 159),
(53, '6', 'experience', 160),
(74, '6', 'experience', 163),
(70, '6', 'experience', 164),
(73, '6', 'experience', 165),
(71, '6', 'experience', 166),
(75, '6', 'experience', 167),
(72, '6', 'experience', 168),
(76, '6', 'experience', 169),
(80, '6', 'experience', 170),
(78, '6', 'experience', 171),
(77, '6', 'experience', 172),
(79, '6', 'experience', 173),
(53, '5', 'experience', 174),
(74, '14', 'experience', 177),
(70, '14', 'experience', 178),
(73, '14', 'experience', 179),
(71, '14', 'experience', 180),
(75, '14', 'experience', 181),
(72, '14', 'experience', 182),
(76, '14', 'experience', 183),
(80, '14', 'experience', 184),
(78, '14', 'experience', 185),
(77, '14', 'experience', 186),
(79, '14', 'experience', 187),
(74, '15', 'experience', 190),
(70, '15', 'experience', 191),
(73, '15', 'experience', 192),
(71, '15', 'experience', 193),
(75, '15', 'experience', 194),
(72, '15', 'experience', 195),
(76, '15', 'experience', 196),
(80, '15', 'experience', 197),
(78, '15', 'experience', 198),
(77, '15', 'experience', 199),
(79, '15', 'experience', 200),
(66, '16', 'post', 201),
(64, '16', 'post', 202),
(74, '16', 'experience', 203),
(70, '16', 'experience', 204),
(73, '16', 'experience', 205),
(71, '16', 'experience', 206),
(75, '16', 'experience', 207),
(72, '16', 'experience', 208),
(76, '16', 'experience', 209),
(80, '16', 'experience', 210),
(78, '16', 'experience', 211),
(77, '16', 'experience', 212),
(79, '16', 'experience', 213),
(66, '17', 'post', 214),
(64, '17', 'post', 215),
(74, '17', 'experience', 216),
(70, '17', 'experience', 217),
(73, '17', 'experience', 218),
(71, '17', 'experience', 219),
(75, '17', 'experience', 220),
(72, '17', 'experience', 221),
(76, '17', 'experience', 222),
(80, '17', 'experience', 223),
(78, '17', 'experience', 224),
(77, '17', 'experience', 225),
(79, '17', 'experience', 226),
(66, '18', 'post', 227),
(64, '18', 'post', 228),
(74, '18', 'experience', 229),
(70, '18', 'experience', 230),
(73, '18', 'experience', 231),
(71, '18', 'experience', 232),
(75, '18', 'experience', 233),
(72, '18', 'experience', 234),
(76, '18', 'experience', 235),
(80, '18', 'experience', 236),
(78, '18', 'experience', 237),
(77, '18', 'experience', 238),
(79, '18', 'experience', 239),
(66, '19', 'post', 240),
(64, '19', 'post', 241),
(74, '19', 'experience', 242),
(70, '19', 'experience', 243),
(73, '19', 'experience', 244),
(71, '19', 'experience', 245),
(75, '19', 'experience', 246),
(72, '19', 'experience', 247),
(76, '19', 'experience', 248),
(80, '19', 'experience', 249),
(78, '19', 'experience', 250),
(77, '19', 'experience', 251),
(79, '19', 'experience', 252),
(66, '20', 'post', 253),
(64, '20', 'post', 254),
(74, '20', 'experience', 255),
(70, '20', 'experience', 256),
(73, '20', 'experience', 257),
(71, '20', 'experience', 258),
(75, '20', 'experience', 259),
(72, '20', 'experience', 260),
(76, '20', 'experience', 261),
(80, '20', 'experience', 262),
(78, '20', 'experience', 263),
(77, '20', 'experience', 264),
(79, '20', 'experience', 265),
(66, '21', 'post', 266),
(64, '21', 'post', 267),
(74, '21', 'experience', 268),
(70, '21', 'experience', 269),
(73, '21', 'experience', 270),
(71, '21', 'experience', 271),
(75, '21', 'experience', 272),
(72, '21', 'experience', 273),
(76, '21', 'experience', 274),
(80, '21', 'experience', 275),
(78, '21', 'experience', 276),
(77, '21', 'experience', 277),
(79, '21', 'experience', 278),
(28, '22', 'post', 279),
(24, '22', 'post', 285),
(4, '22', 'post', 331),
(26, '22', 'post', 332),
(66, '22', 'post', 333),
(64, '22', 'post', 334),
(74, '22', 'experience', 335),
(70, '22', 'experience', 336),
(73, '22', 'experience', 337),
(71, '22', 'experience', 338),
(75, '22', 'experience', 339),
(72, '22', 'experience', 340),
(76, '22', 'experience', 341),
(80, '22', 'experience', 342),
(78, '22', 'experience', 343),
(77, '22', 'experience', 344),
(79, '22', 'experience', 345),
(22, '23', 'post', 346),
(28, '23', 'post', 347),
(24, '23', 'post', 353),
(41, '23', 'post', 390),
(39, '23', 'post', 391),
(44, '23', 'post', 392),
(43, '23', 'post', 393),
(40, '23', 'post', 394),
(37, '23', 'post', 395),
(42, '23', 'post', 396),
(36, '23', 'post', 397),
(38, '23', 'post', 398),
(4, '23', 'post', 399),
(26, '23', 'post', 400),
(66, '23', 'post', 401),
(64, '23', 'post', 402),
(74, '23', 'experience', 403),
(70, '23', 'experience', 404),
(73, '23', 'experience', 405),
(71, '23', 'experience', 406),
(75, '23', 'experience', 407),
(72, '23', 'experience', 408),
(76, '23', 'experience', 409),
(80, '23', 'experience', 410),
(78, '23', 'experience', 411),
(77, '23', 'experience', 412),
(79, '23', 'experience', 413),
(74, '24', 'experience', 477),
(70, '24', 'experience', 478),
(73, '24', 'experience', 479),
(71, '24', 'experience', 480),
(75, '24', 'experience', 481),
(72, '24', 'experience', 482),
(76, '24', 'experience', 483),
(80, '24', 'experience', 484),
(78, '24', 'experience', 485),
(77, '24', 'experience', 486),
(79, '24', 'experience', 487),
(74, '25', 'experience', 491),
(70, '25', 'experience', 492),
(73, '25', 'experience', 493),
(71, '25', 'experience', 494),
(75, '25', 'experience', 495),
(72, '25', 'experience', 496),
(76, '25', 'experience', 497),
(80, '25', 'experience', 498),
(78, '25', 'experience', 499),
(77, '25', 'experience', 500),
(79, '25', 'experience', 501),
(74, '26', 'experience', 505),
(70, '26', 'experience', 506),
(73, '26', 'experience', 507),
(71, '26', 'experience', 508),
(75, '26', 'experience', 509),
(72, '26', 'experience', 510),
(76, '26', 'experience', 511),
(80, '26', 'experience', 512),
(78, '26', 'experience', 513),
(77, '26', 'experience', 514),
(79, '26', 'experience', 515),
(74, '27', 'experience', 519),
(70, '27', 'experience', 520),
(73, '27', 'experience', 521),
(71, '27', 'experience', 522),
(75, '27', 'experience', 523),
(72, '27', 'experience', 524),
(76, '27', 'experience', 525),
(80, '27', 'experience', 526),
(78, '27', 'experience', 527),
(77, '27', 'experience', 528),
(79, '27', 'experience', 529),
(74, '28', 'experience', 533),
(70, '28', 'experience', 534),
(73, '28', 'experience', 535),
(71, '28', 'experience', 536),
(75, '28', 'experience', 537),
(72, '28', 'experience', 538),
(76, '28', 'experience', 539),
(80, '28', 'experience', 540),
(78, '28', 'experience', 541),
(77, '28', 'experience', 542),
(79, '28', 'experience', 543),
(74, '30', 'experience', 637),
(70, '30', 'experience', 638),
(73, '30', 'experience', 639),
(71, '30', 'experience', 640),
(75, '30', 'experience', 641),
(72, '30', 'experience', 642),
(76, '30', 'experience', 643),
(80, '30', 'experience', 644),
(78, '30', 'experience', 645),
(77, '30', 'experience', 646),
(79, '30', 'experience', 647),
(53, '1', 'experience', 651),
(74, '29', 'experience', 652),
(70, '29', 'experience', 653),
(73, '29', 'experience', 654),
(71, '29', 'experience', 655),
(75, '29', 'experience', 656),
(72, '29', 'experience', 657),
(76, '29', 'experience', 658),
(80, '29', 'experience', 659),
(78, '29', 'experience', 660),
(77, '29', 'experience', 661),
(79, '29', 'experience', 662),
(53, '10', 'experience', 663),
(53, '9', 'experience', 664),
(53, '8', 'experience', 665),
(53, '7', 'experience', 666),
(54, '11', 'experience', 667),
(74, '31', 'experience', 668),
(70, '31', 'experience', 669),
(73, '31', 'experience', 670),
(71, '31', 'experience', 671),
(75, '31', 'experience', 672),
(72, '31', 'experience', 673),
(76, '31', 'experience', 674),
(80, '31', 'experience', 675),
(78, '31', 'experience', 676),
(77, '31', 'experience', 677),
(79, '31', 'experience', 678),
(74, '32', 'experience', 682),
(70, '32', 'experience', 683),
(73, '32', 'experience', 684),
(71, '32', 'experience', 685),
(75, '32', 'experience', 686),
(72, '32', 'experience', 687),
(76, '32', 'experience', 688),
(80, '32', 'experience', 689),
(78, '32', 'experience', 690),
(77, '32', 'experience', 691),
(79, '32', 'experience', 692),
(55, '30', 'experience', 695),
(55, '29', 'experience', 696),
(55, '27', 'experience', 697),
(55, '26', 'experience', 698),
(54, '21', 'experience', 699),
(54, '18', 'experience', 700),
(54, '17', 'experience', 701),
(54, '16', 'experience', 702),
(54, '15', 'experience', 703),
(54, '14', 'experience', 704),
(54, '12', 'experience', 705),
(74, '33', 'experience', 707),
(70, '33', 'experience', 708),
(73, '33', 'experience', 709),
(71, '33', 'experience', 710),
(75, '33', 'experience', 711),
(72, '33', 'experience', 712),
(76, '33', 'experience', 713),
(80, '33', 'experience', 714),
(78, '33', 'experience', 715),
(77, '33', 'experience', 716),
(79, '33', 'experience', 717),
(92, '33', 'experience', 720),
(92, '32', 'experience', 721),
(55, '28', 'experience', 722),
(55, '25', 'experience', 723),
(55, '24', 'experience', 724),
(55, '23', 'experience', 725),
(55, '22', 'experience', 726),
(55, '31', 'experience', 727),
(54, '20', 'experience', 728),
(74, '34', 'experience', 730),
(70, '34', 'experience', 731),
(73, '34', 'experience', 732),
(71, '34', 'experience', 733),
(75, '34', 'experience', 734),
(72, '34', 'experience', 735),
(76, '34', 'experience', 736),
(80, '34', 'experience', 737),
(78, '34', 'experience', 738),
(77, '34', 'experience', 739),
(79, '34', 'experience', 740),
(74, '35', 'experience', 865),
(70, '35', 'experience', 866),
(73, '35', 'experience', 867),
(71, '35', 'experience', 868),
(75, '35', 'experience', 869),
(72, '35', 'experience', 870),
(76, '35', 'experience', 871),
(80, '35', 'experience', 872),
(78, '35', 'experience', 873),
(77, '35', 'experience', 874),
(79, '35', 'experience', 875),
(74, '36', 'experience', 1001),
(70, '36', 'experience', 1002),
(73, '36', 'experience', 1003),
(71, '36', 'experience', 1004),
(75, '36', 'experience', 1005),
(72, '36', 'experience', 1006),
(76, '36', 'experience', 1007),
(80, '36', 'experience', 1008),
(78, '36', 'experience', 1009),
(77, '36', 'experience', 1010),
(79, '36', 'experience', 1011),
(29, '23', 'post', 1014),
(29, '22', 'post', 1015),
(74, '37', 'experience', 1017),
(70, '37', 'experience', 1018),
(73, '37', 'experience', 1019),
(71, '37', 'experience', 1020),
(75, '37', 'experience', 1021),
(72, '37', 'experience', 1022),
(76, '37', 'experience', 1023),
(80, '37', 'experience', 1024),
(78, '37', 'experience', 1025),
(77, '37', 'experience', 1026),
(79, '37', 'experience', 1027),
(92, '37', 'experience', 1030),
(74, '38', 'experience', 1165),
(70, '38', 'experience', 1166),
(73, '38', 'experience', 1167),
(71, '38', 'experience', 1168),
(75, '38', 'experience', 1169),
(72, '38', 'experience', 1170),
(76, '38', 'experience', 1171),
(80, '38', 'experience', 1172),
(78, '38', 'experience', 1173),
(77, '38', 'experience', 1174),
(79, '38', 'experience', 1175),
(74, '39', 'experience', 1312),
(70, '39', 'experience', 1313),
(73, '39', 'experience', 1314),
(71, '39', 'experience', 1315),
(75, '39', 'experience', 1316),
(72, '39', 'experience', 1317),
(76, '39', 'experience', 1318),
(80, '39', 'experience', 1319),
(78, '39', 'experience', 1320),
(77, '39', 'experience', 1321),
(79, '39', 'experience', 1322),
(74, '40', 'experience', 1459),
(70, '40', 'experience', 1460),
(73, '40', 'experience', 1461),
(71, '40', 'experience', 1462),
(75, '40', 'experience', 1463),
(72, '40', 'experience', 1464),
(76, '40', 'experience', 1465),
(80, '40', 'experience', 1466),
(78, '40', 'experience', 1467),
(77, '40', 'experience', 1468),
(79, '40', 'experience', 1469),
(53, '4', 'experience', 1606),
(92, '39', 'experience', 1607),
(92, '34', 'experience', 1608),
(92, '35', 'experience', 1609),
(92, '36', 'experience', 1610),
(41, '34', 'home', 1611),
(48, '34', 'home', 1612),
(44, '34', 'home', 1613),
(40, '34', 'home', 1614),
(37, '34', 'home', 1615),
(42, '34', 'home', 1616),
(24, '33', 'home', 1618),
(48, '33', 'home', 1619),
(43, '33', 'home', 1620),
(40, '33', 'home', 1621),
(37, '33', 'home', 1622),
(36, '33', 'home', 1623),
(38, '33', 'home', 1624),
(4, '33', 'home', 1625),
(26, '32', 'home', 1626),
(41, '32', 'home', 1627),
(43, '32', 'home', 1628),
(40, '32', 'home', 1629),
(36, '32', 'home', 1630),
(38, '32', 'home', 1631),
(4, '32', 'home', 1632),
(24, '31', 'home', 1633),
(41, '31', 'home', 1634),
(48, '31', 'home', 1635),
(44, '31', 'home', 1636),
(43, '31', 'home', 1637),
(37, '31', 'home', 1638),
(42, '31', 'home', 1639),
(38, '31', 'home', 1640),
(4, '31', 'home', 1641),
(28, '29', 'home', 1642),
(41, '29', 'home', 1643),
(39, '29', 'home', 1644),
(44, '29', 'home', 1645),
(43, '29', 'home', 1646),
(40, '29', 'home', 1647),
(37, '29', 'home', 1648),
(36, '29', 'home', 1649),
(38, '29', 'home', 1650),
(4, '29', 'home', 1651),
(25, '28', 'home', 1652),
(48, '28', 'home', 1653),
(40, '28', 'home', 1654),
(37, '28', 'home', 1655),
(42, '28', 'home', 1656),
(38, '28', 'home', 1657),
(4, '28', 'home', 1658),
(26, '27', 'home', 1659),
(39, '27', 'home', 1660),
(44, '27', 'home', 1661),
(43, '27', 'home', 1662),
(42, '27', 'home', 1663),
(36, '27', 'home', 1664),
(4, '27', 'home', 1665),
(23, '26', 'home', 1666),
(41, '26', 'home', 1667),
(48, '26', 'home', 1668),
(39, '26', 'home', 1669),
(44, '26', 'home', 1670),
(43, '26', 'home', 1671),
(40, '26', 'home', 1672),
(37, '26', 'home', 1673),
(42, '26', 'home', 1674),
(38, '26', 'home', 1675),
(4, '26', 'home', 1676),
(25, '25', 'home', 1677),
(41, '25', 'home', 1678),
(39, '25', 'home', 1679),
(43, '25', 'home', 1680),
(40, '25', 'home', 1681),
(37, '25', 'home', 1682),
(36, '25', 'home', 1683),
(4, '25', 'home', 1684),
(41, '21', 'home', 1685),
(39, '21', 'home', 1686),
(40, '21', 'home', 1687),
(37, '21', 'home', 1688),
(42, '21', 'home', 1689),
(36, '21', 'home', 1690),
(39, '20', 'home', 1691),
(44, '20', 'home', 1692),
(40, '20', 'home', 1693),
(37, '20', 'home', 1694),
(36, '20', 'home', 1695),
(41, '19', 'home', 1696),
(44, '19', 'home', 1697),
(40, '19', 'home', 1698),
(37, '19', 'home', 1699),
(42, '19', 'home', 1700),
(36, '19', 'home', 1701),
(38, '19', 'home', 1702),
(4, '19', 'home', 1703),
(27, '18', 'home', 1704),
(41, '18', 'home', 1705),
(39, '18', 'home', 1706),
(44, '18', 'home', 1707),
(37, '18', 'home', 1708),
(42, '18', 'home', 1709),
(36, '18', 'home', 1710),
(38, '18', 'home', 1711),
(4, '18', 'home', 1712),
(26, '17', 'home', 1713),
(41, '17', 'home', 1714),
(39, '17', 'home', 1715),
(43, '17', 'home', 1716),
(40, '17', 'home', 1717),
(37, '17', 'home', 1718),
(42, '17', 'home', 1719),
(36, '17', 'home', 1720),
(22, '16', 'home', 1721),
(41, '16', 'home', 1722),
(37, '16', 'home', 1723),
(42, '16', 'home', 1724),
(36, '16', 'home', 1725),
(38, '16', 'home', 1726),
(4, '16', 'home', 1727),
(23, '15', 'home', 1728),
(41, '15', 'home', 1729),
(39, '15', 'home', 1730),
(40, '15', 'home', 1731),
(42, '15', 'home', 1732),
(36, '15', 'home', 1733),
(38, '15', 'home', 1734),
(4, '15', 'home', 1735),
(22, '14', 'home', 1736),
(41, '14', 'home', 1737),
(39, '14', 'home', 1738),
(44, '14', 'home', 1739),
(43, '14', 'home', 1740),
(40, '14', 'home', 1741),
(37, '14', 'home', 1742),
(42, '14', 'home', 1743),
(36, '14', 'home', 1744),
(38, '14', 'home', 1745),
(4, '14', 'home', 1746),
(25, '13', 'home', 1747),
(41, '13', 'home', 1748),
(39, '13', 'home', 1749),
(44, '13', 'home', 1750),
(43, '13', 'home', 1751),
(40, '13', 'home', 1752),
(37, '13', 'home', 1753),
(42, '13', 'home', 1754),
(36, '13', 'home', 1755),
(38, '13', 'home', 1756),
(26, '12', 'home', 1757),
(41, '12', 'home', 1758),
(44, '12', 'home', 1759),
(40, '12', 'home', 1760),
(37, '12', 'home', 1761),
(42, '12', 'home', 1762),
(36, '12', 'home', 1763),
(28, '11', 'home', 1764),
(41, '11', 'home', 1765),
(40, '11', 'home', 1766),
(36, '11', 'home', 1767),
(4, '11', 'home', 1768),
(27, '10', 'home', 1769),
(43, '10', 'home', 1770),
(40, '10', 'home', 1771),
(37, '10', 'home', 1772),
(42, '10', 'home', 1773),
(4, '10', 'home', 1774),
(22, '9', 'home', 1775),
(39, '9', 'home', 1776),
(40, '9', 'home', 1777),
(37, '9', 'home', 1778),
(42, '9', 'home', 1779),
(38, '9', 'home', 1780),
(27, '8', 'home', 1781),
(44, '8', 'home', 1782),
(40, '8', 'home', 1783),
(37, '8', 'home', 1784),
(38, '8', 'home', 1785),
(4, '8', 'home', 1786),
(22, '7', 'home', 1787),
(41, '7', 'home', 1788),
(44, '7', 'home', 1789),
(43, '7', 'home', 1790),
(38, '7', 'home', 1791),
(26, '6', 'home', 1792),
(41, '6', 'home', 1793),
(39, '6', 'home', 1794),
(44, '6', 'home', 1795),
(40, '6', 'home', 1796),
(37, '6', 'home', 1797),
(42, '6', 'home', 1798),
(25, '5', 'home', 1799),
(41, '5', 'home', 1800),
(43, '5', 'home', 1801),
(40, '5', 'home', 1802),
(37, '5', 'home', 1803),
(36, '5', 'home', 1804),
(38, '5', 'home', 1805),
(4, '5', 'home', 1806),
(24, '4', 'home', 1807),
(41, '4', 'home', 1808),
(40, '4', 'home', 1809),
(37, '4', 'home', 1810),
(42, '4', 'home', 1811),
(36, '4', 'home', 1812),
(38, '4', 'home', 1813),
(4, '4', 'home', 1814),
(23, '3', 'home', 1815),
(41, '3', 'home', 1816),
(39, '3', 'home', 1817),
(43, '3', 'home', 1818),
(40, '3', 'home', 1819),
(37, '3', 'home', 1820),
(42, '3', 'home', 1821),
(36, '3', 'home', 1822),
(38, '3', 'home', 1823),
(4, '3', 'home', 1824),
(22, '2', 'home', 1825),
(41, '2', 'home', 1826),
(39, '2', 'home', 1827),
(44, '2', 'home', 1828),
(43, '2', 'home', 1829),
(40, '2', 'home', 1830),
(37, '2', 'home', 1831),
(42, '2', 'home', 1832),
(36, '2', 'home', 1833),
(38, '2', 'home', 1834),
(4, '2', 'home', 1835),
(25, '1', 'home', 1836),
(39, '1', 'home', 1837),
(44, '1', 'home', 1838),
(43, '1', 'home', 1839),
(40, '1', 'home', 1840),
(42, '1', 'home', 1841),
(36, '1', 'home', 1842),
(38, '1', 'home', 1843),
(4, '1', 'home', 1844),
(81, '16', 'car', 2014),
(82, '16', 'car', 2015),
(83, '16', 'car', 2016),
(84, '16', 'car', 2017),
(85, '16', 'car', 2018),
(86, '16', 'car', 2019),
(90, '16', 'car', 2020),
(93, '16', 'car', 2021),
(89, '16', 'car', 2022),
(88, '16', 'car', 2023),
(81, '15', 'car', 2024),
(82, '15', 'car', 2025),
(83, '15', 'car', 2026),
(84, '15', 'car', 2027),
(85, '15', 'car', 2028),
(86, '15', 'car', 2029),
(90, '15', 'car', 2030),
(93, '15', 'car', 2031),
(89, '15', 'car', 2032),
(88, '15', 'car', 2033),
(81, '14', 'car', 2034),
(82, '14', 'car', 2035),
(83, '14', 'car', 2036),
(84, '14', 'car', 2037),
(85, '14', 'car', 2038),
(86, '14', 'car', 2039),
(90, '14', 'car', 2040),
(93, '14', 'car', 2041),
(89, '14', 'car', 2042),
(88, '14', 'car', 2043),
(81, '13', 'car', 2044),
(82, '13', 'car', 2045),
(83, '13', 'car', 2046),
(84, '13', 'car', 2047),
(85, '13', 'car', 2048),
(86, '13', 'car', 2049),
(90, '13', 'car', 2050),
(93, '13', 'car', 2051),
(89, '13', 'car', 2052),
(88, '13', 'car', 2053),
(81, '12', 'car', 2054),
(82, '12', 'car', 2055),
(83, '12', 'car', 2056),
(84, '12', 'car', 2057),
(85, '12', 'car', 2058),
(86, '12', 'car', 2059),
(90, '12', 'car', 2060),
(93, '12', 'car', 2061),
(89, '12', 'car', 2062),
(88, '12', 'car', 2063),
(81, '11', 'car', 2064),
(82, '11', 'car', 2065),
(83, '11', 'car', 2066),
(84, '11', 'car', 2067),
(85, '11', 'car', 2068),
(86, '11', 'car', 2069),
(90, '11', 'car', 2070),
(93, '11', 'car', 2071),
(89, '11', 'car', 2072),
(88, '11', 'car', 2073),
(81, '10', 'car', 2074),
(82, '10', 'car', 2075),
(83, '10', 'car', 2076),
(84, '10', 'car', 2077),
(85, '10', 'car', 2078),
(86, '10', 'car', 2079),
(90, '10', 'car', 2080),
(93, '10', 'car', 2081),
(89, '10', 'car', 2082),
(88, '10', 'car', 2083),
(81, '9', 'car', 2084),
(82, '9', 'car', 2085),
(83, '9', 'car', 2086),
(84, '9', 'car', 2087),
(85, '9', 'car', 2088),
(86, '9', 'car', 2089),
(90, '9', 'car', 2090),
(93, '9', 'car', 2091),
(89, '9', 'car', 2092),
(88, '9', 'car', 2093),
(81, '5', 'car', 2094),
(82, '5', 'car', 2095),
(83, '5', 'car', 2096),
(84, '5', 'car', 2097),
(85, '5', 'car', 2098),
(86, '5', 'car', 2099),
(90, '5', 'car', 2100),
(93, '5', 'car', 2101),
(89, '5', 'car', 2102),
(88, '5', 'car', 2103),
(81, '4', 'car', 2104),
(82, '4', 'car', 2105),
(83, '4', 'car', 2106),
(84, '4', 'car', 2107),
(85, '4', 'car', 2108),
(86, '4', 'car', 2109),
(90, '4', 'car', 2110),
(93, '4', 'car', 2111),
(89, '4', 'car', 2112),
(88, '4', 'car', 2113),
(81, '2', 'car', 2114),
(82, '2', 'car', 2115),
(83, '2', 'car', 2116),
(84, '2', 'car', 2117),
(85, '2', 'car', 2118),
(86, '2', 'car', 2119),
(90, '2', 'car', 2120),
(93, '2', 'car', 2121),
(89, '2', 'car', 2122),
(88, '2', 'car', 2123),
(81, '19', 'post', 2124),
(82, '19', 'post', 2125),
(83, '19', 'post', 2126),
(84, '19', 'post', 2127),
(85, '19', 'post', 2128),
(90, '19', 'post', 2129),
(93, '19', 'post', 2130),
(89, '19', 'post', 2131),
(88, '19', 'post', 2132),
(60, '19', 'post', 2133),
(41, '37', 'home', 2134),
(48, '37', 'home', 2135),
(43, '37', 'home', 2136),
(40, '37', 'home', 2137),
(37, '37', 'home', 2138),
(42, '37', 'home', 2139),
(36, '37', 'home', 2140),
(38, '37', 'home', 2141),
(4, '37', 'home', 2142),
(28, '19', 'home', 2144),
(81, '20', 'post', 2145),
(82, '20', 'post', 2146),
(84, '20', 'post', 2147),
(85, '20', 'post', 2148),
(86, '20', 'post', 2149),
(90, '20', 'post', 2150),
(93, '20', 'post', 2151),
(89, '20', 'post', 2152),
(88, '20', 'post', 2153),
(92, '41', 'experience', 2154),
(74, '41', 'experience', 2164),
(70, '41', 'experience', 2165),
(73, '41', 'experience', 2166),
(71, '41', 'experience', 2167),
(75, '41', 'experience', 2168),
(72, '41', 'experience', 2169),
(76, '41', 'experience', 2170),
(80, '41', 'experience', 2171),
(78, '41', 'experience', 2172),
(77, '41', 'experience', 2173),
(79, '41', 'experience', 2174),
(24, '20', 'home', 2176),
(60, '20', 'post', 2177),
(62, '21', 'post', 2178),
(25, '22', 'post', 2179),
(41, '22', 'post', 2180),
(39, '22', 'post', 2181),
(44, '22', 'post', 2182),
(43, '22', 'post', 2183),
(40, '22', 'post', 2184),
(37, '22', 'post', 2185),
(42, '22', 'post', 2186),
(36, '22', 'post', 2187),
(38, '22', 'post', 2188),
(63, '22', 'post', 2189),
(81, '22', 'post', 2190),
(82, '22', 'post', 2191),
(83, '22', 'post', 2192),
(84, '22', 'post', 2193),
(85, '22', 'post', 2194),
(86, '22', 'post', 2195),
(90, '22', 'post', 2196),
(93, '22', 'post', 2197),
(89, '22', 'post', 2198),
(88, '22', 'post', 2199),
(54, '19', 'experience', 2200),
(62, '23', 'post', 2201),
(81, '23', 'post', 2202),
(82, '23', 'post', 2203),
(83, '23', 'post', 2204),
(86, '23', 'post', 2205),
(90, '23', 'post', 2206),
(93, '23', 'post', 2207),
(89, '23', 'post', 2208),
(81, '17', 'car', 2209),
(82, '17', 'car', 2210),
(83, '17', 'car', 2211),
(84, '17', 'car', 2212),
(85, '17', 'car', 2213),
(86, '17', 'car', 2214),
(90, '17', 'car', 2215),
(93, '17', 'car', 2216),
(89, '17', 'car', 2217),
(88, '17', 'car', 2218),
(95, '17', 'car', 2219),
(28, '21', 'home', 2220),
(92, '40', 'experience', 2221),
(92, '38', 'experience', 2222),
(24, '37', 'home', 2223),
(81, '6', 'car', 2224),
(82, '6', 'car', 2225),
(83, '6', 'car', 2226),
(84, '6', 'car', 2227),
(85, '6', 'car', 2228),
(86, '6', 'car', 2229),
(90, '6', 'car', 2230),
(93, '6', 'car', 2231),
(89, '6', 'car', 2232),
(88, '6', 'car', 2233),
(95, '6', 'car', 2234),
(60, '5', 'car', 2235),
(58, '7', 'car', 2236),
(81, '7', 'car', 2237),
(82, '7', 'car', 2238),
(83, '7', 'car', 2239),
(84, '7', 'car', 2240),
(85, '7', 'car', 2241),
(86, '7', 'car', 2242),
(90, '7', 'car', 2243),
(93, '7', 'car', 2244),
(89, '7', 'car', 2245),
(88, '7', 'car', 2246),
(62, '17', 'car', 2247),
(59, '16', 'car', 2248),
(60, '15', 'car', 2249),
(61, '14', 'car', 2250),
(63, '13', 'car', 2251),
(58, '12', 'car', 2252),
(63, '11', 'car', 2253),
(61, '10', 'car', 2254),
(59, '9', 'car', 2255),
(60, '4', 'car', 2256),
(61, '2', 'car', 2257),
(59, '6', 'car', 2258),
(22, '38', 'home', 2392),
(41, '38', 'home', 2393),
(48, '38', 'home', 2394),
(39, '38', 'home', 2395),
(44, '38', 'home', 2396),
(43, '38', 'home', 2397),
(40, '38', 'home', 2398),
(37, '38', 'home', 2399),
(42, '38', 'home', 2400),
(36, '38', 'home', 2401),
(4, '34', 'home', 2403),
(28, '34', 'home', 2404);

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE `throttle` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usermeta`
--

CREATE TABLE `usermeta` (
  `user_id` bigint(20) NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usermeta`
--

INSERT INTO `usermeta` (`user_id`, `meta_key`, `meta_value`, `ID`) VALUES
(1, 'access_token', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2Mzk2NjY5ODgsImlzcyI6ImxvY2FsaG9zdCIsImlhdCI6MTYzNzA3NDk4OH0.644QekkXHXBKVG8ms_xjN4UVtNn5sX2Y1pcSaIUES5w', 1),
(1, 'last_check_notification', '1637736614', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` int(11) DEFAULT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `last_login`, `first_name`, `last_name`, `avatar`, `mobile`, `location`, `request`, `description`, `created_at`, `updated_at`, `address`, `video`) VALUES
(1, 'admin@admin.com', '$2y$10$h7PMLUJcJaCAakPpuSyyI.6J2nW70QJT4cqBGZKpEeGH6dQi58mN.', NULL, '2021-11-27 18:12:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-27 18:12:15', NULL, ''),
(2, 'partner@admin.com', '$2y$10$VuI0Ec8KPVFAg.o2s7oOG.5v9mr.sTbohJ6Npl2b4XoeiDhMODhiu', NULL, '2021-11-26 08:32:48', 'TEst', 'Test', NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-26 08:32:48', NULL, ''),
(3, 'partner1@admin.com', '$2y$10$9QRsSftAoOhjhPAdGO4NDeoAG4czIDdZax4rILlwq4NVgKDaOPUI.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-16 11:12:47', NULL, ''),
(4, 'partner2@admin.com', '$2y$10$l2.iV/jP6anNIO4.gqTBb.meQ/7INn0K3emWntFW/ii1a2cDL075a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-16 11:12:47', NULL, ''),
(5, 'partner3@admin.com', '$2y$10$YFzuuoAdCDmO1.NbgiNfMehUMiXG0lS4NBJ8fNGL7C8VqfEAlWNoe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-16 11:12:47', NULL, ''),
(6, 'customer@admin.com', '$2y$10$nJCpXkIrCgFO0LKiG2goiuKXDDpQWLDqrWXzKbQWYBLgAfNXYVqh2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-16 11:12:47', '2021-11-16 11:12:47', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activations`
--
ALTER TABLE `activations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `car_price`
--
ALTER TABLE `car_price`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `earning`
--
ALTER TABLE `earning`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `experience_availability`
--
ALTER TABLE `experience_availability`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `experience_price`
--
ALTER TABLE `experience_price`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `home_availability`
--
ALTER TABLE `home_availability`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `home_price`
--
ALTER TABLE `home_price`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `menu_structure`
--
ALTER TABLE `menu_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messenger`
--
ALTER TABLE `messenger`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `messenger_channel`
--
ALTER TABLE `messenger_channel`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `messenger_channel_checking`
--
ALTER TABLE `messenger_channel_checking`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `payout`
--
ALTER TABLE `payout`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `persistences`
--
ALTER TABLE `persistences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persistences_code_unique` (`code`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Indexes for table `seo`
--
ALTER TABLE `seo`
  ADD PRIMARY KEY (`seo_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `taxonomy`
--
ALTER TABLE `taxonomy`
  ADD PRIMARY KEY (`taxonomy_id`);

--
-- Indexes for table `term`
--
ALTER TABLE `term`
  ADD PRIMARY KEY (`term_id`) USING BTREE;

--
-- Indexes for table `term_relation`
--
ALTER TABLE `term_relation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `throttle`
--
ALTER TABLE `throttle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `throttle_user_id_index` (`user_id`);

--
-- Indexes for table `usermeta`
--
ALTER TABLE `usermeta`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activations`
--
ALTER TABLE `activations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `car_price`
--
ALTER TABLE `car_price`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `earning`
--
ALTER TABLE `earning`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `experience_availability`
--
ALTER TABLE `experience_availability`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experience_price`
--
ALTER TABLE `experience_price`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home`
--
ALTER TABLE `home`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `home_availability`
--
ALTER TABLE `home_availability`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_price`
--
ALTER TABLE `home_price`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_structure`
--
ALTER TABLE `menu_structure`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;

--
-- AUTO_INCREMENT for table `messenger`
--
ALTER TABLE `messenger`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messenger_channel`
--
ALTER TABLE `messenger_channel`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messenger_channel_checking`
--
ALTER TABLE `messenger_channel_checking`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payout`
--
ALTER TABLE `payout`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `persistences`
--
ALTER TABLE `persistences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seo`
--
ALTER TABLE `seo`
  MODIFY `seo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomy`
--
ALTER TABLE `taxonomy`
  MODIFY `taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `term`
--
ALTER TABLE `term`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `term_relation`
--
ALTER TABLE `term_relation`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2405;

--
-- AUTO_INCREMENT for table `throttle`
--
ALTER TABLE `throttle`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usermeta`
--
ALTER TABLE `usermeta`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
