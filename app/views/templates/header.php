<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand glyphicon glyphicon-home" href="/"> MVC</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a class="glyphicon glyphicon-asterisk" href="/montagem"> Montagem </a></li>
                <li><a class="glyphicon glyphicon-bookmark" href="/documentacao"> Documentação</a></li>
                <li><a class="glyphicon glyphicon-star" href="/venda"> Venda</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<?php if (!empty($_sucesso)) : ?>
    <?php foreach ($_sucesso as $value): ?>
        <div class="alert alert-success">
            <strong>Sucesso!</strong> <?= $value; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($_info)) : ?>
    <?php foreach ($_info as $value): ?>
        <div class="alert alert-info">
            <strong>Info!</strong> <?= $value; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($_warning)) : ?>
    <?php foreach ($_warning as $value): ?>
        <div class="alert alert-warning">
            <strong>Alerta!</strong> <?= $value; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($_danger)) : ?>
    <?php foreach ($_danger as $value): ?>
        <div class="alert alert-danger">
            <strong>Cuidado!</strong> <?= $value; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>