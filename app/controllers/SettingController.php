<?php

class SettingController extends Controller {

    public function index() {
        return View::make('setting')->with(array(
                    'allSettings' => Setting::getAllRows()
        ));
    }

    public function manageCRM() {
        return View::make('manageCRM');
    }

    public function viewUserCRM() {
        return View::make('viewUserCRM');
    }

    public function createCRMForm() {
        return View::make('addCRMForm');
    }

    public function createNewRow() {
        $data = Input::all();
        Setting::createNewRow($data);
    }

    public function getRowByPrimaryKey() {
        $id = Input::all();
        $row = Setting::getRowByPrimaryKey($id);
        echo json_encode($row);
    }

    public function viewModuleList() {
        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $modules_array = array();

            foreach ($dbh->query('SELECT * from system_plugins') as $row) {
                $arr['id'] = $row['id'];
                $arr['plugin_system_name'] = $row['plugin_system_name'];
                $arr['plugin_name'] = $row['plugin_name'];
                $arr['plugin_uri'] = $row['plugin_uri'];
                $arr['plugin_version'] = $row['plugin_version'];
                $arr['plugin_description'] = $row['plugin_description'];
                $arr['plugin_author'] = $row['plugin_author'];
                $arr['plugin_state'] = $row['plugin_state'];
                $arr['plugin_type'] = $row['plugin_type'];
                $modules_array[$arr['id']] = $arr;
            }
            return $modules_array;
            //$dbh = null;
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function checkVersionModule() {

        $data = Input::all();

        $version = $data['version'];
        $plugin_system_name = $data['plugin_system_name'];

        $root = '/home/denic/server/dialog.crm64.ru/application/modules/';

        $list = File::directories($root);
        $n = 1;

        foreach ($list as $directory):
            $xml_file = simplexml_load_file($directory . "/configDescription.xml");

            if ($xml_file->plugin_system_name == $plugin_system_name && $version == $xml_file->plugin_version) {

                echo json_encode("Актуальная версия");
            }
            if ($xml_file->plugin_system_name == $plugin_system_name && $version != $xml_file->plugin_version) {
                $my_data = array(
                    'text' => "Доступно обновление. Версия: " . $xml_file->plugin_version,
                    'version' => "" . $xml_file->plugin_version . ""
                );
                echo json_encode($my_data);
            }

        endforeach;

        //echo json_encode($notInstalled);
    }

    function startUpdateModuleVersion() {
        $data = Input::all();
        //dd($data);
        $crmDomainName = $data['crmDomainName'];
        $pluginSystemName = $data['pluginSystemName'];
        $crmVersion = $data['crmVersion'];

        $d1 = '/home/denic/server/dialog.crm64.ru/application/modules/' . $pluginSystemName;
        $d2 = '/home/denic/server/' . $crmDomainName . '/application/modules/' . $pluginSystemName;

        $this->rcopy($d1, $d2);

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");
            $dbh->exec("SET NAMES 'utf8'");
            $sqlQueryUpdate = "UPDATE `system_plugins`   
            SET `plugin_version` = '" . $crmVersion . "'
            WHERE `plugin_system_name` = '" . $pluginSystemName . "'";

            $dbh->query($sqlQueryUpdate);

            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function updateModuleVersionRecordDb($crmDomainName, $crmVersion) {
        
    }

    // removes files and non-empty directories
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->rrmdir("$dir/$file");
                }
            }
            rmdir($dir);
        } else {
            if (file_exists($dir)) {
                unlink($dir);
            }
        }
    }

    function rcopy($src, $dst) {
        if (file_exists($dst)) {
            $this->rrmdir($dst);
        }
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->rcopy("$src/$file", "$dst/$file");
                }
            }
        } else if (file_exists($src)) {
            copy($src, $dst);
        }
    }

    public function viewUsersList() {
        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $modules_array = array();

            foreach ($dbh->query('SELECT users.id, users.last_name, users.first_name, users.company, users.work_position, users.phone, users.external_phone, users.email, users.username, users.created_on, users.last_login, groups.description from users inner join users_groups on users_groups.user_id =  users.id inner join groups on groups.id = users_groups.group_id') as $row) {

                $arr['id'] = $row['id'];
                $arr['last_name'] = $row['last_name'];
                $arr['first_name'] = $row['first_name'];
                $arr['company'] = $row['company'];
                $arr['work_position'] = $row['work_position'];
                $arr['phone'] = $row['phone'];
                $arr['external_phone'] = $row['external_phone'];
                $arr['email'] = $row['email'];
                $arr['username'] = $row['username'];
                $arr['created_on'] = $row['created_on'];
                $arr['last_login'] = $row['last_login'];
                $arr['description'] = $row['description'];
                $modules_array[$arr['id']] = $arr;
            }
            return $modules_array;
            //$dbh = null;
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    public function deleteUser() {
        $data = Input::all();
        $id = $data['id'];
        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $dbh->query('delete from `users` where `id`=' . $id);

            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function getUserDetail() {
        $data = Input::all();
        $id = $data['id'];
        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $modules_array = array();

            foreach ($dbh->query('SELECT users.id, users.last_name, users.first_name, users.company, users.work_position, users.phone, users.external_phone, users.email, users.username, users.created_on, users.last_login, groups.description from users inner join users_groups on users_groups.user_id =  users.id inner join groups on groups.id = users_groups.group_id where `users`.`id` = ' . $id) as $row) {

                $arr['id'] = $row['id'];
                $arr['last_name'] = $row['last_name'];
                $arr['first_name'] = $row['first_name'];
                $arr['company'] = $row['company'];
                $arr['work_position'] = $row['work_position'];
                $arr['phone'] = $row['phone'];
                $arr['external_phone'] = $row['external_phone'];
                $arr['email'] = $row['email'];
                $arr['username'] = $row['username'];
                $arr['created_on'] = $row['created_on'];
                $arr['last_login'] = $row['last_login'];
                $arr['description'] = $row['description'];
                $modules_array[$arr['id']] = $arr;
            }
            return $modules_array;
            //$dbh = null;
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function updateUserDetails() {
        $data = Input::all();

        $id = $data['id'];
        //$login = $data['login'];
        $last_name = $data['last_name'];
        $first_name = $data['first_name'];
        $work_position = $data['work_position'];
        $company = $data['company'];
        $email = $data['email'];
        $phone = $data['phone'];
        $external_phone = $data['external_phone'];
        $group = $data['group'];

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");
            $dbh->exec("SET NAMES 'utf8'");
            $sqlQueryUpdate = "UPDATE `users`   
            SET `last_name` = '" . $last_name . "',
                `email` = '" . $email . "',
                `first_name` = '" . $first_name . "',
                `work_position` = '" . $work_position . "',
                `company` = '" . $company . "',
                `phone` = '" . $phone . "',
                `external_phone` = '" . $external_phone . "'
            WHERE `id` = '" . $id . "'";

            $dbh->query($sqlQueryUpdate);

            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    /**
     * Misc functions
     *
     * Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
     *
     * @author Mathew
     */

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password($password, $salt = FALSE) {
        $this->store_salt = FALSE;

        if (empty($password)) {
            return FALSE;
        }

        if ($this->store_salt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password_db($id, $password) {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        $query = $this->db->select('password, salt')
                ->where('id', $id)
                ->limit(1)
                ->get($this->tables['users']);

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        if ($this->store_salt) {
            return sha1($password . $hash_password_db->salt);
        } else {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * Generates a random salt value.
     *
     * @return void
     * @author Mathew
     * */
    public function salt() {
        $this->salt_length = 10;

        return substr(md5(uniqid(rand(), TRUE)), 0, $this->salt_length);
    }

    public static function checkInstallationCRM($folder) {

        $path = "/home/denic/server/" . $folder;

        if (File::exists($path)) {
            $installed_at = Setting::checkInstalledCRM($folder);

            foreach ($installed_at as $counter) {
                if ($counter->counter === '1') {
                    return TRUE;
                }
            }
        } else {
            return FALSE;
        }
    }

    public function createCRMDB() {
        $host = "localhost";
        $root = "root";
        $root_password = "11235813";

        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];
        $asteriskHost = $data['asteriskHost'];
        $asteriskPort = $data['asteriskPort'];
        $asteriskUsername = $data['asteriskUsername'];
        $asteriskPassword = $data['asteriskPassword'];
        $httpPort = $data['httpPort'];
        $crmDomainName = $data['crmDomainName'];
        $crmUsername = $data['crmUsername'];
        $crmPassword = $data['crmPassword'];


        //Создаем начальный XML файл настроек и кладем его куда надо
        $config_xml = new DOMDocument("1.0", "UTF-8");
        $config_xml->preserveWhiteSpace = false;
        $config_xml->formatOutput = true;
        $config_xml_root = $config_xml->createElement("root");

        $xml_baseUrl = $config_xml->createElement("xml_baseUrl", "http://" . $crmDomainName);
        $xml_listnerSocketAddress = $config_xml->createElement("xml_listnerSocketAddress", "http://" . $crmDomainName . ":" . $httpPort . "/socket.io/socket.io.js");
        $xml_listnerAddress = $config_xml->createElement("xml_listnerAddress", "http://" . $crmDomainName . ":" . $httpPort);
        $xml_databaseHost = $config_xml->createElement("dbHost", "localhost");
        $xml_databaseUsername = $config_xml->createElement("dbUsername", $user);
        $xml_databasePassword = $config_xml->createElement("dbPassword", $pass);
        $xml_databaseName = $config_xml->createElement("dbName", $db);


        $config_xml_root->appendChild($xml_baseUrl);
        $config_xml_root->appendChild($xml_listnerSocketAddress);
        $config_xml_root->appendChild($xml_listnerAddress);
        $config_xml_root->appendChild($xml_databaseHost);
        $config_xml_root->appendChild($xml_databaseUsername);
        $config_xml_root->appendChild($xml_databasePassword);
        $config_xml_root->appendChild($xml_databaseName);


        $config_xml->appendChild($config_xml_root);

        $config_xml->save("/tmp/configCRM.xml");


        try {
            $dbh = new PDO("mysql:host=$host", $root, $root_password);
            $dbh->exec("set names utf8");

            $dbh->exec("CREATE DATABASE `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;");

            $dbh->exec("USE `$db`");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `cdr` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `clid` varchar(80) NOT NULL DEFAULT '',
                        `src` varchar(80) NOT NULL DEFAULT '',
                        `dst` varchar(80) NOT NULL DEFAULT '',
                        `answerext` varchar(80) NOT NULL,
                        `channel` varchar(80) NOT NULL DEFAULT '',
                        `dstchannel` varchar(80) NOT NULL DEFAULT '',
                        `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `answer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `duration` int(11) NOT NULL DEFAULT '0',
                        `billsec` int(11) NOT NULL DEFAULT '0',
                        `disposition` varchar(45) NOT NULL DEFAULT '',
                        `cause` int(11) NOT NULL,
                        `uniqueid` varchar(32) NOT NULL DEFAULT '',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `contacts` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `contact_name` varchar(255) NOT NULL,
                        `private_phone_number` varchar(255) NOT NULL,
                        `mobile_number` varchar(255) DEFAULT NULL,
                        `job_position` varchar(255) DEFAULT NULL,
                        `email` varchar(255) DEFAULT NULL,
                        `birthday` varchar(255) DEFAULT NULL,
                        `address` varchar(255) DEFAULT NULL,
                        `comment` varchar(255) DEFAULT NULL,
                        `organization_id` int(11) DEFAULT NULL,
                        `organization_note` varchar(255) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `organization` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `organization_name` varchar(255) NOT NULL,
                        `short_organization_name` varchar(255) DEFAULT NULL,
                        `full_organization_name` varchar(255) DEFAULT NULL,
                        `address` varchar(255) DEFAULT NULL,
                        `alt_address` varchar(255) DEFAULT NULL,
                        `inn` int(25) DEFAULT NULL,
                        `phone_number` varchar(255) DEFAULT NULL,
                        `alt_phone_number` varchar(255) DEFAULT NULL,
                        `comment` varchar(255) DEFAULT NULL,
                        `email` varchar(255) DEFAULT NULL,
                        `fax` varchar(255) DEFAULT NULL,
                        `web_url` varchar(255) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8");


            $dbh->exec("CREATE TABLE IF NOT EXISTS `groups` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `name` varchar(20) NOT NULL,
                        `description` varchar(100) NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;");

            $dbh->exec("INSERT IGNORE INTO `groups` (`id`, `name`, `description`) VALUES
                        (1, 'admin', 'Administrator'),
                        (2, 'members', 'General User');");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `users` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `ip_address` varbinary(16) NOT NULL,
                        `username` varchar(100) NOT NULL,
                        `password` varchar(40) NOT NULL,
                        `salt` varchar(40) DEFAULT NULL,
                        `email` varchar(100) NOT NULL,
                        `activation_code` varchar(40) DEFAULT NULL,
                        `forgotten_password_code` varchar(40) DEFAULT NULL,
                        `forgotten_password_time` int(11) unsigned DEFAULT NULL,
                        `remember_code` varchar(40) DEFAULT NULL,
                        `created_on` int(11) unsigned NOT NULL,
                        `last_login` int(11) unsigned DEFAULT NULL,
                        `active` tinyint(1) unsigned DEFAULT NULL,
                        `first_name` varchar(50) DEFAULT NULL,
                        `last_name` varchar(50) DEFAULT NULL,
                        `company` varchar(100) DEFAULT NULL,
                        `work_position` varchar(255) NOT NULL,
                        `phone` varchar(20) DEFAULT NULL,
                        `external_phone` varchar(255) NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

            $dbh->exec("INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `work_position`, `phone`, `external_phone`) VALUES
                      (1, '0000000000', 'su', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'su@dialog64.ru', '', NULL, NULL, NULL, 1268889823, 1426842234, 1, 'User', 'Super', 'Технический отдел', 'Наш доступ', '00', '0000000000');");

            $dbh->exec("INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `work_position`, `phone`, `external_phone`) VALUES
                      (2, '0000000000', '" . $crmUsername . "', '" . $this->hash_password($crmPassword) . "', '" . $this->salt() . "', 'admin@" . $crmDomainName . "', '', NULL, NULL, NULL, 1268889823, 1426842234, 1, 'Администратор', '000', 'Технический отдел', 'Администратор', '00', '0000000000');");


            $dbh->exec("CREATE TABLE IF NOT EXISTS `users_groups` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `user_id` mediumint(8) unsigned NOT NULL,
                        `group_id` mediumint(8) unsigned NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

            $dbh->exec("INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (1, 1, 1),(2, 2, 1);");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `tasks` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `initiator` varchar(255) DEFAULT NULL,
                        `category` varchar(255) DEFAULT NULL,
                        `status` varchar(255) DEFAULT NULL,
                        `priority` varchar(255) DEFAULT NULL,
                        `task_name` varchar(255) DEFAULT NULL,
                        `task_description` text,
                        `assigned` varchar(255) DEFAULT NULL,
                        `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                        `end_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
                        `reminder_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
                        `id_call` varchar(255) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `login_attempts` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `ip_address` varbinary(16) NOT NULL,
                        `login` varchar(100) NOT NULL,
                        `time` int(11) unsigned DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `system_asteriskSettings` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `asteriskHost` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `asteriskPort` int(10) NOT NULL,
                        `asteriskUsername` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `asteriskPassword` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `httpServerListenPort` int(10) NOT NULL,
                        `databaseHost` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `databaseUsername` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `databasePassword` varchar(255) CHARACTER SET utf8 NOT NULL,
                        `databaseName` varchar(255) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");


            $dbh->exec("INSERT INTO `system_asteriskSettings` (`asteriskHost`, `asteriskPort`, `asteriskUsername`, `asteriskPassword`, `httpServerListenPort`, `databaseHost`, `databaseUsername`, `databasePassword`, `databaseName`) VALUES
                      ('" . $asteriskHost . "', '" . $asteriskPort . "', '" . $asteriskUsername . "', '" . $asteriskPassword . "', '" . $httpPort . "', 'localhost', '" . $user . "', '" . $pass . "', '" . $db . "');");

            //Создаем начальный XML файл настроек и кладем его куда надо
            $xml = new DOMDocument("1.0", "UTF-8");
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml_root = $xml->createElement("root");

            $xml_asteriskHost = $xml->createElement("asteriskHost", $asteriskHost);
            $xml_asteriskPort = $xml->createElement("asteriskPort", $asteriskPort);
            $xml_asteriskUsername = $xml->createElement("asteriskUsername", $asteriskUsername);
            $xml_asteriskPassword = $xml->createElement("asteriskPassword", $asteriskPassword);
            $xml_dbHost = $xml->createElement("dbHost", "localhost");
            $xml_dbUsername = $xml->createElement("dbUsername", $user);
            $xml_dbPassword = $xml->createElement("dbPassword", $pass);
            $xml_dbName = $xml->createElement("dbName", $db);
            $xml_httpPort = $xml->createElement("httpPort", $httpPort);

            $xml_root->appendChild($xml_asteriskHost);
            $xml_root->appendChild($xml_asteriskPort);
            $xml_root->appendChild($xml_asteriskUsername);
            $xml_root->appendChild($xml_asteriskPassword);
            $xml_root->appendChild($xml_dbHost);
            $xml_root->appendChild($xml_dbUsername);
            $xml_root->appendChild($xml_dbPassword);
            $xml_root->appendChild($xml_dbName);
            $xml_root->appendChild($xml_httpPort);

            $xml->appendChild($xml_root);

            $xml->save("/tmp/asteriskSettings.xml");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `system_plugins` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `plugin_system_name` varchar(255) NOT NULL,
                        `plugin_menu_visible` varchar(255) NOT NULL,
                        `plugin_menu_name` varchar(255) NOT NULL,
                        `plugin_menu_item_order` varchar(255) NOT NULL,
                        `plugin_name` varchar(255) NOT NULL,
                        `plugin_uri` varchar(120) DEFAULT NULL,
                        `plugin_version` varchar(30) NOT NULL,
                        `plugin_description` text,
                        `plugin_author` varchar(120) DEFAULT NULL,
                        `plugin_state` varchar(255) NOT NULL,
                        `plugin_type` varchar(255) NOT NULL,
                        `plugin_sql_file` varchar(255) NOT NULL,
                        `plugin_sql_database` varchar(255) NOT NULL,
                        `plugin_sql_user` varchar(255) NOT NULL,
                        `plugin_sql_password` varchar(255) NOT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `plugin_index` (`plugin_system_name`) USING BTREE
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

            //Set Installed TimeStamp
            $this->setInstalledTimeStamp($crmDomainName);

            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();


            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function setInstalledTimeStamp($crmDomainName) {

        Setting::setInstalledTimeStamp($crmDomainName);
    }

    function moveXMLFile() {
        $data = Input::all();

        $crmDomainName = $data['vhost'];

        rename("/home/denic/server/" . $crmDomainName . "/application/config/dialog.crm64.ru", "/home/denic/server/" . $crmDomainName . "/application/config/" . $crmDomainName);
        rename("/tmp/asteriskSettings.xml", "/home/denic/server/" . $crmDomainName . "/assets/js/AsteriskAmi/asteriskSettings.xml");
        rename("/home/denic/server/" . $crmDomainName . "/assets/js/AsteriskAmi/index.js", "/home/denic/server/" . $crmDomainName . "/assets/js/AsteriskAmi/" . $crmDomainName . ".js");
        rename("/tmp/configCRM.xml", "/home/denic/server/" . $crmDomainName . "/application/config/" . $crmDomainName . "/configCRM.xml");
    }

    public function viewAsteriskSettings() {
        return View::make('asteriskSettings');
    }

    public function getAsteriskSettings() {
        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $asteriskSettings_array = array();

            foreach ($dbh->query('SELECT * from `system_asteriskSettings`') as $row) {
                $arr['id'] = $row['id'];
                $arr['asteriskHost'] = $row['asteriskHost'];
                $arr['asteriskPort'] = $row['asteriskPort'];
                $arr['asteriskUsername'] = $row['asteriskUsername'];
                $arr['asteriskPassword'] = $row['asteriskPassword'];
                $arr['databaseHost'] = $row['databaseHost'];
                $arr['databaseUsername'] = $row['databaseUsername'];
                $arr['databasePassword'] = $row['databasePassword'];
                $arr['databaseName'] = $row['databaseName'];
                $arr['httpServerListenPort'] = $row['httpServerListenPort'];
                $asteriskSettings_array[$arr['id']] = $arr;
            }
            return $asteriskSettings_array;
            //$dbh = null;
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function updateAsteriskSettings() {
        $data = Input::all();

        $id = $data['id'];
        $asteriskHost = $data['asteriskHost'];
        $asteriskPort = $data['asteriskPort'];
        $asteriskUsername = $data['asteriskUsername'];
        $asteriskPassword = $data['asteriskPassword'];
        $dbHost = $data['dbHost'];
        $dbUsername = $data['dbUsername'];
        $dbPassword = $data['dbPassword'];
        $dbName = $data['dbName'];
        $httpPort = $data['httpPort'];
        $crmDomainName = $data['crmDomainName'];
        $id_crm = $data['id_crm'];


        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");
            $dbh->exec("SET NAMES 'utf8'");
            $sqlQueryUpdate = "UPDATE `system_asteriskSettings`   
            SET `asteriskHost` = '" . $asteriskHost . "',
                `asteriskPort` = '" . $asteriskPort . "',
                `asteriskUsername` = '" . $asteriskUsername . "',
                `asteriskPassword` = '" . $asteriskPassword . "',
                `databaseHost` = '" . $dbHost . "',
                `databaseUsername` = '" . $dbUsername . "',
                `databasePassword` = '" . $dbPassword . "',
                `databaseName` = '" . $dbName . "',
                `httpServerListenPort` = '" . $httpPort . "' 
            WHERE `id` = '" . $id . "'";

            $dbh->query($sqlQueryUpdate);

            $data_param = array(
                'asteriskAddress' => $asteriskHost,
                'asteriskLogin' => $asteriskUsername,
                'asteriskPassword' => $asteriskPassword,
                'asteriskPort' => $asteriskPort,
                'httpPort' => $httpPort,
                'databaseName' => $dbName,
                'databaseUsername' => $dbUsername,
                'databasePassword' => $dbPassword
            );

            Setting::updateCrmParameters($id_crm, $data_param);

            $this->createAsteriskSettingsXMLFile($crmDomainName);

            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    public function createAsteriskSettingsXMLFile($crmDomainName) {

        $data = Input::all();
        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $xml = new DOMDocument("1.0", "UTF-8");
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml_root = $xml->createElement("root");


            foreach ($dbh->query('SELECT * from `system_asteriskSettings`') as $row) {

                $xml_asteriskHost = $xml->createElement("asteriskHost", $row['asteriskHost']);
                $xml_asteriskPort = $xml->createElement("asteriskPort", $row['asteriskPort']);
                $xml_asteriskUsername = $xml->createElement("asteriskUsername", $row['asteriskUsername']);
                $xml_asteriskPassword = $xml->createElement("asteriskPassword", $row['asteriskPassword']);
                $xml_dbHost = $xml->createElement("dbHost", $row['databaseHost']);
                $xml_dbUsername = $xml->createElement("dbUsername", $row['databaseUsername']);
                $xml_dbPassword = $xml->createElement("dbPassword", $row['databasePassword']);
                $xml_dbName = $xml->createElement("dbName", $row['databaseName']);
                $xml_httpPort = $xml->createElement("httpPort", $row['httpServerListenPort']);
            }

            $xml_root->appendChild($xml_asteriskHost);
            $xml_root->appendChild($xml_asteriskPort);
            $xml_root->appendChild($xml_asteriskUsername);
            $xml_root->appendChild($xml_asteriskPassword);
            $xml_root->appendChild($xml_dbHost);
            $xml_root->appendChild($xml_dbUsername);
            $xml_root->appendChild($xml_dbPassword);
            $xml_root->appendChild($xml_dbName);
            $xml_root->appendChild($xml_httpPort);

            $xml->appendChild($xml_root);

            $xml->save("/home/denic/server/" . $crmDomainName . "/assets/js/AsteriskAmi/asteriskSettings.xml");

            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    public function createVhostEnvironment() {
        $data = Input::all();

        $host = $data['vhost'];
        $directory = $data['vhostDirectory'];

        $result = exec('sudo /home/denic/server/deploy.crm64.ru/scripts/addvhost.sh -u ' . $host . ' -d ' . $directory);

        echo json_encode($result);
    }

    #Function to backup database to a zip file

    function backup($username, $password, $db) {
        
    }

    public function deleteCRM() {

        date_default_timezone_set('Europe/Kaliningrad');

        $data = Input::all();

        $id = $data['id'];

        foreach (Setting::getRowByPrimaryKey($id) as $param) {

            $username = $param['databaseUsername'];
            $password = $param['databasePassword'];
            $db = $param['databaseName'];
            $crmDomainName = $param['crmDomainName'];

            $suffix = date('Y-m-d_H-i-s');
            $command = "mysqldump -u " . $username . " -p" . $password . " --single-transaction " . $db . " > /home/denic/server/" . $crmDomainName . "/backups/db_backup_" . $suffix . ".sql";

            system($command, $output);

            if ($output != 0) {
                echo 'Error during backup';
            } else {

                $cmd = escapeshellcmd('mysql -u' . $username . ' -p' . $password . ' -e "drop database ' . $db . '"');

                shell_exec($cmd);

//                $sourcePath = "/home/denic/server/" . $crmDomainName . "/";
//                $outZipPath = "/home/denic/server/archiveCRM/";

                $archive_dir = "/home/denic/server/archiveCRM/" . $crmDomainName . "_" . $suffix . ".zip";
                $src_dir = "/home/denic/server/" . $crmDomainName;

                $this->zipFile($src_dir, $archive_dir, TRUE);
                $this->deleteDirectory($src_dir);
            }
        }
    }

    function zipFile($source, $destination, $flag = '') {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));
        if ($flag) {
            $flag = basename($source) . '/';
            //$zip->addEmptyDir(basename($source) . '/');
        }

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = str_replace('\\', '/', realpath($file));

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $flag . $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $flag . $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString($flag . basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    function deleteDirectory($dir) {
        system('rm -rf ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

    function checkBoxTrigger() {
        $data = Input::all();

        $id = $data['id'];
        $checkbox_state = $data['checkbox_state'];
        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];
        if ($checkbox_state === 'false') {

            $state = "";
        } else {
            $state = "checked";
        }

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $sqlQueryUpdate = "UPDATE `system_plugins`   
            SET `plugin_state` = '" . $state . "'
            WHERE `id` = " . $id;
            //echo $sqlQueryUpdate;
            $dbh->query($sqlQueryUpdate);
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function getNotInstalledModules() {

        $root = '/home/denic/server/dialog.crm64.ru/application/modules/';

        $list = File::directories($root);
        $n = 1;
        $notInstalled = array();

        foreach ($list as $directory):
            $xml_file = simplexml_load_file($directory . "/configDescription.xml");
            $count = $this->checkModuleList($xml_file->plugin_system_name);
            if ($count === "0") {
                $notInstalled[$n++] = $xml_file;
            }

        endforeach;

        echo json_encode($notInstalled);
    }

    function checkModuleList($plugin_system_name) {
        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];
        //$plugin_system_name = $data['plugin_system_name'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            foreach ($dbh->query('SELECT count(*) as counter from `system_plugins` where `plugin_system_name`="' . $plugin_system_name . '"') as $row) {
                //echo $row['counter'];
            }
            return $row['counter'];
            //$dbh = null;
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function installModule() {
        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];
        $plugin_system_name = $data['plugin_system_name'];
        $plugin_menu_visible = $data['plugin_menu_visible'];
        $plugin_menu_name = $data['plugin_menu_name'];
        $plugin_menu_item_order = $data['plugin_menu_item_order'];
        $plugin_name = $data['plugin_name'];
        $plugin_uri = $data['plugin_uri'];
        $plugin_version = $data['plugin_version'];
        $plugin_description = $data['plugin_description'];
        $plugin_author = $data['plugin_author'];
        $plugin_state = $data['plugin_state'];
        $plugin_type = $data['plugin_type'];


        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("set names utf8");

            $dbh->query("INSERT IGNORE INTO `system_plugins` (`plugin_system_name`,	`plugin_menu_visible`,`plugin_menu_name`,`plugin_menu_item_order`,`plugin_name`, `plugin_uri`, `plugin_version`, `plugin_description`, `plugin_author`, `plugin_state`, `plugin_type`, `plugin_sql_file`, `plugin_sql_database`,`plugin_sql_user`,`plugin_sql_password`) VALUES ('$plugin_system_name','$plugin_menu_visible','$plugin_menu_name','$plugin_menu_item_order','$plugin_name',"
                    . "'$plugin_uri','$plugin_version','$plugin_description','$plugin_author','$plugin_state','$plugin_type','backup.sql','$db','$user','$pass')");


            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

    function saveNewUser() {
        $data = Input::all();

        $inputLogin = $data['inputLogin'];
        $inputLastName = $data['inputLastName'];
        $inputFirstName = $data['inputFirstName'];
        $inputWorkPosition = $data['inputJobPosition'];
        $inputWorkDept = $data['inputWorkDept'];
        $inputEmail = $data['inputEmail'];
        $inputPhone = $data['inputPhone'];
        $inputExternalPhone = $data['inputExternalPhone'];
        $group = $data['group'];
        $inputPassword = $data['inputPassword'];
        $inputPasswordConfirm = $data['inputPasswordConfirm'];
        $db = $data['db'];
        $user = $data['user'];
        $pass = $data['pass'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("set names utf8");
            $query = "INSERT INTO `users` (`ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `work_position`, `phone`, `external_phone`) VALUES
                      ('0000000000', '" . $inputLogin . "', '" . $this->hash_password($inputPassword) . "', '" . $this->salt() . "', '" . $inputEmail . "', '', NULL, NULL, NULL, '" . time() . "', '', 1, '" . $inputFirstName . "', '" . $inputLastName . "', '" . $inputWorkDept . "', '" . $inputWorkPosition . "', '" . $inputPhone . "', '" . $inputExternalPhone . "');";
            $dbh->exec($query);

            $lastId = $dbh->lastInsertId();
            
            $returned_id = $this->getIdGroupByName($group,$db,$user,$pass); //Реализовать метод и возвращать id в Insert запрос
            
            $query = "INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES ('".$lastId."', '" . $returned_id . "');";
            $dbh->exec($query);
            
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                //return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }
    
    function getIdGroupByName($group,$db,$user,$pass){
        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("set names utf8");
            $query = "SELECT id FROM groups where name='".$group."';";
            
            foreach($dbh->query($query) as $value):
                return $value['id'];
            endforeach;
            
            // в случае ошибки SQL выражения выведем сообщене об ошибке
            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                //return json_encode($error_array[2]);
            }
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

}
