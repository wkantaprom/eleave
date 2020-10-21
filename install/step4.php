<?php
if (defined('ROOT_PATH')) {
    // ค่าที่ส่งมา
    $_SESSION['db_username'] = $_POST['db_username'];
    $_SESSION['db_password'] = $_POST['db_password'];
    $_SESSION['db_server'] = $_POST['db_server'];
    $_SESSION['db_name'] = preg_replace('/[^a-z0-9_]+/', '', $_POST['db_name']);
    $_SESSION['prefix'] = preg_replace('/[^a-z0-9_]+/', '', $_POST['prefix']);
    $content = array();
    $error = false;
    try {
        $options = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $conn = new \PDO('mysql:host='.$_SESSION['db_server'].';dbname=INFORMATION_SCHEMA', $_SESSION['db_username'], $_SESSION['db_password'], $options);
        $conn->query("SET SESSION sql_mode = ''");
        $result_id = $conn->query('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME="'.$_SESSION['db_name'].'"');
        $result = $result_id->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) {
            $conn->query('CREATE DATABASE '.$_SESSION['db_name'].' CHARACTER SET utf8');
        }
        $conn->query('USE '.$_SESSION['db_name']);
    } catch (\PDOException $e) {
        $error = true;
        echo '<h2>ความผิดพลาดในการเชื่อมต่อกับฐานข้อมูล</h2>';
        echo '<p class=warning>'.$e->getMessage().'</p>';
        echo '<p>อาจเป็นไปได้ว่า</p>';
        echo '<ol>';
        echo '<li>เซิร์ฟเวอร์ของฐานข้อมูลของคุณไม่สามารถใช้งานได้ในขณะนี้</li>';
        echo '<li>ไม่มีฐานข้อมูลที่ต้องการติดตั้ง กรุณาสร้างฐานข้อมูลก่อน หรือใช้ฐานข้อมูลที่มีอยู่แล้ว</li>';
        echo '<li>ข้อมูลต่างๆที่กรอกไม่ถูกต้อง กรุณากลับไปตรวจสอบ</li>';
        echo '</ol>';
        echo '<p>หากคุณไม่สามารถดำเนินการแก้ไขข้อผิดพลาดด้วยตัวของคุณเองได้ ให้ติดต่อผู้ดูแลระบบเพื่อขอข้อมูลที่ถูกต้อง</p>';
        echo '<p><a href="index.php?step=2" class="button large pink">กลับไปลองใหม่</a></p>';
    }
    if (!$error) {
        // เชื่อมต่อฐานข้อมูลสำเร็จ
        $content[] = '<li class="correct">เชื่อมต่อฐานข้อมูลสำเร็จ</li>';
        // ประมวลผลฐานข้อมูล
        $commands = file_get_contents('database.sql');
        $lines = explode("\n", $commands);
        $commands = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line && !startsWith($line, '--')) {
                if (preg_match('/CREATE TABLE `\{prefix\}_([a-z_\-]+)`/i', $line, $match)) {
                    $commands .= 'DROP TABLE IF EXISTS `'.$_SESSION['prefix'].'_'.$match[1]."`;\n";
                }
                $commands .= $line."\n";
            }
        }
        $commands = explode(";\n", $commands);
        foreach ($commands as $command) {
            if (trim($command)) {
                $command = str_replace('{prefix}', $_SESSION['prefix'], $command);
                try {
                    $conn->query($command);
                    $content[] = '<li class="correct">'.$command.'</li>';
                } catch (\PDOException $ex) {
                    $error = true;
                    $content[] = '<li class="incorrect">'.$ex->getMessage().'</li>';
                }
            }
        }
        if (!$error) {
            // default user
            $password_key = uniqid();
            $salt1 = uniqid();
            $salt2 = uniqid();
            $username = $_SESSION['username'];
            $password = $_SESSION['password'];
            $sql = "DELETE FROM `".$_SESSION['prefix']."_user` WHERE `id`=1;";
            $sql .= "INSERT INTO `".$_SESSION['prefix']."_user` (`id`, `username`, `salt`, `password`, `token`, `status`, `permission`, `name`, `create_date`) VALUES";
            $sql .= "(1, '".$username."', '".$salt1."', '".sha1($password_key.$password.$salt1)."', NULL, 1, '', 'แอดมิน', NOW());";
            $conn->query($sql);
            // บันทึก settings/database.php
            $database_cfg = include 'settings/database.php';
            $database_cfg['mysql']['username'] = $_SESSION['db_username'];
            $database_cfg['mysql']['password'] = $_SESSION['db_password'];
            $database_cfg['mysql']['dbname'] = $_SESSION['db_name'];
            $database_cfg['mysql']['hostname'] = $_SESSION['db_server'];
            $database_cfg['mysql']['prefix'] = $_SESSION['prefix'];
            $f = save($database_cfg, ROOT_PATH.'settings/database.php');
            $content[] = '<li class="'.($f ? 'correct' : 'incorrect').'">สร้างไฟล์ตั้งค่า <b>database.php</b> ...</li>';
            // บันทึก settings/config.php
            $cfg = include 'settings/config.php';
            $cfg['password_key'] = $password_key;
            $f = save($cfg, ROOT_PATH.'settings/config.php');
            $content[] = '<li class="'.($f ? 'correct' : 'incorrect').'">สร้างไฟล์ตั้งค่า <b>config.php</b> ...</li>';
        }
        if (!$error) {
            echo '<h2>ติดตั้งเรียบร้อย</h2>';
            echo '<p>การติดตั้งได้ดำเนินการเสร็จเรียบร้อยแล้ว หากคุณต้องการความช่วยเหลือในการใช้งาน คุณสามารถ ติดต่อสอบถามได้ที่ <a href="https://www.kotchasan.com" target="_blank">https://www.kotchasan.com</a></p>';
            echo '<ul>'.implode('', $content).'</ul>';
            echo '<p class=warning>กรุณาลบไดเร็คทอรี่ <em>install/</em> ออกจาก Server ของคุณ</p>';
            echo '<p>คุณควรปรับ chmod ให้ไดเร็คทอรี่ <em>datas/</em> และ <em>settings/</em> (และไดเร็คทอรี่อื่นๆที่คุณได้ปรับ chmod ไว้ก่อนการติดตั้ง) ให้เป็น 644 ก่อนดำเนินการต่อ (ถ้าคุณได้ทำการปรับ chmod ไว้ด้วยตัวเอง)</p>';
            echo '<p>เมื่อเรียบร้อยแล้ว กรุณา<b>เข้าระบบ</b>เพื่อตั้งค่าที่จำเป็นอื่นๆโดยใช้ขื่ออีเมล <em>'.$username.'</em> และรหัสผ่าน <em>'.$password.'</em> ตามที่ได้ลงทะเบียนไว้</p>';
            echo '<p><a href="../index.php" class="button large admin">เข้าระบบ</a></p>';
        } else {
            echo '<h2>ติดตั้งไม่สำเร็จ</h2>';
            echo '<p>การติดตั้งยังไม่สมบูรณ์ ลองตรวจสอบข้อผิดพลาดที่เกิดขึ้นและแก้ไขดู หากคุณต้องการความช่วยเหลือการติดตั้ง คุณสามารถ ติดต่อสอบถามได้ที่ <a href="https://www.kotchasan.com" target="_blank">https://www.kotchasan.com</a></p>';
            echo '<ul>'.implode('', $content).'</ul>';
            echo '<p><a href="." class="button large admin">ลองใหม่</a></p>';
        }
    }
}

/**
 * @param $haystack
 * @param $needle
 */
function startsWith($haystack, $needle)
{
    $length = strlen($needle);

    return substr($haystack, 0, $length) === $needle;
}

/**
 * @param $config
 * @param $file
 */
function save($config, $file)
{
    $f = @fopen($file, 'wb');
    if ($f !== false) {
        if (!preg_match('/^.*\/([^\/]+)\.php?/', $file, $match)) {
            $match[1] = 'config';
        }
        fwrite($f, '<'."?php\n/* $match[1].php */\nreturn ".var_export((array) $config, true).';');
        fclose($f);

        return true;
    } else {
        return false;
    }
}
