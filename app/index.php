<?php include("session.php"); ?>
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

                <?php if(isset($_GET['tipo_del'])){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Тип записи <?php echo $_GET['tipo_del']; ?> был успешно удален.
                </div>
                <?php 
                    $tipo_registo=$_GET['tipo_del'];
                    $mysql->query("UPDATE tipo_registo SET ativo=0 WHERE userid=$userid AND typecnt=$tipo_registo;");
                } ?>

                <?php if(isset($_GET['pag_del'])){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Страница <?php echo $_GET['pag_del']; ?> была успешно удалена.
                </div>
                <?php 
                    $page=$_GET['pag_del'];
                    $mysql->query("UPDATE pagina SET ativa=0 WHERE userid=$userid AND pagecounter=$page;");
                } ?>

                <?php if(isset($_POST['pagina'])){ ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Страница "<?php echo $_POST['pagina']; ?>" была успешно создана.
                </div>
                <?php 
                    $pageadd=$_POST['pagina'];
                    $mysql->query("INSERT INTO pagina (userid,pagecounter,nome,idseq, ativa) SELECT $userid,max(pagecounter)+1, '".$pageadd."', idseq,1 FROM pagina WHERE userid=$userid;");
                } ?>

                <?php if(isset($_POST['tipo'])){ ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Тип записи "<?php echo $_POST['tipo']; ?>" был успешно создан.
                </div>
                 <?php 
                    $tipoadd=$_POST['tipo'];
                    $mysql->query("INSERT INTO tipo_registo (userid,typecnt,nome,idseq,ativo) SELECT $userid,COALESCE(max(typecnt),0)+1, '".$tipoadd."',idseq,1 FROM tipo_registo WHERE userid=$userid;");
                } ?>

                </div>
            </div>
            
            <div class="row">
                
                <!-- PAGINAS -->
                <?php
                    $paginas = $mysql->query("SELECT pagecounter,nome FROM pagina WHERE userid=$userid AND ativa=1");
                ?>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $paginas->num_rows; ?></div>
                                    <div>Страниц</div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($paginas->num_rows > 0){
                                        while($row = $paginas->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['pagecounter']; ?></td>
                                        <td><?php echo $row['nome']; ?></td>
                                        <td>
                                            <a href="./pagina.php?page=<?php echo $row['pagecounter']; ?>" class="btn btn-primary btn-xs">Редактировать</a>
                                            <button data-toggle="modal" data-target="#modalp-<?php echo $row['pagecounter']; ?>" type="button" class="btn btn-danger btn-xs">Удалить</button>
                                            <div class="modal fade" id="modalp-<?php echo $row['pagecounter']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Информация</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Удалить страницу "<?php echo $row['nome']; ?>"
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                                            <a href="./index.php?pag_del=<?php echo $row['pagecounter']; ?>" class="btn btn-danger">Удалить</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <?php }} else{ echo "<tr><td></td><td>(нет страниц)</td><td></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                        <a data-toggle="modal" data-target="#modalp-create" href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Создать страницу</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <div class="modal fade" id="modalp-create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Создать страницу</h4>
                                    </div>
                                    <form role="form" method="post" action="./index.php">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Название страницы</label>
                                            <input name="pagina" class="form-control" placeholder="Страница">
                                        </div>   
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                        <input type="submit" class="btn btn-success" value="Создать" />
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TIPOS REGISTO -->
                <?php 
                    $tipos = $mysql->query("SELECT typecnt,nome FROM tipo_registo WHERE userid=$userid AND ativo=1");
                ?>
                <div class="col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $tipos->num_rows; ?></div>
                                    <div>Записей</div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($tipos->num_rows > 0){
                                        while($row = $tipos->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['typecnt']; ?></td>
                                        <td><?php echo $row['nome']; ?></td>
                                        <td>
                                            <a href="./tipo.php?tipo=<?php echo $row['typecnt']; ?>" class="btn btn-primary btn-xs">Редактировать</a>
                                            <button data-toggle="modal" data-target="#modalt-<?php echo $row['typecnt']; ?>" type="button" class="btn btn-danger btn-xs">Удалить</button>
                                            <div class="modal fade" id="modalt-<?php echo $row['typecnt']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Информация</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Удалить запись "<?php echo $row['nome']; ?>"
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                                            <a href="./index.php?tipo_del=<?php echo $row['typecnt']; ?>" class="btn btn-danger">Удалить</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php }} else{ echo "<tr><td></td><td>(нет записей)</td><td></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                        <a data-toggle="modal" data-target="#modalt-create" href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Создать запись</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <div class="modal fade" id="modalt-create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Создать запись</h4>
                                    </div>
                                    <form role="form" method="post" action="./index.php">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Название записи</label>
                                            <input name="tipo" class="form-control" placeholder="Запись">
                                        </div>   
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                        <input type="submit" class="btn btn-success" value="Создать" />
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.2.0/metisMenu.min.js"></script>

    <?php $mysql->close(); ?>

</body>
</html>
