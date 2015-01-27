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
        <h3><i class="glyphicon glyphicon-list-alt"></i> Добавление новой CRM</h3>
    </div>
    <form id="example-advanced-form" class="form-horizontal" action="#">
        <h3>Параметры CRM</h3>
        <fieldset>

            <legend>Параметры CRM</legend>
            <p>
                <label for="client_name">Наименование клиента *</label>
                <input id="client_name" name="client_name" type="text" class="required">
            </p>
            <p>
                <label for="crmDomainName">Доменное имя CRM *</label>
                <input id="crmDomainName" name="crmDomainName" type="text" class="required">
            </p>
            <p>
                <label for="crmLogin">CRM пользователь *</label>
                <input id="crmLogin" name="crmLogin" type="text" class="required">
            </p>
            <p>
                <label for="crmPassword">CRM пароль *</label>
                <input id="crmPassword" name="crmPassword" type="password" class="required">
            </p>
            <p>
                <label for="confirm-2">Подтверждение CRM пароля *</label>
                <input id="confirm-2" name="confirm" type="password" class="required">
            </p>
            <p>
                <label for="crmDescription">Описание CRM *</label><br/>
                <textarea id="crmDescription" name="crmDescription" cols="50" rows="3"></textarea>
            </p>
            <p>
                <label for="crmDescription">Активные модули CRM *</label><br/>    
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="activeCRMModules" id="module_1" value="Адресная книга" /> Адресная книга
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="activeCRMModules" id="module_2" value="История звонков" checked/> История звонков (по умолчанию)
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="activeCRMModules" id="module_3" value="Календарь" /> Календарь
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="activeCRMModules" id="module_4" value="Задачи" /> Задачи
                </label>
            </div>
            </p>
            <br/>
            <p>(*) Обязательный параметр</p>
        </fieldset>

        <h3>Параметры Asterisk</h3>
        <fieldset>
            <legend>Параметры Asterisk</legend>
            <p>
                <label for="asteriskAddress">IP-адрес Asterisk *</label>
                <input id="asteriskAddress" name="asteriskAddress" type="text" class="required">
            </p>
            <p>
                <label for="asteriskLogin">Пользователь Asterisk *</label>
                <input id="asteriskLogin" name="asteriskLogin" type="text" class="required">
            </p>
            <p>
                <label for="asteriskPassword">Пароль Asterisk *</label>
                <input id="asteriskPassword" name="asteriskPassword" type="password" class="required">
            </p>
            <p>
                <label for="confirmAsteriskPassword">Подтверждение пароля Asterisk *</label>
                <input id="confirmAsteriskPassword" name="confirmAsteriskPassword" type="password" class="required">
            </p>
            <br/>
            <p>(*) Обязательный параметр</p>
        </fieldset>

        <h3>Подключение к базе данных</h3>
        <fieldset>
            <legend>Подключение к базе данных</legend>
            <p>
                <label for="databaseName">Имя базы данных *</label>
                <input id="databaseName" name="databaseName" type="text" class="required">
            </p>
            <p>
                <label for="databaseUsername">Пользователь базы данных *</label>
                <input id="databaseUsername" name="databaseUsername" type="text" class="required">
            </p>
            <p>
                <label for="databasePassword">Пароль базы данных *</label>
                <input id="databasePassword" name="databasePassword" type="password" class="required">
            </p>
            <p>
                <label for="databaseConfirmPassword">Подтверждение пароля базы данных *</label>
                <input id="databaseConfirmPassword" name="databaseConfirmPassword" type="password" class="required">
            </p>
            <br/>
            <p>(*) Обязательный параметр</p>
        </fieldset>

        <h3>Окончание настройки</h3>
        <fieldset>
            <legend>Сводная информация</legend>
            <div id="collectInfoBlock"></div>
        </fieldset>
    </form>


    @stop