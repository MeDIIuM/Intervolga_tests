<?php
const Lim = 10;
if(isset($_GET['page']))
{
    $num_page=$_GET['page'];
}
else{
    $num_page=1;
}
if(isset($_GET['sort']))
{
    $sort=$_GET['sort'];
}
if(isset($_GET['direction']))
{
    $direction=$_GET['direction'];
}

include "config.php";
$dbh = new PDO('mysql:host='.$mysql_host.";charset=utf8", $username, $password);
$dbh->exec("use $dbname");
$add="INSERT INTO 'country' 
(
`name_country`,
)
VALUES (
?
);
";
if (array_key_exists('country_add', $_POST)) {
    $stmt = $dbh->prepare($add);
    $stmt->bindParam(1, $_POST['name_country'], PDO::PARAM_STR);
    $s = $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" type="text/css"/>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCenter_1">
                        Добавление
                    </button>
    <div id="Table" class="container">
        <table class="table table-striped table-bordered"">
            <thead>
                <tr class="table-primary">
                    <th scope="col"><a href="<?php echo generateSortUrl("id_country", $_GET, $num_page);?>">№</a></th>
                    <th scope="col"><a href="<?php echo generateSortUrl("name_country", $_GET, $num_page);?>">Страна</a></th>
                </tr>
            </thead>
            <tbody>
            <?php
            error_reporting(-1);
            ini_set('display_errors', 'On');
            $q="SELECT * FROM country ";
            if (isset($_GET['sort'])) {
                $q = $q . " ORDER BY " . $_GET['sort'] . " " . $_GET['direction'] . " ";
            }
            $q = $q. " LIMIT ". Lim . " " . "OFFSET ".  ($num_page - 1)* Lim;

            $result = $dbh->query($q);
            foreach($result as $row) {
                ?>
                <tr class="table-primary">
                    <td>
                        <?php
                        echo $row['id_country'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row['name_country'];
                        ?>
                    </td>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
        if($num_page!=1){
            ?>
            <a href="<?php echo generatePageUrl("prev",  $num_page, $sort, $direction);?>">prev</a>
            <?php
        }
        $count = $result->rowCount();
        if ($count>=10){
           ?>
            <a  href="<?php echo generatePageUrl("next", $num_page, $sort, $direction);?>">next</a>
            <?php
        }
        ?>

    </div>
        <div class="modal fade" id="ModalCenter_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Добавление</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="Add" method="post">
                        <div id="country_add" class="container">
                            <div class="form-group row">
                                <label for="input_name" class="col-sm-2 col-form-label">Логин</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name_country">
                                </div>
                            </div>
                            <div class="modal-footer"><button name="country_add" type="submit" class="btn btn-primary" class="btn btn-secondary">Добавить</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            </div>
                            </form>
                </div>

</body>
</html>
<?php
function generateSortUrl($sort, $get,$num_page){
    $get=$_GET;
    $direction = $_GET['direction'];
 if ($direction == "ASC") {
    $direction = "DESC";}
 else {
     $direction = "ASC";
 }
$res="http://intervolga.dev/?"."sort=".$sort."&"."direction=".$direction."&"."page=".$num_page;
return $res;
}

function generatePageUrl($page, $num_page, $sort, $direction){
        if ($page == "next") {
            $num_page++;
            $res = "http://intervolga.dev/?"."sort=".$sort."&"."direction=".$direction."&"."page=".$num_page;
        } elseif ($page == "prev") {
            $num_page--;
            $res = "http://intervolga.dev/?"."sort=".$sort."&"."direction=".$direction."&"."page=".$num_page;
        }
        return $res;
}


