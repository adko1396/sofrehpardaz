-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 06:45 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sofrehpardaz`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_product`
--

CREATE TABLE `add_product` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شماره ثبت',
  `item_name` varchar(255) NOT NULL COMMENT 'نام کالا',
  `entry_date_and_time` varchar(255) NOT NULL COMMENT 'تاریخ و زمان ورود',
  `date_sql` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'تاریخ و زمان SQL',
  `value` double NOT NULL COMMENT 'مقدار',
  `price` decimal(10,0) NOT NULL COMMENT 'قیمت',
  `supplier_name` varchar(255) NOT NULL COMMENT 'تأمین‌کننده',
  `expiry_date` varchar(255) NOT NULL COMMENT 'تاریخ انقضا',
  `note` text NOT NULL COMMENT 'یادداشت'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ثبت ورود کالا';

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شناسه',
  `category_name` varchar(255) NOT NULL COMMENT 'نام دسته‌بندی',
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'تاریخ و ساعت'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='دسته‌بندی';

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_code` int(10) UNSIGNED NOT NULL COMMENT 'کد مشتری',
  `invoice_number` int(11) NOT NULL COMMENT 'شماره فاکتور',
  `cashier_name` varchar(255) NOT NULL COMMENT 'نام صندوق‌دار',
  `customer_phone_number` varchar(255) NOT NULL COMMENT 'شماره تماس مشتری',
  `table_number` varchar(255) NOT NULL COMMENT ' شماره میز',
  `time` varchar(255) NOT NULL COMMENT 'ساعت',
  `date` varchar(255) NOT NULL COMMENT 'تاریخ',
  `total_price` varchar(255) NOT NULL COMMENT 'قیمت کل',
  `date_SQL` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'تاریخ و ساعت',
  `discount` varchar(255) NOT NULL COMMENT 'تخفیف',
  `payment_method` varchar(255) NOT NULL COMMENT 'نوع پرداخت'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='مشتری';

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_code`, `invoice_number`, `cashier_name`, `customer_phone_number`, `table_number`, `time`, `date`, `total_price`, `date_SQL`, `discount`, `payment_method`) VALUES
(0, 0, '', '', '', '', '', '0', '2025-06-24 16:41:43', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شناسه کالا',
  `item_name` varchar(255) NOT NULL COMMENT 'نام کالا',
  `item_type` varchar(255) NOT NULL COMMENT 'نوع کالا',
  `unit` varchar(255) NOT NULL COMMENT 'واحد اندازه‌گیری',
  `minimum_stock_leve` double NOT NULL COMMENT 'حداقل موجودی مجاز',
  `expiry_date` varchar(255) DEFAULT NULL COMMENT 'تاریخ انقضا',
  `expiry_date_sql` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'تاریخ انقضا sql',
  `item_exit` double NOT NULL COMMENT 'خروجی کالا'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='مدیریت کالا';

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL COMMENT 'شماره فاکتور',
  `food_name` varchar(255) NOT NULL COMMENT 'نام غذا',
  `quantity` int(11) NOT NULL COMMENT 'تعداد',
  `unit_price` decimal(10,0) NOT NULL COMMENT 'قیمت واحد	',
  `customer_code` varchar(255) NOT NULL COMMENT 'کد مشتری',
  `invoice_number` varchar(255) NOT NULL COMMENT 'شماره فاکتور	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='صدور فاکتور';

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شناسه',
  `category` varchar(255) NOT NULL COMMENT 'دسته‌بندی',
  `food_name` varchar(255) NOT NULL COMMENT 'نام غذا',
  `price` varchar(255) NOT NULL COMMENT 'قیمت ',
  `food_photo` varchar(255) NOT NULL COMMENT 'عکس غذا',
  `requirements` text NOT NULL COMMENT 'نیازمندی‌ها',
  `active` int(1) NOT NULL COMMENT 'فعال',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'تاریخ و ساعت'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='مدیریت منو';

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شناسه ',
  `table_number` int(11) NOT NULL COMMENT 'شماره میز',
  `list_of_orders` varchar(255) NOT NULL COMMENT 'لیست سفارش‌ها',
  `approval` int(11) NOT NULL COMMENT 'تایید کردن',
  `totalsum` decimal(10,0) NOT NULL COMMENT 'جمع کل '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='سفارشات';

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL COMMENT 'شناسه',
  `restaurant_name` varchar(255) NOT NULL COMMENT 'نام رستوران',
  `restaurant_address` varchar(255) NOT NULL COMMENT 'آدرس رستوران',
  `restaurant_phone_number` varchar(255) NOT NULL COMMENT 'شماره تماس رستوران',
  `manager_name` varchar(255) NOT NULL COMMENT 'نام مدیر رستوران',
  `national_id_number` varchar(255) NOT NULL COMMENT 'کد ملی مدیر رستوران',
  `login_username` varchar(255) NOT NULL COMMENT 'نام کاربری جهت ورود',
  `pin_code` varchar(255) NOT NULL COMMENT 'پین کد',
  `password` varchar(255) NOT NULL COMMENT 'رمز عبور جهت ورود',
  `scene` int(1) NOT NULL COMMENT 'جهت ورود'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='کانفیگ سیستم رستوران';

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `restaurant_name`, `restaurant_address`, `restaurant_phone_number`, `manager_name`, `national_id_number`, `login_username`, `pin_code`, `password`, `scene`) VALUES
(1, '', '', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE `table` (
  `id` int(10) UNSIGNED NOT NULL,
  `active` int(255) NOT NULL COMMENT 'وضعیت',
  `address` varchar(255) NOT NULL COMMENT 'آدرس '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='مدیریت میزها';

--
-- Dumping data for table `table`
--

INSERT INTO `table` (`id`, `active`, `address`) VALUES
(1, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(2, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(3, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(4, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(5, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(6, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(7, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(8, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(9, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(10, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(11, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(12, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(13, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(14, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(15, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(16, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(17, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(18, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(19, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(20, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(21, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(22, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(23, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(24, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(25, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(26, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(27, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(28, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(29, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(30, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(31, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(32, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(33, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(34, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(35, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(36, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(37, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(38, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(39, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(40, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(41, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(42, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(43, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(44, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(45, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(46, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(47, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(48, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(49, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php'),
(50, 0, 'http://localhost/sofrehpardaz/Customer/Order_Menu.php');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'شناسه ',
  `name` varchar(255) NOT NULL COMMENT 'نام کاربری',
  `name_login` varchar(255) NOT NULL COMMENT 'نام ورود',
  `password` varchar(255) NOT NULL COMMENT 'رمز ورود',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'تاریخ ',
  `role` varchar(255) NOT NULL COMMENT 'نقش'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='کاربر ها';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_product`
--
ALTER TABLE `add_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_code`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table`
--
ALTER TABLE `table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_product`
--
ALTER TABLE `add_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شماره ثبت', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شناسه', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_code` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'کد مشتری', AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شناسه کالا', AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شماره فاکتور', AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شناسه', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شناسه ', AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `table`
--
ALTER TABLE `table`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'شناسه ', AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
