-- ====================================================
-- Bhakta-Chintamani Full Database Dump for InfinityFree phpMyAdmin
-- Generated on 2026-08-12 17:54:38
-- ====================================================

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) DEFAULT 0,
  `permissions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chapters` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `title_gu` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_gu` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_gu` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `admins`
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `is_super_admin`, `permissions`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Main Admin', 'naishad@ssgd.com', '$2y$12$dIxPmPHWk39Nd6j7jhWWP.CqzYJCGchpqcwIq3Cb08KWttUpjr3M6', '1', NULL, NULL, '2026-08-08 09:45:19', '2026-08-08 09:45:19');

-- Dumping data for `chapters`
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `description`, `description_gu`, `description_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `published_at`, `created_at`, `updated_at`) VALUES ('1', 'મંગળાચરણ', 'મંગળાચરણ', 'Invocation', 'In the beginning there was nothing — and then everything. A meditative opening on existence and consciousness.', 'પ્રારંભમાં કશું ન હતું — અને પછી બધું જ. અસ્તિત્વ અને ચેતના વિશેનું એક મનનશીલ પ્રારંભિક પ્રકરણ.', 'In the beginning there was nothing — and then everything. A meditative opening on existence and consciousness.', '<p style=\"text-align: justify;\"><b>મંગલમૂર્તિ મહાપ્રભુ, શ્રીસહજાનંદ સુખરૂપ;</b></p><p style=\"text-align: justify;\"><b>ભક્તિ ધર્મ સુત શ્રીહરિ, સમરું સદાય અનુપ. ૧</b></p><p style=\"text-align: justify;\"><b>પરમ દયાળુ છો તમે, શ્રીકૃષ્ણ સર્વાધીશ;</b></p><p style=\"text-align: justify;\"><b>પ્રથમ તમને પ્રણામું, નામું વારંવાર હું શીષ. ૨</b></p><p style=\"text-align: justify;\"><b>અતિ સુંદર ગોલોક મધ્યે, અક્ષર એવું જેનું નામ છે;</b></p><p style=\"text-align: justify;\"><b>કોટિ સૂર્ય ચંદ્ર અગ્નિ સમ, પ્રકાશક દિવ્ય ધામ છે. ૩</b></p><br><p>એવા અક્ષરધામમાં તમે, રહો છો કૃષ્ણ કૃપાળ;&nbsp;</p><p>પુરુષોત્તમ વાસુદેવ નારાયણ, પરમાત્મા પરમદયાળ.&nbsp;</p><p>પરબ્રહ્મ બ્રહ્મ પરમેશ્વર, વિષ્ણુ ઈશ્વર વેદ કહે વળી;&nbsp;</p><p>એહ આદિ અનંત નામે, સુંદર મૂર્તિ શ્યામળી.&nbsp;</p><p>ક્ષર અક્ષર પર સર્વજ્ઞ છો, સર્વકર્તા નિયામક અંતર્યામી;&nbsp;</p><p><br></p><p style=\"text-align: center;\">સર્વકારણના કારણ નિર્ગુણ, સ્વયંપ્રકાશ સહુના સ્વામી. સ્વતંત્ર બ્રહ્મરૂપ સદા.</p>', '<p style=\"text-align: justify;\"><b>મંગલમૂર્તિ મહાપ્રભુ, શ્રીસહજાનંદ સુખરૂપ;</b></p><p style=\"text-align: justify;\"><b>ભક્તિ ધર્મ સુત શ્રીહરિ, સમરું સદાય અનુપ. ૧</b></p><p style=\"text-align: justify;\"><b>પરમ દયાળુ છો તમે, શ્રીકૃષ્ણ સર્વાધીશ;</b></p><p style=\"text-align: justify;\"><b>પ્રથમ તમને પ્રણામું, નામું વારંવાર હું શીષ. ૨</b></p><p style=\"text-align: justify;\"><b>અતિ સુંદર ગોલોક મધ્યે, અક્ષર એવું જેનું નામ છે;</b></p><p style=\"text-align: justify;\"><b>કોટિ સૂર્ય ચંદ્ર અગ્નિ સમ, પ્રકાશક દિવ્ય ધામ છે. ૩</b></p><br><p>એવા અક્ષરધામમાં તમે, રહો છો કૃષ્ણ કૃપાળ;&nbsp;</p><p>પુરુષોત્તમ વાસુદેવ નારાયણ, પરમાત્મા પરમદયાળ.&nbsp;</p><p>પરબ્રહ્મ બ્રહ્મ પરમેશ્વર, વિષ્ણુ ઈશ્વર વેદ કહે વળી;&nbsp;</p><p>એહ આદિ અનંત નામે, સુંદર મૂર્તિ શ્યામળી.&nbsp;</p><p>ક્ષર અક્ષર પર સર્વજ્ઞ છો, સર્વકર્તા નિયામક અંતર્યામી;&nbsp;</p><p><br></p><p style=\"text-align: center;\">સર્વકારણના કારણ નિર્ગુણ, સ્વયંપ્રકાશ સહુના સ્વામી. સ્વતંત્ર બ્રહ્મરૂપ સદા.</p>', 'In the beginning there was silence.

Not the silence of an empty room, nor the silence between two heartbeats — but the absolute silence that precedes all things. It was the silence before the first word was ever spoken, before the first breath was ever drawn.

And then, as though the universe itself had grown tired of stillness, there was a trembling. Consciousness was there at the beginning. Not consciousness as we know it, scattered through billions of human minds, but consciousness as a pure principle: the capacity of the universe to witness itself.

Every atom of your body was forged in the heart of a dying star. Every thought you think is the universe contemplating its own nature. You are not in the universe. You are the universe, briefly and beautifully aware of itself.', '1', 'published', '2026-08-12 17:13:14', '2026-08-08 09:45:19', '2026-08-12 17:24:24');
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `description`, `description_gu`, `description_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `published_at`, `created_at`, `updated_at`) VALUES ('2', 'નદી અને પથ્થર', 'નદી અને પથ્થર', 'The River and the Stone', 'A story about patience, persistence, and how the softest things in the world overcome the hardest.', 'ધૈર્ય અને સતત પ્રયત્ન વિશેની વાર્તા, જે દર્શાવે છે કે નમ્રતા અને નિરંતરતા કેવી રીતે કઠોરમાં કઠોર અવરોધોને ઓગાળી દે છે.', 'A story about patience, persistence, and how the softest things in the world overcome the hardest.', 'There is a river in the mountains that has been flowing for ten thousand years.

For most of that time, there was a great granite boulder sitting in its path — immovable and ancient. But water is patient in ways that stone can never be. It simply moved, finding the subtle channels, through heat and freeze.

And then one morning, the boulder was gone. Water is the softest thing in the world, yet it can overcome the hardest stone. The greatest transformations happen through consistency, not force.', 'પર્વતોની વચ્ચે એક પવિત્ર નદી હજારો વર્ષોથી વહી રહી હતી.

એના માર્ગમાં એક વિશાળ અને કઠોર ખડક હતો. નદીએ ક્યારેય તે પથ્થર સાથે ક્રોધથી યુદ્ધ ન કર્યું. તેણે માત્ર પોતાનો સહજ પ્રવાહ ચાલુ રાખ્યો. સમય જતાં, એ નમ્ર અને સતત વહેતા જળે તે કઠિન ખડકને રણમાં ફેરવી નાખ્યો.

જીવનમાં પણ ધૈર્ય અને સતત ભક્તિ જ તમામ વિઘ્નોને દૂર કરે છે. બળથી નહીં, પરંતુ નમ્રતા અને નિરંતરતાથી પરમ શાંતિ પ્રાપ્ત થાય છે.', 'There is a river in the mountains that has been flowing for ten thousand years.

For most of that time, there was a great granite boulder sitting in its path — immovable and ancient. But water is patient in ways that stone can never be. It simply moved, finding the subtle channels, through heat and freeze.

And then one morning, the boulder was gone. Water is the softest thing in the world, yet it can overcome the hardest stone. The greatest transformations happen through consistency, not force.', '2', 'published', NULL, '2026-08-08 09:45:19', '2026-08-08 09:45:19');
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `description`, `description_gu`, `description_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `published_at`, `created_at`, `updated_at`) VALUES ('3', 'આકાશને પત્રો', 'આકાશને પત્રો', 'Letters to the Sky', 'A poetic exploration of longing, memory, and the messages we send into the universe.', 'ભાવના, સ્મૃતિ અને પરમાત્મા તરફ મોકલાતી પ્રાર્થનાઓનો એક કાવ્યાત્મક અનુભવ.', 'A poetic exploration of longing, memory, and the messages we send into the universe.', 'On clear nights, when the stars are out and the city is quiet, I look up and speak. Not prayers exactly, but words spoken with love and faith.

We speak with the assumption that someone listens. Light from distant stars travels for thousands of years to reach our eyes. Maybe our words and prayers travel too — flowing outward through the universe like light.', 'જ્યારે રાત્રે આકાશ સ્વચ્છ હોય અને તારાઓ ચમકતા હોય, ત્યારે મન આપોઆપ ઈશ્વરના સ્મરણમાં લીન થઈ જાય છે.

આપણે જે પવિત્ર ભાવથી પ્રાર્થના કરીએ છીએ, તે ક્યારેય વ્યર્થ જતી નથી. જેમ તારાઓનો પ્રકાશ અનંત સુધી પહોંચે છે, તેમ આપણી શ્રદ્ધા અને ભક્તિનો અવાજ પણ પરમાત્મા સુધી પહોંચે છે.', 'On clear nights, when the stars are out and the city is quiet, I look up and speak. Not prayers exactly, but words spoken with love and faith.

We speak with the assumption that someone listens. Light from distant stars travels for thousands of years to reach our eyes. Maybe our words and prayers travel too — flowing outward through the universe like light.', '3', 'published', NULL, '2026-08-08 09:45:19', '2026-08-08 09:45:19');
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `description`, `description_gu`, `description_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `published_at`, `created_at`, `updated_at`) VALUES ('4', 'અંતરમુખ યાત્રા', 'અંતરમુખ યાત્રા', 'The Art of Returning', 'On homecoming — returning to a place, a person, or the soul within.', 'પોતાના નિજ સ્વરૂપમાં પાછા ફરવાની અને અંતર આત્માની શાંતિ શોધવાની યાત્રા.', 'On homecoming — returning to a place, a person, or the soul within.', 'Every journey has two halves: the setting out and the returning. To return is not to go backward, but to see, with new eyes, what was always within you.

When we quiet the outer noise and step back into our core, we realize that peace was never lost. It was waiting right where we left it.', 'દરેક યાત્રાના બે પાસા હોય છે: બહાર જવું અને પોતાના ઘર (આત્મા) તરફ પાછા ફરવું.

જ્યારે આપણે બહારના ઘોંઘાટથી દૂર થઈને પોતાના અંતરમાં ઉતરીએ છીએ, ત્યારે સમજાય છે કે પરમ આનંદ અને શાંતિ તો કાયમ આપણી અંદર જ સ્થિત હતા.', 'Every journey has two halves: the setting out and the returning. To return is not to go backward, but to see, with new eyes, what was always within you.

When we quiet the outer noise and step back into our core, we realize that peace was never lost. It was waiting right where we left it.', '4', 'published', NULL, '2026-08-08 09:45:19', '2026-08-08 09:45:19');
INSERT INTO `chapters` (`id`, `title`, `title_gu`, `title_en`, `description`, `description_gu`, `description_en`, `content`, `content_gu`, `content_en`, `order`, `status`, `published_at`, `created_at`, `updated_at`) VALUES ('5', 'મૌનની અનુભૂતિ', 'મૌનની અનુભૂતિ', 'What the Silence Knows', 'On stillness, wisdom, and learning to listen to the voice within.', 'શાંતિ, જ્ઞાન અને પોતાના આંતરિક અવાજને સાંભળવાની આધ્યાત્મિક અનુભૂતિ.', 'On stillness, wisdom, and learning to listen to the voice within.', 'There is a kind of knowledge that cannot be spoken. Silence is the ground beneath all noise.

When the mind is completely still, you discover a deep, unshakeable peace. You only have to get quiet enough to listen.', 'એવું જ્ઞાન પણ છે જે શબ્દોમાં વ્યક્ત થઈ શકતું નથી. મૌન એ સંસારના તમામ અવાજોથી પર છે.

જ્યારે મન શાંત થાય છે, ત્યારે પરમ સત્ય અને વિવેકનો ઉદય થાય છે. મૌનમાં જ પરમાત્માનો વાસ છે.', 'There is a kind of knowledge that cannot be spoken. Silence is the ground beneath all noise.

When the mind is completely still, you discover a deep, unshakeable peace. You only have to get quiet enough to listen.', '5', 'published', NULL, '2026-08-08 09:45:19', '2026-08-08 09:45:19');

SET FOREIGN_KEY_CHECKS=1;
