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
                    <h4><i class="glyphicon glyphicon-user"></i> Список пользователей</h4>
                </div>
                <p>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalAddUserForm">
                        <i class="glyphicon glyphicon-plus"></i> Добавить пользователя
                    </button>
                </p>
            </div>
        </div>
        <div class="col-xs-4 col-md-2 panel panel-default" style="padding-top: 4px; padding-bottom: 4px;">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/"><i class="glyphicon glyphicon-home"> </i> Главная</a></li>
                <li><a href="/manageCRM"><i class="glyphicon glyphicon-gift"> </i> Список модулей</a></li>
                <li><a href="/viewUserCRM"><i class="glyphicon glyphicon-user"> </i> Пользователи системы</a></li>
                <li><a href="#"><i class="glyphicon glyphicon-cog"> </i> Asterisk Listner</a></li>
            </ul>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModalAddUserForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-user"> </i> Новый пользователь</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputLogin" class="control-label col-xs-2">Логин</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputLogin" placeholder="Введите логин">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName" class="control-label col-xs-2">Фамилия</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputLastName" placeholder="Введите фамилию">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFirstName" class="control-label col-xs-2">Имя</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputFirstName" placeholder="Введите имя">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputJobPosition" class="control-label col-xs-2">Должность</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputJobPosition" placeholder="Введите должность">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputWorkDept" class="control-label col-xs-2">Отдел</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputWorkDept" placeholder="Введите название отдела">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="control-label col-xs-2">Email</label>
                            <div class="col-xs-10">
                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone" class="control-label col-xs-2">Телефон (внутр.)</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputPhone" placeholder="Введите номер телефона">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputExternalPhone" class="control-label col-xs-2">Телефон (внешн.)</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputExternalPhone" placeholder="Введите внешний номер телефона">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputRole" class="control-label col-xs-2">Группа (Роль)</label>
                            <div class="col-xs-10">
                                <select id="inputRole" name="group" class="form-control">
                                    <option>Менеджер</option>
                                    <option>Администратор</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="control-label col-xs-2">Пароль</label>
                            <div class="col-xs-10">
                                <input type="password" class="form-control" id="inputPassword" placeholder="Введите пароль">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPasswordConfirm" class="control-label col-xs-2">Повтор пароля</label>
                            <div class="col-xs-10">
                                <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Повторите пароль">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default">Сохранить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <!--    ViewUserForm Begin Block-->

    <!-- Modal -->
    <div class="modal fade" id="myModalViewUserDetailForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-user"> </i> Профиль пользователя</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-condensed"></table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModalEditUserForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-user"> </i> Редактирование данных пользователя</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputLogin" class="control-label col-xs-2">Логин</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputLogin" placeholder="Введите логин">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName" class="control-label col-xs-2">Фамилия</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputLastName" placeholder="Введите фамилию">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFirstName" class="control-label col-xs-2">Имя</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputFirstName" placeholder="Введите имя">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputJobPosition" class="control-label col-xs-2">Должность</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputJobPosition" placeholder="Введите должность">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputWorkDept" class="control-label col-xs-2">Отдел</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputWorkDept" placeholder="Введите название отдела">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="control-label col-xs-2">Email</label>
                            <div class="col-xs-10">
                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone" class="control-label col-xs-2">Телефон (внутр.)</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputPhone" placeholder="Введите номер телефона">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputExternalPhone" class="control-label col-xs-2">Телефон (внешн.)</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="inputExternalPhone" placeholder="Введите внешний номер телефона">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputRole" class="control-label col-xs-2">Группа (Роль)</label>
                            <div class="col-xs-10">
                                <select id="inputRole" name="group" class="form-control">
                                    <option>Менеджер</option>
                                    <option>Администратор</option>
                                </select>
                            </div>
                        </div>
                        <!--                        <div class="form-group">
                                                    <label for="inputPassword" class="control-label col-xs-2">Пароль</label>
                                                    <div class="col-xs-10">
                                                        <input type="password" class="form-control" id="inputPassword" placeholder="Введите пароль">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPasswordConfirm" class="control-label col-xs-2">Повтор пароля</label>
                                                    <div class="col-xs-10">
                                                        <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Повторите пароль">
                                                    </div>
                                                </div>-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default">Сохранить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    @stop