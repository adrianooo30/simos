<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use custom classes
use App\CustomClasses\BackupRestore;

class BackupRestoreController extends Controller
{
    
	public function backupRestore(){

		return view('backup-restore');
	}

  public function backup()
  {
    // backup database
    BackupRestore::backupDatabase();
    // backup file
    BackupRestore::backupFile();

    // return to backup  and restore
    return redirect()->route('backup.restore');
  }


	public function restoreDatabase($dbpath){

		

  //       DB::unprepared(file_get_contents('full/path/to/dump.sql'));

  //       $sql_dump = File::get('/path/to/file.sql');
		// DB::connection()->getPdo()->exec($sql_dump);


        


        // return "hellow";


        return json_encode($dbpath);
        
        


	}


    
}
