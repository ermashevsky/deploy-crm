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
        <h3><i class="glyphicon glyphicon-wrench"></i> Управление CRM - <label id="crm_name"></label> </h3>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10">
            <div id="modulesList" style="width: 90%;">
                <div class="page-header">
                    <h4><i class="glyphicon glyphicon-list-alt"></i> Список модулей</h4>
                </div>
                <p>
                    <a href="#">Все</a> | <a href="#">Активные</a> | <a href="#">Не активные</a>
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
    @stop