<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $verseContentGu = '<p style="text-align: center;"><b>મંગલમૂર્તિ મહાપ્રભુ, શ્રીસહજાનંદ સુખરૂપ;</b></p>' .
            '<p style="text-align: center;"><b>ભક્તિ ધર્મ સુત શ્રીહરિ, સમરું સદાય અનુપ. ૧</b></p>' .
            '<p style="text-align: center;"><b>પરમ દયાળુ છો તમે, શ્રીકૃષ્ણ સર્વાધીશ;</b></p>' .
            '<p style="text-align: center;"><b>પ્રથમ તમને પ્રણામું, નામું વારંવાર હું શીષ. ૨</b></p>' .
            '<p style="text-align: center;"><b>અતિ સુંદર ગોલોક મધ્યે, અક્ષર એવું જેનું નામ છે;</b></p>' .
            '<p style="text-align: center;"><b>કોટિ સૂર્ય ચંદ્ર અગ્નિ સમ, પ્રકાશક દિવ્ય ધામ છે. ૩</b></p><br>' .
            '<p>એવા અક્ષરધામમાં તમે, રહો છો કૃષ્ણ કૃપાળ; પુરુષોત્તમ વાસુદેવ નારાયણ, પરમાત્મા પરમદયાળ. પરબ્રહ્મ બ્રહ્મ પરમેશ્વર, વિષ્ણુ ઈશ્વર વેદ કહે વળી; એહ આદિ અનંત નામે, સુંદર મૂર્તિ શ્યામળી. ક્ષર અક્ષર પર સર્વજ્ઞ છો, સર્વકર્તા નિયામક અંતર્યામી; સર્વકારણના કારણ નિર્ગુણ, સ્વયંપ્રકાશ સહુના સ્વામી. સ્વતંત્ર બ્રહ્મરૂપ સદા.</p>';

        $chapters = [
            [
                'title'          => 'મંગળાચરણ',
                'title_en'       => 'Invocation',
                'title_gu'       => 'મંગળાચરણ',
                'description_en' => 'In the beginning there was nothing — and then everything. A meditative opening on existence and consciousness.',
                'description_gu' => 'પ્રારંભમાં કશું ન હતું — અને પછી બધું જ. અસ્તિત્વ અને ચેતના વિશેનું એક મનનશીલ પ્રારંભિક પ્રકરણ.',
                'content_en'     => "In the beginning there was silence.\n\nNot the silence of an empty room, nor the silence between two heartbeats — but the absolute silence that precedes all things. It was the silence before the first word was ever spoken, before the first breath was ever drawn.\n\nAnd then, as though the universe itself had grown tired of stillness, there was a trembling. Consciousness was there at the beginning. Not consciousness as we know it, scattered through billions of human minds, but consciousness as a pure principle: the capacity of the universe to witness itself.\n\nEvery atom of your body was forged in the heart of a dying star. Every thought you think is the universe contemplating its own nature. You are not in the universe. You are the universe, briefly and beautifully aware of itself.",
                'content_gu'     => $verseContentGu,
                'order'          => 1,
                'status'         => 'published',
            ],
            [
                'title'          => 'નદી અને પથ્થર',
                'title_en'       => 'The River and the Stone',
                'title_gu'       => 'નદી અને પથ્થર',
                'description_en' => 'A story about patience, persistence, and how the softest things in the world overcome the hardest.',
                'description_gu' => 'ધૈર્ય અને સતત પ્રયત્ન વિશેની વાર્તા, જે દર્શાવે છે કે નમ્રતા અને નિરંતરતા કેવી રીતે કઠોરમાં કઠોર અવરોધોને ઓગાળી દે છે.',
                'content_en'     => "There is a river in the mountains that has been flowing for ten thousand years.\n\nFor most of that time, there was a great granite boulder sitting in its path — immovable and ancient. But water is patient in ways that stone can never be. It simply moved, finding the subtle channels, through heat and freeze.\n\nAnd then one morning, the boulder was gone. Water is the softest thing in the world, yet it can overcome the hardest stone. The greatest transformations happen through consistency, not force.",
                'content_gu'     => "પર્વતોની વચ્ચે એક પવિત્ર નદી હજારો વર્ષોથી વહી રહી હતી.\n\nએના માર્ગમાં એક વિશાળ અને કઠોર ખડક હતો. નદીએ ક્યારેય તે પથ્થર સાથે ક્રોધથી યુદ્ધ ન કર્યું. તેણે માત્ર પોતાનો સહજ પ્રવાહ ચાલુ રાખ્યો. સમય જતાં, એ નમ્ર અને સતત વહેતા જળે તે કઠિન ખડકને રણમાં ફેરવી નાખ્યો.\n\nજીવનમાં પણ ધૈર્ય અને સતત ભક્તિ જ તમામ વિઘ્નોને દૂર કરે છે. બળથી નહીં, પરંતુ નમ્રતા અને નિરંતરતાથી પરમ શાંતિ પ્રાપ્ત થાય છે.",
                'order'          => 2,
                'status'         => 'published',
            ],
            [
                'title'          => 'આકાશને પત્રો',
                'title_en'       => 'Letters to the Sky',
                'title_gu'       => 'આકાશને પત્રો',
                'description_en' => 'A poetic exploration of longing, memory, and the messages we send into the universe.',
                'description_gu' => 'ભાવના, સ્મૃતિ અને પરમાત્મા તરફ મોકલાતી પ્રાર્થનાઓનો એક કાવ્યાત્મક અનુભવ.',
                'content_en'     => "On clear nights, when the stars are out and the city is quiet, I look up and speak. Not prayers exactly, but words spoken with love and faith.\n\nWe speak with the assumption that someone listens. Light from distant stars travels for thousands of years to reach our eyes. Maybe our words and prayers travel too — flowing outward through the universe like light.",
                'content_gu'     => "જ્યારે રાત્રે આકાશ સ્વચ્છ હોય અને તારાઓ ચમકતા હોય, ત્યારે મન આપોઆપ ઈશ્વરના સ્મરણમાં લીન થઈ જાય છે.\n\nઆપણે જે પવિત્ર ભાવથી પ્રાર્થના કરીએ છીએ, તે ક્યારેય વ્યર્થ જતી નથી. જેમ તારાઓનો પ્રકાશ અનંત સુધી પહોંચે છે, તેમ આપણી શ્રદ્ધા અને ભક્તિનો અવાજ પણ પરમાત્મા સુધી પહોંચે છે.",
                'order'          => 3,
                'status'         => 'published',
            ],
            [
                'title'          => 'અંતરમુખ યાત્રા',
                'title_en'       => 'The Art of Returning',
                'title_gu'       => 'અંતરમુખ યાત્રા',
                'description_en' => 'On homecoming — returning to a place, a person, or the soul within.',
                'description_gu' => 'પોતાના નિજ સ્વરૂપમાં પાછા ફરવાની અને અંતર આત્માની શાંતિ શોધવાની યાત્રા.',
                'content_en'     => "Every journey has two halves: the setting out and the returning. To return is not to go backward, but to see, with new eyes, what was always within you.\n\nWhen we quiet the outer noise and step back into our core, we realize that peace was never lost. It was waiting right where we left it.",
                'content_gu'     => "દરેક યાત્રાના બે પાસા હોય છે: બહાર જવું અને પોતાના ઘર (આત્મા) તરફ પાછા ફરવું.\n\nજ્યારે આપણે બહારના ઘોંઘાટથી દૂર થઈને પોતાના અંતરમાં ઉતરીએ છીએ, ત્યારે સમજાય છે કે પરમ આનંદ અને શાંતિ તો કાયમ આપણી અંદર જ સ્થિત હતા.",
                'order'          => 4,
                'status'         => 'published',
            ],
            [
                'title'          => 'મૌનની અનુભૂતિ',
                'title_en'       => 'What the Silence Knows',
                'title_gu'       => 'મૌનની અનુભૂતિ',
                'description_en' => 'On stillness, wisdom, and learning to listen to the voice within.',
                'description_gu' => 'શાંતિ, જ્ઞાન અને પોતાના આંતરિક અવાજને સાંભળવાની આધ્યાત્મિક અનુભૂતિ.',
                'content_en'     => "There is a kind of knowledge that cannot be spoken. Silence is the ground beneath all noise.\n\nWhen the mind is completely still, you discover a deep, unshakeable peace. You only have to get quiet enough to listen.",
                'content_gu'     => "એવું જ્ઞાન પણ છે જે શબ્દોમાં વ્યક્ત થઈ શકતું નથી. મૌન એ સંસારના તમામ અવાજોથી પર છે.\n\nજ્યારે મન શાંત થાય છે, ત્યારે પરમ સત્ય અને વિવેકનો ઉદય થાય છે. મૌનમાં જ પરમાત્માનો વાસ છે.",
                'order'          => 5,
                'status'         => 'published',
            ],
        ];

        foreach ($chapters as $chapter) {
            Chapter::create(array_merge($chapter, [
                'title'       => $chapter['title'],
                'description' => $chapter['description_en'],
                'content'     => $chapter['content_en'],
            ]));
        }
    }
}
