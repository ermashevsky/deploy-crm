<?php

class Setting extends Eloquent {
    
protected $fillable = array('client_name', 'asteriskAddress', 'asteriskLogin', 'asteriskPassword', 'crmDomainName',
        'crmLogin', 'crmPassword', 'crmVersion', 'activeCRMModules', 'crmDescription', 'databaseName', 'databaseUsername',
        'databasePassword');

    public static function getAllRows() {
        $settings = Setting::All();
        return $settings;
    }

    public static function getRowByPrimaryKey($primaryKey) {
        $setting = Setting::find($primaryKey);
        return $setting;
    }

    public static function createNewRow($array) {
        $setting = Setting::create($array);
        return $setting;
    }

    public function deleteRowByPrimaryKey($primaryKey) {
        $setting = Setting::find($primaryKey);
        $setting->delete();
    }

}
