<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        Chapter::truncate();

        $titles = [
            1 => ['gu' => 'મંગળાચરણ અને સ્તુતિ', 'en' => 'Invocation & Praise'],
            2 => ['gu' => 'સ્વાભાવિક ચેષ્ટા', 'en' => 'Divine Postures & Virtues'],
            3 => ['gu' => 'શ્રીહરિ પ્રાગટ્ય ચરિત્ર', 'en' => 'Manifestation of Sri Hari'],
            4 => ['gu' => 'બાળલીલા અને વિદ્યાભ્યાસ', 'en' => 'Childhood Pastimes'],
            5 => ['gu' => 'તીર્થયાત્રા અને વનવિચરણ', 'en' => 'Spiritual Pilgrimage'],
            6 => ['gu' => 'લોજ આગમન અને મિલન', 'en' => 'Arrival at Loj'],
            7 => ['gu' => 'રામાનંદ સ્વામીનો સમાગમ', 'en' => 'Meeting Ramanand Swami'],
            8 => ['gu' => 'ધર્મધુરા અને ગાદીપદ', 'en' => 'Bestowal of Fellowship'],
            9 => ['gu' => 'મહાવિષ્ણુયાગ મહોત્સવ', 'en' => 'Grand Vedic Sacrifices'],
            10 => ['gu' => 'સંતમંડળ અને નિયમપાલન', 'en' => 'Assembly of Saints'],
            11 => ['gu' => 'ગઢપુર લીલા વિહાર', 'en' => 'Pastimes at Gadhada'],
            12 => ['gu' => 'વચનામૃત જ્ઞાનસાગર', 'en' => 'Ocean of Wisdom'],
            13 => ['gu' => 'મંદિર નિર્માણ ઉત્સવ', 'en' => 'Consecration of Temples'],
            14 => ['gu' => 'અક્ષરબ્રહ્મ મહિમા', 'en' => 'Glory of Aksharbrahma'],
            15 => ['gu' => 'અખંડ ભક્તિ અને શાંતિ', 'en' => 'Unceasing Devotion & Peace'],
        ];

        for ($i = 1; $i <= 15; $i++) {
            $tGu = $titles[$i]['gu'];
            $tEn = $titles[$i]['en'];

            $contentGu = '<p style="text-align: center;"><b>સાંભળ સૈયર રે, લીલા નટનાગરની;</b></p>' .
                '<p style="text-align: center;"><b>સુણતાં સુખડું રે, આપે સુખસાગરની.....' . $i . '</b></p>' .
                '<p style="text-align: center;"><b>નેત્રકમળને રે, રાખી ઉઘાડાં ક્યારે;</b></p>' .
                '<p style="text-align: center;"><b>ધ્યાન ધરીને રે, બેસે જીવન બારે.....' . ($i + 1) . '</b></p><br>' .
                '<p>પ્રકરણ ' . $i . ' - ' . $tGu . ' માં શ્રીહરિના પવિત્ર જીવન ચરિત્ર, દિવ્ય કથાઓ અને ભક્તિભાવનું અલૌકિક વર્ણન કરવામાં આવ્યું છે. ભક્તો જ્યારે પણ પ્રેમથી આ કથાનું શ્રવણ અને મનન કરે છે ત્યારે અંતરમાં પરમ શાંતિ, દિવ્ય આનંદ અને ભક્તિનો ઉદય થાય છે.</p>' .
                '<p>સર્વ અવતારના અવતારી શ્રીહરિ સર્વ સુખના દાતા છે. તેમના ચરણકમળમાં નિરંતર પ્રીતિ રાખવી એ જ જીવનું પરમ કલ્યાણ છે.</p>';

            $contentEn = "Chapter $i - $tEn\n\nThis sacred chapter illuminates the divine pastimes, spiritual lessons, and devotional glory of the supreme Lord.\n\nContemplating upon these divine virtues brings profound tranquility, clarity, and ever-increasing spiritual joy to the heart.";

            Chapter::create([
                'title'          => $tGu,
                'title_en'       => $tEn,
                'title_gu'       => $tGu,
                'description'    => "Divine chapter $i of Bhakta-Chintamani.",
                'description_en' => "Divine chapter $i of Bhakta-Chintamani.",
                'description_gu' => "ભક્ત-ચિંતામણિનું પવિત્ર પ્રકરણ $i.",
                'content'        => $contentEn,
                'content_en'     => $contentEn,
                'content_gu'     => $contentGu,
                'order'          => $i,
                'status'         => 'published',
            ]);
        }
    }
}

