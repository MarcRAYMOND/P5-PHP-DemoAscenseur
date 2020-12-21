var sideLarge = 50;
var sideHeight = 50;
var tileSize = 12;
var borderTiles = 5;
var sNameElevator='Test';
var iWidthElevator=9;
var iHeightElevator=9;
var iStartLeft=6;
var iNumberFloor=1;
var iCurrentFloor=0;
var ButtonUp='';
var ButtonDown='';
var isDoorOpen=false;
var isMove=false;

function setup() {

	
	jQuery( document ).ready(function( $ ) {
	$.ajax({
		url : 'callAscenseur.php', 
		type : 'POST', 
		data : 'sCommande=init&isDoorOpen='+isDoorOpen+'&nameElevator=' + sNameElevator + '&iHeightElevator='+iHeightElevator+'&iWidthElevator='+iWidthElevator+'&iStartLeft='+iStartLeft+'&iNumberFloor='+iNumberFloor+'&sideLarge='+sideLarge+'&sideHeight='+sideHeight+'&iCurrentFloor='+iCurrentFloor,
		datatype: text,
		success : function(codeelevator, statut){
			if(codeelevator=='OK'){
			ButtonUp = createButton('Go Up');	
			ButtonUp.position(10, 10);
			ButtonUp.mousePressed(fngoUp);

			ButtonDown = createButton('Go Down');	
			ButtonDown.position(300, 10);
			ButtonDown.mousePressed(fngoDown);
		
		
			var s = (sideLarge + borderTiles * 2) * tileSize;
			var oCanvas= createCanvas(s, s);
			stroke(100);
		  frameRate(5);
			background(197, 197, 255);
			}
		},
 
		error : function(resultat, statut, erreur){
			alert('Probleme de traitement');
			console.log(erreur);
		}
	 });
	});

}

function draw() {
	
	jQuery( document ).ready(function( $ ) {
		let dt = new Date( );
		let sTime= dt.getSeconds+'|'+dt.getMilliseconds() ; 

	$.get(sNameElevator+".log?"+sTime, function(data){
		
		
			let dataStr=fnDecryptData(data);
			if(dataStr!=undefined){
			let mapArray = dataStr.split("");
			let pixel;


			for (var y = 0; y < sideHeight; y++) { 
				for (var x = 0; x < sideLarge; x++) {
				
					pixel = parseInt(mapArray[x + y * sideHeight]);  
					if (pixel === 1) {
						fill(185,28,97);
						square(x * tileSize, y * tileSize, tileSize);
						

					}else if (pixel === 2) {
						fill(150,250,10);
						square(x * tileSize, y * tileSize, tileSize);
						

					}else if (pixel === 3) {
						fill(19,77,243);
						square(x * tileSize, y * tileSize, tileSize);
						

					}else if (pixel === 4) {
						fill(227,35,35);
						square(x * tileSize, y * tileSize, tileSize);
						

					}else{
						fill(15,25,15);
						square(x * tileSize, y * tileSize, tileSize);
					}
					
				}
			}
		}
	});
});
}

function fnDecryptData(sData){
	
	let aSplitData = sData.split("|");
	if(ButtonUp!='' && ButtonDown!=''){
		let iCommandMoveElevator=aSplitData[0];
		if(iCommandMoveElevator==0){
			//Afficher boutton up et down
			if(iCurrentFloor==0)
				ButtonUp.show();
			else
				ButtonUp.hide();

			if(iCurrentFloor==1)
				ButtonDown.show();
			else
				ButtonDown.hide();
			
		}else if(iCommandMoveElevator==1){
			//Afficher boutton  down et cacher up
			ButtonDown.hide();
			ButtonUp.hide();
		}
		else if(iCommandMoveElevator==2){
			//Afficher boutton  up et cacher down
			ButtonUp.hide();
			ButtonDown.hide();
		}else{
			//Cacher boutton up et down
			ButtonUp.hide();
			ButtonDown.hide();
		}
	}
	return aSplitData[1];

}




function fngoUp() {
	jQuery( document ).ready(function( $ ) {
		$.ajax({
			url : 'callAscenseur.php', 
			type : 'POST', 
			data : 'sCommande=up&isDoorOpen='+isDoorOpen+'&nameElevator=' + sNameElevator + '&iHeightElevator='+iHeightElevator+'&iWidthElevator='+iWidthElevator+'&iStartLeft='+iStartLeft+'&iNumberFloor='+iNumberFloor+'&sideLarge='+sideLarge+'&sideHeight='+sideHeight+'&iCurrentFloor='+iCurrentFloor ,
			datatype: text,
			success : function(codeelevator, statut){
				iCurrentFloor=1;
			},
	 
			error : function(resultat, statut, erreur){
				alert('Probleme de traitement');
				console.log(erreur);
			}
		 });
		});
}

function fngoDown() {
	jQuery( document ).ready(function( $ ) {
		$.ajax({
			url : 'callAscenseur.php', 
			type : 'POST', 
			data : 'sCommande=down&isDoorOpen='+isDoorOpen+'&nameElevator=' + sNameElevator + '&iHeightElevator='+iHeightElevator+'&iWidthElevator='+iWidthElevator+'&iStartLeft='+iStartLeft+'&iNumberFloor='+iNumberFloor+'&sideLarge='+sideLarge+'&sideHeight='+sideHeight+'&iCurrentFloor='+iCurrentFloor ,
			datatype: text,
			success : function(codeelevator, statut){
				iCurrentFloor=0;
			},
	 
			error : function(resultat, statut, erreur){
				alert('Probleme de traitement');
				console.log(erreur);
			}
		 });
		});
}