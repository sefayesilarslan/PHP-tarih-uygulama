<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<?php
try{
    $db= new PDO("mysql:host=localhost; dbname=veritabaniadi; charset=utf8","kullaniciadi","şifre");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Veri tabanı baglantısı başarılı";
    }
    catch(PDOException $e) {
        die($e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="boost.css" >
    <title>Document</title>
</head>
<body>

<table class="table table-bordered col-md-10 mx-auto mt-4 text-center table-dark">
<thead>
<tr>
<td><a href="tarih.php?tar=bugun">Bugün</a></td>
<td><a href="tarih.php?tar=dun">Dün</a></td>
<td><a href="tarih.php?tar=hafta">Bu hafta</a></td>
<td><a href="tarih.php?tar=ay">Bu ay</a></td>
<td><a href="tarih.php?tar=tum">Tüm Zamanlar</a></td>

<td>
<form action="tarih.php?tar=arama" method="POST"><input type="date" name="tarih1" class="form-control"></td>
<td><form action="tarih.php?tar=arama" method="POST"><input type="date" name="tarih2" class="form-control"></td>
<td><input type="submit" name="buton" value="GETİR" class="btn btn-danger"></form></td>
</tr>
</thead>
</table>

<table class="table table-bordered col-md-4 mx-auto mt-4 text-center table-light table-striped">
<thead>
<tr>
<th>Ürün Ad</th>
<th>Ürün Fiyat</th>
</tr>
</thead>
<tbody>

<?php
function tablo($sorgu,$db){
    $sor=$db->prepare($sorgu);
    $sor->execute();

    while($sonucum=$sor->fetch(PDO::FETCH_ASSOC)){
     echo'<tr>
             <td>'.$sonucum["urunad"].'</td>
             <td>'.$sonucum["urunfiyat"].'</td>
          </tr>';
    }
     
}

@$tarih=$_GET["tar"];
switch($tarih){

    case "bugun":
       $sorgu="select * from rapor where tarih= CURDATE();";//CURDATE() bugünün tarihini alan komuttur.
        tablo($sorgu,$db);
    break;

    case "dun":
        $sorgu="select * from rapor where tarih= DATE_SUB(CURDATE(),INTERVAL 1 DAY);";//DATE_SUB(CURDATE(),INTERVAL 1 DAY);dünün tarihini veren komuttur. Bu günden bir gün geriye giderek yaptık
        tablo($sorgu,$db);
    break;

    case "hafta":
        $sorgu="select * from rapor where YEARWEEK(tarih) = YEARWEEK(CURRENT_DATE)";//bu hafta ve bir hafta geri giderek verileri verir
        tablo($sorgu,$db);
    break;

    case "ay":
        $sorgu="select * from rapor where tarih >=DATE_SUB(CURDATE(),INTERVAL 1 MONTH);";//bu ayı verir. Bulundugumuz aydan 1 ay geriye gider.
        tablo($sorgu,$db);

    break;

    case "tum":
        $sorgu="select * from rapor";
        tablo($sorgu,$db);

    break;
    
    case "arama":
        $tarih1=$_POST["tarih1"];
        $tarih2=$_POST["tarih2"];
        
        echo '<div class="alert alert-info text-center">
        '.$tarih1.'--------'.$tarih2.' arasındaki ürünler
        </div>';

        $sorgu="select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'";
        $sor=$db->prepare($sorgu);
        $sor->execute();
        $sorgusonuc=$sor->get_result();
    
        while($sonucum=$sorgusonuc->fetch_assoc()){
         echo'<tr>
                 <td>'.$sonucum["urunad"].'</td>
                 <td>'.$sonucum["urunfiyat"].'</td>
              </tr>';
        }

    break;
    default:
    $sorgu="select * from rapor where tarih= CURDATE();";//CURDATE() bugünün tarihini alan komuttur.
    tablo($sorgu,$db);
}

?>


<!-- <tr>
<td></td>
<td></td>
</tr> -->

</tbody>
</table>

    
</body>
</html>