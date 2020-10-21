<?php
/**
 * @filesource Kotchasan/Province.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan;

/**
 * รายชื่อจังหวัด ไทย ลาว.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Province
{
    /**
     * โหลดจังหวัดตามประเทศที่เลือก
     * ไม่มี ใช้ประเทศไทย.
     *
     * @param string $country
     *
     * @return array
     */
    private static function init($country)
    {
        if (method_exists('Kotchasan\Province', $country)) {
            return \Kotchasan\Province::$country();
        } else {
            return array();
        }
    }

    /**
     * list รายชื่อจังหวัดทั้งหมด  ตามภาษา (ถ้าไม่มีใช้ภาษาอังกฤษ)
     * สามารถนำไปใช้โดย Form ได้ทันที.
     *
     * @param string $country ค่าเริ่มต้น TH (ไทย), LA (ลาว)
     *
     * @return array
     */
    public static function all($country = 'TH')
    {
        $datas = self::init($country);
        $result = array();
        if (!empty($datas)) {
            $language = Language::name();
            $language = in_array($language, array_keys(reset($datas))) ? $language : 'en';
            $result = array();
            foreach ($datas as $iso => $values) {
                $result[$iso] = $values[$language];
            }
            if ($language == 'en') {
                asort($result);
            }
        }

        return $result;
    }

    /**
     * คืนค่ารายการประเทศที่มีการติดดตั้ง.
     *
     * @return array
     */
    public static function countries()
    {
        return array('TH', 'LA');
    }

    /**
     * อ่านชื่อจังหวัดจาก ISO ตามภาษา (ถ้าไม่มีใช้ภาษาอังกฤษ)
     * คืนค่าว่างถ้าไม่พบ.
     *
     * @assert (102) [==] 'กรุงเทพมหานคร'
     *
     * @param int    $iso
     * @param string $lang
     *
     * @return string
     */
    public static function get($iso, $lang = '', $country = 'TH')
    {
        $datas = self::init($country);
        if (empty($lang)) {
            $lang = Language::name();
        }
        $lang = in_array($lang, array_keys(reset($datas))) ? $lang : 'en';

        return isset($datas[$iso]) ? $datas[$iso][$lang] : '';
    }

    /**
     * รายชื่อจังหวัด ไทย เรียงลำดับตามชื่อไทย.
     *
     * @return array
     */
    private static function TH()
    {
        return array(
            101 => array('th' => 'กระบี่', 'en' => 'Krabi'),
            102 => array('th' => 'กรุงเทพมหานคร', 'en' => 'Bankok'),
            103 => array('th' => 'กาญจนบุรี', 'en' => 'Kanchanaburi'),
            104 => array('th' => 'กาฬสินธุ์', 'en' => 'Kalsin'),
            105 => array('th' => 'กำแพงเพชร', 'en' => 'Kamphaeng phet'),
            106 => array('th' => 'ขอนแก่น', 'en' => 'Khon kean'),
            107 => array('th' => 'จันทบุรี', 'en' => 'Chanthaburi'),
            108 => array('th' => 'ฉะเชิงเทรา', 'en' => 'Chachoengsao'),
            109 => array('th' => 'ชลบุรี', 'en' => 'Chon buri'),
            110 => array('th' => 'ชัยนาท', 'en' => 'Chai nat'),
            111 => array('th' => 'ชัยภูมิ', 'en' => 'Chai yaphum'),
            112 => array('th' => 'ชุมพร', 'en' => 'Chum phon'),
            170 => array('th' => 'เชียงราย', 'en' => 'Chiang rai'),
            171 => array('th' => 'เชียงใหม่', 'en' => 'Chiang mai'),
            113 => array('th' => 'ตรัง', 'en' => 'Trang'),
            114 => array('th' => 'ตราด', 'en' => 'Trat'),
            115 => array('th' => 'ตาก', 'en' => 'Tak'),
            116 => array('th' => 'นครนายก', 'en' => 'Nakhon nayok'),
            117 => array('th' => 'นครปฐม', 'en' => 'Nakhon pathom'),
            118 => array('th' => 'นครพนม', 'en' => 'Nakhon phanom'),
            119 => array('th' => 'นครราชสีมา', 'en' => 'Nakhon ratchasima'),
            120 => array('th' => 'นครศรีธรรมราช', 'en' => 'Nakhon si thammarat'),
            121 => array('th' => 'นครสวรรค์', 'en' => 'Nakhon sawan'),
            122 => array('th' => 'นนทบุรี', 'en' => 'Nonthaburi'),
            123 => array('th' => 'นราธิวาส', 'en' => 'Narathiwat'),
            124 => array('th' => 'น่าน', 'en' => 'Nan'),
            177 => array('th' => 'บึงกาฬ', 'en' => 'Bueng Kan'),
            125 => array('th' => 'บุรีรัมย์', 'en' => 'Buri ram'),
            126 => array('th' => 'ปทุมธานี', 'en' => 'Pathum thani'),
            127 => array('th' => 'ประจวบคีรีขันธ์', 'en' => 'Prachuap khiri khan'),
            128 => array('th' => 'ปราจีนบุรี', 'en' => 'Prachinburi'),
            129 => array('th' => 'ปัตตานี', 'en' => 'Pattani'),
            130 => array('th' => 'พระนครศรีอยุธยา', 'en' => 'Phra Nakhon Si Ayutthaya'),
            131 => array('th' => 'พะเยา', 'en' => 'Phayao'),
            132 => array('th' => 'พังงา', 'en' => 'Phangnga'),
            133 => array('th' => 'พัทลุง', 'en' => 'Phatthalung'),
            134 => array('th' => 'พิจิตร', 'en' => 'Phichit'),
            135 => array('th' => 'พิษณุโลก', 'en' => 'Phitsanulok'),
            172 => array('th' => 'เพชรบุรี', 'en' => 'Phetchaburi'),
            173 => array('th' => 'เพชรบูรณ์', 'en' => 'Phetchabun'),
            175 => array('th' => 'แพร่', 'en' => 'Phrae'),
            136 => array('th' => 'ภูเก็ต', 'en' => 'Phuket'),
            137 => array('th' => 'มหาสารคาม', 'en' => 'Mahasarakham'),
            138 => array('th' => 'มุกดาหาร', 'en' => 'Mukdahan'),
            176 => array('th' => 'แม่ฮ่องสอน', 'en' => 'Mae hong son'),
            140 => array('th' => 'ยโสธร', 'en' => 'Yasothon'),
            139 => array('th' => 'ยะลา', 'en' => 'Yala'),
            144 => array('th' => 'ร้อยเอ็ด', 'en' => 'Roi et'),
            141 => array('th' => 'ระนอง', 'en' => 'Ranong'),
            142 => array('th' => 'ระยอง', 'en' => 'Rayong'),
            143 => array('th' => 'ราชบุรี', 'en' => 'Rachaburi'),
            145 => array('th' => 'ลพบุรี', 'en' => 'Lopburi'),
            174 => array('th' => 'เลย', 'en' => 'Loei'),
            146 => array('th' => 'ลำปาง', 'en' => 'Lampang'),
            147 => array('th' => 'ลำพูน', 'en' => 'Lamphun'),
            148 => array('th' => 'ศรีสะเกษ', 'en' => 'Srisaket'),
            149 => array('th' => 'สกลนคร', 'en' => 'Sakonnakhon'),
            150 => array('th' => 'สงขลา', 'en' => 'Songkhla'),
            151 => array('th' => 'สตูล', 'en' => 'Satun'),
            152 => array('th' => 'สมุทรปราการ', 'en' => 'Samutprakan'),
            153 => array('th' => 'สมุทรสงคราม', 'en' => 'Samut songkhram'),
            154 => array('th' => 'สมุทรสาคร', 'en' => 'Samut sakhon'),
            156 => array('th' => 'สระแก้ว', 'en' => 'Sra kaeo'),
            155 => array('th' => 'สระบุรี', 'en' => 'Saraburi'),
            157 => array('th' => 'สิงห์บุรี', 'en' => 'Singburi'),
            161 => array('th' => 'สุโขทัย', 'en' => 'Sukhothai'),
            158 => array('th' => 'สุพรรณบุรี', 'en' => 'Suphanburi'),
            159 => array('th' => 'สุราษฎร์ธานี', 'en' => 'Surat thani'),
            160 => array('th' => 'สุรินทร์', 'en' => 'Surin'),
            162 => array('th' => 'หนองคาย', 'en' => 'Nongkhai'),
            163 => array('th' => 'หนองบัวลำภู', 'en' => 'Nongbualamphu'),
            169 => array('th' => 'อ่างทอง', 'en' => 'Angthong'),
            164 => array('th' => 'อำนาจเจริญ', 'en' => 'Amnatcharoen'),
            165 => array('th' => 'อุดรธานี', 'en' => 'Udonthani'),
            166 => array('th' => 'อุตรดิตถ์', 'en' => 'Uttaradit'),
            167 => array('th' => 'อุทัยธานี', 'en' => 'Uthaithani'),
            168 => array('th' => 'อุบลราชธานี', 'en' => 'Uboratchathani'),
        );
    }

    /**
     * รายชื่อจังหวัด ลาว เรียงลำดับตามชื่อไทย.
     *
     * @return array
     */
    private static function LA()
    {
        return array(
            101 => array('la' => 'ນຄ.ວຽງຈັນ', 'en' => 'Vientiane Capital'),
            102 => array('la' => 'ຜົ້ງສາລີ', 'en' => 'Phongsaly'),
            103 => array('la' => 'ຫຼວງນ້ຳທາ', 'en' => 'Luangnamtha'),
            104 => array('la' => 'ອຸດົມໄຊ', 'en' => 'Oudomxay'),
            105 => array('la' => 'ບໍ່ແກ້ວ', 'en' => 'Bokeo'),
            106 => array('la' => 'ຫຼວງພະບາງ', 'en' => 'Luangprabang'),
            107 => array('la' => 'ຫົວພັນ', 'en' => 'Huaphanh'),
            108 => array('la' => 'ໄຊຍະບູລີ', 'en' => 'Xayaboury'),
            109 => array('la' => 'ຊຽງຂວາງ', 'en' => 'Xiengkhuang'),
            110 => array('la' => 'ວຽງຈັນ', 'en' => 'Vientiane'),
            111 => array('la' => 'ບໍລິຄຳໄຊ', 'en' => 'Borikhamxay'),
            112 => array('la' => 'ຄຳມ່ວນ', 'en' => 'Khammuane'),
            113 => array('la' => 'ສະຫວັນນະເຂດ', 'en' => 'Savannakhet'),
            114 => array('la' => 'ສາລະວັນ', 'en' => 'Saravane'),
            115 => array('la' => 'ເຊກອງ', 'en' => 'Sekong'),
            116 => array('la' => 'ຈຳປາສັກ', 'en' => 'Champasack'),
            117 => array('la' => 'ອັດຕະປື', 'en' => 'Attapeu'),
            118 => array('la' => 'ໄຊສົມບູນ', 'en' => 'Xaysomboon'),
        );
    }
}
