@extends('template.template')
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand"><i class="glyphicon glyphicon-certificate"> </i> CRM Manager v.0.0.0.1</a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <!--            <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="glyphicon glyphicon-th"> </i> Разделы <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/"><i class="glyphicon glyphicon-home"></i> Главная</a></li>
                                    <li><a href="/operators"><i class="glyphicon glyphicon-phone"></i> Статистика по операторам</a></li>
                                </ul>
                            </li>
                        </ul>-->
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 66px;">
    @section('content')

    <div id="circle"></div>

    <div class="page-header">
        <h3><i class="glyphicon glyphicon-list-alt"></i> Список установленных CRM</h3>
    </div>

    <ul class="nav navbar-nav">

        <li style="margin:10px;">
            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" id="redirectForm">
                <i class="glyphicon glyphicon-plus-sign"> </i> Добавить CRM
            </button>
        </li>
    </ul>
    <?php $i = 1 ?>
    <table class="table table-striped table-bordered table-condensed" id="settingsTable">
        <thead>
            <tr>
                <th>№</th>
                <th>Клиент</th>
                <th>IP-адрес астериска</th>
                <th>Доменное имя CRM</th>
                <th>Активные модули CRM</th>
                <th>Версия CRM</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allSettings as $row)
            <tr>
                <td><?php echo $i ++ ?></td>
                <td>{{ $row->client_name }}</td>
                <td>{{ $row->asteriskAddress }} </td>
                <td>{{ $row->crmDomainName }} </td>
                <td>{{ $row->activeCRMModules }} </td>
                <td>{{ $row->crmVersion }} </td>
                <td>{{ $row->created_at }} </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="">
                        <?php
                        if (SettingController::checkInstallationCRM($row->crmDomainName) == TRUE) {
                            ?>    

                            <button type="button" class="btn btn-default btn-sm" title="Инсталляция CRM" onclick="setupCRM({{$row->id}}); return false;" disabled>
                                <i class="glyphicon glyphicon-play"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Редактировать параметры" onclick="editCRMParameters({{$row->id}}); return false;">
                                <i class="glyphicon glyphicon-wrench"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Апдейт версии CRM" onclick="updateCRMVersion({{$row->id}}); return false;">
                                <i class="glyphicon glyphicon-hdd"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Удалить CRM" onclick="deleteCRM({{$row->id}}); return false;">
                                <i class="glyphicon glyphicon-trash"> </i>
                            </button>

                            <?php
                        } else {
                            ?>

                            <button type="button" class="btn btn-default btn-sm" title="Инсталляция CRM" onclick="setupCRM({{$row->id}}); return false;" >
                                <i class="glyphicon glyphicon-play"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Редактировать параметры" onclick="editCRMParameters({{$row->id}}); return false;" disabled>
                                <i class="glyphicon glyphicon-wrench"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Апдейт версии CRM" onclick="updateCRMVersion({{$row->id}}); return false;" disabled>
                                <i class="glyphicon glyphicon-hdd"> </i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" title="Удалить CRM" onclick="deleteCRM({{$row->id}}); return false;" disabled>
                                <i class="glyphicon glyphicon-trash"> </i>
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    @stop