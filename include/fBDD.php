<?php
function connexionBDD(){
    include("paramcon.php");
    $dsn='pgsql:host='.$host.';dbname='.$dbname.';port='.$port;
    try{
        $connex = new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        print "Erreur de connexion à la base de donnée: ".$e->getMessage();
        die();
    }
    return $connex;
}

function getStockFromSession($c, $mail){
    $sql = "SELECT * FROM stock WHERE ref_proprietaire = '".$mail."'";
    $rStock=$c->query($sql)->fetch();
    return $rStock;
}

function getMatFromId($c, $id){
    $sql = "SELECT * FROM materiel WHERE id = '".$id."'";
    $rMat=$c->query($sql)->fetch();
    return $rMat;
}

function getQteFromStock($c, $stock){
    $arr = array();
    $sql = "SELECT * FROM quantite WHERE ref_stock = ".$stock['id']."";
    $r=$c->query($sql)->fetchAll();
    foreach($r as $ligne){
        $tempMat = getMatFromId($c, $ligne['ref_mat']);
        array_push($arr,array(0 => $tempMat["type"], 1 => $tempMat["marque"], 2 => $tempMat["reference"], 3 => $tempMat["designation"], 4 => $ligne['qte_ne'], 5 => $ligne['qte_eo'], 6 => $ligne['qte_se']));
    }
    return $arr;
}


function ListeMarque($c){ 
    $rMarque=$c->query("SELECT nom_marque FROM Marque ORDER BY nom_marque;");
    return $rMarque;
}

function AjouterMateriel($c, $des, $ma, $ref, $qte){
    $r=$c->query("SELECT * FROM Materiel WHERE reference = '".$ref."';")->fetch();
    if(!$r == false){
        $c->query("UPDATE materiel SET  qte = (qte + ".$qte.") WHERE reference = '".$ref."';");
        return $r['id_materiel'];
    } else {
        $c->query("INSERT INTO Materiel VALUES(DEFAULT, '".$des."', '".$ma."', '".$ref."', ".$qte.");");
        $r = $c->query("SELECT * FROM Materiel WHERE reference = '".$ref."';")->fetch();
        return $r['id_materiel'];
    }
}

function RetirerMateriel($c, $ref, $qte){
    $r=$c->query("SELECT * FROM Materiel WHERE reference = '".$ref."';")->fetch();
    if(!$r == false){
        if(($r['qte'] - $qte) < 0){
            header("Location: ../index.php");
            die();
        } else {
            $c->query("UPDATE materiel SET  qte = (qte - ".$qte.") WHERE reference = '".$ref."';");
            return $r['id_materiel'];
        }
    } else {
        header("Location: ../index.php");
        die();
    }
}

function rechercheDefault($conn){
    $rMat=$conn->query("SELECT * FROM Materiel ORDER BY id_materiel;")->fetchAll();
            $arr = array();
            foreach($rMat as $ligne) {
                array_push($arr,array(0 => $ligne["designation"], 1 => $ligne["ref_marque"], 2 => $ligne["reference"], 3 => $ligne["qte"], 4 => $ligne['id_materiel'], 5 => $ligne['caracteristique'], 6 => $ligne['commentaire'])); 
            }
        return $arr;    
}

function ModifierMateriel($c, $des, $qte, $carac, $com, $id){
    $c->query("UPDATE materiel SET  qte = ".$qte." WHERE id_materiel = '".$id."';");
    $c->query("UPDATE materiel SET  designation = '".$des."' WHERE id_materiel = '".$id."';");
    $c->query("UPDATE materiel SET  caracteristique = '".$carac."' WHERE id_materiel = '".$id."';");
    $c->query("UPDATE materiel SET  commentaire = '".$com."' WHERE id_materiel = '".$id."';");
}

function updateHistorique($c, $date, $action, $detail){
    try{
        $c->query("INSERT INTO historique VALUES(DEFAULT, '".$date."', '".$action."', '".$detail."');");
    } catch (PDOException $e) {
        print "Erreur de connexion à la base de donnée: ".$e->getMessage();
        die();
    }
}

function deleteMat($c, $id){
    $c->query("DELETE FROM materiel where id_materiel = ".$id.";");
}
?>      