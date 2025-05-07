-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 06, 2024 lúc 02:48 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `pepicase`
--
CREATE DATABASE IF NOT EXISTS `pepicase` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pepicase`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `Total_Amount` int(5) NOT NULL,
  `Total_Price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`ID`, `User_ID`, `Total_Amount`, `Total_Price`) VALUES
(1, 13, 3, 29.97),
(2, 9, 2, 19.98),
(3, 10, 3, 29.97),
(4, 16, 2, 19.98),
(5, 15, 1, 9.99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_details`
--

CREATE TABLE `cart_details` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `Product_ID` int(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Size` text DEFAULT NULL,
  `Quantity` int(5) NOT NULL,
  `Price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_details`
--

INSERT INTO `cart_details` (`ID`, `User_ID`, `Product_ID`, `Name`, `Size`, `Quantity`, `Price`) VALUES
(1, 10, 1, 'Cinnamonroll', 'iPhone 12', 1, 9.99),
(2, 13, 16, 'My Melody', 'iPhone 11', 1, 9.99),
(3, 13, 23, 'Pochacco', 'iPhone 13 Pro', 1, 9.99),
(4, 10, 9, 'Pompompurin', 'iPhone 12 Pro Max', 1, 9.99),
(5, 16, 1, 'Cinnamonroll', 'iPhone 14', 2, 9.99),
(6, 10, 7, 'Cinnamonroll', 'iPhone 14 Pro Max', 1, 9.99),
(7, 15, 1, 'Cinnamonroll', 'iPhone 15', 1, 9.99),
(8, 9, 18, 'My Melody', 'iPhone 15 Pro', 2, 9.99),
(9, 13, 17, 'My-melody', 'iPhone 11', 1, 9.99);

--
-- Bẫy `cart_details`
--
DELIMITER $$
CREATE TRIGGER `after_cart_details_delete` AFTER DELETE ON `cart_details` FOR EACH ROW BEGIN
    DECLARE total_quantity INT;
    DECLARE total_price FLOAT;

    -- Tính tổng số lượng và tổng giá trị cho người dùng
    SELECT SUM(Quantity), SUM(Quantity * Price) INTO total_quantity, total_price
    FROM cart_details
    WHERE User_ID = OLD.User_ID;

    -- Cập nhật bảng cart
    UPDATE cart
    SET Total_Amount = total_quantity, Total_Price = total_price
    WHERE User_ID = OLD.User_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_cart_details_insert` AFTER INSERT ON `cart_details` FOR EACH ROW BEGIN
    DECLARE total_quantity INT;
    DECLARE total_price FLOAT;

    -- Tính tổng số lượng và tổng giá trị cho người dùng
    SELECT SUM(Quantity), SUM(Quantity * Price) INTO total_quantity, total_price
    FROM cart_details
    WHERE User_ID = NEW.User_ID;

    -- Cập nhật bảng cart
    UPDATE cart
    SET Total_Amount = total_quantity, Total_Price = total_price
    WHERE User_ID = NEW.User_ID;
    
    -- Cập nhật tên và giá

    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_cart_details_update` AFTER UPDATE ON `cart_details` FOR EACH ROW BEGIN
    DECLARE total_quantity INT;
    DECLARE total_price FLOAT;

    -- Tính tổng số lượng và tổng giá trị cho người dùng
    SELECT SUM(Quantity), SUM(Quantity * Price) INTO total_quantity, total_price
    FROM cart_details
    WHERE User_ID = NEW.User_ID;

    -- Cập nhật bảng cart
    UPDATE cart
    SET Total_Amount = total_quantity, Total_Price = total_price
    WHERE User_ID = NEW.User_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collection`
--

CREATE TABLE `collection` (
  `ID` int(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `collection`
--

INSERT INTO `collection` (`ID`, `Name`, `Image`) VALUES
(1, 'Cinamonroll', '/pepicase/public/collection-pics/cinamonroll.svg'),
(2, 'Pompompurin', '/pepicase/public/collection-pics/pompompurin.svg'),
(3, 'My Melody', '/pepicase/public/collection-pics/my melody.svg'),
(4, 'Pochacco', '/pepicase/public/collection-pics/pochacco.svg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `color`
--

CREATE TABLE `color` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `color`
--

INSERT INTO `color` (`ID`, `Name`) VALUES
(1, 'White'),
(2, 'Clear'),
(3, 'Yellow'),
(4, 'Blue'),
(5, 'Pink'),
(6, 'Multicolor');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `delivery`
--

CREATE TABLE `delivery` (
  `Invoice_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `Apartment` varchar(50) DEFAULT NULL,
  `Country` varchar(50) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Zipcode` text DEFAULT NULL,
  `Phone` text NOT NULL,
  `Shipping_Method` varchar(50) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `delivery`
--

INSERT INTO `delivery` (`Invoice_ID`, `First_Name`, `Last_Name`, `Address`, `Apartment`, `Country`, `City`, `Zipcode`, `Phone`, `Shipping_Method`, `Status`) VALUES
(1, 'Ngọc ', 'Nam', 'Khu phố 6', NULL, 'Việt Nam', 'TP Pleiku, Gia Lai', '600000', '0954673824', 'Express', 1),
(2, 'Ngọc', 'Nữ', 'Khu phố 5', NULL, 'Việt Nam', 'Thanh Hóa', '440000', '0946374633', 'Standard', 0),
(3, 'Minh ', 'Sang', '105 đường Tân Lập', NULL, 'Việt Nam', 'Nghệ An', '460000', '0947382913', 'Standard', 1),
(4, 'Hạnh', 'Nguyễn', '123 đường Võ Nguyên Giáp', NULL, 'Việt Nam', 'Kon Tum', '580000', '0943849302', 'Express', 2),
(5, 'Quang', 'Đạt', 'Khu phố 6, phường Linh Trung', 'KTX khu B', 'Việt Nam', 'TP HCM', '700000', '0934591749', 'Express', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedback`
--

CREATE TABLE `feedback` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `Product_ID` int(20) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Comment` text NOT NULL,
  `Star` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedback`
--

INSERT INTO `feedback` (`ID`, `User_ID`, `Product_ID`, `Created_At`, `Comment`, `Star`) VALUES
(1, 11, 4, '2024-05-09 09:02:03', 'Đẹp quá', 5),
(2, 9, 19, '2024-05-09 09:02:03', 'Thiết kế đẹp', 5),
(3, 13, 19, '2024-05-09 09:02:03', 'Giao hàng nhanh', 5),
(4, 18, 19, '2024-05-09 09:02:03', 'Sản phẩm bị vỡ', 2),
(5, 13, 9, '2024-05-09 09:02:03', 'Giao nhanh', 5),
(6, 12, 16, '2024-05-09 14:41:06', 'Ốp vừa vặn', 5),
(7, 15, 20, '2024-05-09 14:41:06', 'Ốp tạm được', 3),
(8, 16, 21, '2024-05-09 14:41:06', 'Ốp 5quá', 5),
(9, 12, 18, '2024-05-09 14:41:06', 'Giao nhanh chóng', 5),
(10, 10, 16, '2024-05-09 14:41:06', 'Mẫu mã đẹp', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoice`
--

CREATE TABLE `invoice` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `Voucher_ID` int(20) DEFAULT NULL,
  `Method` varchar(20) NOT NULL,
  `Method_ID` int(11) DEFAULT NULL,
  `Order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Note` varchar(100) DEFAULT NULL,
  `Total_Price` float(10,2) NOT NULL,
  `Actual_Price` float(10,2) NOT NULL,
  `Status` tinyint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoice`
--

INSERT INTO `invoice` (`ID`, `User_ID`, `Voucher_ID`, `Method`, `Method_ID`, `Order_date`, `Note`, `Total_Price`, `Actual_Price`, `Status`) VALUES
(1, 9, 1, 'Cash', NULL, '2024-05-09 17:03:36', NULL, 9.99, 7.99, 1),
(2, 10, NULL, 'Cash', NULL, '2024-05-09 17:03:36', NULL, 19.98, 19.98, 0),
(3, 13, 2, 'Cash', NULL, '2024-05-09 17:03:36', NULL, 19.98, 17.98, 1),
(4, 14, 3, 'Cash', NULL, '2024-05-09 17:03:36', NULL, 29.97, 29.07, 0),
(5, 16, 2, 'Cash', NULL, '2024-05-09 17:03:36', NULL, 9.99, 8.99, 1),
(6, 12, 2, 'Cash', NULL, '2024-06-02 03:09:40', NULL, 120.00, 100.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoice_details`
--

CREATE TABLE `invoice_details` (
  `ID` int(20) NOT NULL,
  `Invoice_ID` int(20) NOT NULL,
  `Product_ID` int(20) NOT NULL,
  `Name_Product` varchar(50) DEFAULT NULL,
  `Size` varchar(20) NOT NULL,
  `Quantity` int(5) NOT NULL,
  `Price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoice_details`
--

INSERT INTO `invoice_details` (`ID`, `Invoice_ID`, `Product_ID`, `Name_Product`, `Size`, `Quantity`, `Price`) VALUES
(1, 1, 6, 'Cinnamonroll', 'iPhone 11', 1, 9.99),
(2, 2, 16, 'My Melody', 'iPhone 12', 1, 9.99),
(3, 2, 26, 'Pochaco', 'iPhone 12 Pro Max', 1, 9.99),
(4, 3, 2, 'Cinnamoroll', 'iPhone 11 Pro', 1, 9.99),
(5, 3, 15, 'My Melody', 'iPhone 13', 1, 9.99),
(6, 4, 3, 'Sandra Iphone', 'iPhone 14 Pro Max', 1, 9.99),
(7, 4, 18, 'My Melody', 'iPhone 12 Mini', 1, 9.99),
(8, 4, 24, 'Pochacco_Cat', 'iPhone 15 Pro', 1, 9.99),
(10, 5, 25, 'Chick Iphone', 'iPhone 15 Pro Max', 1, 9.99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `ID` int(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Price` float(10,2) NOT NULL,
  `Description` text NOT NULL,
  `Image` text NOT NULL,
  `QuantityInStock` int(5) NOT NULL,
  `IsInStock` tinyint(2) NOT NULL,
  `IsDeleted` tinyint(2) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Color_ID` int(11) NOT NULL,
  `Collect_ID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`ID`, `Name`, `Price`, `Description`, `Image`, `QuantityInStock`, `IsInStock`, `IsDeleted`, `Created_At`, `Updated_At`, `Color_ID`, `Collect_ID`) VALUES
(1, 'Cinnamonroll_Ice cream Kawaii Iphone Case', 7.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/1.svg', 1000, 1, 0, '2024-05-13 21:05:19', '2024-05-13 14:05:19', 6, 1),
(2, 'Cinnamonroll_Kawaii Iphone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/2.svg', 800, 1, 0, '2024-05-13 21:05:19', '2024-05-13 14:05:19', 4, 1),
(3, 'Cinnamonroll_Sandra Iphone Case', 8.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/3.svg', 1000, 1, 0, '2024-05-13 21:05:19', '2024-05-13 14:05:19', 6, 1),
(4, 'Cinnamonroll_Yummy Iphone Case', 6.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/4.svg', 800, 1, 0, '2024-05-31 17:45:04', '2024-05-31 10:45:04', 2, 1),
(5, 'Cinnamonroll_Biubiu case Iphone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/5.svg', 800, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 2, 1),
(6, 'Cinnamonroll_Bubble Tea Iphone Case', 7.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/6.svg', 750, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 2, 1),
(7, 'Cinnamonroll_Clear Case Iphone Case', 8.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/cinnamonroll/7.svg', 980, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 2, 1),
(8, 'Pompompurin_Bubble iPhone Case ', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/1.svg', 764, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(9, 'Pompompurin_Candy iPhone Case', 5.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/2.svg', 984, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(10, 'Pompompurin_Chef iPhone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/3.svg', 789, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(11, 'Pompompurin_Chocolate iPhone Case ', 8.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/4.svg', 987, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(12, 'Pompompurin_Circus iPhone Case ', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/5.svg', 943, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(13, 'Pompompurin_Flan iPhone Case ', 11.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/6.svg', 547, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(14, 'Pompompurin_Honey iPhone Case ', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pompompurin/7.svg', 984, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 3, 2),
(15, 'My Melody_ Cute Bunnys Iphone Case ', 8.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/1.svg', 432, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 5, 3),
(16, 'My Melody_Best Friend Iphone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/2.svg', 475, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 5, 3),
(17, 'My Melody_Bubble Tea Iphone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/3.svg', 453, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 5, 3),
(18, 'My Melody_Cute Iphone Case', 6.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/4.svg', 543, 1, 0, '2024-05-13 21:22:01', '2024-05-13 14:22:01', 5, 3),
(19, 'My Melody_Cute Smile Iphone Case ', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/5.svg', 322, 1, 0, '2024-05-13 21:23:37', '2024-05-13 14:23:37', 5, 3),
(20, 'My Melody_Kuromi Iphone Case', 7.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/6.svg', 543, 1, 0, '2024-05-13 21:25:08', '2024-05-13 14:25:08', 5, 3),
(21, 'My Melody_Kuromi Soft Silicone Iphone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/mymelody/7.svg', 432, 1, 0, '2024-05-13 21:25:14', '2024-05-13 14:25:14', 3, 3),
(22, 'Pochacco_Banana iPhone Case ', 11.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/1.svg', 544, 1, 0, '2024-05-13 21:25:18', '2024-05-13 14:25:18', 4, 4),
(23, 'Pochacco_Carrot iPhone Case', 6.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/2.svg', 654, 1, 0, '2024-05-13 21:25:26', '2024-05-13 14:25:26', 4, 4),
(24, 'Pochacco_Cat iPhone Case', 5.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/3.svg', 754, 1, 0, '2024-05-13 21:26:20', '2024-05-13 14:26:20', 4, 4),
(25, 'Pochacco_Chick iPhone Case ', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/4.svg', 543, 1, 0, '2024-05-13 21:25:34', '2024-05-13 14:25:34', 4, 4),
(26, 'Pochacco_Duck iPhone Case', 11.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/5.svg', 768, 1, 0, '2024-05-18 20:26:26', '2024-05-18 13:26:26', 4, 4),
(27, 'Pochacco_Friends iPhone Case', 9.99, 'Đây là ốp lưng từ pepicase', '/pepicase/public/product-pics/pochacco/6.svg', 633, 1, 0, '2024-05-18 21:10:23', '2024-05-18 14:10:23', 4, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `ID` int(20) NOT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Email` varchar(35) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Is_Admin` tinyint(1) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oauth_id` varchar(20) DEFAULT NULL,
  `Facebook_ID` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`ID`, `User_Name`, `Email`, `Password`, `Is_Admin`, `Created_At`, `oauth_id`, `Facebook_ID`) VALUES
(1, NULL, 'datnguyen27@gmail.com', 'Aa123456789', 1, '2024-05-09 07:18:53', NULL, NULL),
(2, NULL, 'huy1234@gmail.com', 'Anh123456789', 1, '2024-05-10 03:37:48', NULL, NULL),
(3, NULL, 'conghau1101@gmail.com', 'Hau123456789', 1, '2024-05-09 07:19:01', NULL, NULL),
(4, NULL, 'xuanquynh298@gmail.com', 'Quynh12345', 1, '2024-05-09 07:19:04', NULL, NULL),
(5, NULL, 'quocbao02@gmail.com', 'Baone12345', 1, '2024-05-10 03:36:11', NULL, NULL),
(6, NULL, 'phamha123@gmail.com', 'Ha123456789', 1, '2024-05-10 03:36:16', NULL, NULL),
(7, NULL, 'thuckhanh01@gmail.com', 'Khanh1234567', 1, '2024-05-10 03:36:30', NULL, NULL),
(8, NULL, 'tuvu123@gmail.com', 'Tu0567890', 0, '2024-05-07 07:47:00', NULL, NULL),
(9, NULL, 'ngocnam234@gmail.com', 'Nam78912345', 0, '2024-05-07 07:47:00', NULL, NULL),
(10, NULL, 'ngocnu345@gmail.com', 'Nu2345678', 0, '2024-05-07 07:47:00', NULL, NULL),
(11, NULL, 'minhluan456@gmail.com', 'Luan78912', 0, '2024-05-07 07:47:00', NULL, NULL),
(12, NULL, 'duongtung789@gmail.com', 'Tung567890', 0, '2024-05-07 07:47:00', NULL, NULL),
(13, NULL, 'minhsang543@gmail.com', 'Sang17890', 0, '2024-05-07 07:47:00', NULL, NULL),
(14, NULL, 'hanhnguyen123@gmail.com', 'Hanh123456', 0, '2024-05-07 07:47:00', NULL, NULL),
(15, NULL, 'hoanglong456@gmail.com', 'Long123456789', 0, '2024-05-07 07:47:00', NULL, NULL),
(16, NULL, 'abc789@gmail.com', 'Abc123456789', 0, '2024-05-07 07:47:00', NULL, NULL),
(17, NULL, 'def678@gmail.com', 'Def123456', 0, '2024-05-07 07:47:00', NULL, NULL),
(18, NULL, 'nhom14@gmail.com', 'Nhom12345', 0, '2024-05-07 07:47:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_info`
--

CREATE TABLE `user_info` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Area_Code` varchar(5) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Apartment` varchar(50) DEFAULT NULL,
  `Country` varchar(20) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Zipcode` varchar(20) NOT NULL,
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_info`
--

INSERT INTO `user_info` (`ID`, `User_ID`, `First_Name`, `Last_Name`, `Area_Code`, `Phone`, `Address`, `Apartment`, `Country`, `City`, `Zipcode`, `Updated_At`) VALUES
(1, 1, 'Dat', 'Nguyen', '+84', '0345343343', 'đường Mạc Đĩnh Chi,phường 6', NULL, 'Việt Nam', 'Cần Thơ', '102345', '2024-05-09 07:45:25'),
(2, 2, 'Huy', 'Nguyễn', '+84', '0933433334', 'đường số 8, phường Long Mỹ', NULL, 'Việt Nam', 'Đà Lạt', '344232', '2024-05-09 07:45:25'),
(3, 3, 'Hậu', 'Nguyễn', '+84', '0943574678', 'đường số 10, phường Hà Tiên', NULL, 'Việt Nam', 'Long Xuyên', '346793', '2024-05-09 07:45:25'),
(4, 4, 'Quỳnh', 'Xuân', '+84', '0943756479', 'đường Chu Văn An, phường Linh Trung', NULL, 'Việt Nam', 'Thủ Đức', '874523', '2024-05-09 07:45:25'),
(5, 5, 'Bảo', 'Nguyễn', '+84', '0934526746', 'đường Lương Thế Vinh, phường Tân Lập', NULL, 'Việt Nam', 'Hồ Chí Minh', '536124', '2024-05-09 07:45:25'),
(6, 6, 'Hà', 'Phạm', '+84', '0945574638', 'đường số 2, phường An Bình', NULL, 'Việt Nam', 'Cao Lãnh', '098361', '2024-05-09 07:45:25'),
(7, 7, 'Khanh', 'Thục', '+84', '0946574893', 'đường Lê Duẩn,phường 11', NULL, 'Việt Nam', 'Hà Nội', '432589', '2024-05-09 07:45:25'),
(8, 8, 'Vũ', 'Tú', '+84', '0456473895', 'đường Lê Thái Tổ, phường 5', NULL, 'Việt Nam', 'Đà Nẵng', '324622', '2024-05-09 07:45:25'),
(9, 9, 'Nam', 'Ngọc', '+84', '094758493', 'đường Phạm Ngũ Lão, phường Linh Tây', NULL, 'Việt Nam', 'Quảng Ninh', '243455', '2024-05-09 07:45:25'),
(10, 10, 'Nữ', 'Ngọc', '+84', '094758393', 'đường Điện Biên Phủ, phường 10', NULL, 'Việt Nam', 'Ngã Bảy', '980634', '2024-05-09 07:45:25'),
(11, 11, 'Luân', 'Minh', '+84', '0946378432', 'Khu phố 2, phường Tân Hòa', NULL, 'Việt Nam', 'Biên Hòa', '978431', '2024-05-10 02:12:52'),
(12, 12, 'Tùng', 'Dương', '+84', '09465376283', 'Đường số 8, phường Mĩ Thạnh', NULL, 'Việt Nam', 'Vũng Tàu', '324692', '2024-05-10 02:12:52'),
(13, 13, 'Sang', 'Minh', '+84', '0946736743', 'Đường Mạc Đĩnh Chi, khu phố Tân Hòa', 'KTX khu B', 'Việt Nam', 'Thanh Hóa', '479332', '2024-05-10 02:12:52'),
(14, 14, 'Hạnh', 'Nguyễn', '+84', '0564783749', 'Đường Đinh Tiên Hoàng, phường 5', NULL, 'Việt Nam', 'Hải Dương', '541245', '2024-05-10 02:12:52'),
(15, 15, 'Long', 'Hoàng', '+84', '0984638821', 'Khu phố 3', NULL, 'Việt Nam', 'Phan Thiết', '763423', '2024-05-10 02:12:52'),
(16, 16, 'abc', 'Kiên', '+84', '0946376472', 'Đường Võ Nguyên Giáp, phường Hà Nam', NULL, 'Việt Nam', 'Hà Tiên', '235234', '2024-05-10 02:12:52'),
(17, 17, 'Xuân', 'Minh', '+84', '0946378432', 'Khu phố 3, phường 11', NULL, 'Việt Nam', 'Châu Đốc', '375473', '2024-05-10 02:14:43'),
(18, 18, 'Hoa', 'Kim', '+84', '0946736742', 'Khu phố 6, phường 1', NULL, 'Việt Nam', 'Tây Ninh', '823346', '2024-05-10 02:14:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `voucher`
--

CREATE TABLE `voucher` (
  `ID` int(20) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Discount_Type` int(20) DEFAULT NULL,
  `Discount_Value` decimal(10,2) NOT NULL,
  `Max_Usage` int(200) NOT NULL,
  `Current_Usage` int(200) NOT NULL,
  `Start_Date` datetime NOT NULL,
  `End_Date` datetime NOT NULL,
  `Create_At` datetime NOT NULL,
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Deleted_At` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `voucher`
--

INSERT INTO `voucher` (`ID`, `Name`, `Discount_Type`, `Discount_Value`, `Max_Usage`, `Current_Usage`, `Start_Date`, `End_Date`, `Create_At`, `Updated_At`, `Deleted_At`) VALUES
(1, 'PEPI20', NULL, 0.20, 100, 4, '2024-05-09 16:26:30', '2024-06-13 21:26:30', '2024-05-09 21:31:57', '2024-05-09 14:31:57', '2024-06-14 21:26:30'),
(2, 'PEPI10', NULL, 0.10, 200, 12, '2024-05-09 16:26:30', '2024-06-21 21:26:30', '2024-05-09 21:31:57', '2024-05-09 14:31:57', '2024-06-21 21:26:30'),
(3, 'PEPI05', NULL, 0.05, 250, 10, '2024-05-09 16:26:30', '2024-06-13 21:28:13', '2024-05-09 21:31:57', '2024-05-09 14:31:57', '2024-06-14 21:28:13'),
(4, 'PEPI03', NULL, 0.03, 300, 21, '2024-05-09 16:26:30', '2024-06-26 21:28:13', '2024-05-09 21:31:57', '2024-05-09 14:31:57', '2024-06-27 21:28:13'),
(5, 'PEPI08', NULL, 0.08, 250, 10, '2024-05-09 16:26:30', '2024-05-31 21:28:13', '2024-05-09 21:31:57', '2024-05-09 14:31:57', '2024-06-02 21:28:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist`
--

CREATE TABLE `wishlist` (
  `ID` int(20) NOT NULL,
  `User_ID` int(20) NOT NULL,
  `Product_ID` int(20) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlist`
--

INSERT INTO `wishlist` (`ID`, `User_ID`, `Product_ID`, `Created_At`, `Updated_At`) VALUES
(1, 10, 17, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(2, 10, 2, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(3, 12, 25, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(4, 11, 18, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(5, 11, 25, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(6, 15, 25, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(7, 9, 18, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(8, 9, 8, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(9, 16, 24, '2024-05-13 13:31:37', '2024-05-13 13:31:37'),
(10, 17, 6, '2024-05-13 13:31:37', '2024-05-13 13:31:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Chỉ mục cho bảng `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Chỉ mục cho bảng `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`Invoice_ID`);

--
-- Chỉ mục cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Chỉ mục cho bảng `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Voucher_ID` (`Voucher_ID`);

--
-- Chỉ mục cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Invoice_ID` (`Invoice_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Collect_ID` (`Collect_ID`),
  ADD KEY `Color_ID` (`Color_ID`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Chỉ mục cho bảng `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `collection`
--
ALTER TABLE `collection`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `color`
--
ALTER TABLE `color`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `delivery`
--
ALTER TABLE `delivery`
  MODIFY `Invoice_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `feedback`
--
ALTER TABLE `feedback`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `invoice`
--
ALTER TABLE `invoice`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `user_info`
--
ALTER TABLE `user_info`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `voucher`
--
ALTER TABLE `voucher`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Các ràng buộc cho bảng `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `cart_details_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`ID`),
  ADD CONSTRAINT `cart_details_ibfk_3` FOREIGN KEY (`User_ID`) REFERENCES `cart` (`User_ID`);

--
-- Các ràng buộc cho bảng `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`ID`);

--
-- Các ràng buộc cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`ID`);

--
-- Các ràng buộc cho bảng `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`Voucher_ID`) REFERENCES `voucher` (`ID`);

--
-- Các ràng buộc cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_ibfk_1` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`ID`),
  ADD CONSTRAINT `invoice_details_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`ID`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`Collect_ID`) REFERENCES `collection` (`ID`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`Color_ID`) REFERENCES `color` (`ID`);

--
-- Các ràng buộc cho bảng `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Các ràng buộc cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
