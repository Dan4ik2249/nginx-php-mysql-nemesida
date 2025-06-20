<?php
include("session.php"); 
$tipo=$_GET['tipo'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Блокнот</title>
    <meta name="description" content="">
    <meta name="author" content="77896,77907,77969">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top">     
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">Блокнот</a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li><a href="#"><i class="fa fa-gear"></i> &nbsp;Настройки</a></li>
                <li><a href="#"><i class="fa fa-user"></i> &nbsp;Профиль</a></li>
                <li><a href="./logout.php"><i class="fa fa-sign-out"></i> &nbsp;Выйти</a></li>
            </ul>
        </nav>

        <div id="page-wrapper">


            <div class="row">
                <div class="col-md-12"><br>

                <?php if(isset($_GET['campo_del'])){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Поле  <?php echo $_GET['campo_del']; ?> было успешно очищено.
                </div>
                <?php 
                    $campo_del=$_GET['campo_del'];
                    $mysql->query("UPDATE campo SET ativo=0 WHERE userid=$userid AND campocnt=$campo_del;");
                } ?>


                <?php if(isset($_POST['campo'])){ ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Поле <?php echo $_POST['campo']; ?> было успешно добавлено.
                </div>
                <?php 
                    $nome_campo=$_POST['campo'];

                    $mysql->query("
                        INSERT INTO campo (userid,nome,typecnt,campocnt,ativo,idseq)
                        SELECT '".$userid."','".$nome_campo."','".$tipo."', COALESCE(max(typecnt),0)+1,'1',idseq  
                        FROM campo WHERE userid='".$userid."' AND typecnt='".$tipo."'
                    "); 

                } ?>

                </div>
            </div>
            
            <div class="row">
                
                <!-- Registo -->
                <?php
                    $tipo = $mysql->query("SELECT typecnt,nome FROM tipo_registo WHERE userid=$userid AND typecnt=$tipo AND ativo=1");
                    if($tipo->num_rows == 1){
                        $row = $tipo->fetch_assoc();
                        $tipoid=$row['typecnt'];
                ?>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <h2><?php echo $row['nome']; ?></h2>
                                    <div><a data-toggle="modal" data-target="#modalp-edit" href="#">Изменить название</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalp-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Изменить запись</h4>
                                    </div>
                                    <form role="form" method="post" action="./pagina.php?page=<?php echo $pageid; ?>">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Название записи</label>
                                            <input name="pagina" type="hidden" value="<?php echo $pageid; ?>">
                                            <input name="nome" value="<?php echo $row['nome']; ?>" class="form-control" placeholder="Страница">
                                        </div>   
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                        <input type="submit" class="btn btn-success" value="Editar" />
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Поле</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $campos = $mysql->query("
                                        SELECT campocnt,nome FROM campo WHERE userid=$userid AND typecnt=$tipoid AND ativo=1
                                    ");
                                    if($campos->num_rows > 0){
                                    while($campo = $campos->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $campo['campocnt']; ?></td>
                                        <td><?php echo $campo['nome']; ?></td>
                                        <td>
                                            <button data-toggle="modal" data-target="#modalc-<?php echo $campo['campocnt']; ?>" type="button" class="btn btn-danger btn-xs">Удалить</button>
                                            <div class="modal fade" id="modalc-<?php echo $campo['campocnt']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Информация</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Удалить поле "<?php echo $campo['nome']; ?>"
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                                            <a href="./tipo.php?tipo=<?php echo $tipoid; ?>&campo_del=<?php echo $campo['campocnt']; ?>" class="btn btn-danger">Удалить</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>  
                                    <?php }}else{ echo "<tr><td></td><td>(Для этой записи нет полей)</td><td></td>"; } ?>
                                </tbody>
                            </table>
                        </div>

                        <a data-toggle="modal" data-target="#modalc-add" href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Добавить поле</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <div class="modal fade" id="modalc-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Добавить поле</h4>
                                    </div>
                                    <form role="form" method="post" action="./tipo.php?tipo=<?php echo $tipoid; ?>">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Название поля</label>
                                            <input name="campo" class="form-control input-lg" placeholder="Поле">
                                        </div> 
                                        <br>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                        <input type="submit" class="btn btn-success" value="Добавить" />
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }else{ echo "Тип регистрации не существует!"; } ?>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.2.0/metisMenu.min.js"></script>

    <?php $mysql->close(); ?>

</body>
</html>