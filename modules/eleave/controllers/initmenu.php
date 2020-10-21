<?php
/**
 * @filesource modules/eleave/controllers/initmenu.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Eleave\Initmenu;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Init Menu
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล.
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     * @param array                  $login
     */
    public static function execute(Request $request, $menu, $login)
    {
        if ($login) {
            // รายการเมนูสมาชิกทุกคน
            $submenus = array();
            foreach (Language::get('LEAVE_STATUS') as $k => $label) {
                $submenus[] = array(
                    'text' => $label,
                    'url' => 'index.php?module=eleave&amp;status='.$k,
                );
            }
            $submenus[] = array(
                'text' => '{LNG_Statistics for leave}',
                'url' => 'index.php?module=eleave-statistics',
            );
            $submenus[] = array(
                'text' => '{LNG_Add New} {LNG_Request for leave}',
                'url' => 'index.php?module=eleave-leave',
            );
            $menu->addTopLvlMenu('eleave', '{LNG_E-Leave}', null, $submenus, 'module');
        }
        // สามารถอนุมัติได้
        if (Login::checkPermission($login, 'can_approve_eleave')) {
            $menu->addTopLvlMenu('report', '{LNG_Report}', 'index.php?module=eleave-report', null, 'settings');
            foreach (Language::get('LEAVE_STATUS') as $k => $label) {
                $menu->add('report', '{LNG_E-Leave} '.$label, 'index.php?module=eleave-report&amp;status='.$k, null, 'eleave'.$k);
            }
        }
        // เมนูตั้งค่า
        $submenus = array();
        if (Login::checkPermission($login, 'can_config')) {
            $submenus[] = array(
                'text' => '{LNG_Settings}',
                'url' => 'index.php?module=eleave-settings',
            );
        }
        if (Login::checkPermission($login, 'can_manage_eleave')) {
            $submenus[] = array(
                'text' => '{LNG_List of} {LNG_Leave type}',
                'url' => 'index.php?module=eleave-setup',
            );
            foreach (Language::get('ELEAVE_CATEGORIES', array()) as $type => $label) {
                $submenus[] = array(
                    'text' => $label,
                    'url' => 'index.php?module=eleave-categories&amp;type='.$type,
                );
            }
        }
        if (!empty($submenus)) {
            $menu->add('settings', '{LNG_E-Leave}', null, $submenus, 'eleave');
        }
    }
}
