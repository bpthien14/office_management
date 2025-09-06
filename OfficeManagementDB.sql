-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 05, 2025 at 02:07 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `OfficeManagementDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `DEVICES`
--

CREATE TABLE `DEVICES` (
  `device_id` int NOT NULL,
  `device_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `DEVICES`
--

INSERT INTO `DEVICES` (`device_id`, `device_name`, `description`, `quantity`) VALUES
(1, 'Laptop Dell XPS 13', 'Laptop cho nhân viên kỹ thuật', 10),
(2, 'Laptop MacBook Pro 16', 'Laptop cho quản lý dự án', 10),
(3, 'Máy chiếu Epson EB-S41', 'Máy chiếu phòng họp nhỏ', 10),
(4, 'Máy chiếu Sony VPL-EX575', 'Máy chiếu phòng hội nghị', 10),
(5, 'Điện thoại iPhone 13', 'Điện thoại thử nghiệm ứng dụng', 10),
(6, 'Điện thoại Samsung Galaxy S22', 'Điện thoại test hệ điều hành Android', 10),
(7, 'Tai nghe Sony WH-1000XM4', 'Tai nghe chống ồn dùng cho training', 10),
(8, 'Máy in Canon LBP 2900', 'Máy in văn phòng tầng 2', 10),
(9, 'Máy in HP LaserJet Pro M404dn', 'Máy in văn phòng tầng 3', 10),
(10, 'Máy ảnh Canon EOS 90D', 'Máy ảnh chụp sự kiện công ty', 10),
(11, 'Tablet iPad Air 2022', 'Thiết bị dùng cho trình chiếu', 10),
(12, 'Tablet Samsung Galaxy Tab S8', 'Thiết bị hỗ trợ bán hàng', 10),
(13, 'Bộ micro không dây Shure', 'Dùng trong hội thảo', 10),
(14, 'Loa Bluetooth JBL PartyBox', 'Dùng cho sự kiện ngoài trời', 10),
(15, 'Camera Logitech C920', 'Camera họp trực tuyến', 10);

-- --------------------------------------------------------

--
-- Table structure for table `DEVICE_BORROW`
--

CREATE TABLE `DEVICE_BORROW` (
  `borrow_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `borrow_date` date NOT NULL,
  `expected_return_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approver_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `DEVICE_BORROW`
--

INSERT INTO `DEVICE_BORROW` (`borrow_id`, `employee_id`, `borrow_date`, `expected_return_date`, `return_date`, `status`, `approver_id`) VALUES
(1, 1, '2025-08-01', '2025-08-03', '2025-08-03', 'returned', 6),
(2, 3, '2025-08-02', '2025-08-05', NULL, 'approved', 6),
(3, 4, '2025-08-03', '2025-08-04', NULL, 'pending', NULL),
(4, 5, '2025-08-04', '2025-08-06', '2025-08-06', 'returned', 6),
(5, 6, '2025-08-05', '2025-08-07', NULL, 'approved', 6),
(6, 7, '2025-08-06', '2025-08-08', NULL, 'pending', NULL),
(7, 8, '2025-08-07', '2025-08-09', NULL, 'approved', 6),
(8, 9, '2025-08-08', '2025-08-10', NULL, 'rejected', 6),
(9, 10, '2025-08-09', '2025-08-11', NULL, 'approved', 6),
(10, 2, '2025-08-10', '2025-08-12', '2025-08-12', 'returned', 6);

-- --------------------------------------------------------

--
-- Table structure for table `DEVICE_BORROW_DETAILS`
--

CREATE TABLE `DEVICE_BORROW_DETAILS` (
  `borrow_detail_id` int NOT NULL,
  `borrow_id` int NOT NULL,
  `device_id` int NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `DEVICE_BORROW_DETAILS`
--

INSERT INTO `DEVICE_BORROW_DETAILS` (`borrow_detail_id`, `borrow_id`, `device_id`, `note`) VALUES
(1, 1, 1, 'Mượn laptop Dell để làm việc tại nhà'),
(2, 1, 7, 'Mượn tai nghe phục vụ họp online'),
(3, 2, 2, 'Mượn MacBook cho dự án A'),
(4, 2, 3, 'Mượn máy chiếu cho buổi họp'),
(5, 3, 5, 'Mượn iPhone test ứng dụng'),
(6, 4, 8, 'Mượn máy in Canon dùng gấp'),
(7, 5, 10, 'Mượn máy ảnh chụp sự kiện nội bộ'),
(8, 5, 11, 'Mượn iPad hỗ trợ trình chiếu'),
(9, 6, 6, 'Mượn điện thoại Samsung test app'),
(10, 7, 12, 'Mượn Galaxy Tab demo khách hàng'),
(11, 7, 13, 'Mượn micro Shure cho hội thảo'),
(12, 8, 4, 'Mượn máy chiếu Sony cho phòng họp'),
(13, 9, 14, 'Mượn loa Bluetooth cho team building'),
(14, 9, 15, 'Mượn camera Logitech cho họp trực tuyến'),
(15, 10, 9, 'Mượn máy in HP LaserJet (tạm thời)');

-- --------------------------------------------------------

--
-- Table structure for table `EMPLOYEES`
--

CREATE TABLE `EMPLOYEES` (
  `employee_id` int NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `work_history` json DEFAULT NULL,
  `image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('default','hr','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `EMPLOYEES`
--

INSERT INTO `EMPLOYEES` (`employee_id`, `fullname`, `phone`, `position`, `department`, `supervisor_id`, `user_id`, `work_history`, `image_path`, `role`) VALUES
(1, 'Nguyễn Thị Hoa', '0905123456', 'Nhân viên', 'Kế toán', NULL, 1, '[{\"end_date\": \"2021-12-31\", \"position\": \"Trợ lý kế toán\", \"start_date\": \"2020-01-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Kế toán\", \"start_date\": \"2022-01-01\"}]', 'images/1.jpg', 'default'),
(2, 'Trần Văn Nam', '0906234567', 'Trưởng phòng', 'Nhân sự', NULL, 2, '[{\"end_date\": \"2021-12-31\", \"position\": \"Chuyên viên Nhân sự\", \"start_date\": \"2018-01-01\"}, {\"end_date\": \"\", \"position\": \"Trưởng phòng Nhân sự\", \"start_date\": \"2022-01-01\"}]', 'images/2.jpg', 'hr'),
(3, 'Lê Minh Quân', '0907345678', 'Nhân viên', 'Kỹ thuật', 2, 3, '[{\"end_date\": \"2021-12-31\", \"position\": \"Nhân viên hỗ trợ kỹ thuật\", \"start_date\": \"2019-03-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Kỹ thuật\", \"start_date\": \"2022-01-01\"}]', 'images/3.jpg', 'default'),
(4, 'Phạm Anh Tuấn', '0908456789', 'Nhân viên', 'Kinh doanh', 2, 4, '[{\"end_date\": \"2022-06-30\", \"position\": \"Thực tập sinh Kinh doanh\", \"start_date\": \"2020-06-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Kinh doanh\", \"start_date\": \"2022-07-01\"}]', 'images/4.jpg', 'default'),
(5, 'Đỗ Thị Lan', '0909567890', 'Nhân viên', 'Marketing', 2, 5, '[{\"end_date\": \"2021-05-31\", \"position\": \"Trợ lý Marketing\", \"start_date\": \"2019-05-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Marketing\", \"start_date\": \"2021-06-01\"}]', 'images/5.jpg', 'default'),
(6, 'Ngô Đức Phúc', '0910678901', 'Phó phòng', 'Kỹ thuật', 2, 6, '[{\"end_date\": \"2020-12-31\", \"position\": \"Nhân viên Kỹ thuật\", \"start_date\": \"2017-01-01\"}, {\"end_date\": \"\", \"position\": \"Phó phòng Kỹ thuật\", \"start_date\": \"2021-01-01\"}]', 'images/6.jpg', 'admin'),
(7, 'Vũ Thị Huyền', '0911789012', 'Nhân viên', 'Nhân sự', 2, 7, '[{\"end_date\": \"2021-12-31\", \"position\": \"Trợ lý Nhân sự\", \"start_date\": \"2020-03-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Nhân sự\", \"start_date\": \"2022-01-01\"}]', 'images/7.jpg', 'default'),
(8, 'Đặng Hoàng An', '0912890123', 'Nhân viên', 'Kế toán', 1, 8, '[{\"end_date\": \"2021-12-31\", \"position\": \"Trợ lý Kế toán\", \"start_date\": \"2019-01-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Kế toán\", \"start_date\": \"2022-01-01\"}]', 'images/8.jpg', 'default'),
(9, 'Hoàng Văn Quang', '0913901234', 'Nhân viên', 'Kỹ thuật', 6, 9, '[{\"end_date\": \"2020-12-31\", \"position\": \"Nhân viên Thực tập Kỹ thuật\", \"start_date\": \"2018-09-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Kỹ thuật\", \"start_date\": \"2021-01-01\"}]', 'images/9.jpg', 'default'),
(10, 'Phạm Thị Thảo', '0914902345', 'Nhân viên', 'Marketing', 5, 10, '[{\"end_date\": \"2021-06-30\", \"position\": \"Trợ lý Marketing\", \"start_date\": \"2019-07-01\"}, {\"end_date\": \"\", \"position\": \"Nhân viên Marketing\", \"start_date\": \"2021-07-01\"}]', 'images/10.jpg', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `LEAVE_REQUESTS`
--

CREATE TABLE `LEAVE_REQUESTS` (
  `leave_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `leave_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `details` json DEFAULT NULL,
  `total` decimal(5,2) DEFAULT NULL,
  `reason_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approver_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `LEAVE_REQUESTS`
--

INSERT INTO `LEAVE_REQUESTS` (`leave_id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `details`, `total`, `reason_type`, `description`, `status`, `approver_id`) VALUES
(1, 1, 'Nghỉ phép năm', '2025-08-01', '2025-08-01', '{\"2025-08-01\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 0.50, 'bệnh', 'Đau đầu, cần nghỉ nửa ngày sáng', 'approved', 2),
(2, 3, 'Nghỉ không lương', '2025-08-02', '2025-08-02', '{\"2025-08-02\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.00, 'chăm con nhỏ', 'Con ốm, cần nghỉ cả ngày', 'approved', 2),
(3, 4, 'Nghỉ phép năm', '2025-08-03', '2025-08-04', '{\"2025-08-03\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}, \"2025-08-04\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 1.50, 'khác', 'Đi làm giấy tờ cá nhân', 'pending', NULL),
(4, 5, 'Nghỉ phép năm', '2025-08-05', '2025-08-05', '{\"2025-08-05\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 0.50, 'bệnh', 'Khám răng buổi chiều', 'approved', 2),
(5, 6, 'Nghỉ phép năm', '2025-08-06', '2025-08-07', '{\"2025-08-06\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}, \"2025-08-07\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 2.00, 'về quê', 'Về quê giải quyết việc gia đình', 'approved', 2),
(6, 7, 'Nghỉ không lương', '2025-08-08', '2025-08-08', '{\"2025-08-08\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 0.50, 'khác', 'Tham gia sự kiện cá nhân', 'rejected', 2),
(7, 8, 'Nghỉ phép năm', '2025-08-09', '2025-08-09', '{\"2025-08-09\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.00, 'chăm con nhỏ', 'Đưa con đi tiêm chủng', 'approved', 2),
(8, 9, 'Nghỉ phép năm', '2025-08-10', '2025-08-11', '{\"2025-08-10\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}, \"2025-08-11\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 1.50, 'khác', 'Tham gia khóa học ngắn hạn', 'approved', 6),
(9, 10, 'Nghỉ phép năm', '2025-08-12', '2025-08-12', '{\"2025-08-12\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.00, 'bệnh', 'Ốm sốt cao, cần nghỉ cả ngày', 'approved', 5),
(10, 1, 'Nghỉ phép năm', '2025-08-13', '2025-08-13', '{\"2025-08-13\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 0.50, 'khác', 'Hỗ trợ gia đình việc riêng', 'approved', 2),
(11, 3, 'Nghỉ không lương', '2025-08-14', '2025-08-14', '{\"2025-08-14\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 0.50, 'chăm con nhỏ', 'Con đi khám bệnh', 'pending', NULL),
(12, 4, 'Nghỉ phép năm', '2025-08-15', '2025-08-16', '{\"2025-08-15\": {\"Sáng\": \"x\", \"Chiều\": \"\"}, \"2025-08-16\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.50, 'bệnh', 'Mổ răng khôn, cần nghỉ', 'approved', 2),
(13, 5, 'Nghỉ phép năm', '2025-08-17', '2025-08-17', '{\"2025-08-17\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 0.50, 'khác', 'Tham gia đám cưới người thân', 'approved', 2),
(14, 6, 'Nghỉ không lương', '2025-08-18', '2025-08-18', '{\"2025-08-18\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.00, 'về quê', 'Về quê gấp vì việc gia đình', 'rejected', 2),
(15, 7, 'Nghỉ phép năm', '2025-08-19', '2025-08-19', '{\"2025-08-19\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 0.50, 'khác', 'Đi khám mắt', 'approved', 2),
(16, 8, 'Nghỉ phép năm', '2025-08-20', '2025-08-21', '{\"2025-08-20\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}, \"2025-08-21\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 1.50, 'chăm con nhỏ', 'Con vào lớp 1, cần nghỉ đưa đón', 'approved', 2),
(17, 9, 'Nghỉ không lương', '2025-08-22', '2025-08-22', '{\"2025-08-22\": {\"Sáng\": \"\", \"Chiều\": \"x\"}}', 0.50, 'khác', 'Tham gia sự kiện cá nhân', 'pending', NULL),
(18, 10, 'Nghỉ phép năm', '2025-08-23', '2025-08-24', '{\"2025-08-23\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}, \"2025-08-24\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 1.50, 'về quê', 'Tham gia giỗ tổ gia đình', 'approved', 5),
(19, 1, 'Nghỉ phép năm', '2025-08-25', '2025-08-25', '{\"2025-08-25\": {\"Sáng\": \"x\", \"Chiều\": \"x\"}}', 1.00, 'bệnh', 'Cảm cúm, cần nghỉ cả ngày', 'approved', 2),
(20, 3, 'Nghỉ không lương', '2025-08-26', '2025-08-26', '{\"2025-08-26\": {\"Sáng\": \"x\", \"Chiều\": \"\"}}', 0.50, 'khác', 'Tham gia buổi học thêm', 'rejected', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ROOMS`
--

CREATE TABLE `ROOMS` (
  `room_id` int NOT NULL,
  `room_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('normal','special') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `capacity` int DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ROOMS`
--

INSERT INTO `ROOMS` (`room_id`, `room_name`, `type`, `capacity`, `location`, `status`) VALUES
(1, 'Phòng họp Hoa Sen', 'normal', 10, 'Tầng 2 - Khu A', 'available'),
(2, 'Phòng họp Hoa Mai', 'normal', 8, 'Tầng 2 - Khu A', 'available'),
(3, 'Phòng họp Hoa Đào', 'normal', 12, 'Tầng 3 - Khu B', 'available'),
(4, 'Phòng họp Hoa Cúc', 'normal', 6, 'Tầng 1 - Khu B', 'available'),
(5, 'Phòng họp Hoa Hồng', 'normal', 15, 'Tầng 4 - Khu A', 'available'),
(6, 'Phòng họp Tre Việt', 'special', 30, 'Tầng 5 - Khu Hội nghị', 'available'),
(7, 'Phòng họp Trúc Xanh', 'special', 25, 'Tầng 5 - Khu Hội nghị', 'available'),
(8, 'Phòng họp Bình Minh', 'normal', 20, 'Tầng 3 - Khu A', 'unavailable'),
(9, 'Phòng họp Hoàng Hôn', 'normal', 18, 'Tầng 4 - Khu B', 'available'),
(10, 'Phòng họp Đại Dương', 'special', 40, 'Tầng 6 - Khu Hội nghị', 'available'),
(11, 'Phòng họp Sông Hồng', 'normal', 14, 'Tầng 2 - Khu C', 'available'),
(12, 'Phòng họp Mekong', 'special', 50, 'Tầng 7 - Khu Hội nghị', 'available'),
(13, 'Phòng họp Núi Rừng', 'normal', 16, 'Tầng 3 - Khu C', 'unavailable'),
(14, 'Phòng họp Biển Xanh', 'normal', 12, 'Tầng 2 - Khu D', 'available'),
(15, 'Phòng họp Ánh Trăng', 'normal', 8, 'Tầng 1 - Khu D', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `ROOM_BOOKING`
--

CREATE TABLE `ROOM_BOOKING` (
  `booking_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `room_id` int NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approver_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

CREATE TABLE `USERS` (
  `user_id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `USERS`
--

INSERT INTO `USERS` (`user_id`, `email`, `password`, `status`) VALUES
(1, 'nguyen.hoa@example.com', '123456', 'active'),
(2, 'tran.nam@example.com', '123456', 'active'),
(3, 'le.minh@example.com', '123456', 'active'),
(4, 'pham.tuan@example.com', '123456', 'active'),
(5, 'do.lan@example.com', '123456', 'active'),
(6, 'ngo.phuc@example.com', '123456', 'active'),
(7, 'vu.huyen@example.com', '123456', 'active'),
(8, 'dang.an@example.com', '123456', 'active'),
(9, 'hoang.quang@example.com', '123456', 'active'),
(10, 'pham.thao@example.com', '123456', 'active'),
(11, 'truong.kien@example.com', '123456', 'active'),
(12, 'bui.linh@example.com', '123456', 'active'),
(13, 'tran.hai@example.com', '123456', 'active'),
(14, 'ngo.anh@example.com', '123456', 'active'),
(15, 'le.yen@example.com', '123456', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DEVICES`
--
ALTER TABLE `DEVICES`
  ADD PRIMARY KEY (`device_id`);

--
-- Indexes for table `DEVICE_BORROW`
--
ALTER TABLE `DEVICE_BORROW`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `fk_borrow_employee` (`employee_id`),
  ADD KEY `fk_borrow_approver` (`approver_id`);

--
-- Indexes for table `DEVICE_BORROW_DETAILS`
--
ALTER TABLE `DEVICE_BORROW_DETAILS`
  ADD PRIMARY KEY (`borrow_detail_id`),
  ADD KEY `fk_borrow_details_borrow` (`borrow_id`),
  ADD KEY `fk_borrow_details_device` (`device_id`);

--
-- Indexes for table `EMPLOYEES`
--
ALTER TABLE `EMPLOYEES`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_employee_supervisor` (`supervisor_id`);

--
-- Indexes for table `LEAVE_REQUESTS`
--
ALTER TABLE `LEAVE_REQUESTS`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `fk_leave_employee` (`employee_id`),
  ADD KEY `fk_leave_approver` (`approver_id`);

--
-- Indexes for table `ROOMS`
--
ALTER TABLE `ROOMS`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `ROOM_BOOKING`
--
ALTER TABLE `ROOM_BOOKING`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_booking_employee` (`employee_id`),
  ADD KEY `fk_booking_room` (`room_id`),
  ADD KEY `fk_booking_approver` (`approver_id`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DEVICES`
--
ALTER TABLE `DEVICES`
  MODIFY `device_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `DEVICE_BORROW`
--
ALTER TABLE `DEVICE_BORROW`
  MODIFY `borrow_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `DEVICE_BORROW_DETAILS`
--
ALTER TABLE `DEVICE_BORROW_DETAILS`
  MODIFY `borrow_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `EMPLOYEES`
--
ALTER TABLE `EMPLOYEES`
  MODIFY `employee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `LEAVE_REQUESTS`
--
ALTER TABLE `LEAVE_REQUESTS`
  MODIFY `leave_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ROOMS`
--
ALTER TABLE `ROOMS`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ROOM_BOOKING`
--
ALTER TABLE `ROOM_BOOKING`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USERS`
--
ALTER TABLE `USERS`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DEVICE_BORROW`
--
ALTER TABLE `DEVICE_BORROW`
  ADD CONSTRAINT `fk_borrow_approver` FOREIGN KEY (`approver_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_borrow_employee` FOREIGN KEY (`employee_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `DEVICE_BORROW_DETAILS`
--
ALTER TABLE `DEVICE_BORROW_DETAILS`
  ADD CONSTRAINT `fk_borrow_details_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `DEVICE_BORROW` (`borrow_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_borrow_details_device` FOREIGN KEY (`device_id`) REFERENCES `DEVICES` (`device_id`) ON DELETE CASCADE;

--
-- Constraints for table `EMPLOYEES`
--
ALTER TABLE `EMPLOYEES`
  ADD CONSTRAINT `fk_employee_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_employee_user` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `LEAVE_REQUESTS`
--
ALTER TABLE `LEAVE_REQUESTS`
  ADD CONSTRAINT `fk_leave_approver` FOREIGN KEY (`approver_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_leave_employee` FOREIGN KEY (`employee_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `ROOM_BOOKING`
--
ALTER TABLE `ROOM_BOOKING`
  ADD CONSTRAINT `fk_booking_approver` FOREIGN KEY (`approver_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_booking_employee` FOREIGN KEY (`employee_id`) REFERENCES `EMPLOYEES` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `ROOMS` (`room_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
