-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2021 at 08:46 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) NOT NULL,
  `appointment_id` varchar(16) NOT NULL,
  `appointment_date` varchar(12) NOT NULL,
  `patient_name` varchar(30) NOT NULL,
  `patient_gurdian_name` varchar(30) NOT NULL,
  `patient_email` varchar(30) NOT NULL,
  `patient_phno` varchar(10) NOT NULL,
  `patient_dob` varchar(12) NOT NULL,
  `patient_gender` varchar(8) NOT NULL,
  `patient_addres1` varchar(255) NOT NULL,
  `patient_addres2` varchar(255) NOT NULL,
  `patient_ps` varchar(50) NOT NULL,
  `patient_dist` varchar(50) NOT NULL,
  `patient_pin` varchar(7) NOT NULL,
  `patient_state` varchar(50) NOT NULL,
  `doctor_id` varchar(6) NOT NULL,
  `appointment_on` varchar(12) NOT NULL,
  `appointment_by` varchar(30) NOT NULL,
  `appointment_modified_on` varchar(12) NOT NULL,
  `appointment_modified_by` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_dob`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `appointment_on`, `appointment_by`, `appointment_modified_on`, `appointment_modified_by`) VALUES
(1, 'AH011811202101', '11 19 2021', 'Raju Barman', 'Suvendu Barman', 'rajabarman@gmail.com', '7842198952', '2021-11-06', 'Male', 'No.22, Narendra Chandra Dutta Sarani,Strand Road,', 'Fairley Place, B.B.D. Bagh, Kolkata, West Bengal 700001', 'Lalbazar', 'Kolkata', '700001', 'West bengal', 'Dr. La', '', '', '', ''),
(10, 'AH01181120210A', '28 12 2021', 'Raj das', 'Shya das', 'Rajdas@gmail.com', '7848898952', '21/01/1988', 'Male', 'No.22, Narendra Chandra Dutta Sarani,Strand Road,', 'B.B.D. Bagh, Kolkata, West Bengal 700001', 'Lalbazar', 'Kolkata', '700001', 'West Bengal', '5418', '', '', '', ''),
(11, 'AH01181120210B', '18-11-2021', 'Raj das', 'Shya das', 'Rajdas@gmail.com', '7848898952', '21/01/1988', 'Male', 'No.22, Narendra Chandra Dutta Sarani,Strand Road', 'B.B.D. Bagh, Kolkata, West Bengal 700001', 'Lalbazar', 'Kolkata', '700001', 'West Bengal', '5418', '', '', '', ''),
(12, 'AH01181120210C', '01 01 2021', 'Rakas Roy', 'Shyamal Roy', 'RakasRoy@gmail.com', '9940098952', '23/12/2009', 'Male', 'No.22, Narendra Chandra Dutta Sarani,Strand Road,', 'B.B.D. Bagh, Kolkata, West Bengal 700001', 'Lalbazar', 'Kolkata', '700001', 'West Bengal', '5418', '', '', '', ''),
(13, 'AH01181120210Z', '11 19 2021', 'Bishal Haldar', 'Bishali Haldar', 'bishalhaldar@.com', '7840098882', '1997-06-18', 'Male', 'No.22, Narendra Chandra Dutta Sarani,Strand Road', 'B.B.D. Bagh, Kolkata, West Bengal 700001', 'Lalbazar', 'Kolkata', '7000005', 'West bengal', 'Dr. Ri', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` varchar(6) NOT NULL,
  `doctor_reg_no` varchar(12) NOT NULL,
  `doctor_name` varchar(50) NOT NULL,
  `doctor_degree` varchar(50) NOT NULL,
  `also_with` varchar(100) NOT NULL,
  `doctor_address` varchar(255) NOT NULL,
  `doctor_email` varchar(30) NOT NULL,
  `doctor_phno` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `doctor_reg_no`, `doctor_name`, `doctor_degree`, `also_with`, `doctor_address`, `doctor_email`, `doctor_phno`) VALUES
('2518', '398F40W', 'Dr. Sania Hayat', 'MBBS, BDS', 'Jagorani (NGO)', 'G-1 S.S.Height AC-130 East, Prafulla Kanan, Krishnapur, Kolkata, West Bengal 700101', 'dr.saniahayat@apollo.com', '8912304518'),
('3518', '298F40W', 'Dr. Shantu Roy', 'MBBS, BS, MD', 'Nabatara NGO', '375, PK Guha Rd, Arabinda Sarani, Rajbari, Dum Dum, Kolkata, West Bengal 700028', 'dr.sahnturoy@apollo.com', '8312304518'),
('4508', '198F40W', 'Dr. Shamim Hussain', 'MBBS, DO, D-Ortho, PDCC', 'Turnstone Global', 'F1, Om Tower,, 36C, B.T. Road,, Kolkata, West Bengal 700002', 'dr.shamimhussain@apollo.com', '9512674518'),
('4519', '598F40W', 'Dr. Bhawan Sah', 'MBBS, MD, DNB', 'Hope Kolkata Foundation', '400A, Prince Anwar Shah Rd, Jodhpur Park, Kolkata, West Bengal 700655', 'dr.bhawan@apollo.com', '9512304518'),
('5418', '56DF40W', 'Dr. Ritu Agarwal', 'MBBS, MD, DM', 'West Bengal Working & Helpless Women Society(NGO)', 'Saibon Rd, Kerulia, Kalyan Nagar, Doperia Village, Kolkata, West Bengal 700118', 'dr.ritu@apollo.com', '7914234850');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_timing`
--

CREATE TABLE `doctor_timing` (
  `doc_timing_id` int(6) NOT NULL,
  `doctor_id` varchar(8) NOT NULL,
  `days` varchar(25) NOT NULL,
  `shift` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_timing`
--

INSERT INTO `doctor_timing` (`doc_timing_id`, `doctor_id`, `days`, `shift`) VALUES
(1, '2518', 'Monday', 'Morning'),
(2, '2518', 'Wednesday', 'Evening'),
(3, '2518', 'Satarday', 'Full Day'),
(4, '3518', 'Sunday', 'Morning - Evening'),
(5, '3518', 'Tuesday', 'Morning'),
(6, '3518', 'Thrusday', 'Evening'),
(7, '4419', 'Satarday', 'Full Day'),
(8, '4419', 'Wednesday', 'Evening'),
(9, '4419', 'Satarday', 'Full Day'),
(10, '4508', 'Sunday', 'Morning - Evening'),
(11, '4508', 'Monday', 'Morning'),
(12, '4508', 'Tuesday', 'Morning'),
(13, '4508', 'Thrusday', 'Evening'),
(14, '5418', 'Monday', ' Evening'),
(15, '5418', 'Tuesday', 'Morning'),
(16, '5418', 'Wednesday', 'Morning'),
(17, '5418', 'Thrusday', 'Morning-Evening');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(6) NOT NULL,
  `employee_username` varchar(12) NOT NULL,
  `employee_name` varchar(30) NOT NULL,
  `employee_password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_username`, `employee_name`, `employee_password`) VALUES
(1, 'jayshree', 'Jayshree Roy', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_info`
--

CREATE TABLE `hospital_info` (
  `doctor_id` varchar(12) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `hospital_address` varchar(355) NOT NULL,
  `hospital_email` varchar(50) NOT NULL,
  `hospital_phno` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hospital_info`
--

INSERT INTO `hospital_info` (`doctor_id`, `hospital_name`, `hospital_address`, `hospital_email`, `hospital_phno`) VALUES
('', 'Apllo Hospital', 'No.22, Narendra Chandra Dutta Sarani,Strand Road, Fairley Place, B.B.D. Bagh, Kolkata, West Bengal 700001', 'aplollo@gmail.com', '32125469247');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `doctor_timing`
--
ALTER TABLE `doctor_timing`
  ADD PRIMARY KEY (`doc_timing_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_info`
--
ALTER TABLE `hospital_info`
  ADD PRIMARY KEY (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctor_timing`
--
ALTER TABLE `doctor_timing`
  MODIFY `doc_timing_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
