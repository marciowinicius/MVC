<?php include $this->render_template('header.php'); ?>
    <!--<div class="container">-->
    <!--    <div id="table">-->
    <!--        <table cellpadding="0" cellspacing="0" border="0" class="display" id="pagination-table">-->
    <!--            <thead>-->
    <!--            <tr>-->
    <!---->
    <!--            </tr>-->
    <!--            </thead>-->
    <!--        </table>-->
    <!--    </div>-->
    <!--</div>-->

    <div class="container">
        <h4>Listagem de carros</h4>
        <div class="pull-right">
            <a href="/montagem/adicionar" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-plus"></span>
                Adicionar</a>
        </div>
        <div class="clearfix"></div>
        <br/>
        <div id="table">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-responsive"
                   id="pagination-table">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Ano Fabr.</th>
                    <th>Ano Modelo</th>
                    <th>Cor</th>
                    <th>Completo</th>
                    <th>Ação</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            oTable = $('#pagination-table').dataTable({
                "bJQueryUI": true,
                "paginationType": "full_numbers",
                "processing": true,
                "serverSide": true,
                "ajaxSource": "/montagem/data/",
                "bStateSave": true,
                "columns": [
                    {data: 'codigo'},
                    {data: 'ano_fabricacao'},
                    {data: 'ano_modelo'},
                    {data: 'cor'},
                    {
                        data: function (data) {
                            if (data.completo == 'NAO') {
                                return 'NÃO';
                            }
                            return data.completo;
                        }
                    },
                    {
                        data: function (data) {
                            var excluir = '<a title="Excluir carro." href="/montagem/excluir/' + data.config_boleto_id + '" class="btn btn-danger"><span class="glyphicon glyphicon-minus-sign"></span>';
                            return excluir;
                        }, orderable: false, searchable: false
                    },
                ],
                "fnServerData": function (sSource, aoData, fnCallback) {
                    $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": aoData,
                        "success": fnCallback
                    })
                }
            });
        });
    </script>
<?php include $this->render_template('footer.php'); ?>