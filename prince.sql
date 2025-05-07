CREATE DATABASE `prince`

USE `prince`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u214863458_prince`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `BRANCH_ID` INT(11) NOT NULL,
  `BRANCH_NAME` VARCHAR(50) NOT NULL,
  `LOCATION_ID` INT(11) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`BRANCH_ID`, `BRANCH_NAME`, `LOCATION_ID`) VALUES
(1, 'MJC Hardware Trading', 158),
(2, 'JMM Hardware Trading', 159);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CUST_ID` INT(11) NOT NULL,
  `FIRST_NAME` VARCHAR(50) DEFAULT NULL,
  `LAST_NAME` VARCHAR(50) DEFAULT NULL,
  `PHONE_NUMBER` VARCHAR(11) DEFAULT NULL,
  `BRANCH_ID` INT(11) DEFAULT NULL,
  `LOCATION_ID` INT(11) DEFAULT NULL
) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CUST_ID`, `FIRST_NAME`, `LAST_NAME`, `PHONE_NUMBER`, `BRANCH_ID`, `LOCATION_ID`) VALUES
(2, 'Walk-in', 'Customer', NULL, 1, 158),
(3, 'Walk-in', 'Customer', NULL, 2, 160),
(4, 'Kyle Dennis', 'Regacho', '09292729120', 1, 186);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EMPLOYEE_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `GENDER` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL,
  `JOB_ID` int(11) DEFAULT NULL,
  `HIRED_DATE` date DEFAULT NULL,
  `LOCATION_ID` int(11) DEFAULT NULL,
  `BRANCH_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EMPLOYEE_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER`, `EMAIL`, `PHONE_NUMBER`, `JOB_ID`, `HIRED_DATE`, `LOCATION_ID`, `BRANCH_ID`) VALUES
(1, 'Helen', 'Mejia', 'Female', 'helenmejia@gmail.com', '09091245761', 3, '2020-12-24', 158, 1),
(2, 'Kris', 'Mejia', 'Male', 'Kmejia@gmail.com', '929 272 912', 1, '2020-02-10', 168, 1),
(3, 'Melody', ' De Castro', 'Female', 'Mdcastro@gmail.com', '', 2, '2020-03-04', 169, 1),
(4, 'Madel', 'Joaquin', 'Female', 'krevecrave@gmail.com', '929 272 912', 2, '2021-02-14', 170, 2),
(5, 'Carmelita', 'De Castro', 'Female', 'apple2@gmail.com', '0929229120', 1, '2021-03-14', 171, 2);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(255) NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `JOB_ID` int(11) NOT NULL,
  `JOB_TITLE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`JOB_ID`, `JOB_TITLE`) VALUES
(1, 'Manager'),
(2, 'Cashier'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LOCATION_ID` int(11) NOT NULL,
  `PROVINCE` varchar(100) DEFAULT NULL,
  `CITY` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LOCATION_ID`, `PROVINCE`, `CITY`) VALUES
(158, 'La Union', 'Agoo'),
(159, 'La Union', 'Rosario'),
(160, 'La Union', 'San Fernando'),
(161, 'Pangasinan', 'Pozorrubio'),
(162, 'La Union', 'Bacnotan'),
(163, 'Cebu', 'Cebu City'),
(164, 'Pangasinan', 'Calasiao'),
(165, 'La Union', 'Rosario'),
(166, 'La Union', 'Bauang'),
(167, 'La Union', 'Bacnotan'),
(168, 'La Union', 'Rosario'),
(169, 'La Union', 'Agoo'),
(170, 'La Union', 'Rosario'),
(171, 'La Union', 'San Fernando'),
(172, 'Rizal', 'Angono'),
(173, 'Bulacan', 'Malolos'),
(174, 'Pangasinan', 'Mangaldan'),
(175, 'Ilocos Sur', 'Sinait'),
(176, 'Bulacan', 'Plaridel'),
(177, 'Pampanga', 'Angeles'),
(178, 'Pampanga', 'Angeles'),
(179, 'Marinduque', 'Boac'),
(180, 'Pampanga', 'Angeles'),
(181, 'Pangasinan', 'Dagupan'),
(182, 'Abra', 'Bangued'),
(183, 'Abra', 'Bangued'),
(184, 'Abra', 'Bangued'),
(185, 'Abra', 'Bangued'),
(186, 'Metro Manila', 'Marikina'),
(187, 'Metro Manila', 'Marikina'),
(188, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pos_cart`
--

CREATE TABLE `pos_cart` (
  `ID` int(11) NOT NULL,
  `SESSION_ID` varchar(255) DEFAULT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `PRODUCT_NAME` varchar(255) DEFAULT NULL,
  `QUANTITY` int(11) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `TOTAL` decimal(10,2) DEFAULT NULL,
  `BRANCH_ID` int(11) NOT NULL,
  `ADDED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `PRODUCT_ID` int(11) NOT NULL,
  `PRODUCT_CODE` varchar(20) DEFAULT NULL,
  `NAME` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `QTY_STOCK` int(11) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `SUPPLIER_ID` int(11) NOT NULL,
  `DATE_STOCK_IN` date DEFAULT NULL,
  `EXPIRATION_DATE` date DEFAULT NULL,
  `BRANCH_ID` int(11) DEFAULT NULL,
  `STATUS` enum('Available','Out of Stock','About to Expire','Expired') NOT NULL DEFAULT 'Available',
  `REMARKS` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PRODUCT_ID`, `PRODUCT_CODE`, `NAME`, `DESCRIPTION`, `QTY_STOCK`, `PRICE`, `SUPPLIER_ID`, `DATE_STOCK_IN`, `EXPIRATION_DATE`, `BRANCH_ID`, `STATUS`, `REMARKS`) VALUES
(113, '', 'ANGLE BAR', '1/4 x 3', 19, 1460.00, 1, '2025-03-19', NULL, 1, 'Available', ''),
(114, NULL, 'ANGLE BAR', '1/4 x 2', 10, 710.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(115, NULL, 'ANGLE BAR', '1/4 X 1 1/2', 7, 575.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(116, NULL, 'ANGLE BAR', '1/4 X 1', 6, 380.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(117, NULL, 'ANGLE BAR', '3/16 X 2', 6, 650.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(118, NULL, 'ANGLE BAR', '3/16 X 1 1/2', 6, 435.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(119, NULL, 'ANGLE BAR', '3/16 X 1', 12, 300.00, 1, '2025-03-19', NULL, 1, 'Available', NULL),
(120, NULL, 'FLAT BAR', '1/4 X 2', 6, 470.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(121, NULL, 'FLAT BAR', '1/4 X 1 1/2', 6, 360.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(122, NULL, 'FLAT BAR', '1/4 X 1', 3, 250.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(123, NULL, 'FLAT BAR', '3/16 X 2', 8, 320.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(124, NULL, 'FLAT BAR', '3/16 X 1 1/2', 8, 250.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(125, NULL, 'FLAT BAR', '3/16 X 1', 7, 150.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(126, NULL, 'SQUARE TUBE', '2 X 6 X 1.2', 10, 1025.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(127, NULL, 'SQUARE TUBE', '2 X 4 X 1.2', 7, 775.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(128, NULL, 'SQUARE TUBE', '2 X 3 X 1.5', 11, 670.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(129, NULL, 'SQUARE TUBE', '2 X 3 X 1.2', 6, 635.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(130, NULL, 'SQUARE TUBE', '2 X 2 X 1.5', 4, 545.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(131, NULL, 'SQUARE TUBE', '1 1/2 X 1 1/2 X 1.5', 10, 420.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(132, NULL, 'SQUARE TUBE', '1 1/2 X 1 1/2 X 1.2', 4, 380.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(133, NULL, 'SQUARE TUBE', '1 X 2 X 1.5', 5, 435.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(134, NULL, 'SQUARE TUBE', '1 X 2 X 1.2', 3, 425.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(135, NULL, 'SQUARE TUBE', '1 X 1 X 1.5', 15, 280.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(136, NULL, 'SQUARE TUBE', '1 X 1 X 1.2', 6, 220.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(137, NULL, 'SQUARE TUBE', '3/4 X 3/4 X 1.5', 9, 280.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(138, NULL, 'SQUARE TUBE', '3/4 X 3/4 X 1.2', 10, 250.00, 7, '2025-03-19', NULL, 2, 'Available', NULL),
(139, NULL, 'MARINE PLYWOOD', '1/4\"', 8, 380.00, 6, '2025-03-19', NULL, 1, 'Available', NULL),
(140, NULL, 'MARINE PLYWOOD', '1/2\"', 4, 700.00, 6, '2025-03-19', NULL, 1, 'Available', NULL),
(141, NULL, 'MARINE PLYWOOD', '3/4\"', 3, 1200.00, 6, '2025-03-19', NULL, 1, 'Available', NULL),
(142, NULL, 'PVC PIPE', '#1/2 (Orange)', 28, 80.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(143, NULL, 'PVC PIPE', '#1/2 (Blue)', 28, 80.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(144, NULL, 'PVC PIPE', '#3/4 (Orange)', 33, 90.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(145, NULL, 'PVC PIPE', '#3/4 (Blue)', 13, 90.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(146, NULL, 'PVC PIPE', '#1 (Orange)', 15, 140.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(147, NULL, 'PVC PIPE', '#1 (Blue)', 8, 140.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(148, NULL, 'PVC PIPE', '#1 1/2 (Blue)', 5, 200.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(149, NULL, 'PVC PIPE', '#1 1/4 (Orange)', 10, 160.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(150, NULL, 'PVC PIPE', '#1 1/4 (Blue)', 9, 160.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(151, NULL, 'PVC PIPE', '#1 1/4 (Black)', 7, 160.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(152, NULL, 'PVC PIPE', '#2 (S600) (Orange)', 20, 240.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(153, NULL, 'PVC PIPE', '#2 (S1000) (Orange)', 5, 280.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(154, NULL, 'PVC PIPE', '#3 (S600) (Orange)', 12, 340.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(155, NULL, 'PVC PIPE', '#3 (S600) (Blue)', 10, 280.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(156, NULL, 'PVC PIPE', '#3 (S1000) (Orange)', 3, 380.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(157, NULL, 'PVC PIPE', '#4 (S600) (Orange)', 15, 440.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(158, NULL, 'PVC PIPE', '#4 (S600) (Blue)', 5, 320.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(159, NULL, 'PVC PIPE', '#4 (S1000) (Orange)', 1, 480.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(160, NULL, 'PVC PIPE', '#6 (Orange)', 1, 650.00, 8, '2025-03-19', NULL, 2, 'Available', NULL),
(161, NULL, 'CEMENT', 'HOLCIM', 105, 225.00, 2, '2025-03-19', NULL, 1, 'Available', NULL),
(162, NULL, 'CEMENT', 'NCC', 47, 225.00, 2, '2025-03-19', NULL, 1, 'Available', NULL),
(163, NULL, 'CEMENT', 'HOLCIM', 135, 225.00, 2, '2025-03-19', NULL, 2, 'Available', NULL),
(164, NULL, 'CEMENT', 'NCC', 27, 225.00, 2, '2025-03-19', NULL, 2, 'Available', NULL),
(168, NULL, 'ADHESIVE', 'MORTABOND', 9, 350.00, 2, '2025-03-19', NULL, 2, 'Available', NULL),
(170, NULL, 'SAHARA', '', 31, 60.00, 2, NULL, NULL, 2, 'Available', NULL),
(171, '', 'ANGLE BAR', '1/4 x 3', 0, 1460.00, 6, NULL, NULL, 1, 'Available', ''),
(172, NULL, 'ANGLE BAR', '1/4 x 2', 11, 710.00, 6, NULL, NULL, 1, 'Available', NULL),
(173, NULL, 'ANGLE BAR', '1/4 x 1 1/2', 7, 575.00, 6, NULL, NULL, 1, 'Available', NULL),
(174, NULL, 'ANGLE BAR', '1/4 x 1', 10, 380.00, 6, NULL, NULL, 1, 'Available', NULL),
(175, NULL, 'ANGLE BAR', '3/16 x 2', 6, 650.00, 6, NULL, NULL, 1, 'Available', NULL),
(176, NULL, 'ANGLE BAR', '3/16 x 1 1/2', 7, 435.00, 6, NULL, NULL, 1, 'Available', NULL),
(177, NULL, 'ANGLE BAR', '3/16 x 1', 12, 300.00, 6, NULL, NULL, 1, 'Available', NULL),
(178, NULL, 'FLAT BAR', '1/4 x 2', 6, 470.00, 1, NULL, NULL, 2, 'Available', NULL),
(179, NULL, 'FLAT BAR', '1/4 x 1 1/2', 6, 360.00, 1, NULL, NULL, 2, 'Available', NULL),
(180, NULL, 'FLAT BAR', '1/4 x 1', 3, 250.00, 1, NULL, NULL, 2, 'Available', NULL),
(181, NULL, 'FLAT BAR', '3/16 x 2', 8, 320.00, 1, NULL, NULL, 2, 'Available', NULL),
(182, NULL, 'FLAT BAR', '3/16 x 1 1/2', 8, 250.00, 1, NULL, NULL, 2, 'Available', NULL),
(183, NULL, 'FLAT BAR', '3/16 x 1', 7, 150.00, 1, NULL, NULL, 2, 'Available', NULL),
(184, NULL, 'SQUARE TUBE', '2 x 6 x 1.2', 9, 1025.00, 7, NULL, NULL, 2, 'Available', NULL),
(185, NULL, 'SQUARE TUBE', '2 x 4 x 1.2', 7, 775.00, 7, NULL, NULL, 2, 'Available', NULL),
(186, NULL, 'SQUARE TUBE', '2 x 3 x 1.5', 11, 670.00, 7, NULL, NULL, 2, 'Available', NULL),
(187, NULL, 'SQUARE TUBE', '2 x 3 x 1.2', 6, 635.00, 7, NULL, NULL, 2, 'Available', NULL),
(188, NULL, 'SQUARE TUBE', '2 x 2 x 1.5', 4, 545.00, 7, NULL, NULL, 2, 'Available', NULL),
(189, NULL, 'SQUARE TUBE', '1 1/2 x 1 1/2 x 1.5', 10, 420.00, 7, NULL, NULL, 2, 'Available', NULL),
(190, NULL, 'SQUARE TUBE', '1 1/2 x 1 1/2 x 1.2', 4, 380.00, 7, NULL, NULL, 2, 'Available', NULL),
(191, NULL, 'SQUARE TUBE', '1 x 2 x 1.5', 5, 435.00, 7, NULL, NULL, 2, 'Available', NULL),
(192, NULL, 'SQUARE TUBE', '1 x 2 x 1.2', 3, 425.00, 7, NULL, NULL, 2, 'Available', NULL),
(193, NULL, 'SQUARE TUBE', '1 x 1 x 1.5', 15, 280.00, 7, NULL, NULL, 2, 'Available', NULL),
(194, NULL, 'SQUARE TUBE', '1 x 1 x 1.2', 9, 220.00, 7, NULL, NULL, 2, 'Available', NULL),
(195, NULL, 'SQUARE TUBE', '3/4 x 3/4 x 1.5', 9, 280.00, 7, NULL, NULL, 2, 'Available', NULL),
(196, NULL, 'SQUARE TUBE', '3/4 x 3/4 x 1.2', 10, 250.00, 7, NULL, NULL, 2, 'Available', NULL),
(197, NULL, 'GI PIPE', '1/2\" (15 MM)', 5, 425.00, 7, NULL, NULL, 2, 'Available', NULL),
(198, NULL, 'GI PIPE', '3/4\" (20 MM)', 1, 440.00, 7, NULL, NULL, 2, 'Available', NULL),
(199, NULL, 'GI PIPE', '1\" (25 MM)', 6, 765.00, 7, NULL, NULL, 2, 'Available', NULL),
(200, NULL, 'GI PIPE', '1 1/4\" (32 MM)', 5, 1050.00, 7, NULL, NULL, 2, 'Available', NULL),
(201, NULL, 'GI PIPE', '1 1/2\" (40 MM)', 10, 1205.00, 7, NULL, NULL, 2, 'Available', NULL),
(202, NULL, 'GI PIPE', '2\" (50 MM)', 8, 1625.00, 7, NULL, NULL, 2, 'Available', NULL),
(203, NULL, 'GI PIPE', '2 1/2\" (65 MM)', 1, 2110.00, 7, NULL, NULL, 2, 'Available', NULL),
(204, NULL, 'GI PIPE', '3\" (80 MM)', 0, 2720.00, 7, NULL, NULL, 2, 'Available', NULL),
(205, NULL, 'GI PIPE', '4\" (100 MM)', 1, 3700.00, 7, NULL, NULL, 2, 'Available', NULL),
(206, NULL, 'SQUARE BAR', 'RED', 1, 360.00, 6, NULL, NULL, 2, 'Available', NULL),
(207, NULL, 'SQUARE BAR', 'WHITE', 5, 220.00, 6, NULL, NULL, 2, 'Available', NULL),
(208, NULL, 'DEF BAR', '20 MM', 51, 525.00, 6, NULL, NULL, 2, 'Available', NULL),
(209, NULL, 'DEF BAR', '16 MM', 49, 345.00, 6, NULL, NULL, 2, 'Available', NULL),
(210, NULL, 'DEF BAR', '12 MM', 95, 195.00, 6, NULL, NULL, 2, 'Available', NULL),
(211, NULL, 'DEF BAR', '10 MM', 198, 138.00, 6, NULL, NULL, 2, 'Available', NULL),
(212, NULL, 'DEF BAR', '9 MM', 147, 105.00, 6, NULL, NULL, 2, 'Available', NULL),
(213, NULL, 'DEF BAR', '8 MM', 17, 100.00, 6, NULL, NULL, 2, 'Available', NULL),
(214, NULL, 'C-PURLINS', '2 x 10 x 1.5', 2, 1300.00, 7, NULL, NULL, 1, 'Available', NULL),
(215, NULL, 'C-PURLINS', '2 x 10 x 1.2', 6, 1200.00, 7, NULL, NULL, 1, 'Available', NULL),
(216, NULL, 'C-PURLINS', '2 x 10 x 1.0', 3, 1000.00, 7, NULL, NULL, 1, 'Available', NULL),
(217, NULL, 'C-PURLINS', '2 x 8 x 8 1.2', 9, 765.00, 7, NULL, NULL, 1, 'Available', NULL),
(218, NULL, 'C-PURLINS', '2 x 3 x 1.5', 12, 630.00, 7, NULL, NULL, 1, 'Available', NULL),
(219, NULL, 'C-PURLINS', '2 x 3 x 1.2', 8, 545.00, 7, NULL, NULL, 1, 'Available', NULL),
(220, NULL, 'C-PURLINS', '2 x 3 x 1.0', 17, 480.00, 7, NULL, NULL, 1, 'Available', NULL),
(221, NULL, 'CHANNEL BAR', '2 x 4 x 3/16', 1, 1340.00, 1, NULL, NULL, 1, 'Available', NULL),
(222, NULL, 'CHANNEL BAR', '2 x 3 x 1/4', 5, 1340.00, 1, NULL, NULL, 1, 'Available', NULL),
(223, NULL, 'CHANNEL BAR', '2 x 3 x 3/16', 5, 1000.00, 1, NULL, NULL, 1, 'Available', NULL),
(224, NULL, 'ROUND BAR', '16 MM', 4, 360.00, 1, NULL, NULL, 1, 'Available', NULL),
(225, NULL, 'ROUND BAR', '12 MM', 2, 220.00, 1, NULL, NULL, 1, 'Available', NULL),
(226, NULL, 'ROUND BAR', '10 MM', 5, 165.00, 1, NULL, NULL, 1, 'Available', NULL),
(227, NULL, 'ROUND BAR', '9 MM', 3, 120.00, 1, NULL, NULL, 1, 'Available', NULL),
(228, NULL, 'ROUND BAR', '8 MM', 4, 100.00, 1, NULL, NULL, 1, 'Available', NULL),
(229, NULL, 'METAL FURRING', '0.4', 33, 100.00, 7, NULL, NULL, 2, 'Available', NULL),
(230, NULL, 'METAL STUD', '0.4', 12, 115.00, 8, NULL, NULL, 2, 'Available', NULL),
(231, NULL, 'METAL STUD', '0.5', 10, 125.00, 8, NULL, NULL, 2, 'Available', NULL),
(232, NULL, 'WALL ANGLE', '0.4', 54, 55.00, 11, NULL, NULL, 2, 'Available', NULL),
(233, NULL, 'PHENOLIC', '1/2\"', 5, 635.00, 1, NULL, NULL, 2, 'Available', NULL),
(234, NULL, 'MARINE PLYWOOD', '1/4\"', 9, 380.00, 1, NULL, NULL, 2, 'Available', NULL),
(235, NULL, 'MARINE PLYWOOD', '1/2\"', 4, 700.00, 1, NULL, NULL, 2, 'Available', NULL),
(236, NULL, 'MARINE PLYWOOD', '3/4\"', 3, 1200.00, 1, NULL, NULL, 2, 'Available', NULL),
(237, NULL, 'HARDIFLEX', '3/16\"', 52, 350.00, 1, NULL, NULL, 2, 'Available', NULL),
(238, NULL, 'HARDIFLEX', '1/4\"', 68, 450.00, 1, NULL, NULL, 2, 'Available', NULL),
(239, NULL, 'STEEL MATTING', '6MM', 7, 400.00, 6, NULL, NULL, 2, 'Available', NULL),
(240, NULL, 'STEEL MATTING', '8MM', 10, 650.00, 6, NULL, NULL, 2, 'Available', NULL),
(241, NULL, 'ECO WOOD', '2X2X8', 60, 107.00, 1, NULL, NULL, 2, 'Available', NULL),
(242, NULL, 'ECO WOOD', '2X2X10', 45, 133.00, 1, NULL, NULL, 2, 'Available', NULL),
(243, NULL, 'ECO WOOD', '2X2X12', 54, 160.00, 1, NULL, NULL, 2, 'Available', NULL),
(244, NULL, 'ECO WOOD', '2X3X8', 21, 160.00, 1, NULL, NULL, 2, 'Available', NULL),
(245, NULL, 'ECO WOOD', '2X3X10', 39, 200.00, 1, NULL, NULL, 2, 'Available', NULL),
(246, NULL, 'ECO WOOD', '2X3X12', 25, 240.00, 1, NULL, NULL, 2, 'Available', NULL),
(247, NULL, 'PVC PIPE ORANGE', '#1/2', 28, 80.00, 2, NULL, NULL, 1, 'Available', NULL),
(248, NULL, 'PVC PIPE ORANGE', '#3/4', 33, 90.00, 2, NULL, NULL, 1, 'Available', NULL),
(249, NULL, 'PVC PIPE ORANGE', '#1', 15, 140.00, 2, NULL, NULL, 1, 'Available', NULL),
(250, NULL, 'PVC PIPE ORANGE', '#1 1/4', 10, 160.00, 3, NULL, NULL, 1, 'Available', NULL),
(251, NULL, 'PVC PIPE ORANGE', '#2 (S600)', 20, 240.00, 2, NULL, NULL, 1, 'Available', NULL),
(252, NULL, 'PVC PIPE ORANGE', '#2 (S1000)', 5, 280.00, 2, NULL, NULL, 1, 'Available', NULL),
(253, NULL, 'PVC PIPE ORANGE', '#3 (S600)', 12, 340.00, 2, NULL, NULL, 1, 'Available', NULL),
(254, NULL, 'PVC PIPE ORANGE', '#3 (S1000)', 3, 380.00, 2, NULL, NULL, 1, 'Available', NULL),
(255, NULL, 'PVC PIPE ORANGE', '#4 (S600)', 15, 440.00, 2, NULL, NULL, 1, 'Available', NULL),
(256, NULL, 'PVC PIPE ORANGE', '#4 (S1000)', 1, 480.00, 2, NULL, NULL, 1, 'Available', NULL),
(257, NULL, 'PVC PIPE ORANGE', '#6', 1, 650.00, 2, NULL, NULL, 1, 'Available', NULL),
(258, NULL, 'PVC PIPE BLUE', '#1/2', 28, 80.00, 2, NULL, NULL, 1, 'Available', NULL),
(259, NULL, 'PVC PIPE BLUE', '#3/4', 13, 90.00, 2, NULL, NULL, 1, 'Available', NULL),
(260, NULL, 'PVC PIPE BLUE', '#1', 8, 140.00, 2, NULL, NULL, 1, 'Available', NULL),
(261, NULL, 'PVC PIPE BLUE', '#1 1/2', 5, 200.00, 2, NULL, NULL, 1, 'Available', NULL),
(262, NULL, 'PVC PIPE BLUE', '#1 1/4', 9, 160.00, 2, NULL, NULL, 1, 'Available', NULL),
(263, NULL, 'PVC PIPE BLACK', '#2 (S600)', 7, 160.00, 2, NULL, NULL, 1, 'Available', NULL),
(264, NULL, 'PVC PIPE BLACK', '#3 (S600)', 9, 280.00, 2, NULL, NULL, 1, 'Available', NULL),
(265, NULL, 'PVC PIPE BLACK', '#4 (S600)', 5, 320.00, 2, NULL, NULL, 1, 'Available', NULL),
(266, NULL, 'FITTINGS ELBOW ORANGE', '#6', 5, 400.00, 3, NULL, NULL, 1, 'Available', NULL),
(267, NULL, 'FITTINGS ELBOW ORANGE', '#4', 8, 100.00, 3, NULL, NULL, 1, 'Available', NULL),
(268, NULL, 'FITTINGS ELBOW ORANGE', '#3', 10, 80.00, 3, NULL, NULL, 1, 'Available', NULL),
(269, NULL, 'FITTINGS ELBOW ORANGE', '#2', 20, 50.00, 3, NULL, NULL, 1, 'Available', NULL),
(270, NULL, 'FITTINGS ELBOW ORANGE', '#1', 25, 25.00, 3, NULL, NULL, 1, 'Available', NULL),
(271, NULL, 'FITTINGS ELBOW ORANGE', '#3/4', 20, 20.00, 3, NULL, NULL, 1, 'Available', NULL),
(272, NULL, 'FITTINGS ELBOW ORANGE', '#1/2', 37, 15.00, 3, NULL, NULL, 1, 'Available', NULL),
(273, NULL, 'FITTINGS ELBOW BLUE', '#1', 18, 25.00, 3, NULL, NULL, 1, 'Available', NULL),
(274, NULL, 'FITTINGS ELBOW BLUE', '#3/4', 11, 20.00, 3, NULL, NULL, 1, 'Available', NULL),
(275, NULL, 'FITTINGS ELBOW BLUE', '#1/2', 19, 15.00, 3, NULL, NULL, 1, 'Available', NULL),
(276, NULL, 'FITTINGS ELBOW BLACK', '#4', 15, 45.00, 3, NULL, NULL, 1, 'Available', NULL),
(277, NULL, 'FITTINGS ELBOW BLACK', '#3', 13, 35.00, 3, NULL, NULL, 1, 'Available', NULL),
(278, NULL, 'FITTINGS ELBOW BLACK', '#2', 14, 25.00, 3, NULL, NULL, 1, 'Available', NULL),
(279, NULL, 'FITTINGS COUPLING ORANGE', '#1', 20, 25.00, 3, NULL, NULL, 1, 'Available', NULL),
(280, NULL, 'FITTINGS COUPLING ORANGE', '#3/4', 13, 20.00, 3, NULL, NULL, 1, 'Available', NULL),
(281, NULL, 'FITTINGS COUPLING ORANGE', '#1/2', 25, 15.00, 3, NULL, NULL, 1, 'Available', NULL),
(282, NULL, 'FITTINGS COUPLING BLUE', '#1', 17, 25.00, 3, NULL, NULL, 1, 'Available', NULL),
(283, NULL, 'FITTINGS COUPLING BLUE', '#3/4', 15, 20.00, 3, NULL, NULL, 1, 'Available', NULL),
(284, NULL, 'FITTINGS COUPLING BLUE', '#1/2', 50, 15.00, 3, NULL, NULL, 1, 'Available', NULL),
(285, NULL, 'FITTINGS UNION BLUE', '#1', 8, 60.00, 3, NULL, NULL, 1, 'Available', NULL),
(286, NULL, 'FITTINGS UNION BLUE', '#3/4', 10, 50.00, 3, NULL, NULL, 1, 'Available', NULL),
(287, NULL, 'FITTINGS UNION BLUE', '#1/2', 15, 40.00, 3, NULL, NULL, 1, 'Available', NULL),
(288, NULL, 'CEMENT', 'HOLCIM', 105, 225.00, 2, NULL, NULL, 1, 'Available', NULL),
(289, NULL, 'CEMENT', 'NCC', 57, 225.00, 2, NULL, NULL, 1, 'Available', NULL),
(290, NULL, 'ADHESIVE', 'BIG ELEPHANT', 27, 200.00, 2, NULL, NULL, 1, 'Available', NULL),
(291, NULL, 'ADHESIVE', 'VCC', 18, 230.00, 2, NULL, NULL, 1, 'Available', NULL),
(292, NULL, 'ADHESIVE', 'ABC', 16, 300.00, 2, NULL, NULL, 1, 'Available', NULL),
(293, NULL, 'ADHESIVE', 'MORTABOND', 9, 350.00, 2, NULL, NULL, 1, 'Available', NULL),
(294, NULL, 'SKIMCOAT', 'BIG ELEPHANT', 23, 300.00, 2, NULL, NULL, 2, 'Available', NULL),
(295, NULL, 'SKIMCOAT', 'VCC', 14, 400.00, 2, NULL, NULL, 2, 'Available', NULL),
(296, NULL, 'TILE GROUT', 'WHITE', 21, 100.00, 5, NULL, NULL, 2, 'Available', NULL),
(297, NULL, 'TILE GROUT', 'GRAY', 2, 100.00, 5, NULL, NULL, 2, 'Available', NULL),
(298, NULL, 'TILE GROUT', 'BLACK', 1, 100.00, 5, NULL, NULL, 2, 'Available', NULL),
(299, NULL, 'TILE GROUT', 'BEIGE', 16, 100.00, 5, NULL, NULL, 2, 'Available', NULL),
(300, NULL, 'TILE GROUT', 'BROWN', 19, 100.00, 5, NULL, NULL, 2, 'Available', NULL),
(301, NULL, 'POLITUFF', 'LITER', 5, 300.00, 2, NULL, NULL, 2, 'Available', NULL),
(302, NULL, 'EASY TITE', 'LITER', 6, 250.00, 2, NULL, NULL, 2, 'Available', NULL),
(303, NULL, 'SURESEAL', 'LITER', 5, 550.00, 7, NULL, NULL, 1, 'Available', NULL),
(304, NULL, 'SURESEAL', '82.5 ML', 6, 75.00, 7, NULL, NULL, 1, 'Available', NULL),
(305, NULL, 'HUDSON', 'LITER', 2, 400.00, 3, NULL, NULL, 2, 'Available', NULL),
(306, NULL, 'ELASTOSEAL', 'LITER', 1, 600.00, 2, NULL, NULL, 1, 'Available', NULL),
(307, NULL, 'ELASTOSEAL', '250G', 9, 100.00, 2, NULL, NULL, 1, 'Available', NULL),
(308, NULL, 'ELASTOSEAL', '85G', 7, 75.00, 2, NULL, NULL, 1, 'Available', NULL),
(309, NULL, 'WIRE', '#14 ROYU', 4, 3000.00, 2, NULL, NULL, 2, 'Available', NULL),
(310, NULL, 'WIRE', '#14 POWERFLEX', 2, 2500.00, 2, NULL, NULL, 2, 'Available', NULL),
(311, NULL, 'WIRE', '#14 PDX', 1, 4000.00, 2, NULL, NULL, 2, 'Available', NULL),
(312, NULL, 'WIRE', '#12 ROYU', 2, 4300.00, 2, NULL, NULL, 2, 'Available', NULL),
(313, NULL, 'WIRE', '#12 POWERFLEX', 1, 3500.00, 2, NULL, NULL, 2, 'Available', NULL),
(314, NULL, 'WIRE', '#10 ROYU', 1, 6800.00, 2, NULL, NULL, 2, 'Available', NULL),
(315, NULL, 'FLAT CORD WIRE', '#18 POWERFLEX', 1, 2100.00, 4, NULL, NULL, 2, 'Available', NULL),
(316, NULL, 'FLAT CORD WIRE', '#16 ROYU', 1, 3500.00, 4, NULL, NULL, 2, 'Available', NULL),
(317, NULL, 'POLITUFF', 'LITER', 5, 300.00, 2, NULL, NULL, 2, 'Available', NULL),
(318, NULL, 'EASY TITE', 'LITER', 6, 250.00, 2, NULL, NULL, 2, 'Available', NULL),
(319, NULL, 'SURESEAL', 'LITER', 5, 550.00, 7, NULL, NULL, 1, 'Available', NULL),
(320, NULL, 'SURESEAL', '82.5 ML', 6, 75.00, 7, NULL, NULL, 1, 'Available', NULL),
(321, NULL, 'HUDSON', 'LITER', 2, 400.00, 3, NULL, NULL, 2, 'Available', NULL),
(322, NULL, 'ELASTOSEAL', 'LITER', 1, 600.00, 2, NULL, NULL, 1, 'Available', NULL),
(323, NULL, 'ELASTOSEAL', '250G', 9, 100.00, 2, NULL, NULL, 1, 'Available', NULL),
(324, NULL, 'ELASTOSEAL', '85G', 7, 75.00, 2, NULL, NULL, 1, 'Available', NULL),
(325, NULL, 'B701 FLAT LATEX', 'PAIL', 4, 2500.00, 1, NULL, NULL, 2, 'Available', NULL),
(326, NULL, 'B701 FLAT LATEX', 'GALLON', 5, 650.00, 1, NULL, NULL, 2, 'Available', NULL),
(327, NULL, 'B701 FLAT LATEX', 'LITER', 24, 210.00, 1, NULL, NULL, 2, 'Available', NULL),
(328, NULL, 'B710 GLOSS LATEX', 'PAIL', 9, 2850.00, 1, NULL, NULL, 2, 'Available', NULL),
(329, NULL, 'B710 GLOSS LATEX', 'GALLON', 9, 740.00, 1, NULL, NULL, 2, 'Available', NULL),
(330, NULL, 'B710 GLOSS LATEX', 'LITER', 3, 220.00, 1, NULL, NULL, 2, 'Available', NULL),
(331, NULL, 'B715 SEMI GLOSS LATEX', 'PAIL', 1, 2850.00, 1, NULL, NULL, 2, 'Available', NULL),
(332, NULL, 'B715 SEMI GLOSS LATEX', 'GALLON', 3, 740.00, 1, NULL, NULL, 2, 'Available', NULL),
(333, NULL, 'B715 SEMI GLOSS LATEX', 'LITER', 4, 220.00, 1, NULL, NULL, 2, 'Available', NULL),
(334, NULL, 'B7760 PLEXIBOND', 'PAIL', 5, 2950.00, 1, NULL, NULL, 2, 'Available', NULL),
(335, NULL, 'B7760 PLEXIBOND', 'GALLON', 1, 780.00, 1, NULL, NULL, 2, 'Available', NULL),
(336, '', 'B1254 LACQUER SANDING SEALER', 'GALLON', 0, 750.00, 1, NULL, NULL, 2, 'Available', ''),
(337, NULL, 'B1254 LACQUER SANDING SEALER', 'LITER', 6, 220.00, 1, NULL, NULL, 2, 'Available', NULL),
(338, NULL, 'B7311 MASONRY PUTTY', 'GALLON', 1, 350.00, 1, NULL, NULL, 2, 'Available', NULL),
(339, NULL, 'B7311 MASONRY PUTTY', 'LITER', 15, 120.00, 1, NULL, NULL, 2, 'Available', NULL),
(340, NULL, 'B2700 OIL WOOD STAIN (WALNUT)', 'LITER', 12, 180.00, 1, NULL, NULL, 1, 'Available', NULL),
(341, NULL, 'B2705 OIL WOOD STAIN (MAPLE)', 'LITER', 12, 180.00, 1, NULL, NULL, 1, 'Available', NULL),
(342, NULL, 'B2707 OIL WOOD STAIN (MAHOGANY)', 'LITER', 8, 180.00, 1, NULL, NULL, 1, 'Available', NULL),
(343, NULL, 'B310 RED OXIDE', 'GALLON', 6, 520.00, 1, NULL, NULL, 1, 'Available', NULL),
(344, '', 'B310 RED OXIDE', 'LITER', 0, 180.00, 1, NULL, NULL, 1, 'Available', ''),
(345, NULL, 'B311 PLASOLUX GLAZING PUTTY', 'GALLON', 1, 650.00, 1, NULL, NULL, 1, 'Available', NULL),
(346, NULL, 'B311 PLASOLUX GLAZING PUTTY', 'LITER', 15, 200.00, 1, NULL, NULL, 1, 'Available', NULL),
(347, '', 'B800 FLATWALL ENAMEL', 'PAIL', 30, 2900.00, 1, NULL, NULL, 1, 'Available', ''),
(348, NULL, 'B800 FLATWALL ENAMEL', 'GALLON', 1, 750.00, 1, NULL, NULL, 1, 'Available', NULL),
(349, NULL, 'B800 FLATWALL ENAMEL', 'LITER', 6, 220.00, 1, NULL, NULL, 1, 'Available', NULL),
(350, NULL, 'B600 QDE WHITE', 'PAIL', 11, 3220.00, 1, NULL, NULL, 1, 'Available', NULL),
(351, NULL, 'B600 QDE WHITE', 'GALLON', 2, 850.00, 1, NULL, NULL, 1, 'Available', NULL),
(352, NULL, 'B600 QDE WHITE', 'LITER', 6, 230.00, 1, NULL, NULL, 1, 'Available', NULL),
(353, NULL, 'B680 QDE CHOCOLATE BROWN', 'LITER', 3, 220.00, 1, NULL, NULL, 1, 'Available', NULL),
(354, NULL, 'B690 QDE BLACK', 'GALLON', 6, 650.00, 1, NULL, NULL, 1, 'Available', NULL),
(355, NULL, 'B690 QDE BLACK', 'LITER', 9, 220.00, 1, NULL, NULL, 1, 'Available', NULL),
(356, NULL, 'B780 LATEX CHOCOLATE BROWN', 'LITER', 1, 150.00, 1, NULL, NULL, 2, 'Available', NULL),
(357, NULL, 'B711 JOINT COMPOUND', 'GALLON', 1, 230.00, 1, NULL, NULL, 2, 'Available', NULL),
(358, NULL, 'B2550 ROOFGARD BAGUIO GREEN', 'GALLON', 10, 680.00, 1, NULL, NULL, 2, 'Available', NULL),
(359, '', 'B2570 ROOFGARD SPANISH RED', 'GALLON', 0, 650.00, 1, NULL, NULL, 2, 'Available', ''),
(360, NULL, 'DOMINO EPOXY PRIMER GRAY', 'GALLON', 2, 750.00, 1, NULL, NULL, 2, 'Available', NULL),
(361, NULL, 'DOMINO EPOXY PRIMER GRAY', 'LITER', 11, 250.00, 1, NULL, NULL, 2, 'Available', NULL),
(362, NULL, 'DOMINO RED OXIDE', 'GALLON', 6, 450.00, 1, NULL, NULL, 2, 'Available', NULL),
(363, NULL, 'DOMINO RED OXIDE', 'LITER', 15, 250.00, 1, NULL, NULL, 2, 'Available', NULL),
(364, '', 'B1705 ACRYTEX PRIMER', 'GALLON', 0, 980.00, 1, NULL, NULL, 1, 'Available', ''),
(365, NULL, 'LACQUER THINNER', 'GALLON', 4, 380.00, 1, NULL, NULL, 1, 'Available', NULL),
(366, NULL, 'LACQUER THINNER', 'BOTTLE', 13, 60.00, 1, NULL, NULL, 1, 'Available', NULL),
(367, NULL, 'PAINT THINNER', 'GALLON', 10, 380.00, 1, NULL, NULL, 1, 'Available', NULL),
(368, NULL, 'PAINT THINNER', 'BOTTLE', 4, 60.00, 1, NULL, NULL, 1, 'Available', NULL),
(369, '', 'ACRYLIC THINNER', 'BOTTLE', 1, 60.00, 12, NULL, NULL, 1, 'Available', ''),
(370, NULL, 'Rust Converter', 'Liter', 5, 250.00, 1, NULL, NULL, 2, 'Available', NULL),
(371, NULL, 'Varnish - Mahogany', 'Bottle', 24, 60.00, 1, NULL, NULL, 2, 'Available', NULL),
(372, NULL, 'Varnish - Maple', 'Bottle', 4, 60.00, 1, NULL, NULL, 2, 'Available', NULL),
(373, NULL, 'Varnish - Black', 'Bottle', 2, 60.00, 1, NULL, NULL, 2, 'Available', NULL),
(374, NULL, 'Varnish - Nippon', 'Liter', 8, 490.00, 1, NULL, NULL, 2, 'Available', NULL),
(375, NULL, 'Varnish - Natural', 'Bottle', 3, 60.00, 2, NULL, NULL, 2, 'Available', NULL),
(376, NULL, 'Denatured Alcohol', 'Bottle', 19, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(377, NULL, 'Masonry Neutralizer', 'Quart', 5, 150.00, 6, NULL, NULL, 2, 'Available', NULL),
(378, NULL, 'Rugby', 'Bottle', 9, 100.00, 4, NULL, NULL, 2, 'Available', NULL),
(379, NULL, 'Epoxy A&B', 'Liter', 3, 720.00, 14, NULL, NULL, 2, 'Available', NULL),
(380, NULL, 'Epoxy A&B', '1/2 Liter', 7, 450.00, 14, NULL, NULL, 2, 'Available', NULL),
(381, NULL, 'Epoxy A&B', '1/4 Liter', 7, 250.00, 14, NULL, NULL, 2, 'Available', NULL),
(382, NULL, 'Epoxy A&B', '1/8 Liter', 9, 140.00, 14, NULL, NULL, 2, 'Available', NULL),
(383, NULL, 'Epoxy A&B', '10 ML', 4, 65.00, 14, NULL, NULL, 2, 'Available', NULL),
(384, NULL, 'Vulcaseal', 'Liter', 5, 650.00, 10, NULL, NULL, 2, 'Available', NULL),
(385, NULL, 'Vulcaseal', '1/2 Liter', 5, 350.00, 10, NULL, NULL, 2, 'Available', NULL),
(386, NULL, 'Vulcaseal', '1/4 Liter', 18, 200.00, 10, NULL, NULL, 2, 'Available', NULL),
(387, NULL, 'Vulcaseal', 'Pouch', 12, 75.00, 10, NULL, NULL, 2, 'Available', NULL),
(388, NULL, 'No More Nails', '300G', 1, 220.00, 11, NULL, NULL, 1, 'Available', NULL),
(389, NULL, 'No More Nails', '100G', 7, 100.00, 11, NULL, NULL, 1, 'Available', NULL),
(390, NULL, 'Builders Bond', '300G', 8, 220.00, 2, NULL, NULL, 1, 'Available', NULL),
(391, NULL, 'Builders Bond', '100G', 19, 85.00, 2, NULL, NULL, 1, 'Available', NULL),
(392, NULL, 'Gasket', '85G', 6, 130.00, 10, NULL, NULL, 1, 'Available', NULL),
(393, NULL, 'Gasket', '30G', 5, 85.00, 10, NULL, NULL, 1, 'Available', NULL),
(394, NULL, 'Neltex', '100CC', 45, 110.00, 10, NULL, NULL, 1, 'Available', NULL),
(395, NULL, 'Neltex', '200CC', 24, 180.00, 10, NULL, NULL, 1, 'Available', NULL),
(396, NULL, 'Neltex', '400CC', 1, 220.00, 10, NULL, NULL, 1, 'Available', NULL),
(397, NULL, 'Stikwel', '250G', 16, 75.00, 7, NULL, NULL, 1, 'Available', NULL),
(398, NULL, 'Stikwel', '500G', 10, 150.00, 7, NULL, NULL, 1, 'Available', NULL),
(399, NULL, 'Stikwel', 'Liter', 4, 250.00, 7, NULL, NULL, 1, 'Available', NULL),
(400, NULL, 'Silicon Sealant', 'Clear', 16, 150.00, 2, NULL, NULL, 2, 'Available', NULL),
(401, NULL, 'Silicon Sealant', 'Brown', 11, 150.00, 2, NULL, NULL, 2, 'Available', NULL),
(402, NULL, 'Silicon Sealant', 'Black', 5, 150.00, 2, NULL, NULL, 2, 'Available', NULL),
(403, NULL, 'Silicon Sealant', 'Gray', 11, 150.00, 2, NULL, NULL, 2, 'Available', NULL),
(404, NULL, 'Steel Brush', '#4', 5, 60.00, 4, NULL, NULL, 2, 'Available', NULL),
(405, NULL, 'Roller Brush', '#9', 20, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(406, NULL, 'Roller Brush', '#7', 18, 90.00, 4, NULL, NULL, 2, 'Available', NULL),
(407, NULL, 'Roller Brush', '#4', 27, 60.00, 4, NULL, NULL, 2, 'Available', NULL),
(408, NULL, 'Paint Brush', '#4', 15, 100.00, 4, NULL, NULL, 2, 'Available', NULL),
(409, NULL, 'Paint Brush', '#3', 20, 80.00, 4, NULL, NULL, 2, 'Available', NULL),
(410, NULL, 'Paint Brush', '#2.5', 14, 70.00, 4, NULL, NULL, 2, 'Available', NULL),
(411, NULL, 'Paint Brush', '#2', 22, 45.00, 4, NULL, NULL, 2, 'Available', NULL),
(412, NULL, 'Paint Brush', '#1.5', 13, 35.00, 4, NULL, NULL, 2, 'Available', NULL),
(413, NULL, 'Paint Brush', '#1', 25, 25.00, 4, NULL, NULL, 2, 'Available', NULL),
(414, NULL, 'Paint Brush', '#3/4', 29, 20.00, 4, NULL, NULL, 2, 'Available', NULL),
(415, NULL, 'Paint Brush', '#1/2', 18, 15.00, 4, NULL, NULL, 2, 'Available', NULL),
(416, NULL, 'Spray Paint', 'Black', 8, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(417, NULL, 'Spray Paint', 'Gray', 3, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(418, NULL, 'Spray Paint', 'Red', 2, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(419, NULL, 'Spray Paint', 'White', 3, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(420, NULL, 'Spray Paint', 'Blue', 8, 110.00, 4, NULL, NULL, 2, 'Available', NULL),
(421, NULL, 'Electrical Tape', 'Big', 24, 50.00, 9, NULL, NULL, 2, 'Available', NULL),
(422, NULL, 'Electrical Tape', 'Medium', 11, 35.00, 9, NULL, NULL, 2, 'Available', NULL),
(423, NULL, 'Electrical Tape', 'Small', 2, 25.00, 9, NULL, NULL, 2, 'Available', NULL),
(424, NULL, 'Masking Tape', '#2', 30, 70.00, 13, NULL, NULL, 2, 'Available', NULL),
(425, NULL, 'Masking Tape', '#1', 10, 50.00, 13, NULL, NULL, 2, 'Available', NULL),
(426, NULL, 'Masking Tape', '#3/4', 10, 35.00, 13, NULL, NULL, 2, 'Available', NULL),
(427, NULL, 'Masking Tape', '#1/2', 15, 25.00, 13, NULL, NULL, 2, 'Available', NULL),
(428, NULL, 'Gaza Tape', 'Big', 6, 180.00, 9, NULL, NULL, 2, 'Available', NULL),
(429, NULL, 'Gaza Tape', 'Small', 5, 130.00, 9, NULL, NULL, 2, 'Available', NULL),
(430, NULL, 'Diamond Cutter', '', 20, 300.00, 6, NULL, NULL, 2, 'Available', NULL),
(431, NULL, 'Saw Blade', '', 4, 230.00, 6, NULL, NULL, 2, 'Available', NULL),
(432, NULL, 'Grinding Disc', '', 27, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(433, NULL, 'Cutting Disc', '', 100, 25.00, 6, NULL, NULL, 2, 'Available', NULL),
(434, NULL, 'Masonry Grinding Wheel', '', 3, 70.00, 6, NULL, NULL, 2, 'Available', NULL),
(435, NULL, 'Flap Disc', '#120', 25, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(436, NULL, 'Flap Disc', '#100', 13, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(437, NULL, 'Flap Disc', '#80', 9, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(438, NULL, 'Flap Disc', '#60', 18, 60.00, 6, NULL, NULL, 2, 'Available', NULL),
(439, NULL, 'Blind Rivet', '3/16 X 1', 13, 400.00, 8, NULL, NULL, 1, 'Available', NULL),
(440, NULL, 'Blind Rivet', '3/16 X 3/4', 10, 400.00, 8, NULL, NULL, 1, 'Available', NULL),
(441, NULL, 'Blind Rivet', '3/16 X 1/2', 16, 380.00, 8, NULL, NULL, 1, 'Available', NULL),
(442, NULL, 'Blind Rivet', '5/32 X 1', 5, 350.00, 8, NULL, NULL, 1, 'Available', NULL),
(443, NULL, 'Blind Rivet', '5/32 X 3/4', 7, 250.00, 8, NULL, NULL, 1, 'Available', NULL),
(444, NULL, 'Blind Rivet', '5/32 X 1/2', 18, 300.00, 8, NULL, NULL, 1, 'Available', NULL),
(445, NULL, 'Blind Rivet', '1/8 X 1', 8, 400.00, 8, NULL, NULL, 1, 'Available', NULL),
(446, NULL, 'Blind Rivet', '1/8 X 3/4', 15, 280.00, 8, NULL, NULL, 1, 'Available', NULL),
(447, NULL, 'Blind Rivet', '1/8 X 1/2', 8, 250.00, 8, NULL, NULL, 1, 'Available', NULL),
(448, NULL, 'Texscrew (Metal)', '#3', 3, 625.00, 8, NULL, NULL, 1, 'Available', NULL),
(449, NULL, 'Texscrew (Wood)', '#3', 6, 625.00, 8, NULL, NULL, 1, 'Available', NULL),
(450, NULL, 'Texscrew (Metal)', '#2.5', 2, 500.00, 8, NULL, NULL, 1, 'Available', NULL),
(451, NULL, 'Texscrew (Wood)', '#2.5', 2, 500.00, 8, NULL, NULL, 1, 'Available', NULL),
(452, NULL, 'Texscrew (Metal)', '#2', 6, 450.00, 8, NULL, NULL, 1, 'Available', NULL),
(453, NULL, 'Texscrew (Wood)', '#2', 6, 450.00, 8, NULL, NULL, 1, 'Available', NULL),
(454, NULL, 'Texscrew (Metal)', '#1.5', 1, 600.00, 8, NULL, NULL, 1, 'Available', NULL),
(455, NULL, 'Texscrew (Wood)', '#1.5', 1, 600.00, 8, NULL, NULL, 1, 'Available', NULL),
(456, NULL, 'Texscrew', '#1', 1, 1000.00, 8, NULL, NULL, 1, 'Available', NULL),
(457, NULL, 'Blackscrew (Metal)', '#3', 5, 750.00, 7, NULL, NULL, 1, 'Available', NULL),
(458, NULL, 'Blackscrew (Wood)', '#3', 6, 750.00, 7, NULL, NULL, 1, 'Available', NULL),
(459, NULL, 'Blackscrew (Metal)', '#2', 6, 800.00, 7, NULL, NULL, 1, 'Available', NULL),
(460, NULL, 'Blackscrew (Wood)', '#2', 3, 800.00, 7, NULL, NULL, 1, 'Available', NULL),
(461, NULL, 'Blackscrew (Metal)', '#1.5', 1, 900.00, 7, NULL, NULL, 1, 'Available', NULL),
(462, NULL, 'Blackscrew (Wood)', '#1.5', 1, 900.00, 7, NULL, NULL, 1, 'Available', NULL),
(463, NULL, 'Blackscrew (Metal)', '#1', 4, 1000.00, 7, NULL, NULL, 1, 'Available', NULL),
(464, NULL, 'Blackscrew (Wood)', '#1', 2, 1000.00, 7, NULL, NULL, 1, 'Available', NULL),
(465, NULL, 'Sand Paper', '#1000', 55, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(466, NULL, 'Sand Paper', '#800', 70, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(467, NULL, 'Sand Paper', '#600', 150, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(468, NULL, 'Sand Paper', '#400', 130, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(469, NULL, 'Sand Paper', '#360', 60, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(470, NULL, 'Sand Paper', '#320', 150, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(471, NULL, 'Sand Paper', '#280', 75, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(472, NULL, 'Sand Paper', '#240', 65, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(473, NULL, 'Sand Paper', '#220', 85, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(474, NULL, 'Sand Paper', '#180', 70, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(475, NULL, 'Sand Paper', '#150', 60, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(476, NULL, 'Sand Paper', '#120', 80, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(477, NULL, 'Sand Paper', '#100', 30, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(478, NULL, 'Sand Paper', '#80', 45, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(479, NULL, 'Sand Paper', '#60', 30, 30.00, 7, NULL, NULL, 1, 'Available', NULL),
(480, NULL, 'Door', 'Laminated Door', 6, 2800.00, 13, NULL, NULL, 1, 'Available', NULL),
(481, NULL, 'Door', 'PVC Door', 4, 1800.00, 13, NULL, NULL, 1, 'Available', NULL),
(482, NULL, 'Door', 'Panel Door with Varnish', 3, 4500.00, 13, NULL, NULL, 1, 'Available', NULL),
(483, NULL, 'Door', 'Panel Door', 5, 3500.00, 13, NULL, NULL, 1, 'Available', NULL),
(484, NULL, 'Door', 'Aluminum Door', 6, 2300.00, 13, NULL, NULL, 1, 'Available', NULL),
(485, NULL, 'Door', 'Fiber Glass Door', 1, 8500.00, 11, NULL, NULL, 1, 'Available', NULL),
(486, NULL, 'Door Knob', '', 83, 500.00, 13, NULL, NULL, 1, 'Available', NULL),
(487, NULL, 'Hinge', '4\"', 9, 130.00, 13, NULL, NULL, 1, 'Available', NULL),
(488, NULL, 'Hinge', '3 1/2\"', 10, 115.00, 13, NULL, NULL, 1, 'Available', NULL),
(489, NULL, 'Hinge', '3\"', 15, 110.00, 13, NULL, NULL, 1, 'Available', NULL),
(490, NULL, 'Hinge', '2\"', 16, 50.00, 3, NULL, NULL, 1, 'Available', NULL),
(491, NULL, 'Masonry Bit', '#5/16', 10, 75.00, 9, NULL, NULL, 1, 'Available', NULL),
(492, NULL, 'Masonry Bit', '#1/4', 5, 70.00, 9, NULL, NULL, 1, 'Available', NULL),
(493, NULL, 'Masonry Bit', '#3/16', 6, 90.00, 9, NULL, NULL, 1, 'Available', NULL),
(494, NULL, 'Masonry Bit', '#3/18', 8, 95.00, 9, NULL, NULL, 1, 'Available', NULL),
(495, NULL, 'Masonry Bit', '#5/32', 15, 65.00, 9, NULL, NULL, 1, 'Available', NULL),
(496, NULL, 'Masonry Bit', '#1/8', 16, 55.00, 9, NULL, NULL, 1, 'Available', NULL),
(497, NULL, 'Drill Bit', '#1/2', 3, 450.00, 9, NULL, NULL, 1, 'Available', NULL),
(498, NULL, 'Drill Bit', '#5/16', 2, 180.00, 9, NULL, NULL, 1, 'Available', NULL),
(499, NULL, 'Drill Bit', '#1/4', 8, 120.00, 9, NULL, NULL, 1, 'Available', NULL),
(500, NULL, 'Drill Bit', '#3/16', 10, 100.00, 9, NULL, NULL, 1, 'Available', NULL),
(501, NULL, 'Drill Bit', '#5/32', 12, 85.00, 9, NULL, NULL, 1, 'Available', NULL),
(502, NULL, 'Drill Bit', '#1/8', 10, 75.00, 9, NULL, NULL, 1, 'Available', NULL),
(503, NULL, 'Tansi', '', 19, 30.00, 8, NULL, NULL, 1, 'Available', NULL),
(504, NULL, 'Bulb', '18W Firefly', 12, 240.00, 11, NULL, NULL, 1, 'Available', NULL),
(505, NULL, 'Bulb', '15W Firefly', 5, 170.00, 11, NULL, NULL, 1, 'Available', NULL),
(506, NULL, 'Bulb', '15W Alco', 8, 180.00, 11, NULL, NULL, 1, 'Available', NULL),
(507, NULL, 'Bulb', '13W Firefly', 1, 150.00, 11, NULL, NULL, 1, 'Available', NULL),
(508, NULL, 'Bulb', '12W Alco', 3, 115.00, 11, NULL, NULL, 1, 'Available', NULL),
(509, NULL, 'Bulb', '11W Firefly', 8, 125.00, 11, NULL, NULL, 1, 'Available', NULL),
(510, NULL, 'Bulb', '9W Firefly', 15, 110.00, 11, NULL, NULL, 1, 'Available', NULL),
(511, NULL, 'Bulb', '9W Alco', 3, 95.00, 11, NULL, NULL, 1, 'Available', NULL),
(512, NULL, 'Bulb', '7W Firefly', 15, 100.00, 11, NULL, NULL, 1, 'Available', NULL),
(513, NULL, 'Bulb', '7W Alco', 6, 85.00, 11, NULL, NULL, 1, 'Available', NULL),
(514, NULL, 'Bulb', '5W Firefly', 6, 85.00, 11, NULL, NULL, 1, 'Available', NULL),
(515, NULL, 'Bulb', '5W Alco', 8, 80.00, 11, NULL, NULL, 1, 'Available', NULL),
(516, NULL, 'Bulb', '3W Firefly', 16, 70.00, 11, NULL, NULL, 1, 'Available', NULL),
(517, NULL, 'Bulb', '3W Alco', 8, 70.00, 11, NULL, NULL, 1, 'Available', NULL),
(518, NULL, 'Circuit Breaker', '100 AMPS Alco', 5, 650.00, 11, NULL, NULL, 1, 'Available', NULL),
(519, NULL, 'Circuit Breaker', '60 AMPS Royu', 5, 400.00, 11, NULL, NULL, 1, 'Available', NULL),
(520, NULL, 'Circuit Breaker', '60 AMPS Alco', 6, 335.00, 11, NULL, NULL, 1, 'Available', NULL),
(521, NULL, 'Circuit Breaker', '60 AMPS Koten', 4, 420.00, 11, NULL, NULL, 1, 'Available', NULL),
(522, NULL, 'Circuit Breaker', '40 AMPS Royu', 10, 400.00, 11, NULL, NULL, 1, 'Available', NULL),
(523, NULL, 'Circuit Breaker', '30 AMPS Royu', 5, 400.00, 11, NULL, NULL, 1, 'Available', NULL),
(524, NULL, 'Circuit Breaker', '30 AMPS Alco', 7, 335.00, 11, NULL, NULL, 1, 'Available', NULL),
(525, NULL, 'Circuit Breaker', '30 AMPS Koten', 2, 420.00, 11, NULL, NULL, 1, 'Available', NULL),
(526, NULL, 'Circuit Breaker', '20 AMPS Alco', 3, 335.00, 11, NULL, NULL, 1, 'Available', NULL),
(527, NULL, 'Circuit Breaker', '20 AMPS Koten', 7, 420.00, 11, NULL, NULL, 1, 'Available', NULL),
(528, NULL, 'Circuit Breaker', '15 AMPS Royu', 6, 400.00, 11, NULL, NULL, 1, 'Available', NULL),
(529, NULL, 'Circuit Breaker', '15 AMPS Alco', 10, 335.00, 11, NULL, NULL, 1, 'Available', NULL),
(530, NULL, 'Circuit Breaker', '15 AMPS Koten', 13, 420.00, 11, NULL, NULL, 1, 'Available', NULL),
(531, NULL, 'Safety Breaker with Cover', '60 AMPS Koten', 5, 700.00, 11, NULL, NULL, 1, 'Available', NULL),
(532, NULL, 'Safety Breaker with Cover', '40 AMPS Royu', 3, 500.00, 11, NULL, NULL, 1, 'Available', NULL),
(533, NULL, 'Safety Breaker with Cover', '30 AMPS Royu', 5, 500.00, 11, NULL, NULL, 1, 'Available', NULL),
(534, NULL, 'Safety Breaker with Cover', '30 AMPS Alco', 4, 450.00, 11, NULL, NULL, 1, 'Available', NULL),
(535, NULL, 'Safety Breaker with Cover', '20 AMPS Royu', 2, 500.00, 11, NULL, NULL, 1, 'Available', NULL),
(536, NULL, 'Safety Breaker with Cover', '20 AMPS Alco', 1, 450.00, 11, NULL, NULL, 1, 'Available', NULL),
(537, NULL, 'Switch Classic', '1 Gang Royu', 20, 80.00, 11, NULL, NULL, 1, 'Available', NULL),
(538, NULL, 'Switch Classic', '1 Gang Alco', 17, 70.00, 11, NULL, NULL, 1, 'Available', NULL),
(539, NULL, 'Switch Classic', '1 Gang Goneo', 10, 100.00, 11, NULL, NULL, 1, 'Available', NULL),
(540, NULL, 'Switch Classic', '2 Gang Royu', 15, 150.00, 11, NULL, NULL, 1, 'Available', NULL),
(541, NULL, 'Switch Classic', '2 Gang Alco', 11, 135.00, 11, NULL, NULL, 1, 'Available', NULL),
(542, NULL, 'Switch Classic', '2 Gang Goneo', 7, 150.00, 11, NULL, NULL, 1, 'Available', NULL),
(543, NULL, 'Switch Classic', '3 Gang Royu', 13, 180.00, 11, NULL, NULL, 1, 'Available', NULL),
(544, NULL, 'Switch Classic', '3 Gang Alco', 6, 170.00, 11, NULL, NULL, 1, 'Available', NULL),
(545, NULL, 'Outlet Classic', '1 Gang Royu', 27, 80.00, 11, NULL, NULL, 1, 'Available', NULL),
(546, NULL, 'Outlet Classic', '1 Gang Alco', 25, 70.00, 11, NULL, NULL, 1, 'Available', NULL),
(547, NULL, 'Outlet Classic', '1 Gang Goneo', 7, 100.00, 11, NULL, NULL, 1, 'Available', NULL),
(548, NULL, 'Outlet Classic', '2 Gang Royu', 14, 130.00, 11, NULL, NULL, 1, 'Available', NULL),
(549, NULL, 'Outlet Classic', '2 Gang Alco', 16, 135.00, 11, NULL, NULL, 1, 'Available', NULL),
(550, NULL, 'Outlet Classic', '3 Gang Royu', 8, 180.00, 11, NULL, NULL, 1, 'Available', NULL),
(551, NULL, 'Outlet Classic', '3 Gang Alco', 5, 170.00, 11, NULL, NULL, 1, 'Available', NULL),
(552, NULL, 'Utility Box', '', 30, 30.00, 4, NULL, NULL, 1, 'Available', NULL),
(553, NULL, 'Junction Box', '', 25, 40.00, 4, NULL, NULL, 1, 'Available', NULL),
(554, NULL, 'Square Box', '', 23, 80.00, 4, NULL, NULL, 1, 'Available', NULL),
(559, '6', 'test', 'test', 22, 2.00, 7, '0000-00-00', NULL, 1, 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_edit_history`
--

CREATE TABLE `product_edit_history` (
  `EDIT_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `FIELD_CHANGED` varchar(50) NOT NULL,
  `OLD_VALUE` text DEFAULT NULL,
  `NEW_VALUE` text DEFAULT NULL,
  `EDIT_TIME` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_edit_history`
--

INSERT INTO `product_edit_history` (`EDIT_ID`, `PRODUCT_ID`, `USER_ID`, `FIELD_CHANGED`, `OLD_VALUE`, `NEW_VALUE`, `EDIT_TIME`) VALUES
(4, 369, 1, 'Quantity', '14', '15', '2025-04-01 13:46:38'),
(5, 113, 1, 'Quantity', '0', '20', '2025-04-07 03:20:05'),
(6, 347, 1, 'Quantity', '0', '30', '2025-04-21 15:29:12'),
(7, 171, 1, 'Quantity', '3', '0', '2025-04-21 15:29:27'),
(8, 336, 1, 'Quantity', '1', '0', '2025-04-21 15:32:39'),
(9, 364, 1, 'Quantity', '1', '0', '2025-04-21 15:46:13'),
(10, 359, 1, 'Quantity', '4', '0', '2025-04-21 16:07:59'),
(11, 344, 1, 'Quantity', '2', '0', '2025-04-21 16:22:58'),
(12, 369, 1, 'Quantity', '15', '1', '2025-04-24 02:21:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_history`
--

CREATE TABLE `product_history` (
  `ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `PRODUCT_CODE` varchar(20) DEFAULT NULL,
  `NAME` varchar(50) DEFAULT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `QTY_STOCK` int(11) DEFAULT NULL,
  `PRICE` decimal(10,2) DEFAULT NULL,
  `SUPPLIER_ID` int(11) DEFAULT NULL,
  `BRANCH_ID` int(11) DEFAULT NULL,
  `ACTION_TYPE` enum('Added','Deleted') NOT NULL,
  `ACTION_DATE` timestamp NULL DEFAULT current_timestamp(),
  `USER` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_history`
--

INSERT INTO `product_history` (`ID`, `PRODUCT_ID`, `PRODUCT_CODE`, `NAME`, `DESCRIPTION`, `QTY_STOCK`, `PRICE`, `SUPPLIER_ID`, `BRANCH_ID`, `ACTION_TYPE`, `ACTION_DATE`, `USER`) VALUES
(12, 559, '6', 'test', 'test', 22, 2.00, 7, 1, 'Added', '2025-04-01 14:57:30', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `product_notifications`
--

CREATE TABLE `product_notifications` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_notifications`
--

INSERT INTO `product_notifications` (`id`, `product_id`, `status`, `message`, `created_at`) VALUES
(1, 359, 'out_of_stock', 'B2570 ROOFGARD SPANISH RED is now out of stock.', '2025-04-21 16:07:59'),
(2, 369, 'low_stock', 'ACRYLIC THINNER is running low on stock.', '2025-04-24 02:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `PO_ID` int(11) NOT NULL,
  `SUPPLIER_ID` int(11) NOT NULL,
  `ORDER_DATE` datetime DEFAULT current_timestamp(),
  `EXPECTED_DELIVERY_DATE` date DEFAULT NULL,
  `STATUS` enum('Pending','Approved','Partially Received','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `TOTAL_AMOUNT` decimal(10,2) DEFAULT 0.00,
  `CREATED_BY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`PO_ID`, `SUPPLIER_ID`, `ORDER_DATE`, `EXPECTED_DELIVERY_DATE`, `STATUS`, `TOTAL_AMOUNT`, `CREATED_BY`) VALUES
(9, 14, '2025-03-27 22:12:00', '2025-03-28', 'Completed', 0.00, 3),
(10, 12, '2025-04-03 12:58:00', '2025-04-03', 'Pending', 0.00, 3),
(11, 14, '2025-04-07 09:58:00', '2025-04-07', 'Pending', 0.00, 3),
(12, 1, '2025-04-09 23:54:00', '2025-04-09', 'Pending', 0.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `POI_ID` int(11) NOT NULL,
  `PO_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `QUANTITY_ORDERED` int(11) NOT NULL,
  `UNIT_PRICE` decimal(10,2) NOT NULL,
  `TOTAL_PRICE` decimal(10,2) GENERATED ALWAYS AS (`QUANTITY_ORDERED` * `UNIT_PRICE`) STORED
) ;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`POI_ID`, `PO_ID`, `PRODUCT_ID`, `QUANTITY_ORDERED`, `UNIT_PRICE`) VALUES
(10, 9, 379, 1, 720.00),
(11, 9, 380, 2, 450.00),
(12, 10, 369, 20, 60.00),
(13, 11, 379, 20, 720.00),
(14, 11, 382, 10, 140.00),
(15, 12, 347, 2, 2900.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_notifications`
--

CREATE TABLE `stock_notifications` (
  `NOTIF_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `MESSAGE` text NOT NULL,
  `NOTIF_TIME` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SUPPLIER_ID` int(11) NOT NULL,
  `COMPANY_NAME` varchar(50) DEFAULT NULL,
  `LOCATION_ID` int(11) NOT NULL,
  `PHONE_NUMBER` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SUPPLIER_ID`, `COMPANY_NAME`, `LOCATION_ID`, `PHONE_NUMBER`) VALUES
(1, 'Philippine Topwood Industries & Trading Corp.', 160, '(072) 607-6492'),
(2, 'Sa-Shay Construction Supplies Trading', 161, ''),
(3, 'NASMC Trading', 162, ''),
(4, 'NGC Marketing Corporation', 163, ''),
(5, 'Omega Tile Center', 164, ''),
(6, 'Myle Construction Supply', 165, '09175117975'),
(7, 'TW Steel and Construction Supply, INC.', 166, '682-2755'),
(8, 'North Aseana Summit Metal Trading Corp.', 167, ''),
(9, 'Homexpo Building Depot', 173, ''),
(10, 'TradeNorth INC.', 174, ''),
(11, 'Wilchat Marketing', 175, '09095758160'),
(12, 'Cabiawan Enterprises', 176, ''),
(13, 'Rockhold Hardware & Indl Supply Corp.', 180, '0912931996'),
(14, 'Alice Commercial OPC', 181, '');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `TRANS_ID` int(11) NOT NULL,
  `CUST_ID` int(11) DEFAULT NULL,
  `NUMOFITEMS` varchar(250) NOT NULL,
  `SUBTOTAL` varchar(50) NOT NULL,
  `LESSVAT` varchar(50) NOT NULL,
  `NETVAT` varchar(50) NOT NULL,
  `ADDVAT` varchar(50) NOT NULL,
  `GRANDTOTAL` varchar(250) NOT NULL,
  `CASH` varchar(250) NOT NULL,
  `DATE` varchar(50) NOT NULL,
  `TRANS_D_ID` varchar(250) NOT NULL,
  `TRANS_NO` varchar(250) NOT NULL,
  `PAYMENT` varchar(50) NOT NULL DEFAULT 'cash',
  `REF_NO` varchar(100) DEFAULT NULL,
  `STATUS` enum('Active','Voided') NOT NULL DEFAULT 'Active',
  `DISCOUNT` varchar(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`TRANS_ID`, `CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `LESSVAT`, `NETVAT`, `ADDVAT`, `GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID`, `TRANS_NO`, `PAYMENT`, `REF_NO`, `STATUS`, `DISCOUNT`) VALUES
(151, 2, '1', '710.00', '76.07', '633.93', '76.07', '709.00', '709.00', '2025-03-25 12:57PM', '032545725', '1', 'bank', 'N/A', 'Active', '1.00'),
(152, 2, '1', '2920.00', '312.86', '2607.14', '312.86', '2628.00', '2628.00', '2025-03-31 10:49PM', '0331145033', '22222222', 'cash', 'N/A', 'Active', '292.00'),
(153, 2, '2', '1140.00', '122.14', '1017.86', '122.14', '1139.00', '1139.00', '2025-04-03 02:29PM', '040363003', '2', 'online', 'N/A', 'Active', '1.00'),
(154, 4, '3', '3290.00', '352.50', '2937.50', '352.50', '3290.00', '3290.00', '2025-04-07 10:00AM', '040720033', '20', 'cash', 'N/A', 'Active', '0.00'),
(155, 3, '3', '1792.00', '192.00', '1600.00', '192.00', '1792.00', '1792.00', '2025-04-07 10:01AM', '040720214', '30', 'cash', 'N/A', 'Active', '0.00'),
(156, 2, '1', '1460.00', '156.43', '1303.57', '156.43', '1387.00', '1387.00', '2025-04-07 11:23AM', '040732417', '88', 'cash', 'N/A', 'Active', '73.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `ID` int(11) NOT NULL,
  `TRANS_D_ID` varchar(250) NOT NULL,
  `PRODUCTS` varchar(250) NOT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `QTY` int(11) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `EMPLOYEE` varchar(250) DEFAULT NULL,
  `ROLE` varchar(250) NOT NULL,
  `PAYMENT` enum('Cash','Online','Bank') NOT NULL,
  `TRANS_NO` varchar(250) NOT NULL,
  `REF_NO` varchar(250) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`ID`, `TRANS_D_ID`, `PRODUCTS`, `DESCRIPTION`, `QTY`, `PRICE`, `EMPLOYEE`, `ROLE`, `PAYMENT`, `TRANS_NO`, `REF_NO`, `PRODUCT_ID`) VALUES
(31682909, '032545725', 'ANGLE BAR', '1/4 x 2', 1, 710.00, 'Melody', 'Cashier', 'Bank', '1', 'N/A', 114),
(31682910, '0331145033', 'ANGLE BAR', '1/4 x 3', 2, 1460.00, 'Melody', 'Cashier', 'Cash', '22222222', 'N/A', 113),
(31682911, '040363003', 'MARINE PLYWOOD', '1/4\"', 1, 380.00, 'Melody', 'Cashier', 'Online', '2', 'N/A', 139),
(31682912, '040363003', 'ANGLE BAR', '1/4 X 1', 2, 380.00, 'Melody', 'Cashier', 'Online', '2', 'N/A', 116),
(31682913, '040720033', 'PVC PIPE BLACK', '#3 (S600)', 1, 280.00, 'Melody', 'Cashier', 'Cash', '20', 'N/A', 264),
(31682914, '040720033', 'ANGLE BAR', '1/4 X 1', 2, 380.00, 'Melody', 'Cashier', 'Cash', '20', 'N/A', 116),
(31682915, '040720033', 'CEMENT', 'NCC', 10, 225.00, 'Melody', 'Cashier', 'Cash', '20', 'N/A', 162),
(31682916, '040720214', 'SQUARE TUBE', '1 X 1 X 1.2', 3, 220.00, 'Madel', 'Cashier', 'Cash', '30', 'N/A', 136),
(31682917, '040720214', 'SQUARE TUBE', '2 x 6 x 1.2', 1, 1025.00, 'Madel', 'Cashier', 'Cash', '30', 'N/A', 184),
(31682918, '040720214', 'ECO WOOD', '2X2X8', 1, 107.00, 'Madel', 'Cashier', 'Cash', '30', 'N/A', 241),
(31682919, '040732417', 'ANGLE BAR', '1/4 x 3', 1, 1460.00, 'Melody', 'Cashier', 'Cash', '88', 'N/A', 113);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `TYPE_ID` int(11) NOT NULL,
  `TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`TYPE_ID`, `TYPE`) VALUES
(1, 'Admin'),
(2, 'Cashier'),
(3, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `USERNAME` varchar(50) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `TYPE_ID` int(11) DEFAULT NULL,
  `BRANCH_ID` int(11) DEFAULT NULL,
  `STATUS` enum('Enable','Disable') NOT NULL DEFAULT 'Enable'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `EMPLOYEE_ID`, `USERNAME`, `PASSWORD`, `TYPE_ID`, `BRANCH_ID`, `STATUS`) VALUES
(1, 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 1, 'Enable'),
(2, 3, 'cashier1', 'fd033e22ae348aeb5660fc2140aec35850c4da997', 2, 1, 'Enable'),
(3, 2, 'manager1', 'd033e22ae348aeb5660fc2140aec35850c4da997', 3, 1, 'Enable'),
(4, 5, 'manager2', 'dd033e22ae348aeb5660fc2140aec35850c4da997', 3, 2, 'Enable'),
(5, 4, 'cashier2', 'd033e22ae348aeb5660fc2140aec35850c4da997', 2, 2, 'Enable');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`BRANCH_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CUST_ID`),
  ADD KEY `BRANCH_ID` (`BRANCH_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EMPLOYEE_ID`),
  ADD KEY `JOB_ID` (`JOB_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`),
  ADD KEY `BRANCH_ID` (`BRANCH_ID`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`JOB_ID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LOCATION_ID`);

--
-- Indexes for table `pos_cart`
--
ALTER TABLE `pos_cart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `BRANCH_ID` (`BRANCH_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `product_ibfk_1` (`SUPPLIER_ID`),
  ADD KEY `product_ibfk_2` (`BRANCH_ID`);

--
-- Indexes for table `product_edit_history`
--
ALTER TABLE `product_edit_history`
  ADD PRIMARY KEY (`EDIT_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `product_history`
--
ALTER TABLE `product_history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `product_notifications`
--
ALTER TABLE `product_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_status` (`product_id`,`status`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`PO_ID`),
  ADD KEY `po_supplier_fk` (`SUPPLIER_ID`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`POI_ID`),
  ADD KEY `poi_po_fk` (`PO_ID`),
  ADD KEY `poi_product_fk` (`PRODUCT_ID`);

--
-- Indexes for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  ADD PRIMARY KEY (`NOTIF_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SUPPLIER_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`TRANS_ID`),
  ADD KEY `TRANS_DETAIL_ID` (`TRANS_D_ID`),
  ADD KEY `CUST_ID` (`CUST_ID`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TRANS_D_ID` (`TRANS_D_ID`) USING BTREE;

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`TYPE_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_USERNAME` (`USERNAME`),
  ADD KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  ADD KEY `TYPE_ID` (`TYPE_ID`),
  ADD KEY `BRANCH_ID` (`BRANCH_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `BRANCH_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CUST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EMPLOYEE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `JOB_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `pos_cart`
--
ALTER TABLE `pos_cart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_edit_history`
--
ALTER TABLE `product_edit_history`
  MODIFY `EDIT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_history`
--
ALTER TABLE `product_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_notifications`
--
ALTER TABLE `product_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `PO_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `POI_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  MODIFY `NOTIF_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=764;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SUPPLIER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `TRANS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31682920;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `TYPE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`BRANCH_ID`) REFERENCES `branches` (`BRANCH_ID`),
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`JOB_ID`) REFERENCES `job` (`JOB_ID`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`),
  ADD CONSTRAINT `employee_ibfk_3` FOREIGN KEY (`BRANCH_ID`) REFERENCES `branches` (`BRANCH_ID`);

--
-- Constraints for table `pos_cart`
--
ALTER TABLE `pos_cart`
  ADD CONSTRAINT `pos_cart_ibfk_1` FOREIGN KEY (`BRANCH_ID`) REFERENCES `users` (`BRANCH_ID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `supplier` (`SUPPLIER_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`BRANCH_ID`) REFERENCES `branches` (`BRANCH_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `po_supplier_fk` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `supplier` (`SUPPLIER_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `poi_po_fk` FOREIGN KEY (`PO_ID`) REFERENCES `purchase_orders` (`PO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poi_product_fk` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  ADD CONSTRAINT `stock_notifications_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`CUST_ID`) REFERENCES `customer` (`CUST_ID`),
  ADD CONSTRAINT `transaction_ibfk_4` FOREIGN KEY (`TRANS_D_ID`) REFERENCES `transaction_details` (`TRANS_D_ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_branch` FOREIGN KEY (`BRANCH_ID`) REFERENCES `branches` (`BRANCH_ID`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`TYPE_ID`) REFERENCES `type` (`TYPE_ID`),
  ADD CONSTRAINT `users_ibfk_4` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employee` (`EMPLOYEE_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
