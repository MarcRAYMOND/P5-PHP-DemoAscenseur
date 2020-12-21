<?php
/*****************************
CLASS Ascenseur 
*******************************/


class MonAscenseur  
{
    // 0 pour rez de chaussez , 1 etage 1 , etc ... pour position, nombre d'étage
    public $sName ;
    public $iNumberEtage;
    //Boutton commande goUp
    public $bdetectAskUp;
    //Boutton commande goDown
    public $bdetectAskDown;
    public $bDoorOpen;
    public $iStepBetweenEtage=35;
    public $iCurrentStep;
    public $bReady=false;
    public $iHauteur=8;
    public $iLargeur=8;
    public $iPositionLeft=6;

    public $iLargeurBatiment=50;
    public $iHauteurBatiment=50;

    public $iCurrentPosition;
    public $sError='';
    public $sMatrix='';
    
    
    
        
    public function __construct($_sName, $_iNumberEtage, $_iCurrentPostion,$_bDoorOpen,$_iPositionLeft=6,$_iLargeur=8,$_iHauteur=8,$_iLargeurBatiment=50,$_iHauteurBatiment=50){
        
        //Dans notre cas il n'y aura qu'un etage mais le code prévoit le multi-etage, il faudra implémenter les boutton qui gere les étages
        $this->sName = $_sName;
        $this->iNumberEtage = $_iNumberEtage;
        //Position de l'ascenseur par étage
        $this->iCurrentPosition = $_iCurrentPostion;
        //Normalemet le detecteur doit nous lenvoiyer la vrai position de la porte
        $this->bDoorOpen=$_bDoorOpen;
        //A l'inizialisation personne n'a encore appuyer les boutton goUp et goDown
        $this->bdetectAskUp=false;
        $this->bdetectAskDown=false;
        //Position de l'ascenseur par step
        $this->iCurrentStep=$this->iCurrentPosition*$this->iStepBetweenEtage;
        
        $this->iLargeur=$_iLargeur;
        $this->iHauteur=$_iHauteur;
        $this->iPositionLeft=$_iPositionLeft;
        $this->iHauteurBatiment=$_iHauteurBatiment;
        $this->iLargeurBatiment=$_iLargeurBatiment;

        //detecter toute anomalie lié aux data
        if($this->iPositionLeft+$this->iLargeur>$this->iLargeurBatiment){
            $this->sError='Erreur, l\'ascenseur ne peux pas positionner ici.';
        }
        else if(!is_string($_sName)){
            $this->sError='La variable _sName n\'est pas une chaine.';
        }
        else if(!$this->is_int_val($_iNumberEtage)){
            $this->sError='La variable _iNumberEtage : '.$_iNumberEtage.'  n\'est pas un int.';
        }
        else if(!$this->is_int_val($_iCurrentPostion)){
            $this->sError='La variable _iNumberEtage  n\'est pas un int.';
        }
        
        else if(!$this->is_int_val($_iPositionLeft)){
            $this->sError='La variable _iPositionLeft  n\'est pas un int.';
        }
        else if(!$this->is_int_val($_iLargeur)){
            $this->sError='La variable _iLargeur  n\'est pas un int.';
        }
        else if(!$this->is_int_val($_iHauteur)){
            $this->sError='La variable _iHauteur  n\'est pas un int.';
        }
        else if(!$this->is_int_val($_iLargeurBatiment)){
            $this->sError='La variable _iLargeurBatiment  n\'est pas un int.';
        }
        else if(!$this->is_int_val($_iHauteurBatiment)){
            $this->sError='La variable _iHauteurBatiment  n\'est pas un int.';
        }else{
            $this->iHauteurBatimentsError='';
        }
        if( !$this->checkError()){
            //Pas d'erreur on peux 
            $this->bReady=true;
            $sMatrice=$this->DessinezAscenseur(0);
            $this->sMatrix=$sMatrice;
            file_put_contents($this->sName.'.log',$sMatrice);
            
        }

    }
    
    private function DessinezAscenseur($iMoveElevator){
            $sMatrice=$iMoveElevator.'|';
            for($i=$this->iHauteurBatiment;$i>0;$i--){
                
                for($z=1;$z<=$this->iLargeurBatiment;$z++){
                    if(( $i<=($this->iHauteur+$this->iCurrentStep) && $i>$this->iCurrentStep && $z>$this->iPositionLeft && $z<=($this->iPositionLeft +$this->iLargeur) ) ){
                        $sMatrice.='1';
                    }elseif($i==$this->iStepBetweenEtage+1 || $i==1){
                        $sMatrice.='2';
                    }elseif($z==$this->iPositionLeft || $z==$this->iPositionLeft+$this->iLargeur+1 ){
                        $sMatrice.='3';
                    }elseif($z==(ceil($this->iLargeur/2)+$this->iPositionLeft) && $i>$this->iHauteur+$this->iCurrentStep  ){
                        $sMatrice.='4';
                    }else{
                        $sMatrice.='0';
                    }
                }
            }
            return $sMatrice;
    }
    public function goDown($_iNbetage){
        if( !$this->checkError()){
            $this->bdetectAskDown=true;
            //on ferme les portes avant le départ
            if($this->bDoorOpen){
                $this->CloseDoor();
            }

            if($this->iCurrentPosition > 0){
                //le 2eme parametre pourra nous servir si nous gérons plusieur etage
                $this->StartAscenseur('Down',$_iNbetage);

            }
            else{
                //L'ascenceur et deja en bas, ouvrir la porte
                $this->OpenDoor();
            }
            $this->bdetectAskDown=false;
        }

    }



    public function goUp($_iNbetage){
        if( !$this->checkError()){
            $this->bdetectAskUp=true;
            //on ferme les portes avant le départ
            if($this->bDoorOpen){
                $this->CloseDoor();
            }
            if($this->iNumberEtage > $this->iCurrentPosition){
                //le 2eme parametre pourra nous servir si nous gérons plusieur etage
                $this->StartAscenseur('Up',$_iNbetage);
                
    
            }
            else{
                //L'ascenceur et deja en a sont étage, ouvrir la porte
                $this->OpenDoor();
            }
            $this->bdetectAskUp=false;
        }
 
    }

    private function OpenDoor(){
        
        if(!$this->bDoorOpen){
            $this->bDoorOpen=true;
        }
    }

    private function CloseDoor(){
        if($this->bDoorOpen){
            //nous pouvons rajouter ici un system de detecteur de force ou de main pour arreter la fermerture
            $this->bDoorOpen=false;
        }
    }

    //gérer les  mouvements haut et bas 
    private function StartAscenseur($_sSens,$_iNbetage){

        try{
        if($_sSens=='Up' && $this->is_int_val($_iNbetage) && $_iNbetage>0 ){

            
            do {
                $this->MovePosition('+1');
                usleep(1000000);
                $sMatrice=$this->DessinezAscenseur(1);
                $this->sMatrix=$sMatrice;
                file_put_contents($this->sName.'.log',$sMatrice);
            } while ($this->iCurrentStep < $_iNbetage*$this->iStepBetweenEtage && $this->iCurrentStep<($this->iNumberEtage*$this->iStepBetweenEtage) );
            //rajout d'un controle si la monter d'étage au dela du nombre d'étage de l'ascenseur prévoir d'arreter au dernier étage
            $sMatrice=$this->DessinezAscenseur(0);
            file_put_contents($this->sName.'.log',$sMatrice);
            $_error=false;
        }
        elseif($_sSens=='Down' && $this->is_int_val($_iNbetage) && $_iNbetage>0 ){
            
            $iFutureStep=$this->iCurrentStep-$_iNbetage*$this->iStepBetweenEtage ;
            do {
                $this->MovePosition('-1');  
                usleep(1000000);
                $sMatrice=$this->DessinezAscenseur(2);
                $this->sMatrix=$sMatrice;
                file_put_contents($this->sName.'.log',$sMatrice);
            } while ($this->iCurrentStep !=$iFutureStep && $this->iCurrentStep>0 );
            //Rajouter d'un controle pour ne descencdre en dessous de  0
            $sMatrice=$this->DessinezAscenseur(0);
            $this->sMatrix=$sMatrice;
            file_put_contents($this->sName.'.log',$sMatrice);
            $this->sError='';
        }
        else{
            if($_sSens!='Up' && $_sSens!='Down'){
                //créer un log pour détermininer l'erreur pb de sens
                $this->sError='Probleme de sens';
            }
             else{
                 //créer un log pour détermininer l'erreur, impossible de determiner l'origine
                 $this->sError='Erreur indéfini';
             }    
             $sMatrice=$this->DessinezAscenseur(0);
             $this->sMatrix=$sMatrice;
             file_put_contents($this->sName.'.log',$sMatrice);
        }
        // dans tout les cas ouvrir la porte a la find e l'opération
        $this->OpenDoor();

        if($_error){
            return false;
        }else{
            return true;
        }
        }catch (Exception $e) {
           //mettre en log l'exception et arrtger l'ascenseur
        }

    }

    //fait le mouvement de la position, peux servir de log ou autre action pendant le mouvement
    private function MovePosition($_iStep){
        $this->iCurrentStep=$this->iCurrentStep+$_iStep;
    }

    private function checkError(){
        $_bReturnError=false;
        if($this->sError!=''){
            $_bReturnError=true;
        }else{
            $_bReturnError=false;
        }
        return $_bReturnError;
    }

    //controler le statut de l'ascenseur a tout moment
    public function VerifyStatus(){
        $sReturne='PASOK'.$this->sError;
        if($this->bReady)
        {
            $sReturne='OK';
        }
        return $sReturne;      
    }
    
    
    private function is_int_val($value){

        if( ! preg_match( '/^-?[0-9]+$/', $value ) ){
            return FALSE;
        }
    
    
        /* Disallow leading 0 */
    
        // cast value to string, to make index work
        $value = (string) $value;
    
        if( ( $value[0] === '-' && $value[1] == 0 ) || ( $value[0] == 0 && strlen( $value ) > 1 ) ){
            return FALSE;
        }
    
    
        return TRUE;
    }

    
    
}
?>