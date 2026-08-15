-- InfinityFree MySQL Schema and Chapters Data for Bhakta-Chintamani
-- Database: if0_42640441_bhaktchintamani

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','admin','editor','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'editor',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin User: admin@readverse.com / password
INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@readverse.com', 'superadmin', '$2y$12$eA5u9qCcfR1w2wR7I3Lp4.q2f4I2P4B9L1yq5K6X7a8b9c0d1e2f3', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------
-- Table structure for table `chapters`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chapters` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_gu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_gu` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_gu` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_en` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL,
  `status` enum('published','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapters_order_index` (`order`),
  KEY `chapters_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 15 Chapters Seed Data
-- --------------------------------------------------------
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'મંગલાચરણ', 'મંગલાચરણ', 'Mangalacharan', 'શ્રીવલ્લભં કૃપાલુ શ્રીહરિં વંદે ગુરું મુદમ્। સર્વમંગલદાતારં ભક્તાભીષ્ટપ્રદાયકમ્॥', 'શ્રીવલ્લભં કૃપાલુ શ્રીહરિં વંદે ગુરું મુદમ્। સર્વમંગલદાતારં ભક્તાભીષ્ટપ્રદાયકમ્॥', 'Obeisances to the Supreme Lord Shree Hari, the bestower of all divine blessings and bliss.', 1, 'published', NOW(), NOW()),
(2, 'શ્રીહરિ પ્રાગટ્ય હેતુ', 'શ્રીહરિ પ્રાગટ્ય હેતુ', 'Divine Manifestation', 'ધર્મ રક્ષણ અને અધર્મ નિવારણ કાજે પ્રભુનું આ ધરાતલ પર દિવ્ય પ્રાગટ્ય થયું.', 'ધર્મ રક્ષણ અને અધર્મ નિવારણ કાજે પ્રભુનું આ ધરાતલ પર દિવ્ય પ્રાગટ્ય થયું.', 'The Lord manifested to protect righteousness and bestow liberation upon all souls.', 2, 'published', NOW(), NOW()),
(3, 'બાળલીલા ચરિત્ર', 'બાળલીલા ચરિત્ર', 'Childhood Leela', 'ઘનશ્યામ મહારાજની બાળપણની અદ્ભુત લીલાઓ સૌ ભક્તોના મનને મોહી લે છે.', 'ઘનશ્યામ મહારાજની બાળપણની અદ્ભુત લીલાઓ સૌ ભક્તોના મનને મોહી લે છે.', 'The divine childhood pastimes of Ghanshyam Maharaj captivated the hearts of all devotees.', 3, 'published', NOW(), NOW()),
(4, 'વનવિચરણ યાત્રા', 'વનવિચરણ યાત્રા', 'Forest Pilgrimage', 'નીલકંઠ વર્ણીએ બાળવયે ગૃહત્યાગ કરી સમગ્ર ભારતવર્ષમાં કઠોર તપશ્ચર્યા કરી.', 'નીલકંઠ વર્ણીએ બાળવયે ગૃહત્યાગ કરી સમગ્ર ભારતવર્ષમાં કઠોર તપશ્ચર્યા કરી.', 'Nilkanth Varni performed intense austerities throughout the sacred lands of India.', 4, 'published', NOW(), NOW()),
(5, 'ગુજરાત આગમન', 'ગુજરાત આગમન', 'Arrival in Gujarat', 'પીપલાણા અને લોજ ગામમાં મુક્તાનંદ સ્વામી તથા રામાનંદ સ્વામીનો દિવ્ય મિલાપ થયો.', 'પીપલાણા અને લોજ ગામમાં મુક્તાનંદ સ્વામી તથા રામાનંદ સ્વામીનો દિવ્ય મિલાપ થયો.', 'The divine meeting with Muktanand Swami and Ramanand Swami in Loj and Piplana.', 5, 'published', NOW(), NOW()),
(6, 'મહાદીક્ષા મહોત્સવ', 'મહાદીક્ષા મહોત્સવ', 'Maha Diksha', 'રામાનંદ સ્વામીએ નીલકંઠ વર્ણીને સહજાનંદ સ્વામી અને નારાયણ મુનિ નામથી દીક્ષા આપી.', 'રામાનંદ સ્વામીએ નીલકંઠ વર્ણીને સહજાનંદ સ્વામી અને નારાયણ મુનિ નામથી દીક્ષા આપી.', 'Ramanand Swami initiated Nilkanth Varni with the holy names Sahajanand and Narayan Muni.', 6, 'published', NOW(), NOW()),
(7, 'ગાદી સોંપણી', 'ગાદી સોંપણી', 'Succession of the Fellowship', 'જેતપુરમાં રામાનંદ સ્વામીએ સમગ્ર સંપ્રદાયની ધુરા સહજાનંદ સ્વામીને સોંપી.', 'જેતપુરમાં રામાનંદ સ્વામીએ સમગ્ર સંપ્રદાયની ધુરા સહજાનંદ સ્વામીને સોંપી.', 'Ramanand Swami handed over the leadership of the fellowship in Jetpur.', 7, 'published', NOW(), NOW()),
(8, 'સ્વામિનારાયણ મહામંત્ર પ્રવર્તન', 'સ્વામિનારાયણ મહામંત્ર પ્રવર્તન', 'Introduction of the Mahamantra', 'ફાણસી ગામમાં સર્વ પાપોને હરનારા પરમ પવિત્ર સ્વામિનારાયણ મહામંત્રની સ્થાપના થઈ.', 'ફાણસી ગામમાં સર્વ પાપોને હરનારા પરમ પવિત્ર સ્વામિનારાયણ મહામંત્રની સ્થાપના થઈ.', 'The revelation of the auspicious Swaminarayan Mahamantra in Phaneni.', 8, 'published', NOW(), NOW()),
(9, 'સમાધિ પ્રકરણ', 'સમાધિ પ્રકરણ', 'Divine Samadhi', 'અસંખ્ય જીવોને સમાધિ કરાવી અક્ષરધામના દિવ્ય દર્શન કરાવ્યા.', 'અસંખ્ય જીવોને સમાધિ કરાવી અક્ષરધામના દિવ્ય દર્શન કરાવ્યા.', 'The Lord granted divine samadhi experiences and visions of Akshardham to countless souls.', 9, 'published', NOW(), NOW()),
(10, 'પરમહંસોની દીક્ષા', 'પરમહંસોની દીક્ષા', 'Initiation of 500 Paramhansas', 'કાલવાણીમાં એક જ રાતમાં ૫૦૦ પરમહંસોને ત્યાગાશ્રમની દીક્ષા આપી.', 'કાલવાણીમાં એક જ રાતમાં ૫૦૦ પરમહંસોને ત્યાગાશ્રમની દીક્ષા આપી.', 'Initiation of 500 sublime Paramhansas in Kalvani in a single night.', 10, 'published', NOW(), NOW()),
(11, 'મહાન યજ્ઞ મહોત્સવ', 'મહાન યજ્ઞ મહોત્સવ', 'Grand Vedic Yagnas', 'ડભાણ અને જૈયતલપુરમાં અહિંસક વૈદિક યજ્ઞો દ્વારા ધર્મની સાચી પ્રતિષ્ઠા કરી.', 'ડભાણ અને જૈયતલપુરમાં અહિંસક વૈદિક યજ્ઞો દ્વારા ધર્મની સાચી પ્રતિષ્ઠા કરી.', 'Reviving pure non-violent Vedic yagnas in Dabhan and Jetalpur.', 11, 'published', NOW(), NOW()),
(12, 'દિવ્ય મંદિરોનું નિર્માણ', 'દિવ્ય મંદિરોનું નિર્માણ', 'Construction of Sacred Temples', 'અમદાવાદ, ભુજ, વડતાલ, ધોલેરા, જૂનાગઢ અને ગઢડામાં શિખરબદ્ધ મંદિરો બનાવ્યા.', 'અમદાવાદ, ભુજ, વડતાલ, ધોલેરા, જૂનાગઢ અને ગઢડામાં શિખરબદ્ધ મંદિરો બનાવ્યા.', 'Construction of grand shikharbaddh temples across Gujarat.', 12, 'published', NOW(), NOW()),
(13, 'વચનામૃત શાસ્ત્ર રચના', 'વચનામૃત શાસ્ત્ર રચના', 'The Vachanamrut Scripture', 'ગઢડા, સારંગપુર, કારિયાણી, લોયા, પંચાળામાં આપેલા અમૃત વચનોનું સંકલન.', 'ગઢડા, સારંગપુર, કારિયાણી, લોયા, પંચાળામાં આપેલા અમૃત વચનોનું સંકલન.', 'Compilation of divine discourses known as the holy Vachanamrut.', 13, 'published', NOW(), NOW()),
(14, 'શિક્ષાપત્રી પ્રાગટ્ય', 'શિક્ષાપત્રી પ્રાગટ્ય', 'Shikshapatri Code of Conduct', 'સર્વ જીવોના કલ્યાણ માટે ૨૧૨ શ્લોકોની દિવ્ય આચારસંહિતા શિક્ષાપત્રી આપી.', 'સર્વ જીવોના કલ્યાણ માટે ૨૧૨ શ્લોકોની દિવ્ય આચારસંહિતા શિક્ષાપત્રી આપી.', 'Bestowing the code of 212 verses, the holy Shikshapatri, for the welfare of mankind.', 14, 'published', NOW(), NOW()),
(15, 'અક્ષરધામ ગમન લીલા', 'અક્ષરધામ ગમન લીલા', 'Return to Akshardham', 'પોતાના દિવ્ય સંકલ્પથી પૃથ્વી પરનું કાર્ય પૂર્ણ કરી પ્રભુ અક્ષરધામ પધાર્યા.', 'પોતાના દિવ્ય સંકલ્પથી પૃથ્વી પરનું કાર્ય પૂર્ણ કરી પ્રભુ અક્ષરધામ પધાર્યા.', 'Completing the divine mission on Earth and returning to the eternal Akshardham.', 15, 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
