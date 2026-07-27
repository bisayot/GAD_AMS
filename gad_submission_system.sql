-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 02:21 PM
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
-- Database: `gad_submission_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment_budget_items`
--

CREATE TABLE `accomplishment_budget_items` (
  `id` int(11) NOT NULL,
  `accomplishment_report_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `sub_item` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment_report`
--

CREATE TABLE `accomplishment_report` (
  `id` int(11) NOT NULL,
  `control_number` varchar(50) NOT NULL,
  `act_design_id` int(11) DEFAULT NULL,
  `activity_title` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `venue` varchar(255) NOT NULL,
  `attendees` int(11) NOT NULL,
  `male` int(11) NOT NULL,
  `female` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `attachment` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_viewed_by_admin` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_budget_items`
--

CREATE TABLE `activity_budget_items` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `sub_item` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_classifications`
--

CREATE TABLE `activity_classifications` (
  `id` int(11) NOT NULL,
  `classification_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_classifications`
--

INSERT INTO `activity_classifications` (`id`, `classification_name`) VALUES
(1, 'Client-focused activity'),
(2, 'Organization-focused activity'),
(3, 'Attributed program');

-- --------------------------------------------------------

--
-- Table structure for table `activity_design`
--

CREATE TABLE `activity_design` (
  `act_design_id` int(11) NOT NULL,
  `activity_title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `attachment` varchar(500) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gpb_id` int(11) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `target_participants` int(11) DEFAULT NULL,
  `proposed_budget` decimal(15,2) DEFAULT NULL,
  `form_type` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `accomplishment_deadline` date DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `gad_mandate_id` int(11) DEFAULT NULL,
  `gender_issue_id` int(11) DEFAULT NULL,
  `classification_id` int(11) DEFAULT NULL,
  `is_viewed_by_admin` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `control_number` varchar(255) DEFAULT NULL,
  `modification_request_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `modification_remarks` text DEFAULT NULL,
  `is_modified` tinyint(1) NOT NULL DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_design_issues`
--

CREATE TABLE `activity_design_issues` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_design_mandates`
--

CREATE TABLE `activity_design_mandates` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `mandate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_annual_reports`
--

CREATE TABLE `archived_annual_reports` (
  `id` int(11) UNSIGNED NOT NULL,
  `fiscal_year` varchar(4) NOT NULL,
  `html_content` longtext NOT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_categories`
--

INSERT INTO `budget_categories` (`id`, `name`) VALUES
(1, 'Meals and Snacks'),
(2, 'Function Room / Venue'),
(3, 'Accommodation'),
(4, 'Equipment Rental'),
(5, 'Professional Fee / Honoraria'),
(6, 'Tokens'),
(7, 'Materials and Supplies'),
(8, 'Transportation'),
(9, 'Other Expenses');

-- --------------------------------------------------------

--
-- Table structure for table `budget_item_mandate_allocations`
--

CREATE TABLE `budget_item_mandate_allocations` (
  `id` int(11) NOT NULL,
  `budget_item_id` int(11) NOT NULL,
  `item_type` enum('AD','AR') NOT NULL,
  `mandate_id` int(11) NOT NULL,
  `gpb_budget_line_id` varchar(255) DEFAULT NULL,
  `allocated_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_realignment_logs`
--

CREATE TABLE `budget_realignment_logs` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `gpb_id` int(11) NOT NULL,
  `type` enum('augmentation','realignment') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `justification` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_results`
--

CREATE TABLE `evaluation_results` (
  `id` int(11) UNSIGNED NOT NULL,
  `accomplishment_report_id` int(11) NOT NULL,
  `question_key` varchar(100) NOT NULL,
  `score` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_types`
--

CREATE TABLE `form_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_types`
--

INSERT INTO `form_types` (`id`, `name`) VALUES
(1, 'In-Service Training Design and Request'),
(2, 'Employees\' Activity Design'),
(3, 'Extension Training Design'),
(4, 'External Training Form');

-- --------------------------------------------------------

--
-- Table structure for table `gad_plan_budget`
--

CREATE TABLE `gad_plan_budget` (
  `gpb_id` int(11) NOT NULL,
  `gender_issue_mandate` varchar(255) DEFAULT NULL,
  `cause_of_gender_issue` text DEFAULT NULL,
  `gad_result_objective` text DEFAULT NULL,
  `relevant_org_mfo_pap` text DEFAULT NULL,
  `gad_activity` varchar(255) DEFAULT NULL,
  `performance_indicators_targets` text DEFAULT NULL,
  `gad_budget` decimal(15,2) DEFAULT NULL,
  `source_of_budget` varchar(100) DEFAULT NULL,
  `responsible_unit_office` varchar(100) NOT NULL,
  `form_type` enum('client-focused activity','organization-focused activity','attributed program') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gad_plan_budget`
--

INSERT INTO `gad_plan_budget` (`gpb_id`, `gender_issue_mandate`, `cause_of_gender_issue`, `gad_result_objective`, `relevant_org_mfo_pap`, `gad_activity`, `performance_indicators_targets`, `gad_budget`, `source_of_budget`, `responsible_unit_office`, `form_type`) VALUES
(1, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination', 'To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.', 'MFO: Higher Education Program', 'Implementation of Affirmative Action Agenda', 'Number of served disadvantaged students - 100% disadvantaged students', 700000.00, 'GAA GAA', 'OSS', 'client-focused activity'),
(2, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'High tuition and miscellaneous fees, compounded by socio-cultural expectations for women to prioritize domestic roles over education', 'To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.', 'MFO: Higher Education Program', 'Provision of free tuition fee under RA 10931 to eligible male and female students of the university', 'Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.', 131100000.00, 'GAA', 'OSS, OUR, UHS', 'client-focused activity'),
(3, 'CHED Memorandum Order No. 01 series 2015', 'Limited activities to increase awareness of men and women students to GAD-related information (1st year students, transferees)', 'To increase the students level of awareness and appreciation on GAD', 'MFO: Higher Education Program', 'Conduct GAD orientation/ forum/ seminar to BSU 1st year/ transferees students (face to face/ online: 14 colleges)', 'No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)', 453363.00, 'GAA GAA GAA', 'OSS, GAD Office, 3 Campuses (La Trinidad, Bokod & Buguias Campus)', 'client-focused activity'),
(4, 'CHED Memorandum Order No. 01 series 2015', 'Student leaders have limited understanding on GAD in the University', 'To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'MFO: Higher Education Program', 'Continuous conduct of GAD responsive leadership training for student', 'No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)', 150000.00, 'GAA GAA GAA', 'OSS', 'client-focused activity'),
(5, 'Part VII of CHED Memorandum Order Number 1, series 2015 on Gender-Responsive Research and Extension Program', 'Presence of gender inequality, poverty and GAD-related concerns in the community', 'To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable developmen', 'MFO: Extension Services- Research Services- Advance Education ServicesHigher Education Services', 'Conduct of Extension project/ activities to partner organizational/ communities as component of Gender Responsive Extension Program (GREP) to partner organization/ communities', 'No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male: 500)', 3500000.00, 'GAA GAA', 'Research and Extension, various offices/ colleges in the University/ external campuses', 'client-focused activity'),
(6, 'Limited access of PWDs to gender-responsive programs and services/DBM-DSWD Joint Circular No. 2003-01 provides guidelines for the implementation of Section 29 of the General Appropriations Act (GAA), requiring government agencies to set aside at least 1% ', 'Limited access of PWDs to gender-responsive programs and services', 'Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.', 'MFO: Research Services- Extension Services-Advance Education ServicesHigher Education Services', 'Awareness of women PWDs who benefited from the program', 'Number of women PWDs who benefited from the program - FM No. of GAD program/project/ activityprovided for PWD - At least 1 program/project/ activity', 350000.00, 'GAA GAA', 'HRMO, OSS', 'client-focused activity'),
(7, 'Lack of senior citizens access to gender-responsive programs and services/DBM-DSWD Joint Circular No. 2003-01 provides guidelines for the implementation of Section 29 of the General Appropriations Act (GAA), requiring government agencies to set aside at l', 'Absence of sustainable and gender-responsive university programs for senior citizens', 'Improved access of senior citizensto gender-responsive, inclusive, and empowering programs and services', 'MFO: Research Services- Extension Services- Advance Education ServicesHigher Education Services', 'Program: BSU Kalinga for women Senior Citizens', 'Number of Programs provided for Senior Citizens - At least 1 program for BSU Number of women senior citizens who benefited from the program - F21 M23', 250000.00, 'GAA', 'GAD Office, Colleges, External Campuses', 'client-focused activity'),
(8, 'Low number of women\'s participation in sports/MCW-IRR Section 14 Develop, establish and strengthen programs for the participation of women in competitive and non-competitive sports as means to achieve excellence, promote physical and social well-being', 'Minimal attendance of female students to competitive and non-competitive sports', 'To increase female students level of participation and awareness on Gender in Sports', 'MFO: Higher Education Program', 'Participate in sports activities targeted for female students', 'No. of sports activities supported through allocation of budget for sports and socio- cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival- 2 sports activities (Female:20)', 160000.00, 'GAA GAA GAA', 'CHK', 'client-focused activity'),
(9, 'Programs on Awards and Incentives for Service Excellence (PRAISE under CSC Res.No.010112 and CSC MC No.1,s. 2001); Memo Circular No.2011-01 (Guidelines for the Creation, Strengthening and Institutionalization of GAD Focal Point System: Roles and Responsib', 'Low recognition/ appreciation on the Gender Mainstreaming in BSU', 'Strengthen Gender Mainstreaming through recognition of GAD implementation in the University', 'MFO: Research Services-Extension Services- Advance Education ServicesHigher Education Services', 'Provide recognition and award to GAD implementer and other GAD-related award (GAD implementer for students and employees, GAD Advocate Award)', 'No. of award will be provided through BSU-PRAISE- At least 1 GAD Advocate award will be provided through BSU-PRAISE', 205000.00, 'GAA GAA', 'HRDO, HRMO, BSU-PRAISE Committee, GAD Office', 'organization-focused activity'),
(10, 'Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production/Magna Carta of Women (RA 9710)', 'Low awareness among personnel in the University about GAD mainstreaming', 'To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production', 'MFO: Research Services- Extension Services- Advance Education ServicesHigher Education Services', 'Conduct GAD related Gender Mainstreaming capability building and competency acquisition', 'No. of training/workshop/ seminars conducted - 25 training/ workshop/ seminars/ Learning and Development (F:1500 M: 1000)', 4000000.00, 'GAA GAA', 'GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses', 'organization-focused activity'),
(11, 'Magna Carta of Women IRR Section 37 Gender Mainstreaming as a Strategy for Implementing the Magna Carta of Women', 'Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or from participating in capability enhancement sessions', 'Inadequate support services to personnel and students with children', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Operationalize of BSU College of Nursing Reproductive Health Care Center', 'No. of maintained Reproductive Health Care Center- 1 maintained BSU CN Reproductive Health Care Center', 120000.00, 'GAA GAA', 'College of Nursing', 'organization-focused activity'),
(12, 'Executive Order No. 340 s. 1997 Directing National Government Agencies and Government-Owned and-Controlled Corporations to provide Day Care Services for their Employee\'s Children under five years of age', 'Problems of parents and students related to child care', 'Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Maintenance of Child Minding Center for working parents in ensuring that they have a safe place to leave their child while they are at their work places', 'No. of established child minding center- Fully maintained new established and existing child minding centers at BSU La Trinidad,Bokod Campus and Buguias Campus', 230000.00, 'GAA GAA', 'GAD Office, External Campuses', 'organization-focused activity'),
(13, 'Magna Carta of Women (RA 9710)', 'Low integration of gender mainstreaming of BSU', 'To strengthen the GAD integration in the operations of BSU', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Create a Monitoring Team to conduct monitoring and evaluation of the utilization/ outcome of GAD PAPs and ensure effectiveness of the GAD PAPs', 'No. of monitoring and assessment meetings with reports conducted4 monitoring and assessment meetings with reports conducted', 330000.00, 'GAA GAA', 'GAD Office', 'organization-focused activity'),
(14, 'Magna Carta of Women IRR Section 37 C. Creation and/or Strengthening of the GAD Focal Points (GFPs)', 'Low level of capacity of GFPS to develop and implement GAD programs and activities due to new members', 'Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'for GFPS/Secretariat: GMEF/HGDG/GPB/GAD Agenda/GAD Deepening Session and TOT among other related trainings and capacity building activities (Regional/ National GAD-related trainings/ seminars/ forum/workshop)', 'No. training/ seminars/ workshop attendance for each GFPS-members on GAD related updates and mandates- At least 1 Training/ seminar/ workshop attendance for each GFPS-members on GAD related updates and mandates (Female: 31, Male: 15)', 396000.00, 'GAA GAA', 'GAD Office', 'organization-focused activity'),
(15, 'Section 37-C2 Rule VI of the Magna Carta of Women\'s IRR on duties and function of the GAD Focal Point System/Magna Carta of Women (RA 9710)', 'Compliance to provisions regarding regular monitoring of gender mainstreaming efforts', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Regular coordination and meetings of GAD-GFPS (Execom, GFPS- TWG members and external campus TWG members) and emergency meeting when necessary', 'No. of reports on regular meetings per campus conducted will be available at the end of the year - At least 6 reports on regular meetings conducted will be available at the end of the year, RGADC quarterly meeting/s', 211720.00, 'GAA GAA', 'GAD Office', 'organization-focused activity'),
(16, 'Duties and function of the GAD Focal Point System/CHED Memo 2015-1', 'No plantilla personnel assigned to plan, implement and monitor GAD PAPs on a full-time basis', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Engage support staff to assist in the implementation of GFPS PPA\'s and Gender Mainstreaming in the university through rehiring of GAD staff and Student Assistant', 'Salary of GAD Staff: Casual No. of rehired personnel (casual) and student assistant -At least two (2) staff renewed/rehired (Casual) and at least one (3) Student Assistant/ SPES per semester', 550000.00, 'GAA', 'GAD Office', 'organization-focused activity'),
(17, 'Low level of Awareness on Gender Mainstreaming (GM) in Instruction, Research, Extension and\nProduction among newly hired personnel/Magna of Women (RA 9710), CHED Memo 2015-1', 'Lack of regular orientation and refresher training on gender sensitivity and GAD mandates', 'To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel', 'MFO: Conduct Gender Sensitivity Training (GST) for newly hired and current personnel (continuing activity)', 'Conduct Gender Sensitivity Training (GST) for newly hired and current personnel (continuing activity)', 'No. of training conducted for newly hired personnel and refresher trainings for current personnel - 1 training conducted for at least 100% of newly hired personnel and 3 refresher trainings for current personnel', 421728.00, 'GAA GAA GAA GAA GAA', 'GAD Office', 'organization-focused activity'),
(18, 'Part V, Rule II, Section 4 of CHED Memorandum Order No. 1 Series of 2015/CHED Memo 2015-1', 'Limited number of GAD library and related learning materials across various discipline', 'To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Provision of knowledge products (books magazine, multi-media) for adequate and accessible library and related learning materials in support to gender-responsive Curriculum Programs', 'No. of procured library and learning materials- 200 books', 2600000.00, 'GAA GAA', 'ULIS', 'organization-focused activity'),
(19, 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'Presence of Gender Based Violence (GBV) issues/reports/cases in the university', 'Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'No. of Communication and IEC materials/knowledge products - Official Publication of BSU with GAD articles, pictures as a medium for employees, clients, students partners to disseminate programs, achievement and advocacies, Maintained GAD Bulletin board - At least 8 GAD Bulletin board ,Sector-specific knowledge products on GAD generated and designed to be downloadable via BSU website - At least 2', 296000.00, 'GAA GAA GAA', 'UPAO, GAD Office', 'organization-focused activity'),
(20, 'Institutionalizing GAD database and Sex-Disaggregated Database/Magna Carta of Women (RA 9710), Section 36 on Sex-Disaggregated Database', 'Minimal awareness and appreciation on the relevance of the centralized Sex-Disaggregated database', 'To establish a centralized GAD-related database of the University', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Updating of Sex-Disaggregated Data (SDD) and other data related to personnel/students/clients GAD-related database for gender analysis and report preparation', 'No. of well-organized and maintained GAD database for easy reference and access 1 GAD databaseEstablishment of GAD-Database system per college/unit.', 500000.00, 'GAA GAA', 'ICT, GAD Office', 'organization-focused activity'),
(21, 'Magna Carta for women, Chapter IV: Section 10 and RA 10121, Section 2 & 9', 'Limited resources of the DSWD and LGU to provide for students who are transient residents and limited appreciation on women\'s role in nation building among employees and students, especially new ones', 'To ensure that disaster assistance provided to distressed students are gender-responsive', 'MFO: MFO: Research ServicesMFO: Extension Services MFO: Advance Education ServicesMFO: Higher Education Services', 'Provision of gender-responsive services to employees and students who experienced crisis/ disaster (e.g. Distribution of hygiene kits for both women and men)', 'No. of pax of the most affected employees/ students during crises - 1,000 employees/students', 211200.00, 'GAA GAA', 'GAD Office, HDRO, NSTP, various offices/ all colleges in the University', 'organization-focused activity'),
(22, 'Compliance to Section 18 of MCW RA 9710:Special Leave Benefits for Women RA 8187: Paternity Leave Section 8 of RA 8972:Solo Parents Welfare Act of 2000 Section 43 of RA 9262: Anti-Violence Against Women and Their Children Act of 2004)', 'Employees may require special leaves due to parental obligations, health concerns and other circumstances that may require the need thereof', 'Enhanced support services for employees in need of special leaves', 'MFO: MFO: Research ServicesMFO: Extension Services MFO: Advance Education ServicesMFO: Higher Education Services', 'Provision of gender leaves and conduct of Seminar on Gender Related Leaves for Newly Hired Employees', 'No. of Maternity, Paternity, Solo parent, gynecological, VAWC leave of employees who will avail and 1 Seminar conducted (M:20 F:50)- All (100%) Maternity, Paternity, Solo parent, gynecological, VAWC leave of employees who will avail and 1 Seminar conducted (M:20 F:50)', 1000000.00, 'GAA', 'HRMO, CBOO, various offices/ colleges in the University', 'organization-focused activity'),
(23, 'Compliance to Proclamation 227 on the observance of Women\'s Role in History Month and Proclamation 1172, s. 2006 on the 18-Day Campaign to End Violence Against Women (VAW)', 'The need to highlight women\'s rights, their role in national development/ nation building and need to provide platform to invoke protection of women\'s rights against VAW, gender-based violence, Safe Spaces Act (RA No. 11313) and concerns that affect women and men', 'To strengthen awareness of BSU students/ employees on women\'s rights and their role in national development and nation building', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Participation to18-Day Campaign to end VAW and Women\'s Month Celebration/ activities and programs organized by PCW and other agency/ies', 'No. of activities conducted per campus - At least one (1) activity conducted per campus', 450000.00, 'GAA GAA', 'GAD Office, various offices/ colleges in the University/ external campus', 'organization-focused activity'),
(24, 'Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or from participating in capability enhancement sessions/Magna Carta of Women IRR Section 37 Gender Mainstreaming as a Strategy for Implementing', 'Inadequate support services for personnel/students with young children and breastfeeding mothers (affecting productivity, especially among women non implementation of RA 10028)', 'Inadequate support services to personnel and students with children', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Establishment/ maintenance of breastfeeding station established in the preceding years', 'Fully maintained Lactation rooms - 100% fully maintained lactation rooms at BSU La Trinidad, Bokod and BuguiasCampus', 220000.00, 'GAA GAA', 'GAD Office, External Campuses', 'organization-focused activity'),
(25, 'Low level of employees understanding of gender issues/ concept to promote gender equality and a gender-responsive work environment./RA 9710 (Magna Carta of Women), PCWNEDADBM Joint Circular 2012-01, and CSC MC No. 12 s. 2005, the University shall conduct ', 'Lack of regular gender-related capacity-building activities and insufficient integration of gender sensitivity in employee development programs', 'To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Conduct of gender sensitivity orientations for BSU Personnel (continuing activity)', 'No. of training conducted for BSU personnel -At least 3 training to be conducted', 253796.24, 'GAA GAA GAA', 'GAD Office', 'organization-focused activity'),
(26, 'Establishment of Gender-Responsive Curricular Programs/Part V of CMO 01, s. 2015/ CHED Memo 2015-1', 'Limited subject for GAD Integration of Gender-Responsive Instruction and Curriculum Developmen', 'Integration of gender mainstreaming in curriculum/ subjects in all levels', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Preparation of syllabi and classroom teaching integrating gender perspective', 'Number of faculty members integrated Gender perspective in the syllabus - 567 permanent and 125 COS females and males faculty integrating and development of gender-sensitive learning materials', 58295539.71, 'GAA GAA', 'GAD Office, GFPS-TWG members, all colleges', 'organization-focused activity'),
(27, 'Need to sustain a functional and gender-responsive GAD Focal Point System (GFPS) and GAD Office to ensure the effective mainstreaming of gender perspective in BSU academic, research, extension, and administrative programs/Section 37-C2 of the Magna Carta ', 'Sustained operations of the existing GAD Office-Provision of administrative, logistical, and financial support for the day-to-day functioning of the GAD Office maintenance of GAD database and documentation systems coordination of GFPS and GAD-related activities across colleges and units.', 'To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.', 'MFO: Research ServicesExtension Services Advance Education ServicesHigher Education Services', 'Sustaining Gender Mainstreaming and Institutional Support in the University', 'Fully maintained GAD Office - 100% fully maintained GAD office', 8052420.09, 'GAA GAA GAA GAA', 'GAD Office', 'organization-focused activity'),
(28, '', '', '', '', 'Transportation Equipment Outlay', '', 12285000.00, 'GAA', 'TASU, PMO, SPMO', 'attributed program'),
(29, '', '', '', '', 'Repair and Maintenance Office Building and other Structures', '', 6402000.00, 'GAA', 'PU, PMO, SPMO', 'attributed program'),
(30, '', '', '', '', 'Bamboo Industry Development for Environment Conservation and Countryside', '', 3750000.00, 'GAA', 'PU, PMO, SPMO', 'attributed program'),
(31, '', '', '', '', 'Benguet State University Student Information and Accounting System (SIAS)', '', 6098183.58, 'GAA', 'ICT, PMO', 'attributed program');

-- --------------------------------------------------------

--
-- Table structure for table `gpb_budget_breakdown`
--

CREATE TABLE `gpb_budget_breakdown` (
  `breakdown_id` int(11) NOT NULL,
  `gpb_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpb_budget_breakdown`
--

INSERT INTO `gpb_budget_breakdown` (`breakdown_id`, `gpb_id`, `category`, `amount`) VALUES
(1, 1, 'PS Attribution', 500000.00),
(2, 1, 'Supplies and Materials', 200000.00),
(3, 2, 'Tuition Fee', 131100000.00),
(4, 3, 'Meals and Snack', 318800.00),
(5, 3, 'Supplies and Materials', 10000.00),
(6, 3, 'PS Attribution', 124563.26),
(7, 4, 'Supplies and Materials', 30000.00),
(8, 4, 'Snack', 20000.00),
(9, 4, 'PS Attribution', 100000.00),
(10, 5, 'Seminar Package/Meals & Snacks/ Fuel for Transportation/ Vehicle Rental/ Other Professional Services', 2500000.00),
(11, 5, 'PS Attribution', 1000000.00),
(12, 6, 'PS Attribution', 100000.00),
(13, 6, 'Supplies & Materials/ Meals & Snacks', 250000.00),
(14, 7, 'Supplies & Materials/ Meals & Snacks', 250000.00),
(15, 8, 'Registration & Travelling Expenses', 80000.00),
(16, 8, 'Meals and Snacks', 60000.00),
(17, 8, 'PS Attribution', 20000.00),
(18, 9, 'Incentive GAD Advocate Award', 5000.00),
(19, 9, 'PS Attribution', 200000.00),
(20, 10, 'Seminar Package/Meals & Snacks/ Fuel for Transportation/ Vehicle Rental/ Professional Services (La Trinidad Bokod and Buguias Campus)', 3500000.00),
(21, 10, 'PS Attribution', 500000.00),
(22, 11, 'Supplies and Materials', 20000.00),
(23, 11, 'PS Attribution', 100000.00),
(24, 12, 'Supplies and Materials', 130000.00),
(25, 12, 'PS Attribution', 100000.00),
(26, 13, 'Supplies and Materials', 10000.00),
(27, 13, 'PS Attribution', 320000.00),
(28, 14, 'GFPS TWG PAPs', 396000.00),
(29, 14, 'PS Attribution', 500000.00),
(30, 15, 'Meals & Snack', 111720.00),
(31, 15, 'PS Attribution', 100000.00),
(32, 16, 'Salary of GAD Staff and SPES/ Student Assistant', 550000.00),
(33, 17, 'Meals & Snack', 167200.00),
(34, 17, 'Token', 4000.00),
(35, 17, 'Professional Fee', 144528.32),
(36, 17, 'Supplies and Materials', 6000.00),
(37, 17, 'PS Attribution', 100000.00),
(38, 18, 'Books and Instructional Materials', 2500000.00),
(39, 18, 'PS Attribution', 100000.00),
(40, 19, 'Shamag', 96000.00),
(41, 19, 'PS Attribution', 100000.00),
(42, 19, 'Supplies and Materials', 100000.00),
(43, 20, 'PS Attribution', 100000.00),
(44, 20, 'Maintenance of SDD/ Internet connection', 1000000.00),
(45, 21, 'Crisis pack: Php,200/ pack X', 1000.00),
(46, 21, 'pax', 200000.00),
(47, 21, 'PS TWG Members', 10000.00),
(48, 22, 'PS Attribution', 1000000.00),
(49, 23, 'At least one (1) activity conducted per campus', 250000.00),
(50, 23, 'PS Attribution', 200000.00),
(51, 24, 'Supplies and Materials', 50000.00),
(52, 24, 'PS Attribution', 170000.00),
(53, 25, 'Supplies and Materials', 10000.00),
(54, 25, 'Meals & Snack/ Professional Fee', 233796.24),
(55, 25, 'PS', 10000.00),
(56, 26, 'PS', 567.00),
(57, 26, 'Teaching employees and COS', 51294972.71),
(58, 26, 'Teaching Overload', 7000000.00),
(59, 27, 'PS on Procurement Process', 30992.09),
(60, 27, 'PS Attribution: Execom & TWG members', 7219424.00),
(61, 27, 'Supplies Equipment and Materials', 300000.00),
(62, 27, 'PS of GAD Director(50%)', 501954.00);

-- --------------------------------------------------------

--
-- Table structure for table `gpb_items`
--

CREATE TABLE `gpb_items` (
  `id` int(11) NOT NULL,
  `fiscal_year` int(11) NOT NULL,
  `section` enum('client_focused','organization_focused','attributed_program','client','org','attributed') NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `mandate` text DEFAULT NULL,
  `cause` text DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `result` text DEFAULT NULL,
  `ppa` text DEFAULT NULL,
  `mfo` text DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `targets` text DEFAULT NULL,
  `indicators` text DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT 0.00,
  `source` varchar(50) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `budget_lines` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`budget_lines`)),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpb_items`
--

INSERT INTO `gpb_items` (`id`, `fiscal_year`, `section`, `sort_order`, `mandate`, `cause`, `objective`, `result`, `ppa`, `mfo`, `activity`, `targets`, `indicators`, `budget`, `source`, `office`, `responsible`, `budget_lines`, `created_at`, `updated_at`) VALUES
(1, 2026, 'client', 1, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination', 'To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.', 'To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.', 'Higher Education Program', 'Higher Education Program', 'Implementation of Affirmative Action Agenda', 'Number of served disadvantaged students - 100% disadvantaged students', 'Number of served disadvantaged students - 100% disadvantaged students', 700000.00, 'GAA', 'OSS', 'OSS', '[{\"id\":\"c1-l1\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"},{\"id\":\"c1-l2\",\"label\":\"Supplies and Materials\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(2, 2026, 'client', 2, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'High tuition and miscellaneous fees, compounded by socio-cultural expectations for women to prioritize domestic roles over education', 'To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.', 'To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.', 'Higher Education Program', 'Higher Education Program', 'Provision of free tuition fee under RA 10931 to eligible male and female students of the university.', 'Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.', 'Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.', 131100000.00, 'GAA', 'OSS, OUR, UHS', 'OSS, OUR, UHS', '[{\"id\":\"c2-l1\",\"label\":\"Tuition Fee\",\"amount\":131100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(3, 2026, 'client', 3, 'CHED Memorandum Order No. 01 series 2015', 'Limited activities to increase awareness of men and women students to GAD-related information (1st year students, transferees)', 'To increase the students level of awareness and appreciation on GAD', 'To increase the students level of awareness and appreciation on GAD', 'Higher Education Program', 'Higher Education Program', 'Conduct GAD orientation/ forum/ seminar to BSU 1st year/ transferees students (face to face/ online: 14 colleges)', 'No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)', 'No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)', 453363.26, 'GAA', 'OSS, GAD Office, 3 Campuses (La Trinidad, Bokod & Buguias Campus)', 'OSS, GAD Office, 3 Campuses (La Trinidad, Bokod & Buguias Campus)', '[{\"id\":\"c3-l1\",\"label\":\"Meals and Snack\",\"amount\":318800,\"source\":\"GAA\"},{\"id\":\"c3-l2\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"c3-l3\",\"label\":\"PS Attribution\",\"amount\":124563.26,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(4, 2026, 'client', 4, 'CHED Memorandum Order No. 01 series 2015', 'Student leaders have limited understanding on GAD in the University', 'To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'Higher Education Program', 'Higher Education Program', 'Continuous conduct of GAD responsive leadership training for student', 'No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)', 150000.00, 'GAA', 'OSS', 'OSS', '[{\"id\":\"c4-l1\",\"label\":\"Supplies and Materials\",\"amount\":30000,\"source\":\"GAA\"},{\"id\":\"c4-l2\",\"label\":\"Snack\",\"amount\":20000,\"source\":\"GAA\"},{\"id\":\"c4-l3\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(5, 2026, 'client', 5, 'Part VII of CHED Memorandum Order Number 1, series 2015 on Gender-Responsive Research and Extension Program', 'Presence of gender inequality, poverty and GAD-related concerns in the community', 'To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development', 'To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development', 'Extension Services / Research Services / Advance Education Services / Higher Education Services', 'Extension Services / Research Services / Advance Education Services / Higher Education Services', 'Conduct of Extension project/ activities to partner organizational/ communities as component of Gender Responsive Extension Program (GREP) to partner organization/ communities', 'No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male:500)', 'No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male:500)', 3500000.00, 'GAA', 'Research and Extension, various offices/ colleges in the University/ external campuses', 'Research and Extension, various offices/ colleges in the University/ external campuses', '[{\"id\":\"c5-l1\",\"label\":\"Seminar Package \\/ Meals & Snacks \\/ Fuel for Transportation \\/ Vehicle Rental \\/ Other Professional Services\",\"amount\":2500000,\"source\":\"GAA\"},{\"id\":\"c5-l2\",\"label\":\"PS Attribution\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(6, 2026, 'client', 6, 'Limited access of PWDs to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)', 'Limited access of PWDs to gender-responsive programs and services', 'Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.', 'Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Awareness of women PWDs who benefited from the program', 'Number of women PWDs who benefited from the program - F/M. No. of GAD program/project/activity provided for PWD - At least 1 program/project/activity', 'Number of women PWDs who benefited from the program - F/M. No. of GAD program/project/activity provided for PWD - At least 1 program/project/activity', 350000.00, 'GAA', 'HRMO, OSS', 'HRMO, OSS', '[{\"id\":\"c6-l1\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"c6-l2\",\"label\":\"Supplies & Materials \\/ Meals & Snacks\",\"amount\":250000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(7, 2026, 'client', 7, 'Lack of senior citizens access to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)', 'Absence of sustainable and gender-responsive university programs for senior citizens.', 'Improved access of senior citizens to gender-responsive, inclusive, and empowering programs and services', 'Improved access of senior citizens to gender-responsive, inclusive, and empowering programs and services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Program: BSU Kalinga for women Senior Citizens', 'Number of Programs provided for Senior Citizens - At least 1 program for BSU. Number of women senior citizens who benefited from the program - F21 M23', 'Number of Programs provided for Senior Citizens - At least 1 program for BSU. Number of women senior citizens who benefited from the program - F21 M23', 250000.00, 'GAA', 'GAD Office, Colleges, External Campuses', 'GAD Office, Colleges, External Campuses', '[{\"id\":\"c7-l1\",\"label\":\"Supplies & Materials \\/ Meals & Snacks\",\"amount\":250000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(8, 2026, 'client', 8, 'Low number of women\'s participation in sports / MCW-IRR Section 14', 'Minimal attendance of female students to competitive and non-competitive sports', 'To increase female students level of participation and awareness on Gender in Sports', 'To increase female students level of participation and awareness on Gender in Sports', 'Higher Education Program', 'Higher Education Program', 'Participate in sports activities targeted for female students', 'No. of sports activities supported through allocation of budget for sports and socio-cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival) - 2 sports activities (Female:20)', 'No. of sports activities supported through allocation of budget for sports and socio-cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival) - 2 sports activities (Female:20)', 160000.00, 'GAA', 'CHK', 'CHK', '[{\"id\":\"c8-l1\",\"label\":\"Registration & Travelling Expenses\",\"amount\":80000,\"source\":\"GAA\"},{\"id\":\"c8-l2\",\"label\":\"Meals and Snacks\",\"amount\":60000,\"source\":\"GAA\"},{\"id\":\"c8-l3\",\"label\":\"PS Attribution\",\"amount\":20000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(9, 2026, 'org', 9, 'Programs on Awards and Incentives for Service Excellence (PRAISE, CSC Res. No. 010112 and CSC MC No.1, s.2001); GFPS Memo Circular No. 2011-01; BOR Res. No. 2316, s.2014', 'Low recognition/appreciation on the Gender Mainstreaming in BSU', 'Strengthen Gender Mainstreaming through recognition of GAD implementation in the University', 'Strengthen Gender Mainstreaming through recognition of GAD implementation in the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provide recognition and award to GAD implementer and other GAD-related award (GAD implementer for students and employees, GAD Advocate Award)', 'No. of award will be provided through BSU-PRAISE - At least 1 GAD Advocate award will be provided through BSU-PRAISE', 'No. of award will be provided through BSU-PRAISE - At least 1 GAD Advocate award will be provided through BSU-PRAISE', 205000.00, 'GAA', 'HRDO, HRMO, BSU-PRAISE Committee, GAD Office', 'HRDO, HRMO, BSU-PRAISE Committee, GAD Office', '[{\"id\":\"o9-l1\",\"label\":\"Incentive GAD Advocate Award\",\"amount\":5000,\"source\":\"GAA\"},{\"id\":\"o9-l2\",\"label\":\"PS Attribution\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(10, 2026, 'org', 10, 'Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production / Magna Carta of Women (RA 9710)', 'Low awareness among personnel in the University about GAD mainstreaming', 'To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production', 'To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct GAD related Gender Mainstreaming capability building and competency acquisition', 'No. of training/workshop/seminars conducted - 25 training/workshop/seminars/Learning and Development (F:1500 M:1000)', 'No. of training/workshop/seminars conducted - 25 training/workshop/seminars/Learning and Development (F:1500 M:1000)', 4000000.00, 'GAA', 'GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses', 'GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses', '[{\"id\":\"o10-l1\",\"label\":\"Seminar Package \\/ Meals & Snacks \\/ Fuel for Transportation \\/ Vehicle Rental \\/ Professional Services (La Trinidad, Bokod, Buguias)\",\"amount\":3500000,\"source\":\"GAA\"},{\"id\":\"o10-l2\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(11, 2026, 'org', 11, 'Magna Carta of Women IRR Section 37 - Gender Mainstreaming as a Strategy for Implementing the Magna Carta of Women', 'Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or from participating in capability enhancement sessions', 'Inadequate support services to personnel and students with children', 'Inadequate support services to personnel and students with children', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Operationalize of BSU College of Nursing Reproductive Health Care Center', 'No. of maintained Reproductive Health Care Center - 1 maintained BSU CN Reproductive Health Care Center', 'No. of maintained Reproductive Health Care Center - 1 maintained BSU CN Reproductive Health Care Center', 120000.00, 'GAA', 'College of Nursing', 'College of Nursing', '[{\"id\":\"o11-l1\",\"label\":\"Supplies and Materials\",\"amount\":20000,\"source\":\"GAA\"},{\"id\":\"o11-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(12, 2026, 'org', 12, 'Executive Order No. 340 s.1997 - Directing National Government Agencies and GOCCs to provide Day Care Services for their Employees\' Children under five years of age', 'Problems of parents and students related to child care', 'Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism', 'Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Maintenance of Child Minding Center for working parents in ensuring that they have a safe place to leave their child while they are at their work places', 'No. of established child minding center - Fully maintained new established and existing child minding centers at BSU La Trinidad, Bokod Campus and Buguias Campus', 'No. of established child minding center - Fully maintained new established and existing child minding centers at BSU La Trinidad, Bokod Campus and Buguias Campus', 230000.00, 'GAA', 'GAD Office, External Campuses', 'GAD Office, External Campuses', '[{\"id\":\"o12-l1\",\"label\":\"Supplies and Materials\",\"amount\":130000,\"source\":\"GAA\"},{\"id\":\"o12-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(13, 2026, 'org', 13, 'Magna Carta of Women (RA 9710)', 'Low integration of gender mainstreaming of BSU', 'To strengthen the GAD integration in the operations of BSU', 'To strengthen the GAD integration in the operations of BSU', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Create a Monitoring Team to conduct monitoring and evaluation of the utilization/outcome of GAD PAPs and ensure effectiveness of the GAD PAPs', 'No. of monitoring and assessment meetings with reports conducted - 4 monitoring and assessment meetings with reports conducted', 'No. of monitoring and assessment meetings with reports conducted - 4 monitoring and assessment meetings with reports conducted', 330000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o13-l1\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"o13-l2\",\"label\":\"PS Attribution\",\"amount\":320000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(14, 2026, 'org', 14, 'Magna Carta of Women IRR Section 37-C - Creation and/or Strengthening of the GAD Focal Points (GFPs)', 'Low level of capacity of GFPS to develop and implement GAD programs and activities due to new members', 'Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University', 'Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'For GFPS/Secretariat: GMEF/HGDG/GPB/GAD Agenda/GAD Deepening Session and TOT among other related trainings and capacity building activities (Regional/National GAD-related trainings/seminars/forum/workshop)', 'No. training/seminars/workshop attendance for each GFPS-member on GAD related updates and mandates - At least 1 each (Female:31, Male:15)', 'No. training/seminars/workshop attendance for each GFPS-member on GAD related updates and mandates - At least 1 each (Female:31, Male:15)', 896000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o14-l1\",\"label\":\"GFPS TWG PAPs\",\"amount\":396000,\"source\":\"GAA\"},{\"id\":\"o14-l2\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(15, 2026, 'org', 15, 'Section 37-C2 Rule VI of the Magna Carta of Women\'s IRR on duties and function of the GAD Focal Point System / Magna Carta of Women (RA 9710)', 'Compliance to provisions regarding regular monitoring of gender mainstreaming efforts', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Regular coordination and meetings of GAD-GFPS (Execom, GFPS-TWG members and external campus TWG members) and emergency meeting when necessary', 'No. of reports on regular meetings per campus - At least 6 reports available at year end, plus RGADC quarterly meeting/s', 'No. of reports on regular meetings per campus - At least 6 reports available at year end, plus RGADC quarterly meeting/s', 211720.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o15-l1\",\"label\":\"Meals & Snack\",\"amount\":111720,\"source\":\"GAA\"},{\"id\":\"o15-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(16, 2026, 'org', 16, 'Duties and function of the GAD Focal Point System / CHED Memo 2015-1', 'No plantilla personnel assigned to plan, implement and monitor GAD PAPs on a full-time basis', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Engage support staff to assist in the implementation of GFPS PPA\'s and Gender Mainstreaming in the university through rehiring of GAD staff and Student Assistant', 'Salary of GAD Staff: Casual. No. of rehired personnel - At least 2 staff renewed/rehired (Casual) and at least 3 Student Assistant/SPES per semester', 'Salary of GAD Staff: Casual. No. of rehired personnel - At least 2 staff renewed/rehired (Casual) and at least 3 Student Assistant/SPES per semester', 550000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o16-l1\",\"label\":\"Salary of GAD Staff and SPES \\/ Student Assistant\",\"amount\":550000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(17, 2026, 'org', 17, 'Low level of Awareness on Gender Mainstreaming (GM) among newly hired personnel / Magna Carta of Women (RA 9710), CHED Memo 2015-1', 'Lack of regular orientation and refresher training on gender sensitivity and GAD mandates', 'To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel', 'To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct Gender Sensitivity Training (GST) for newly hired and current personnel (continuing activity)', 'No. of trainings conducted - 1 training for at least 100% of newly hired personnel and 3 refresher trainings for current personnel', 'No. of trainings conducted - 1 training for at least 100% of newly hired personnel and 3 refresher trainings for current personnel', 421728.32, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o17-l1\",\"label\":\"Meals & Snack\",\"amount\":167200,\"source\":\"GAA\"},{\"id\":\"o17-l2\",\"label\":\"Token\",\"amount\":4000,\"source\":\"GAA\"},{\"id\":\"o17-l3\",\"label\":\"Professional Fee\",\"amount\":144528.32,\"source\":\"GAA\"},{\"id\":\"o17-l4\",\"label\":\"Supplies and Materials\",\"amount\":6000,\"source\":\"GAA\"},{\"id\":\"o17-l5\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(18, 2026, 'org', 18, 'Part V, Rule II, Section 4 of CHED Memorandum Order No. 1 Series of 2015', 'Limited number of GAD library and related learning materials across various disciplines', 'To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels', 'To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of knowledge products (books, magazines, multi-media) for adequate and accessible library and related learning materials in support to gender-responsive Curriculum Programs', 'No. of procured library and learning materials - 200 books', 'No. of procured library and learning materials - 200 books', 2600000.00, 'GAA', 'ULIS', 'ULIS', '[{\"id\":\"o18-l1\",\"label\":\"Books and Instructional Materials\",\"amount\":2500000,\"source\":\"GAA\"},{\"id\":\"o18-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(19, 2026, 'org', 19, 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'Presence of Gender Based Violence (GBV) issues/reports/cases in the university', 'Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence', 'Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'Official Publication with GAD articles/pictures. Maintained GAD Bulletin board - At least 8. Sector-specific downloadable knowledge products - At least 2', 'Official Publication with GAD articles/pictures. Maintained GAD Bulletin board - At least 8. Sector-specific downloadable knowledge products - At least 2', 296000.00, 'GAA', 'UPAO, GAD Office', 'UPAO, GAD Office', '[{\"id\":\"o19-l1\",\"label\":\"Shamag\",\"amount\":96000,\"source\":\"GAA\"},{\"id\":\"o19-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"o19-l3\",\"label\":\"Supplies and Materials\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(20, 2026, 'org', 20, 'Institutionalizing GAD database and Sex-Disaggregated Database / Magna Carta of Women (RA 9710) Section 36 on Sex-Disaggregated Database', 'Minimal awareness and appreciation on the relevance of the centralized Sex-Disaggregated database', 'To establish a centralized GAD-related database of the University', 'To establish a centralized GAD-related database of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Updating of Sex-Disaggregated Data (SDD) and other data related to personnel/students/clients for gender analysis and report preparation', '1 well-organized and maintained GAD database. Establishment of GAD-Database system per college/unit.', '1 well-organized and maintained GAD database. Establishment of GAD-Database system per college/unit.', 1100000.00, 'GAA', 'ICT, GAD Office', 'ICT, GAD Office', '[{\"id\":\"o20-l1\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"o20-l2\",\"label\":\"Maintenance of SDD \\/ Internet connection\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(21, 2026, 'org', 21, 'Magna Carta for Women, Chapter IV Section 10 and RA 10121, Section 2 & 9', 'Limited resources of the DSWD and LGU to provide for students who are transient residents and limited appreciation on women\'s role in nation building among employees and students, especially new ones', 'To ensure that disaster assistance provided to distressed students are gender-responsive', 'To ensure that disaster assistance provided to distressed students are gender-responsive', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of gender-responsive services to employees and students who experienced crisis/disaster (e.g. distribution of hygiene kits for both women and men)', 'No. of pax of the most affected employees/students during crises - 1,000', 'No. of pax of the most affected employees/students during crises - 1,000', 210000.00, 'GAA', 'GAD Office, HDRO, NSTP, various offices/all colleges in the University', 'GAD Office, HDRO, NSTP, various offices/all colleges in the University', '[{\"id\":\"o21-l1\",\"label\":\"Crisis pack (\\u20b1200\\/pack x 1,000 pax)\",\"amount\":200000,\"source\":\"GAA\"},{\"id\":\"o21-l2\",\"label\":\"PS TWG Members\",\"amount\":10000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(22, 2026, 'org', 22, 'RA 9710 Sec.18 Special Leave for Women; RA 8187 Paternity Leave; RA 8972 Sec.8 Solo Parents Welfare Act; RA 9262 Sec.43 Anti-VAWC Act', 'Employees may require special leaves due to parental obligations, health concerns and other circumstances', 'Enhanced support services for employees in need of special leaves', 'Enhanced support services for employees in need of special leaves', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of gender leaves and conduct of Seminar on Gender Related Leaves for Newly Hired Employees', 'No. of Maternity, Paternity, Solo parent, gynecological, VAWC leaves availed and 1 Seminar conducted (M:20 F:50) - 100%', 'No. of Maternity, Paternity, Solo parent, gynecological, VAWC leaves availed and 1 Seminar conducted (M:20 F:50) - 100%', 1000000.00, 'GAA', 'HRMO, CBOO, various offices/colleges in the University', 'HRMO, CBOO, various offices/colleges in the University', '[{\"id\":\"o22-l1\",\"label\":\"PS Attribution\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(23, 2026, 'org', 23, 'Proclamation 227 (Women\'s Role in History Month) and Proclamation 1172, s.2006 (18-Day Campaign to End VAW)', 'The need to highlight women\'s rights, their role in nation building, and to provide a platform against VAW, gender-based violence, Safe Spaces Act (RA 11313)', 'To strengthen awareness of BSU students/employees on women\'s rights and their role in national development and nation building', 'To strengthen awareness of BSU students/employees on women\'s rights and their role in national development and nation building', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Participation to 18-Day Campaign to end VAW and Women\'s Month Celebration/activities and programs organized by PCW and other agency/ies', 'No. of activities conducted per campus - At least 1 per campus', 'No. of activities conducted per campus - At least 1 per campus', 450000.00, 'GAA', 'GAD Office, various offices/colleges in the University/external campus', 'GAD Office, various offices/colleges in the University/external campus', '[{\"id\":\"o23-l1\",\"label\":\"Activities \\/ Programs\",\"amount\":250000,\"source\":\"GAA\"},{\"id\":\"o23-l2\",\"label\":\"PS Attribution\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(24, 2026, 'org', 24, 'Productivity of employees affected due to filial obligations / Magna Carta of Women IRR Section 37', 'Inadequate support services for personnel/students with young children and breastfeeding mothers (RA 10028)', 'Inadequate support services to personnel and students with children', 'Inadequate support services to personnel and students with children', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Establishment/maintenance of breastfeeding station established in the preceding years', 'Fully maintained Lactation rooms - 100% at BSU La Trinidad, Bokod and Buguias Campus', 'Fully maintained Lactation rooms - 100% at BSU La Trinidad, Bokod and Buguias Campus', 220000.00, 'GAA', 'GAD Office, External Campuses', 'GAD Office, External Campuses', '[{\"id\":\"o24-l1\",\"label\":\"Supplies and Materials\",\"amount\":50000,\"source\":\"GAA\"},{\"id\":\"o24-l2\",\"label\":\"PS Attribution\",\"amount\":170000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(25, 2026, 'org', 25, 'RA 9710, PCW-NEDA-DBM Joint Circular 2012-01, CSC MC No.12 s.2005', 'Lack of regular gender-related capacity-building activities and insufficient integration of gender sensitivity in employee development programs', 'To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace', 'To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct of gender sensitivity orientations for BSU Personnel (continuing activity)', 'No. of trainings conducted for BSU personnel - At least 3', 'No. of trainings conducted for BSU personnel - At least 3', 253796.24, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o25-l1\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"o25-l2\",\"label\":\"Meals & Snack \\/ Professional Fee\",\"amount\":233796.24,\"source\":\"GAA\"},{\"id\":\"o25-l3\",\"label\":\"PS\",\"amount\":10000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(26, 2026, 'org', 26, 'Establishment of Gender-Responsive Curricular Programs / Part V of CMO 01, s.2015', 'Limited subject for GAD Integration of Gender-Responsive Instruction and Curriculum Development', 'Integration of gender mainstreaming in curriculum/subjects in all levels', 'Integration of gender mainstreaming in curriculum/subjects in all levels', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Preparation of syllabi and classroom teaching integrating gender perspective', 'Number of faculty members integrating gender perspective in the syllabus - 567 permanent and 125 COS females/males', 'Number of faculty members integrating gender perspective in the syllabus - 567 permanent and 125 COS females/males', 58294972.71, 'GAA', 'GAD Office, GFPS-TWG members, all colleges', 'GAD Office, GFPS-TWG members, all colleges', '[{\"id\":\"o26-l1\",\"label\":\"PS - 567 Teaching employees and COS\",\"amount\":51294972.71,\"source\":\"GAA\"},{\"id\":\"o26-l2\",\"label\":\"Teaching Overload\",\"amount\":7000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(27, 2026, 'org', 27, 'Section 37-C2 of the Magna Carta of Women (MCW) IRR - mandates establishment of a GAD Focal Point System (GFPS)', 'Sustained operations of the existing GAD Office - administrative, logistical, and financial support for day-to-day functioning; maintenance of GAD database; coordination of GFPS and GAD-related activities across colleges and units', 'To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.', 'To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Sustaining Gender Mainstreaming and Institutional Support in the University', 'Fully maintained GAD Office - 100%', 'Fully maintained GAD Office - 100%', 8052370.09, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o27-l1\",\"label\":\"PS on Procurement Process\",\"amount\":30992.09,\"source\":\"GAA\"},{\"id\":\"o27-l2\",\"label\":\"PS Attribution: Execom & TWG members\",\"amount\":7219424,\"source\":\"GAA\"},{\"id\":\"o27-l3\",\"label\":\"Supplies, Equipment and Materials\",\"amount\":300000,\"source\":\"GAA\"},{\"id\":\"o27-l4\",\"label\":\"PS of GAD Director (50%)\",\"amount\":501954,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(28, 2026, 'attributed', 28, '', '', '', '', '', '', 'Transportation Equipment Outlay', '', '', 12285000.00, 'GAA', 'TASU, PMO, SPMO', 'TASU, PMO, SPMO', '[{\"id\":\"a28-l1\",\"label\":\"Transportation Equipment Outlay\",\"amount\":12285000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(29, 2026, 'attributed', 29, '', '', '', '', '', '', 'Repair and Maintenance of Office Building and other Structures', '', '', 6402000.00, 'GAA', 'PU, PMO, SPMO', 'PU, PMO, SPMO', '[{\"id\":\"a29-l1\",\"label\":\"Repair and Maintenance - Office Building and other Structures\",\"amount\":6402000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(30, 2026, 'attributed', 30, '', '', '', '', '', '', 'Bamboo Industry Development for Environment Conservation and Countryside', '', '', 3750000.00, 'GAA', 'College of Forestry', 'College of Forestry', '[{\"id\":\"a30-l1\",\"label\":\"Bamboo Industry Development for Environment Conservation and Countryside\",\"amount\":3750000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(31, 2026, 'attributed', 31, '', '', '', '', '', '', 'Benguet State University Student Information and Accounting System (SIAS)', '', '', 5000000.00, 'GAA', 'ICT, PMO', 'ICT, PMO', '[{\"id\":\"a31-l1\",\"label\":\"Student Information and Accounting System (SIAS)\",\"amount\":5000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30');

-- --------------------------------------------------------

--
-- Table structure for table `gpb_offices_map`
--

CREATE TABLE `gpb_offices_map` (
  `gpb_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `gpb_offices_map`
--
DELIMITER $$
CREATE TRIGGER `trg_sync_offices` AFTER INSERT ON `gpb_offices_map` FOR EACH ROW BEGIN
    UPDATE gad_plan_budget
    SET responsible_unit_office = (
        SELECT GROUP_CONCAT(o.office_name SEPARATOR ', ')
        FROM office_units o
        JOIN gpb_offices_map gom ON o.office_id = gom.office_id
        WHERE gom.gpb_id = NEW.gpb_id
    )
    WHERE gpb_id = NEW.gpb_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_sync_offices_delete` AFTER DELETE ON `gpb_offices_map` FOR EACH ROW BEGIN
    UPDATE gad_plan_budget
    SET responsible_unit_office = (
        SELECT GROUP_CONCAT(o.office_name SEPARATOR ', ')
        FROM office_units o
        JOIN gpb_offices_map gom ON o.office_id = gom.office_id
        WHERE gom.gpb_id = OLD.gpb_id
    )
    WHERE gpb_id = OLD.gpb_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_sync_offices_update` AFTER UPDATE ON `gpb_offices_map` FOR EACH ROW BEGIN
    UPDATE gad_plan_budget
    SET responsible_unit_office = (
        SELECT GROUP_CONCAT(o.office_name SEPARATOR ', ')
        FROM office_units o
        JOIN gpb_offices_map gom ON o.office_id = gom.office_id
        WHERE gom.gpb_id = NEW.gpb_id
    )
    WHERE gpb_id = NEW.gpb_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `recipient_id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `message_text` text NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `document_id` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `deleted_by_sender_at` datetime DEFAULT NULL,
  `deleted_by_recipient_at` datetime DEFAULT NULL,
  `is_announcement` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-19-022849', 'App\\Database\\Migrations\\CreateMessagesTable', 'default', 'App', 1781836185, 1),
(2, '2026-06-19-033911', 'App\\Database\\Migrations\\AlterMessagesDocumentId', 'default', 'App', 1781840369, 2),
(3, '2026-06-19-063936', 'App\\Database\\Migrations\\AddThreadAndTrashToMessages', 'default', 'App', 1781851202, 3),
(4, '2026-06-19-082034', 'App\\Database\\Migrations\\AddDeletedAtToDocuments', 'default', 'App', 1781857260, 4),
(5, '2026-06-23-025857', 'App\\Database\\Migrations\\CreateActivityLogsTable', 'default', 'App', 1782183569, 5),
(6, '2026-06-24-000000', 'App\\Database\\Migrations\\AddLastLoginToUsers', 'default', 'App', 1782263806, 6),
(7, '2026-06-24-000001', 'App\\Database\\Migrations\\AddIsAnnouncementToMessages', 'default', 'App', 1782264591, 7),
(8, '2026-06-26-014303', 'App\\Database\\Migrations\\DropTitleFromMessages', 'default', 'App', 1782438211, 8),
(9, '2026-06-28-000000', 'App\\Database\\Migrations\\AddBudgetTrackingTables', 'default', 'App', 1782635571, 9),
(10, '2026-06-28-120229', 'App\\Database\\Migrations\\AddDynamicFormTables', 'default', 'App', 1782649887, 10),
(11, '2026-06-28-124500', 'App\\Database\\Migrations\\AlterGadMandatesAndIssues', 'default', 'App', 1782650786, 11),
(12, '2026-07-06-140440', 'App\\Database\\Migrations\\ConsolidateUserProfiles', 'default', 'App', 1783346776, 12),
(13, '2026-07-06-224628', 'App\\Database\\Migrations\\ImplementSoftDeletes', 'default', 'App', 1783378166, 13),
(14, '2026-07-07-065600', 'App\\Database\\Migrations\\NormalizeBudgetItems', 'default', 'App', 1783378659, 14),
(15, '2026-07-06-230544', 'App\\Database\\Migrations\\NormalizeEvaluationResults', 'default', 'App', 1783379201, 15),
(16, '2026-07-06-231227', 'App\\Database\\Migrations\\NormalizeGpbBudgetBreakdown', 'default', 'App', 1783379574, 16),
(17, '2026-07-06-232214', 'App\\Database\\Migrations\\DropGpbBudgetTriggers', 'default', 'App', 1783380163, 17),
(18, '2026-07-06-233134', 'App\\Database\\Migrations\\DropLegacyMandateTable', 'default', 'App', 1783380715, 18),
(19, '2026-07-08-025213', 'App\\Database\\Migrations\\AddModificationFieldsToActivityDesign', 'default', 'App', 1783479262, 19),
(20, '2026-07-13-070642', 'App\\Database\\Migrations\\CreateGpbItemsTable', 'default', 'App', 1784032286, 20),
(21, '2026-07-13-124813', 'App\\Database\\Migrations\\AddArchivedAtToDocuments', 'default', 'App', 1784032286, 20),
(22, '2026-07-14-122738', 'App\\Database\\Migrations\\CreateBudgetItemMandateAllocationsTable', 'default', 'App', 1784032286, 20),
(23, '2026-07-17-001059', 'App\\Database\\Migrations\\CreateArchivedAnnualReportsTable', 'default', 'App', 1784247144, 21),
(24, '2026-07-17-031815', 'App\\Database\\Migrations\\AddIsInsideBsuToVenuesAndSubmissions', 'default', 'App', 1784258396, 22),
(25, '2026-07-27-021925', 'App\\Database\\Migrations\\AddGpbBudgetLineIdToAllocations', 'default', 'App', 1785118785, 23);

-- --------------------------------------------------------

--
-- Table structure for table `office_units`
--

CREATE TABLE `office_units` (
  `office_id` int(11) NOT NULL,
  `office_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_units`
--

INSERT INTO `office_units` (`office_id`, `office_name`) VALUES
(25, 'Accounting Office'),
(28, 'Bokod Focal Person'),
(23, 'BSU Office of Student Services'),
(14, 'Budget Office'),
(31, 'Budget Office Buguias Campus'),
(29, 'Buguias Focal Person'),
(2, 'College of Agriculture'),
(8, 'College of Applied Techonology BSU Bokod Campus'),
(36, 'College of Arts and Humanities'),
(45, 'College of Education BSU Bokod Campus'),
(21, 'College of Engineering'),
(46, 'College of Forestry'),
(39, 'College of Home Economics and Technology'),
(26, 'College of Human Kenetics'),
(32, 'College of Information Sciences'),
(17, 'College of Natural Sciences'),
(42, 'College of Numeracy and Applied Sciences'),
(22, 'College of Nursing'),
(18, 'College of Public Administration and Governance'),
(7, 'College of Social Science'),
(37, 'College of Teacher Education'),
(11, 'College of Veterinary Medicine'),
(12, 'Compensarion, Benefits and Other Obligations'),
(6, 'Disaster Risk Reduction Management'),
(1, 'Gender and Development Office'),
(20, 'General Services Office'),
(27, 'Horticulture'),
(38, 'Human Resource and Management Office'),
(4, 'Human Resources and Management Office BSU Bokod Campus'),
(30, 'Human Resources Development Office'),
(19, 'Information and Communications Technolgy'),
(5, 'International Relations Office'),
(43, 'Northern Philippines Root Crops Research  & Training Center'),
(15, 'Office for Quality Assurance and Accreditation'),
(35, 'Office of Student Services'),
(44, 'Open University'),
(34, 'Procurement Management Office'),
(33, 'Procurement Management Office BSU Bokod Campus'),
(13, 'Records Office and Archives'),
(3, 'Registrar\'s Office BSU Buguias Campus'),
(40, 'Supply Property Management Office'),
(9, 'University Business Affairs Office'),
(16, 'University Health Services BSU Buguias Campus'),
(10, 'University Library and Information Service BSU Buguias Campus'),
(41, 'University Library and Information Services'),
(24, 'University Public Affairs Office');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `fiscal_year`, `created_at`, `updated_at`) VALUES
(1, 'name', 'Benguet State University', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(2, 'category', 'State Universities and Colleges', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(3, 'hierarchy', 'Benguet State University', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(4, 'year', '2026', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(5, 'totalOrgBudget', '1062488000', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(6, 'otherSources', '0', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(7, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(8, 'approvedByName', 'Kenneth Alip Laruan, President', 2026, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(9, 'name', 'Benguet State University', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(10, 'category', 'State Universities and Colleges', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(11, 'hierarchy', 'Benguet State University', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(12, 'year', '2027', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(13, 'totalOrgBudget', '1062488000', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(14, 'otherSources', '0', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(15, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(16, 'approvedByName', 'Kenneth Alip Laruan, President', 2027, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(17, 'name', 'Benguet State Universityy', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(18, 'category', 'State Universities and Collegess', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(19, 'hierarchy', 'Benguet State Universityy', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(20, 'year', '20264', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(21, 'totalOrgBudget', '10624880000', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(22, 'otherSources', '1', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(23, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(24, 'approvedByName', 'Kenneth Alip Laruan, President', 20264, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(25, 'baseline_amounts', '{\"meals_inside\":220,\"meals_outside\":350,\"snacks_inside\":85,\"snacks_outside\":150,\"pf_honoraria\":2258.25,\"tokens\":1000,\"materials\":1000,\"transportation_limit\":20000}', 0, '2026-07-17 04:01:46', '2026-07-17 04:05:34'),
(26, 'system_settings', '{\"ad_submission_limit_enabled\":false}', 0, '2026-07-20 02:07:20', '2026-07-26 02:08:04'),
(27, 'last_cleanup_run', '2026-07-27 02:06:03', 0, '2026-07-23 04:57:55', '2026-07-27 02:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `user_acronym` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `reset_token`, `reset_token_expires_at`, `role`, `full_name`, `student_id`, `office_id`, `year_level`, `user_acronym`, `remember_token`, `deleted_at`, `created_at`, `updated_at`, `last_login`, `first_name`, `middle_name`, `last_name`, `profile_role`) VALUES
(1, 'Gender and Development Office', 'gad.office@bsu.edu.ph', NULL, '$2y$10$a9XVQgTdygySA0E7XCNf4euNdZmuXjqGxSvUbQEzd5X7qiFmPNae6', NULL, NULL, 'admin', 'Jude Tayaben', NULL, 1, NULL, 'GAD', NULL, NULL, '2026-05-25 11:58:10', '2026-07-27 12:15:09', '2026-07-27 12:15:09', '', NULL, '', 'Director'),
(2, 'College of Agriculture', 'ca@bsu.edu.ph', NULL, '$2y$10$CKShTYh97GNm4C1Y20XFneDWhDBXhvtNyUwftPM9aDAbz4u9mz6Jy', NULL, NULL, 'twg', 'CA TWG', NULL, 2, NULL, 'CA', NULL, NULL, '2026-05-25 11:58:10', '2026-07-27 12:15:21', '2026-07-27 12:15:21', '', NULL, '', 'TWG'),
(3, 'Registrar\'s Office BSU Buguias Campus', 'buguias.registrar@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'college', 'George Pacyaden', NULL, 3, NULL, 'Buguias-RO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 23:38:18', '2026-06-29 04:00:21', '', NULL, '', 'TWG'),
(4, 'Human Resources and Management Office BSU Bokod Campus', 'bokod.hrmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 4, NULL, NULL, 'Bokod-HRMO', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(5, 'International Relations Office', 'iro@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 5, NULL, 'IRO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(6, 'Disaster Risk Reduction Management', 'drrm@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 6, NULL, 'DRRM', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(7, 'College of Social Science', 'css@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 7, NULL, 'CSS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(8, 'College of Applied Techonology BSU Bokod Campus', 'bokod.cat@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 8, NULL, 'Bokod-CAT', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(9, 'University Business Affairs Office', 'ubao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 9, NULL, 'UBAO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(10, 'University Library and Information Service BSU Buguias Campus', 'ulis.buguias@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 10, NULL, 'Buguias-ULIS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(11, 'College of Veterinary Medicine', 'vm@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 11, NULL, 'CVM', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(12, 'Compensarion, Benefits and Other Obligations', 'cboo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 12, NULL, 'CBOO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(13, 'Records Office and Archives', 'roa@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 13, NULL, 'ROA', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(14, 'Budget Office', 'bo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 14, NULL, NULL, 'BO', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(15, 'Office for Quality Assurance and Accreditation', 'oqaa@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 15, NULL, 'OQAA', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(16, 'University Health Services BSU Buguias Campus', 'buguias.uhs@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 16, NULL, 'Buguias-UHS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(17, 'College of Natural Sciences', 'cns@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 17, NULL, NULL, 'CNS', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(18, 'College of Public Administration and Governance', 'cpag@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 18, NULL, 'CPAG', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(19, 'Information and Communications Technolgy', 'ict@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 19, NULL, 'ICT', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(20, 'General Services Office', 'gso@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 20, NULL, 'GSO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(21, 'College of Engineering', 'ce@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 21, NULL, 'CE', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(22, 'College of Nursing', 'cn@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 22, NULL, 'CN', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(23, 'BSU Office of Student Services', 'oss@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 23, NULL, 'OSS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(24, 'University Public Affairs Office', 'upao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 24, NULL, 'UPAO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(25, 'Accounting Office', 'ao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 25, NULL, 'AO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(26, 'College of Human Kenetics', 'chk@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 26, NULL, 'CHK', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(27, 'Horticulture', 'h@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 27, NULL, 'Horticulture', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(28, 'Bokod Focal Person, University Health Services', 'bokod.uhs@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 28, NULL, 'Bokod-FC,UHS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(29, 'Buguias Focal Person, College of Agriculture', 'buguias.ca@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 29, NULL, 'Buguias-FC,CA', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(30, 'Human Resources Development Office', 'hrdo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 30, NULL, 'HRDO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(31, 'Budget Office Buguias Campus', 'buguias.bo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 31, NULL, 'Buguias-BO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(32, 'College of Information Sciences', 'cis@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 32, NULL, 'CIS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(33, 'Procurement Management Office BSU Bokod Campus', 'bokod.pmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 33, NULL, 'Bokod-PMO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(34, 'Procurement Management Office', 'pmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 34, NULL, 'PMO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(35, 'Office of Student Services', 'oss.2@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 35, NULL, 'OSS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(36, 'College of Arts and Humanities', 'cah@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 36, NULL, 'CAH', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(37, 'College of Teacher Education', 'cte@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 37, NULL, 'CTE', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(38, 'Human Resource and Management Office', 'hrmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 38, NULL, 'HRMO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(39, 'College of Home Economics and Technology', 'chet@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 39, NULL, 'CHET', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(40, 'Supply Property Management Office', 'spmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 40, NULL, 'SPMO', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(41, 'University Library and Information Services', 'ulis@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 41, NULL, 'ULIS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(42, 'College of Numeracy and Applied Sciences', 'cnas@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 42, NULL, 'CNAS', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(43, 'Northern Philippines Root Crops Research  & Training Center', 'nprcrtc@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 43, NULL, 'NPRCRTC', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(44, 'Open University', 'ou@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 44, NULL, 'OU', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(45, 'College of Education BSU Bokod Campus', 'bokod.ce@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 45, NULL, 'Bokod-CE', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(46, 'College of Forestry', 'cf@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 46, NULL, 'CF', NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(47, 'gad.staff', 'gad.staff@bsu.edu.ph', NULL, '$2y$12$fbD/jvk.znEQnBmKq4.ebOojmijHJO/zU7.P7Tzo.zV3FgvP8PzNe', NULL, NULL, 'gad_staff', 'GAD Staff', NULL, 1, NULL, 'GAD-STAFF', NULL, NULL, '2026-03-26 15:53:56', '2026-07-27 12:15:28', '2026-07-27 12:15:27', 'GAD', 'Staff', 'User', 'Staff'),
(51, 'marksantos', 'marksantos@gmail.com', NULL, '$2y$10$vEdSBaP5YNzsdUal1Ajwhuk/4moO5JVDu.I6VpCEG3N85F3KEimXe', NULL, NULL, 'non-twg', 'Mark Santos', NULL, 32, NULL, NULL, NULL, NULL, '2026-06-17 12:57:12', '2026-07-06 14:06:16', '2026-06-30 01:05:39', 'Mark', '', 'Santos', 'Non-TWG'),
(52, 'bisayotduligas', 'bisayotduligas@gmail.com', NULL, '$2y$10$YgMBMgszFRJ2dJBHbSAu.uFj1D9jXSQgs.Z8gMg0MSZ6C4Kd.FIF.', NULL, NULL, 'non-twg', 'Joshua Duligas', NULL, 32, NULL, NULL, NULL, NULL, '2026-06-25 02:46:48', '2026-07-06 14:06:16', '2026-06-25 07:39:59', '', NULL, '', 'Non-TWG');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(255) NOT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`venue_id`, `venue_name`, `is_inside_bsu`) VALUES
(1, 'BSU Gymnasium', 1),
(2, 'BSU Covered Court', 1),
(3, 'RDC Hall', 1),
(4, 'VP AdFin Hall', 1),
(5, 'International Dorm Hall', 1),
(6, 'IRO Hall', 1),
(7, 'Gladiola Center', 1),
(8, 'RSDC Executive Hall', 1),
(9, 'CTE DSG Hall', 1),
(10, 'CHET Hall', 1),
(11, 'CHK Function Hall', 1),
(12, 'Carnation Hall', 1),
(13, 'Everlasting Hall', 1),
(14, 'Solibao Hall', 1),
(15, 'Igorota Hall', 1),
(16, 'Dimas Hall, IHFSA', 1),
(17, 'OSS Social Hall', 1),
(18, 'Main Auditorium', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accomplishment_budget_items`
--
ALTER TABLE `accomplishment_budget_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_user` (`user_id`),
  ADD KEY `idx_ar_control_number` (`control_number`);

--
-- Indexes for table `activity_budget_items`
--
ALTER TABLE `activity_budget_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_classifications`
--
ALTER TABLE `activity_classifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_design`
--
ALTER TABLE `activity_design`
  ADD PRIMARY KEY (`act_design_id`),
  ADD KEY `fk_activity_user` (`user_id`),
  ADD KEY `fk_activity_gpb` (`gpb_id`);

--
-- Indexes for table `activity_design_issues`
--
ALTER TABLE `activity_design_issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `act_design_id` (`act_design_id`,`issue_id`);

--
-- Indexes for table `activity_design_mandates`
--
ALTER TABLE `activity_design_mandates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `act_design_id` (`act_design_id`,`mandate_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `archived_annual_reports`
--
ALTER TABLE `archived_annual_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_item_mandate_allocations`
--
ALTER TABLE `budget_item_mandate_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_item_id_item_type` (`budget_item_id`,`item_type`),
  ADD KEY `mandate_id` (`mandate_id`);

--
-- Indexes for table `budget_realignment_logs`
--
ALTER TABLE `budget_realignment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accomplishment_report_id` (`accomplishment_report_id`);

--
-- Indexes for table `form_types`
--
ALTER TABLE `form_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gad_plan_budget`
--
ALTER TABLE `gad_plan_budget`
  ADD PRIMARY KEY (`gpb_id`);

--
-- Indexes for table `gpb_budget_breakdown`
--
ALTER TABLE `gpb_budget_breakdown`
  ADD PRIMARY KEY (`breakdown_id`),
  ADD KEY `gpb_id` (`gpb_id`);

--
-- Indexes for table `gpb_items`
--
ALTER TABLE `gpb_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gpb_offices_map`
--
ALTER TABLE `gpb_offices_map`
  ADD PRIMARY KEY (`gpb_id`,`office_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_units`
--
ALTER TABLE `office_units`
  ADD PRIMARY KEY (`office_id`),
  ADD UNIQUE KEY `office_name` (`office_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_fiscal_year` (`key`,`fiscal_year`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_users_office_id` (`office_id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accomplishment_budget_items`
--
ALTER TABLE `accomplishment_budget_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `activity_budget_items`
--
ALTER TABLE `activity_budget_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=827;

--
-- AUTO_INCREMENT for table `activity_classifications`
--
ALTER TABLE `activity_classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `activity_design`
--
ALTER TABLE `activity_design`
  MODIFY `act_design_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `activity_design_issues`
--
ALTER TABLE `activity_design_issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT for table `activity_design_mandates`
--
ALTER TABLE `activity_design_mandates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=522;

--
-- AUTO_INCREMENT for table `archived_annual_reports`
--
ALTER TABLE `archived_annual_reports`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `budget_item_mandate_allocations`
--
ALTER TABLE `budget_item_mandate_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `budget_realignment_logs`
--
ALTER TABLE `budget_realignment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `form_types`
--
ALTER TABLE `form_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gad_plan_budget`
--
ALTER TABLE `gad_plan_budget`
  MODIFY `gpb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `gpb_budget_breakdown`
--
ALTER TABLE `gpb_budget_breakdown`
  MODIFY `breakdown_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `gpb_items`
--
ALTER TABLE `gpb_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=721;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `office_units`
--
ALTER TABLE `office_units`
  MODIFY `office_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_design`
--
ALTER TABLE `activity_design`
  ADD CONSTRAINT `fk_activity_gpb` FOREIGN KEY (`gpb_id`) REFERENCES `gad_plan_budget` (`gpb_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gpb_budget_breakdown`
--
ALTER TABLE `gpb_budget_breakdown`
  ADD CONSTRAINT `gpb_budget_breakdown_ibfk_1` FOREIGN KEY (`gpb_id`) REFERENCES `gad_plan_budget` (`gpb_id`) ON DELETE CASCADE;

--
-- Constraints for table `gpb_offices_map`
--
ALTER TABLE `gpb_offices_map`
  ADD CONSTRAINT `gpb_offices_map_ibfk_1` FOREIGN KEY (`gpb_id`) REFERENCES `gad_plan_budget` (`gpb_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gpb_offices_map_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `office_units` (`office_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_office_id` FOREIGN KEY (`office_id`) REFERENCES `office_units` (`office_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
