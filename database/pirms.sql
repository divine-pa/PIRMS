-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:2007
-- Generation Time: Nov 30, 2025 at 07:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pirms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `related_case_id` varchar(50) DEFAULT NULL,
  `browser_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case`
--

CREATE TABLE `case` (
  `case_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Ongoing','Closed','Under Review') NOT NULL DEFAULT 'Ongoing',
  `date_started` date NOT NULL,
  `progress_percent` int(11) DEFAULT 0,
  `priority` enum('High','Medium','Low') NOT NULL DEFAULT 'Medium',
  `assigned_officer_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case`
--

INSERT INTO `case` (`case_id`, `title`, `description`, `status`, `date_started`, `progress_percent`, `priority`, `assigned_officer_id`) VALUES
('CASE-2025-001', 'Mobile Phone Snatching at Ojota Bus Stop', 'Victim snatched while waiting for bus. Multiple eyewitnesses, CCTV nearby.', 'Closed', '2025-01-15', 100, 'Low', 'OFF-001'),
('CASE-2025-002', 'POS Fraud Incident in Ikeja', 'Victim reports unauthorized withdrawals via cloned POS terminal and card skim.', 'Ongoing', '2025-03-10', 45, 'High', 'OFF-004'),
('CASE-2025-003', 'Neighbor Assault Case in Warri', 'Physical altercation between two neighbors; one treated at hospital.', 'Closed', '2025-02-20', 100, 'Medium', 'OFF-010'),
('CASE-2025-004', 'Hotel Burglary Series', 'Multiple burglaries reported at same hotel chain in Lagos; pattern suggests repeat offenders.', 'Ongoing', '2025-04-05', 30, 'Medium', 'OFF-005'),
('CASE-2025-005', 'Car Theft Ring in Ajah', 'Organized group stealing high-end vehicles. Stolen vehicle parts found in compound.', 'Closed', '2024-11-21', 100, 'High', 'OFF-008'),
('CASE-2025-006', 'Internet Scam (Yahoo Scam) Complaint in Enugu', 'Multiple victims defrauded via romance scam and work-from-home offers.', 'Ongoing', '2025-06-01', 50, 'High', 'OFF-003'),
('CASE-2025-007', 'Market Shop Break-in at Onitsha', 'Small shop broken into; goods stolen overnight during market close.', 'Under Review', '2025-09-12', 10, 'Medium', 'OFF-009'),
('CASE-2025-008', 'Domestic Theft by Housemaid – Benin City', 'Household items and cash discovered missing; housemaid detained for questioning.', 'Closed', '2025-02-02', 100, 'Low', 'OFF-010'),
('CASE-2025-009', 'Motorcycle (Okada) Theft in Ibadan', 'Multiple okada thefts reported in commercial area; complainants provided partial plate numbers.', 'Under Review', '2025-10-01', 20, 'Medium', 'OFF-007');

-- --------------------------------------------------------

--
-- Table structure for table `case_suspect`
--

CREATE TABLE `case_suspect` (
  `case_id` varchar(50) NOT NULL,
  `suspect_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_suspect`
--

INSERT INTO `case_suspect` (`case_id`, `suspect_id`) VALUES
('CASE-2025-001', 'SUS-001'),
('CASE-2025-002', 'SUS-002'),
('CASE-2025-003', 'SUS-003'),
('CASE-2025-004', 'SUS-004'),
('CASE-2025-004', 'SUS-010'),
('CASE-2025-005', 'SUS-006'),
('CASE-2025-006', 'SUS-008'),
('CASE-2025-006', 'SUS-011'),
('CASE-2025-007', 'SUS-005'),
('CASE-2025-008', 'SUS-007'),
('CASE-2025-009', 'SUS-009');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` enum('Homicide','Robbery','Narcotics','Cyber Crime','Fraud & Financial Crimes','Patrol Division','Forensics','Internal Affairs','Criminal Investigations') NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `description`) VALUES
(1, 'Criminal Investigations', 'Primary investigations and case coordination'),
(2, 'Cyber Crime', 'Investigations involving online fraud, hacking and cyber-enabled offences'),
(3, 'Robbery', 'Armed robbery and anti-robbery operations'),
(4, 'Fraud & Financial Crimes', 'Banking/financial investigations and anti-fraud operations'),
(5, 'Patrol Division', 'General patrol and rapid response units'),
(6, 'Forensics', 'Evidence analysis and laboratory services'),
(7, 'Narcotics', 'Drug-related investigations'),
(8, 'Internal Affairs', 'Internal conduct and disciplinary investigations'),
(9, 'Homicide', 'Death investigations and major crime homicide');

-- --------------------------------------------------------

--
-- Table structure for table `evidence`
--

CREATE TABLE `evidence` (
  `evidence_id` varchar(50) NOT NULL,
  `evidence_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `condition_status` varchar(50) DEFAULT NULL,
  `case_id` varchar(50) DEFAULT NULL,
  `collected_by` varchar(100) DEFAULT NULL,
  `date_collected` date DEFAULT NULL,
  `location_found` varchar(255) DEFAULT NULL,
  `storage_location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Logged',
  `priority` varchar(20) DEFAULT 'Standard',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evidence`
--

INSERT INTO `evidence` (`evidence_id`, `evidence_type`, `description`, `condition_status`, `case_id`, `collected_by`, `date_collected`, `location_found`, `storage_location`, `notes`, `status`, `priority`, `created_at`) VALUES
('EVI-2025-7629', 'Drug Sample', 'blah', 'Fair', 'CASE-2025-082', 'Det. Robert Williams', '2025-07-06', 'babcock', 'evidence room', 'sha dont touch', 'Logged', 'Standard', '2025-11-30 00:01:12'),
('EVID-2025-001', 'Physical', 'Recovered Tecno Spark 10 with scratched back', NULL, 'CASE-2025-001', NULL, '2025-01-16', 'Near Ojota Bus Stop', NULL, 'SIM shows recent calls to stolen numbers', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-002', 'Digital', 'POS terminal log extract and transaction snapshots', NULL, 'CASE-2025-002', NULL, '2025-03-12', 'Ikeja Branch - POS Counter', NULL, 'Card cloning suspected', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-003', 'Video', 'CCTV footage from neighbouring shop showing assailant', NULL, 'CASE-2025-003', NULL, '2025-02-20', 'Warri Market Road', NULL, 'Footage covers 02:10 - 02:30', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-004', 'Physical', 'Broken window frame with pry marks', NULL, 'CASE-2025-004', NULL, '2025-04-06', 'Hotel Block A - Lagos', NULL, 'Tool mark consistent with crowbar', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-005', 'Physical', 'Set of car keys recovered in suspect compound', NULL, 'CASE-2025-005', NULL, '2024-11-22', 'Compound near Ajah', NULL, 'Key fob matches Toyota Camry reported stolen', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-006', 'Digital', 'Confiscated laptop with email logs and spreadsheets', NULL, 'CASE-2025-006', NULL, '2025-06-02', 'Enugu suspect residence', NULL, 'Emails reference payment requests', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-007', 'Physical', 'Damaged shop lock and broken padlock', NULL, 'CASE-2025-007', NULL, '2025-09-13', 'Onitsha Main Market', NULL, 'Lock shows forced entry from outside', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-008', 'Audio', 'Housemaid confession audio recording', NULL, 'CASE-2025-008', NULL, '2025-02-03', 'Benin City - victim residence', NULL, 'Transcribed and signed by witness', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-009', 'Physical', 'Partial license plate fragment and screw', NULL, 'CASE-2025-009', NULL, '2025-10-03', 'Ibadan - roadside', NULL, 'Plate characters: AB•123', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-010', 'Physical', 'Victim ATM card recovered during search', NULL, 'CASE-2025-002', NULL, '2025-03-12', 'Ikeja - suspect bag', NULL, 'Card shows multiple withdrawals', 'Logged', 'Standard', '2025-11-29 23:59:25'),
('EVID-2025-011', 'Digital', 'WhatsApp chat export between victim and suspect', NULL, 'CASE-2025-006', NULL, '2025-06-05', 'Enugu - suspect phone', NULL, 'Chat includes negotiation for advance fees', 'Logged', 'Standard', '2025-11-29 23:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` enum('Email','Case Update','Evidence Alert','System Alert','Weekly Report','SMS') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `related_case_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `officer_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `badge_number` varchar(50) NOT NULL,
  `rank` enum('Officer','Detective','Sergeant','Lieutenant','Captain','Commander','Chief') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `years_of_service` int(11) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `status` enum('Active','On Leave','Administrative') NOT NULL DEFAULT 'Active',
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer`
--

INSERT INTO `officer` (`officer_id`, `name`, `badge_number`, `rank`, `email`, `phone`, `years_of_service`, `specialization`, `status`, `department_id`) VALUES
('OFF-001', 'Musa Abdullahi', 'BDG-11001', 'Detective', 'musa.abdullahi@pirms.ng', '+2348010000001', 8, 'Theft & Street Crime', 'Active', 1),
('OFF-002', 'Kemi Adeyemi', 'BDG-11002', 'Sergeant', 'kemi.adeyemi@pirms.ng', '+2348010000002', 6, 'Community Policing', 'Active', 5),
('OFF-003', 'Uche Okafor', 'BDG-11003', 'Detective', 'uche.okafor@pirms.ng', '+2348010000003', 12, 'Cyber Investigations', 'Active', 2),
('OFF-004', 'Funmi Ogunleye', 'BDG-11004', 'Captain', 'funmi.ogunleye@pirms.ng', '+2348010000004', 14, 'Financial Crimes', 'Active', 4),
('OFF-005', 'Thomas Reed', 'BDG-12349', 'Commander', 'thomas.reed@pirms.ng', '+2348010000005', 20, 'Major Crimes', 'Active', 9),
('OFF-006', 'Lisa Wang', 'BDG-12350', 'Detective', 'lisa.wang@pirms.ng', '+2348010000006', 4, 'Digital Forensics', 'Active', 6),
('OFF-007', 'John Ekanem', 'BDG-11005', 'Officer', 'john.ekanem@pirms.ng', '+2348010000007', 3, 'Traffic Enforcement', 'Active', 5),
('OFF-008', 'Yusuf Danjuma', 'BDG-11006', 'Sergeant', 'yusuf.danjuma@pirms.ng', '+2348010000008', 7, 'Robbery Taskforce', 'Active', 3),
('OFF-009', 'Samuel Afolabi', 'BDG-11007', 'Lieutenant', 'samuel.afolabi@pirms.ng', '+2348010000009', 10, 'Patrol & Rapid Response', 'Active', 5),
('OFF-010', 'Aisha Gambo', 'BDG-11008', 'Detective', 'aisha.gambo@pirms.ng', '+2348010000010', 5, 'Sexual & Domestic Crimes', 'Active', 1),
('OFF-011', 'Chinedu Nwosu', 'BDG-11009', 'Captain', 'chinedu.nwosu@pirms.ng', '+2348010000011', 11, 'Anti-Fraud', 'Active', 4),
('OFF-012', 'Amaka Okeke', 'BDG-11010', 'Detective', 'amaka.okeke@pirms.ng', '+2348010000012', 2, 'Community Outreach', 'Active', 1),
('OFF-2025-999', 'New Recruit', 'YOUR_BADGE_NUMBER', 'Detective', 'recruit@pirms.com', '555-0000', 0, 'General', 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `suspect`
--

CREATE TABLE `suspect` (
  `suspect_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `physical_description` text DEFAULT NULL,
  `status` enum('Under Investigation','Arrested','Released','Convicted') NOT NULL DEFAULT 'Under Investigation',
  `charges` text DEFAULT NULL,
  `case_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suspect`
--

INSERT INTO `suspect` (`suspect_id`, `name`, `gender`, `age`, `date_of_birth`, `address`, `physical_description`, `status`, `charges`, `case_id`) VALUES
('SUS-001', 'Tunde Bakare', 'Male', 28, '1997-03-15', 'Ojota, Lagos', 'Slim, 5\'8\", scar on left cheek', 'Arrested', 'Phone Snatching', NULL),
('SUS-002', 'Ngozi Nwachukwu', 'Female', 32, '1993-07-02', 'Ikeja GRA, Lagos', 'Medium build, 5\'5\"', 'Under Investigation', 'POS Fraud', NULL),
('SUS-003', 'Emeka Ojo', 'Male', 35, '1990-12-01', 'Warri, Delta', 'Stocky, 6\'0\"', 'Released', 'Assault', NULL),
('SUS-004', 'Marcus Brown', 'Male', 30, '1995-04-22', 'Ajah, Lagos', 'Athletic, tattoo on forearm', 'Arrested', 'Burglary; Trespassing', NULL),
('SUS-005', 'Amanda Lewis', 'Female', 26, '1999-01-10', 'Onitsha, Anambra', 'Petite, 5\'3\"', 'Released', 'Shoplifting', NULL),
('SUS-006', 'Hope Idowu', 'Male', 29, '1996-05-08', 'Ajah, Lagos', 'Medium height, brown eyes', 'Released', 'Car Theft', NULL),
('SUS-007', 'Blessing Ighalo', 'Female', 22, '2003-09-30', 'Benin City, Edo', 'Housemaid build', 'Arrested', 'Domestic Theft', NULL),
('SUS-008', 'Oluwatobi Adedeji', 'Male', 24, '2001-06-11', 'Enugu, Enugu', 'Lean, usually wears glasses', 'Arrested', 'Cyber Fraud; Accessing Financial Accounts', NULL),
('SUS-009', 'Hassan Bello', 'Male', 31, '1994-02-15', 'Ibadan, Oyo', 'Tall, dark complexion', 'Under Investigation', 'Motorcycle Theft', NULL),
('SUS-010', 'Sarah John', 'Female', 27, '1998-11-12', 'Onitsha, Anambra', 'Slim, 5\'6\"', 'Under Investigation', 'Breaking & Entering', NULL),
('SUS-011', 'Peter Onuoha', 'Male', 36, '1989-08-03', 'Enugu, Enugu', 'Stocky, trimmed beard', 'Arrested', 'Internet Scam', NULL),
('SUS-3201', 'chioma', 'Male', 30, '2004-06-13', 'babcock uni', 'tall', 'Under Investigation', 'breaking and entering', 'CASE-2025-082');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `badge_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) NOT NULL,
  `rank` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `two_factor_status` enum('Enabled','Disabled') NOT NULL DEFAULT 'Disabled',
  `account_status` enum('Active','Pending','Deactivated') NOT NULL DEFAULT 'Pending',
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `officer_id` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `first_name`, `last_name`, `badge_number`, `email`, `phone`, `department`, `rank`, `username`, `password_hash`, `two_factor_status`, `account_status`, `created_date`, `officer_id`, `password`) VALUES
(1, '', '', '', '', NULL, '', '', 'Chioma Nkenchor', '', 'Disabled', 'Active', '2025-11-29 23:44:19', NULL, '$2y$10$A9dv6xRt3KTvTdsCY.s3hOVtWmTnTinipC.y1n48wIX4TnQvh4sla'),
(2, '', '', '', '', NULL, '', '', 'CHIBABY', '', 'Disabled', 'Active', '2025-11-30 01:21:30', NULL, '$2y$10$MWVK08qLSatSLZLjlZCP6urcibgUeEzSQKKjrRbWEzPY8OtUtkAwi');

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `last_active_time` datetime NOT NULL DEFAULT current_timestamp(),
  `session_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `settings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme` enum('Light','Dark','System') NOT NULL DEFAULT 'Light',
  `compact_view` enum('Yes','No') NOT NULL DEFAULT 'No',
  `high_contrast_mode` enum('Enabled','Disabled') NOT NULL DEFAULT 'Disabled',
  `privacy_settings` text DEFAULT NULL,
  `notification_prefs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_ACTIVITY_LOG_USER_ACCOUNT_idx` (`user_id`),
  ADD KEY `fk_ACTIVITY_LOG_CASE_idx` (`related_case_id`);

--
-- Indexes for table `case`
--
ALTER TABLE `case`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `fk_CASE_OFFICER_idx` (`assigned_officer_id`);

--
-- Indexes for table `case_suspect`
--
ALTER TABLE `case_suspect`
  ADD PRIMARY KEY (`case_id`,`suspect_id`),
  ADD KEY `fk_CASE_SUSPECT_SUSPECT_idx` (`suspect_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `evidence`
--
ALTER TABLE `evidence`
  ADD PRIMARY KEY (`evidence_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_NOTIFICATION_USER_ACCOUNT_idx` (`user_id`),
  ADD KEY `fk_NOTIFICATION_CASE_idx` (`related_case_id`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`officer_id`),
  ADD KEY `fk_OFFICER_DEPARTMENT_idx` (`department_id`);

--
-- Indexes for table `suspect`
--
ALTER TABLE `suspect`
  ADD PRIMARY KEY (`suspect_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_USER_ACCOUNT_OFFICER_idx` (`officer_id`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `fk_USER_SESSION_USER_ACCOUNT_idx` (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`settings_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_USER_SETTINGS_USER_ACCOUNT_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_ACTIVITY_LOG_CASE` FOREIGN KEY (`related_case_id`) REFERENCES `case` (`case_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ACTIVITY_LOG_USER_ACCOUNT` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `case`
--
ALTER TABLE `case`
  ADD CONSTRAINT `fk_CASE_OFFICER` FOREIGN KEY (`assigned_officer_id`) REFERENCES `officer` (`officer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `case_suspect`
--
ALTER TABLE `case_suspect`
  ADD CONSTRAINT `fk_CASE_SUSPECT_CASE` FOREIGN KEY (`case_id`) REFERENCES `case` (`case_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_CASE_SUSPECT_SUSPECT` FOREIGN KEY (`suspect_id`) REFERENCES `suspect` (`suspect_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_NOTIFICATION_CASE` FOREIGN KEY (`related_case_id`) REFERENCES `case` (`case_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_NOTIFICATION_USER_ACCOUNT` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `officer`
--
ALTER TABLE `officer`
  ADD CONSTRAINT `fk_OFFICER_DEPARTMENT` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `fk_USER_ACCOUNT_OFFICER` FOREIGN KEY (`officer_id`) REFERENCES `officer` (`officer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `fk_USER_SESSION_USER_ACCOUNT` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_USER_SETTINGS_USER_ACCOUNT` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
