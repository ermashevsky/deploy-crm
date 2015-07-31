<?php

class Setting extends Eloquent {
    
protected $fillable = array('client_name', 'asteriskAddress', 'asteriskPort', 'httpPort','asteriskLogin', 'asteriskPassword', 'crmDomainName',
        'crmLogin', 'crmPassword', 'crmVersion', 'activeCRMModules', 'crmDescription', 'databaseName', 'databaseUsername',
        'databasePassword');

    public static function getAllRows() {
        $settings = Setting::All();
        return $settings;
    }

    public static function getRowByPrimaryKey($primaryKey) {
        $setting = Setting::select('*')
                ->where('id', $primaryKey)->get();
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
    
    public static function updateCrmParameters($id, $data){
        Setting::where('id', $id)
            ->update($data);
    }
    
    public static function setInstalledTimeStamp($crmDomainName){
        
        $dt = new DateTime;
        
        
        $data = array(
            'installed_at' => $dt->format('Y-m-d H:i:s')
        );
        
        Setting::where('crmDomainName', $crmDomainName)
            ->update($data);
    }
    
    public static function checkInstalledCRM($folder){
        $installed_at = Setting::select(DB::raw('COUNT(*) as counter'))
                ->where('crmDomainName', $folder)
                ->where('installed_at', '<>', '0000-00-00 00:00:00')
                ->where('deleted_at', '0000-00-00 00:00:00')->get();
        
        return $installed_at;
        
    }
   
}
