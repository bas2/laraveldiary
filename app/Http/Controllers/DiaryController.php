<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiaryController extends Controller
{

  public function index () {
  $dirpath='../..';$proj=[];foreach(\File::directories($dirpath) as $project){$prj=str_replace($dirpath.'/','',$project);if(substr($prj,0,1)!='_'){$proj[]=ucwords($prj);}}
  return view('welcome')->with('projlist',$proj)
  ->with('vals',['datesel'=>Carbon::now(),'seldate'=>Carbon::now(),'blankcells'=>1,'daysinmonth'=>31]);
  }



  

}
