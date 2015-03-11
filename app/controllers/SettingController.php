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
    
    public function viewUserCRM(){
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

            $dbh->query('delete from `users` where `id`='.$id);

            $error_array = $dbh->errorInfo();

            if ($dbh->errorCode() !== 00000 && $error_array[2] !== "") {

                return json_encode($error_array[2]);
            }
        
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }
    
    function getUserDetail(){
        $data = Input::all();
        $id = $data['id'];
        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
            $dbh->exec("SET CHARACTER SET utf8");

            $modules_array = array();

            foreach ($dbh->query('SELECT users.id, users.last_name, users.first_name, users.company, users.work_position, users.phone, users.external_phone, users.email, users.username, users.created_on, users.last_login, groups.description from users inner join users_groups on users_groups.user_id =  users.id inner join groups on groups.id = users_groups.group_id where `users`.`id` = '.$id) as $row) {
                
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

    public function createCRMDB() {
        $host = "localhost";
        $root = "root";
        $root_password = "11235813";

        $data = Input::all();

        $user = $data['user'];
        $pass = $data['pass'];
        $db = $data['db'];

        try {
            $dbh = new PDO("mysql:host=$host", $root, $root_password);

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
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `users_groups` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `user_id` mediumint(8) unsigned NOT NULL,
                        `group_id` mediumint(8) unsigned NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

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

            $dbh->exec("CREATE TABLE IF NOT EXISTS `plugins` (
                        `plugin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `plugin_system_name` varchar(255) NOT NULL,
                        `plugin_name` varchar(255) NOT NULL,
                        `plugin_uri` varchar(120) DEFAULT NULL,
                        `plugin_version` varchar(30) NOT NULL,
                        `plugin_description` text,
                        `plugin_author` varchar(120) DEFAULT NULL,
                        `plugin_author_uri` varchar(120) DEFAULT NULL,
                        `plugin_data` longtext,
                        PRIMARY KEY (`plugin_id`),
                        UNIQUE KEY `plugin_index` (`plugin_system_name`) USING BTREE
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

            $dbh->exec("CREATE TABLE IF NOT EXISTS `login_attempts` (
                        `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `ip_address` varbinary(16) NOT NULL,
                        `login` varchar(100) NOT NULL,
                        `time` int(11) unsigned DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");



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

}
