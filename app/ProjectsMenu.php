<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectsMenu extends Model
{
  public static function display() {
    $list = \File::directories('../..');
    $currentapp=\Config::get('constants.appname');
    echo '<ul id="projectsmenu">';
    foreach($list as $project){
      $projectname = ucwords(str_replace('../../', '', $project));
      if ($projectname!='Shared') {
        if ($currentapp==$projectname) {
        echo "<li><span>$projectname</span></li>";
        } else {
        echo '<li><a href="/'.strtolower($projectname)."/public/home\">$projectname</a></li>" ;
        }
      }
    }
    echo '</ul>';
  }
}
