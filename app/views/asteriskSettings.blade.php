@extends('template.template')
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand"><i class="glyphicon glyphicon-certificate"> </i> CRM Manager v.0.0.0.1</a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 66px;">
    @section('content')

    <div id="circle"></div>

    <div class="page-header">
        <h3><i class="glyphicon glyphicon-wrench"></i> Управление CRM - <label id="crm_name"></label> </h3>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10">
            <div id="modulesList" style="width: 90%;">
                <div class="page-header">
                    <h4><i class="glyphicon glyphicon-cog"></i> Параметры слушателя Asterisk</h4>
                </div>
                <p>

                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEditAsteriskSettingsForm" id="editAsteriskSettings">
                        <i class="glyphicon glyphicon-pencil"></i> Редактировать настройки
                    </button>

                </p>
            </div>
        </div>
        <div class="col-xs-4 col-md-2 panel panel-default" style="padding-top: 4px; padding-bottom: 4px;">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/"><i class="glyphicon glyphicon-home"> </i> Главная</a></li>
                <li><a href="/manageCRM"><i class="glyphicon glyphicon-gift"> </i> Список модулей</a></li>
                <li><a href="/viewUserCRM"><i class="glyphicon glyphicon-user"> </i> Пользователи системы</a></li>
                <li><a href="/viewAsteriskSettings"><i class="glyphicon glyphicon-cog"> </i> Asterisk Listner</a></li>
            </ul>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModalEditAsteriskSettingsForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-cog"> </i> Редактирование настроек слушателя Asterisk</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="editAsteriskSettingsForm">
                        <input type="hidden" class="form-control" id="idHidden">
                        <input type="hidden" class="form-control" id="crmDomainNameInput">
                        <div class="form-group">
                            <label for="asteriskHost" class="control-label col-xs-3">Asterisk Host</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="asteriskHost">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asteriskPort" class="control-label col-xs-3">Asterisk Port</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="asteriskPort">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asteriskUsername" class="control-label col-xs-3">Asterisk Username</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="asteriskUsername">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asteriskPassword" class="control-label col-xs-3">Asterisk Password</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="asteriskPassword">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dbHost" class="control-label col-xs-3">DB Host</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="dbHost">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dbUsername" class="control-label col-xs-3">DB Username</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="dbUsername">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dbPassword" class="control-label col-xs-3">DB Password</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="dbPassword">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dbName" class="control-label col-xs-3">DB Name</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="dbName">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="httpPort" class="control-label col-xs-3">HTTP Port</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" id="httpPort">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="updateAsteriskSettings(); return false;">Сохранить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <!--    ViewUserForm Begin Block-->

    @stop