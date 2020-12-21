<?php
require_once('cAscenseur.php');
if(isset($_POST['nameElevator']) && isset($_POST['iHeightElevator']) && isset($_POST['iWidthElevator']) 
    && isset($_POST['iStartLeft']) && isset($_POST['iNumberFloor']) && isset($_POST['sideLarge']) 
    && isset($_POST['sideHeight']) && isset($_POST['iCurrentFloor'])   && isset($_POST['sCommande'])   && isset($_POST['isDoorOpen']) )
{
    $oAscenceur= new MonAscenseur($_POST['nameElevator'], $_POST['iNumberFloor'],$_POST['iCurrentFloor'],$_POST['isDoorOpen'],$_POST['iStartLeft'],$_POST['iWidthElevator'],$_POST['iHeightElevator'],$_POST['sideLarge'],$_POST['sideHeight']); 
//initi d'un ascenseur 
    if($_POST['sCommande']=='init'){
        $sVerify=$oAscenceur->VerifyStatus();  
        
            echo  $sVerify;
        
    }

    if($_POST['sCommande']=='up'){
        $oAscenceur= new MonAscenseur($_POST['nameElevator'], $_POST['iNumberFloor'],$_POST['iCurrentFloor'],$_POST['isDoorOpen'],$_POST['iStartLeft'],$_POST['iWidthElevator'],$_POST['iHeightElevator'],$_POST['sideLarge'],$_POST['sideHeight']);
        $sVerify=$oAscenceur->VerifyStatus();  
        if($sVerify=='OK')
        {
            $oAscenceur->goUp(1) ;
        }
    }

    if($_POST['sCommande']=='down'){
        $oAscenceur= new MonAscenseur($_POST['nameElevator'], $_POST['iNumberFloor'],$_POST['iCurrentFloor'],$_POST['isDoorOpen'],$_POST['iStartLeft'],$_POST['iWidthElevator'],$_POST['iHeightElevator'],$_POST['sideLarge'],$_POST['sideHeight']);
        $sVerify=$oAscenceur->VerifyStatus();  
        if($sVerify=='OK')
        {
            $oAscenceur->goDown(1) ;
        }
    }

    
    
}
?>