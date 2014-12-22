-- phpMyAdmin SQL Dump
-- version 4.2.8.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2014 at 09:17 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xenon_support`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
`id` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `text`, `created_at`, `updated_at`) VALUES
(2, 'tickets.create', 'Create Ticket', '2014-12-05 09:39:52', '2014-12-05 09:39:52'),
(3, 'tickets.edit', 'Edit Ticket', '2014-12-05 09:40:04', '2014-12-05 09:40:04'),
(4, 'tickets.delete', 'Delete Ticket', '2014-12-05 09:40:14', '2014-12-05 09:40:14'),
(5, 'tickets.all', 'View all Tickets', '2014-12-05 09:40:40', '2014-12-05 09:40:40'),
(6, 'customers.create', 'Create Customer', '2014-12-05 09:42:44', '2014-12-05 09:42:44'),
(7, 'customers.edit', 'Edit Customer', '2014-12-05 09:42:55', '2014-12-05 09:42:55'),
(8, 'customers.all', 'View Customer', '2014-12-05 09:43:06', '2014-12-05 09:43:06'),
(9, 'customers.delete', 'Delete Customer', '2014-12-05 09:43:29', '2014-12-05 09:43:29'),
(10, 'operators.create', 'Create Operator', '2014-12-05 09:44:42', '2014-12-05 09:44:42'),
(11, 'operators.edit', 'Edit Operator', '2014-12-05 09:45:12', '2014-12-05 09:45:12'),
(12, 'operators.delete', 'Delete Operator', '2014-12-05 09:45:28', '2014-12-05 09:45:28'),
(13, 'operators.view', 'View Operator', '2014-12-05 09:45:57', '2014-12-05 09:45:57'),
(14, 'departments.create', 'Create Department', '2014-12-05 09:52:55', '2014-12-05 09:52:55'),
(15, 'departments.edit', 'Edit Department', '2014-12-05 09:53:05', '2014-12-05 09:53:05'),
(16, 'departments.delete', 'Delete Department', '2014-12-05 09:53:19', '2014-12-05 09:53:19'),
(17, 'departments.view', 'View Department Info', '2014-12-05 09:53:31', '2014-12-05 09:53:31'),
(18, 'companies.create', 'Create Company', '2014-12-05 09:53:52', '2014-12-05 09:53:52'),
(19, 'companies.edit', 'Edit Company', '2014-12-05 09:54:00', '2014-12-05 09:54:00'),
(20, 'companies.delete', 'Delete Company', '2014-12-05 09:54:09', '2014-12-05 09:54:09'),
(21, 'companies.view', 'View Company', '2014-12-05 09:54:23', '2014-12-05 09:54:23'),
(22, 'canned_messages.create', 'Create Canned Messages', '2014-12-14 04:18:52', '2014-12-14 04:18:52'),
(23, 'canned_messages.edit', 'Edit Canned Messages', '2014-12-14 04:19:00', '2014-12-14 04:19:00'),
(24, 'canned_messages.view', 'View Canned Messages', '2014-12-14 04:19:07', '2014-12-14 04:19:07'),
(25, 'canned_messages.delete', 'Delete Canned Messages', '2014-12-14 04:19:16', '2014-12-14 04:19:16'),
(26, 'conversations.accept', 'Can Accept Conversation', '2014-12-21 14:25:28', '2014-12-21 14:25:28'),
(27, 'conversations.closed', 'Can View Closed Conversations', '2014-12-21 14:25:44', '2014-12-21 14:25:44'),
(28, 'mailchimp.pair_email', 'Can Pair Mailchimp Templates to Emails', '2014-12-21 14:26:25', '2014-12-21 14:26:25'),
(29, 'blocking.block', 'Can Block IP', '2014-12-21 14:26:54', '2014-12-21 14:26:54'),
(30, 'blocking.delete', 'Can Delete Blocked IP', '2014-12-21 14:27:09', '2014-12-21 14:27:09'),
(31, 'settings.all', 'Can change Settings', '2014-12-21 14:27:25', '2014-12-21 14:27:25'),
(32, 'departments_admins.create', 'Can Create Department Admins', '2014-12-21 15:02:03', '2014-12-21 15:02:03'),
(33, 'departments_admins.edit', 'Can Edit Department Admins', '2014-12-21 15:02:12', '2014-12-21 15:02:12'),
(34, 'departments_admins.view', 'Can View Department Admins', '2014-12-21 15:02:20', '2014-12-21 15:02:20'),
(35, 'departments_admins.delete', 'Can Delete Department Admins', '2014-12-21 15:02:31', '2014-12-21 15:02:31'),
(36, 'blocking.view', 'Can view blocked List of IP', '2014-12-21 15:22:12', '2014-12-21 15:22:12'),
(37, 'conversations.accept_close', 'Can Close the Conversation', '2014-12-21 15:29:58', '2014-12-21 15:29:58'),
(38, 'conversations.closed_delete', 'Can Delete Closed Conversation', '2014-12-21 15:30:15', '2014-12-21 15:30:15'),
(39, 'departments_admins.remove', 'Remove admin from department', '2014-12-21 15:38:04', '2014-12-21 15:38:04'),
(40, 'departments_admins.activate', 'Activate department admin', '2014-12-21 15:38:21', '2014-12-21 15:38:21'),
(41, 'operators.activate', 'Activate Operator', '2014-12-21 15:42:11', '2014-12-21 15:42:11'),
(42, 'mailchimp.view', 'View all Templates from Mailchimp', '2014-12-21 15:45:52', '2014-12-21 15:45:52'),
(43, 'mailchimp.delete', 'Delete Paired Template', '2014-12-21 15:46:08', '2014-12-21 15:46:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
