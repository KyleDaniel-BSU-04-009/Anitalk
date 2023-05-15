-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2023 at 10:21 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anitalk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_comment`
--

CREATE TABLE `tb_comment` (
  `postid` varchar(36) NOT NULL,
  `commenttext` longtext NOT NULL,
  `usercommenterid` int(11) NOT NULL,
  `commentid` varchar(36) NOT NULL,
  `dateposted` varchar(64) NOT NULL,
  `datedetails` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_comment`
--

INSERT INTO `tb_comment` (`postid`, `commenttext`, `usercommenterid`, `commentid`, `dateposted`, `datedetails`) VALUES
('2535496f-91c6-4e74-a2b8-0ae204513e7b', 'This is the first comment!', 25, 'a3888288-08f3-4e69-af4d-95530346b76f', '04/05/23', '10:50:20 AM'),
('2535496f-91c6-4e74-a2b8-0ae204513e7b', 'This is the second comment!', 25, '7600892f-5f9e-47ee-8bfb-10ad909b24ee', '04/05/23', '10:50:28 AM'),
('2535496f-91c6-4e74-a2b8-0ae204513e7b', 'This might be the last comment..', 25, '4dfaccae-f80b-4988-ae42-a4fc38e26ab1', '04/05/23', '10:50:56 AM'),
('2535496f-91c6-4e74-a2b8-0ae204513e7b', 'A comment made by another user', 26, 'c98fc857-03a6-4aac-aafc-cf142243a8cb', '04/05/23', '10:53:15 AM'),
('2535496f-91c6-4e74-a2b8-0ae204513e7b', ' A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.  A long comment.', 26, '32f96a2a-e5aa-4326-822a-54219102cecf', '04/05/23', '10:53:46 AM'),
('d3d384e6-9f75-407b-9fd2-cc1dbd57020f', 'tggewrg', 27, '06a9033c-293e-4e91-b7b3-29d4eba0088e', '05/05/23', '02:05:22 PM'),
('d3d384e6-9f75-407b-9fd2-cc1dbd57020f', 'wefewfew', 27, '3924d0ec-a3f3-4873-8339-bb45e3599f6b', '05/05/23', '02:05:23 PM'),
('c903948b-f2ea-464b-974c-5190a26298e5', 'this is a new comment', 28, 'a81baa84-bae9-4d3b-aceb-c24c85730410', '09/05/23', '11:20:50 PM'),
('c98987ac-e018-4704-9725-24f03858e9ea', 'chfthtrhtr', 25, '196511c5-9ed0-44d0-99b6-356ac91834db', '10/05/23', '01:33:39 AM'),
('c98987ac-e018-4704-9725-24f03858e9ea', 'sdgsgs', 25, 'e979a927-ee0b-4adf-b7f2-2726b477657e', '10/05/23', '01:33:40 AM'),
('c98987ac-e018-4704-9725-24f03858e9ea', 'xcvxx', 25, '57765bcf-c99a-4df7-9355-ca255f1aaf18', '10/05/23', '01:33:42 AM'),
('c98987ac-e018-4704-9725-24f03858e9ea', 'SUPPPPPPP', 25, '16797a22-e20f-4d4b-82ab-70eb5be8b577', '10/05/23', '01:33:45 AM');

-- --------------------------------------------------------

--
-- Table structure for table `tb_like`
--

CREATE TABLE `tb_like` (
  `likedpost` varchar(36) NOT NULL,
  `likedby` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_like`
--

INSERT INTO `tb_like` (`likedpost`, `likedby`) VALUES
('1efbe881-f5e9-4b8c-aa3e-f739e8f9b147', 26),
('08e0c9e4-24a6-4a7f-aace-fd0eadad6061', 25);

-- --------------------------------------------------------

--
-- Table structure for table `tb_post`
--

CREATE TABLE `tb_post` (
  `postid` varchar(36) NOT NULL,
  `userpostid` int(11) NOT NULL,
  `has_image` tinyint(4) NOT NULL,
  `animetag` varchar(256) NOT NULL,
  `image` longtext DEFAULT NULL,
  `posttext` longtext NOT NULL,
  `posttag` varchar(50) NOT NULL,
  `datedetails` varchar(64) NOT NULL,
  `dateposted` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_post`
--

INSERT INTO `tb_post` (`postid`, `userpostid`, `has_image`, `animetag`, `image`, `posttext`, `posttag`, `datedetails`, `dateposted`) VALUES
('4956d090-2934-4a4d-a57e-5decb8cfb069', 25, 0, '178b291d-831e-438d-be79-a74e9b8b19dd', '', 'Short post test', 'review', '10:48:58 AM', '04/05/23'),
('166369aa-9f1a-48d7-9395-4edcdb4798c4', 25, 0, '178b291d-831e-438d-be79-a74e9b8b19dd', '', 'Differently tagged post test', 'humor', '10:49:11 AM', '04/05/23'),
('8dbf2125-bc5b-4708-9ea9-7c77f8df061f', 25, 0, '178b291d-831e-438d-be79-a74e9b8b19dd', '', 'This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test. This is a long post test.', 'review', '10:49:27 AM', '04/05/23'),
('c98987ac-e018-4704-9725-24f03858e9ea', 25, 1, '178b291d-831e-438d-be79-a74e9b8b19dd', '5054859-Fkghiw5aMAclFBT.jfif', 'A post with an image.', 'review', '10:49:56 AM', '04/05/23'),
('2535496f-91c6-4e74-a2b8-0ae204513e7b', 25, 0, '178b291d-831e-438d-be79-a74e9b8b19dd', '', 'A post with comments', 'review', '10:50:09 AM', '04/05/23'),
('1efbe881-f5e9-4b8c-aa3e-f739e8f9b147', 25, 0, '54d2cbf0-9e43-424a-8057-2207f58cf629', '', 'A post liked by another user!', 'review', '10:51:46 AM', '04/05/23'),
('80b9cb6e-c4ed-4abe-8af0-db8b9a883572', 26, 0, 'add2c79a-5ea3-4e7d-953d-432871f2c8bb', '', 'This is a user who liked a lot of posts!', 'review', '10:52:35 AM', '04/05/23'),
('08e0c9e4-24a6-4a7f-aace-fd0eadad6061', 26, 0, 'c5fd0ccf-12df-4d48-985c-ef33e7387dbf', '', 'A post for this anime :))', 'review', '10:54:23 AM', '04/05/23'),
('2a7f36e3-f6dd-42bd-9e98-7edec7998012', 26, 0, '2387f918-6968-4d9c-9b22-1274a5af4933', '', 'One of my posts..', 'review', '10:55:01 AM', '04/05/23'),
('d3d384e6-9f75-407b-9fd2-cc1dbd57020f', 27, 1, '178b291d-831e-438d-be79-a74e9b8b19dd', '9180301-FB0-NUDWUAYdB_z.jpg', 'osrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvweosrigioewngwenwenvwoenvwoenmopwemvwe', 'art', '02:05:13 PM', '05/05/23'),
('c903948b-f2ea-464b-974c-5190a26298e5', 28, 1, '178b291d-831e-438d-be79-a74e9b8b19dd', '1509930-FvpIGNnWYAE2L_D.jpg', 'seanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseanseansean', 'art', '11:19:51 PM', '09/05/23'),
('b0f11466-024d-4793-952f-7e563762c67d', 25, 1, '54d2cbf0-9e43-424a-8057-2207f58cf629', '1455602-FvqLYPLaEAAv4Y-.jpg', 'Another post test', 'criticism', '01:32:53 AM', '10/05/23'),
('d32fcdf8-4c80-4bf5-8fd7-9b4eba31fbbb', 25, 1, '6abd77dc-010c-47ed-b0d9-356c72892861', '8510806-FvpIGNnWYAE2L_D.jpg', 'ano postttttttttttttttttttttttt', 'art', '12:25:37 AM', '11/05/23');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `profilepic` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `name`, `username`, `email`, `password`, `profilepic`) VALUES
(25, 'Kyle Daniel', 'Dandan', 'kyle21daniel@gmail.com', '$2y$10$BR.hoh9UXYbV7GxTALuuUuIn0PnFIQIVGX7ITGyFSOt98WH5hj9rS', '8064096-FB0-NUDWUAYdB_z.jpg'),
(26, 'another', 'another', 'another@gmail.com', '$2y$10$gind5gKgL.IRI/TsdVQV9OGVOBwUlIT8rNXP5kHg6HKoUg5m4dkVu', 'default.png'),
(27, 'bago', 'bago', 'bago@gmail.com', '$2y$10$uDV/3SM.d1s3P.yYMiUTI.fHhK/tHC8W9s7NhDC6I21SjZO4/FQbe', '5730682-FklxZlVWAAIHBTx.jfif'),
(28, 'sean', 'sean', 'sean@gmail.com', '$2y$10$w5/Znnz60ENvFc4jKQ0zWePJpwl4FSLPV7CqLLn1hupiQWRZW5tni', '4875532-FvpIGNnWYAE2L_D.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
