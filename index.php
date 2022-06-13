<?php
session_start();
require_once('./include/fBDD.php');
$connex = connexionBDD();
if (!isset($_SESSION['mail'])){
    header("Location: ./pageCon.php");
    die();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <link href="./style/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="./ressource/BYES.png" />
        <script type="text/javascript">

             var objRequete = new XMLHttpRequest;

             function fRetour() {
				if (objRequete.readyState==4 && objRequete.status==200) { 
                    try {
                        obj=JSON.parse(objRequete.responseText);
                        tableUpdate(obj);
                    } catch (e) {
                        alert("pb parsing. Voir l'erreur dans la console si possible");
                        console.error("Parsing error:-( :", e);
                    }
				}
			}
			
			function fAction() {
                    var recherche = document.getElementById('recherche').value;
                    var marque = document.getElementById('marque').value;
                    var designation = document.getElementById('designation').value;
					objRequete.open('get','./include/api_stock.php');	
                    objRequete.onreadystatechange = fRetour;		
                    objRequete.send(null);									
					return true; 
			 }

            
        </script>
	</head>
	<body onload="fAction();">
        <h1>Stock de <?= $_SESSION['mail'] ?></h1><br>
        <div id="global">
            <input type="text" id="designation" placeholder="Designation" size="30">
            <input type="text" id="marque" placeholder="Marque" size="30">
            <input type="text" id="recherche" placeholder="Reference" size="30">
            <button onclick="fAction();">Rechercher</button>
        </div>
        <br><br>
        <Table Border=1 class="tabcenter" id="tableauref">
            <tr>
                <th><b>Détail</b></th>
                <th><b>Type</b></th>
                <th><b>Marque</b></th>
                <th><b>Reference</b></th>
                <th><b>Designation</b></th>
                <th><b>Quantite totale</b></th>
            </tr>
        </table>
        <br>
            <input type="image" onclick="copyCsvToClickBoard(obj);" class="exportbutton" id="csv" alt="csv button" src="./ressource/csv.png" />
            <input type="image" onclick="copyJsonToClickBoard(obj);" class="exportbutton" id="json" alt="json button" src="./ressource/json.png" />
            <script src='./js/index2.js'></script>
	</body>
</html>