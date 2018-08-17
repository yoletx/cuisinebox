<?php

class Util
{
  public static function formater_quantite($quantite, $unite){
    switch($unite){
      case "U":
        return $quantite;
        break;
      case "P":
        if($quantite>1){
          return round($quantite,3)."(kgs)";
        } else {
          return round($quantite,3)."(gr.)";
        }
        break;
      case "L":
        if($quantite>1){
          return round($quantite,3)."(l)";
        } else {
          return (round($quantite,3)*1000)."(ml)";
        }
        break;
    }
  }
}
?>