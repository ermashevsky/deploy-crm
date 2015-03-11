<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRM Manager v.0.0.0.1</title>
        {{ HTML::script('js/jquery-2.1.1.min.js') }}
        {{ HTML::script('js/bootstrap.js') }}
        {{ HTML::script('js/jquery.datetimepicker.js') }}
        {{ HTML::script('js/date.format.js') }}
        {{ HTML::script('js/jquery.dataTables.js') }}
        {{ HTML::script('js/waitMe.js') }}
        {{ HTML::script('js/bootbox.js') }}
        {{ HTML::script('js/bootstrap-switch.js') }}
        {{ HTML::script('js/jquery.bootstrap-growl.js') }}
        {{ HTML::script('js/jquery.steps.js') }}
        {{ HTML::script('js/jquery.validate.js') }}
        {{ HTML::script('js/store.min.js') }}
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/bootstrap-theme.css') }}
        {{ HTML::style('css/jquery.datetimepicker.css') }}
        {{ HTML::style('css/jquery.dataTables.css') }}
        {{ HTML::style('css/waitMe.css') }}
        {{ HTML::style('css/bootstrap-switch.css') }}
        {{ HTML::style('css/jquery.steps.css') }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Armata') }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Ubuntu&subset=cyrillic-ext,latin') }}


        <script type="text/javascript">
            $(function () {
                /* Set the defaults for DataTables initialisation */
                $.extend(true, $.fn.dataTable.defaults, {
                    "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ records per page"
                    }
                });




                /* Default class modification */
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline",
                    "sFilterInput": "form-control input-sm",
                    "sLengthSelect": "form-control input-sm"
                });


                /* API method to get paging information */
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                /* Bootstrap style pagination control */
                $.extend($.fn.dataTableExt.oPagination, {
                    "bootstrap": {
                        "fnInit": function (oSettings, nPaging, fnDraw) {
                            var oLang = oSettings.oLanguage.oPaginate;
                            var fnClickHandler = function (e) {
                                e.preventDefault();
                                if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                                    fnDraw(oSettings);
                                }
                            };

                            $(nPaging).append(
                                    '<ul class="pagination">' +
                                    '<li class="prev disabled"><a href="#">&larr; ' + oLang.sPrevious + '</a></li>' +
                                    '<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
                                    '</ul>'
                                    );
                            var els = $('a', nPaging);
                            $(els[0]).bind('click.DT', {action: "previous"}, fnClickHandler);
                            $(els[1]).bind('click.DT', {action: "next"}, fnClickHandler);
                        },
                        "fnUpdate": function (oSettings, fnDraw) {
                            var iListLength = 5;
                            var oPaging = oSettings.oInstance.fnPagingInfo();
                            var an = oSettings.aanFeatures.p;
                            var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

                            if (oPaging.iTotalPages < iListLength) {
                                iStart = 1;
                                iEnd = oPaging.iTotalPages;
                            }
                            else if (oPaging.iPage <= iHalf) {
                                iStart = 1;
                                iEnd = iListLength;
                            } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                                iStart = oPaging.iTotalPages - iListLength + 1;
                                iEnd = oPaging.iTotalPages;
                            } else {
                                iStart = oPaging.iPage - iHalf + 1;
                                iEnd = iStart + iListLength - 1;
                            }

                            for (i = 0, ien = an.length; i < ien; i++) {
                                // Remove the middle elements
                                $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                                // Add the new list items and their event handlers
                                for (j = iStart; j <= iEnd; j++) {
                                    sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                                    $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                                            .insertBefore($('li:last', an[i])[0])
                                            .bind('click', function (e) {
                                                e.preventDefault();
                                                oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                                                fnDraw(oSettings);
                                            });
                                }

                                // Add / remove disabled classes from the static elements
                                if (oPaging.iPage === 0) {
                                    $('li:first', an[i]).addClass('disabled');
                                } else {
                                    $('li:first', an[i]).removeClass('disabled');
                                }

                                if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                                    $('li:last', an[i]).addClass('disabled');
                                } else {
                                    $('li:last', an[i]).removeClass('disabled');
                                }
                            }
                        }
                    }
                });

                if (document.URL === "http://deploy.crm64.ru/manageCRM") {

                    $('#crm_name').append(store.get('client_name'));

                    if (!store.enabled) {

                        $.bootstrapGrowl("Local storage is not supported by your browser. Please disable 'Private Mode', or upgrade to a modern browser.", {
                            type: 'danger',
                            align: 'center',
                            width: 'auto',
                            allow_dismiss: false
                        });

                        return;
                    }

                    $.post("/viewModuleList", {
                        db: store.get('databaseName'),
                        user: store.get('databaseUsername'),
                        pass: store.get('databasePassword')
                    }, function (data) {
                        $('div#modulesList').append('<table class="table table-striped table-bordered table-condensed" id="tableModuleList"><thead><th>#</th><th>Наименование модуля</th><th>Описание</th><th>Действия</th></thead></table>');
                        var n = 1;
                        $.each(data, function (i, val) {
                            $('#tableModuleList').append('<tr><td>' + n++ + '</td><td><label>' + data[i].plugin_name +
                                    '</label></td><td>' + data[i].plugin_description +
                                    '<p>Версия: ' + data[i].plugin_version +
                                    ' | Автор: ' + data[i].plugin_author + '</p></td><td><input type="checkbox" id="' + data[i].id + '" name="my-checkbox"  ' + data[i].plugin_state + '></td></tr>');
                        });


                        $("[name='my-checkbox']").bootstrapSwitch({
                            'onText': 'ВКЛ',
                            'offText': 'ВЫКЛ'
                        });
                        $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function (event, state) {
                            console.log(this); // DOM element
                            console.log(event); // jQuery event
                            console.log(state); // true | false

                            //Тут отправить в php метод два параметра id записи и его текущее состояние, а в методе апдейт состояния
                        });

                    }, 'json');

                }

                //viewUserCRM CodeBlock start

                if (document.URL === "http://deploy.crm64.ru/viewUserCRM") {

                    $('#crm_name').append(store.get('client_name'));

                    if (!store.enabled) {

                        $.bootstrapGrowl("Local storage is not supported by your browser. Please disable 'Private Mode', or upgrade to a modern browser.", {
                            type: 'danger',
                            align: 'center',
                            width: 'auto',
                            allow_dismiss: false
                        });

                        return;
                    }

                    $.post("/viewUsersList", {
                        db: store.get('databaseName'),
                        user: store.get('databaseUsername'),
                        pass: store.get('databasePassword')
                    }, function (data) {
                        $('div#modulesList').append('<table class="table table-striped table-bordered table-condensed" id="tableModuleList"><thead>\n\
                    <th>#</th>\n\
                    <th>ФИО</th>\n\
                    <th>Должность</th>\n\
                    <th>E-mail</th>\n\
                    <th>Логин</th>\n\
                    <th>Группа</th>\n\
                    <th>Действия</th>\n\
                    </thead></table>');
                        var n = 1;
                        $.each(data, function (i, val) {


                            $('#tableModuleList').append('<tr><td>' + n++ + '</td><td><label>' + data[i].last_name + ' ' + data[i].first_name +
                                    '</label></td><td>' + data[i].work_position + '</td><td>' + data[i].email + '</td><td>' +
                                    data[i].username + '</td><td>' +
                                    data[i].description + '</td><td><div class="btn-group btn-group-sm" role="group" aria-label="">\n\
                        <button type="button" class="btn btn-default" onclick="viewUser(' + data[i].id + ')"><i class="glyphicon glyphicon-eye-open"></i></button>\n\
                        <button type="button" class="btn btn-default" onclick="editUser(' + data[i].id + ')"><i class="glyphicon glyphicon-pencil"></i></button>\n\
                        <button type="button" class="btn btn-default" onclick="deleteUser(' + data[i].id + ')"><i class="glyphicon glyphicon-trash"></i></button></div></td></tr>');
                        });

                    }, 'json');

                }
                //End of manageCRM CodeBlock


                $("[name='my-checkbox']").bootstrapSwitch({
                    size: 'normal',
                    onColor: 'warning',
                    offColor: 'danger'
                });

                var lastIdx = null;
                var table = $('#tableData').DataTable();

                $('#tableData tbody')
                        .on('mouseover', 'td', function () {
                            var colIdx = table.cell(this).index().column;

                            if (colIdx !== lastIdx) {
                                $(table.cells().nodes()).removeClass('highlight');
                                $(table.column(colIdx).nodes()).addClass('highlight');
                            }
                        })
                        .on('mouseleave', function () {
                            $(table.cells().nodes()).removeClass('highlight');
                        });

                $('#start_date').datetimepicker({
                    format: 'M d Y H:i:s',
                    value: new Date().format('mmm d yyyy 00:00:00'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                $('#end_date').datetimepicker({
                    format: 'M d Y H:i:s',
                    value: new Date().format('mmm d yyyy HH:MM:ss'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });


                $('#redirectForm').click(function () {

                    window.location = "http://deploy.crm64.ru/createCRM";
                });

                //stepForm

                var form = $("#example-advanced-form").show();

                form.steps({
                    headerTag: "h3",
                    bodyTag: "fieldset",
                    transitionEffect: "slideLeft",
                    onStepChanging: function (event, currentIndex, newIndex)
                    {
                        // Allways allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex)
                        {
                            return true;
                        }
                        // Forbid next action on "Warning" step if the user is to young
                        if (newIndex === 3 && Number($("#age-2").val()) < 18)
                        {
                            return false;
                        }
                        // Needed in some cases if the user went back (clean up)
                        if (currentIndex < newIndex)
                        {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onStepChanged: function (event, currentIndex, priorIndex)
                    {
                        // Used to skip the "Warning" step if the user is old enough.
                        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                        {
                            form.steps("next");
                        }
                        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                        if (currentIndex === 2 && priorIndex === 3)
                        {
                            form.steps("previous");
                        }
                        $("#collectInfoBlock").empty();

                        var clientName = $('#client_name').val();
                        var CRMDomainName = $('#crmDomainName').val();
                        var userName = $('#crmLogin').val();
                        var password = $('#crmPassword').val();
                        var crmDescription = $('#crmDescription').val();
                        var asteriskAddress = $('#asteriskAddress').val();
                        var asteriskUsername = $('#asteriskLogin').val();
                        var asteriskPassword = $('#asteriskPassword').val();
                        var databaseName = $('#databaseName').val();
                        var databaseUsername = $('#databaseUsername').val();
                        var databasePassword = $('#databasePassword').val();
                        var checkboxes = [];
                        $('input[name="activeCRMModules"]:checked').each(function () {
                            checkboxes.push(this.value);
                        });

                        var table = '<table class="table table-striped table-bordered table-condensed">'
                                + '<tr><th>Наименование клиента</th><td>' + clientName + '</td></tr>' +
                                '<tr><th>Доменное имя CRM</th><td>' + CRMDomainName + '</td></tr>' +
                                '<tr><th>Пользователь CRM</th><td>' + userName + '</td></tr>' +
                                '<tr><th>Пароль CRM</th><td>' + password + '</td></tr>' +
                                '<tr><th>Описание CRM</th><td>' + crmDescription + '</td></tr>' +
                                '<tr><th>Активные модули CRM</th><td>' + checkboxes + '</td></tr>' +
                                '<tr><th>Адрес Asterisk</th><td>' + asteriskAddress + '</td></tr>' +
                                '<tr><th>Пользователь Asterisk</th><td>' + asteriskUsername + '</td></tr>' +
                                '<tr><th>Пароль Asterisk</th><td>' + asteriskPassword + '</td></tr>' +
                                '<tr><th>Имя базы данных</th><td>' + databaseName + '</td></tr>' +
                                '<tr><th>Пользователь базы данных</th><td>' + databaseUsername + '</td></tr>' +
                                '<tr><th>Пароль базы данных</th><td>' + databasePassword + '</td></tr>' +
                                '</table>';


                        $("#collectInfoBlock").append(table);

                    },
                    onFinishing: function (event, currentIndex)
                    {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();
                    },
                    onFinished: function (event, currentIndex)
                    {
                        var str = $("#example-advanced-form").serialize();

                        $.post("/saveNewCRMData", str, function (data) {

                            $.bootstrapGrowl("Новая запись успешно сохранена!", {
                                type: 'success',
                                align: 'center',
                                width: 'auto',
                                allow_dismiss: false
                            });
                            var delay = 1500;
                            setTimeout("document.location.href='http://deploy.crm64.ru/'", delay);
                        });

                    }
                }).validate({
                    errorPlacement: function errorPlacement(error, element) {
                        element.before(error);
                    },
                    rules: {
                        confirm: {
                            equalTo: "#crmPassword",
                            minlength: 5
                        },
                        confirmAsteriskPassword: {
                            equalTo: "#asteriskPassword",
                            minlength: 5
                        },
                        databaseConfirmPassword: {
                            equalTo: "#databasePassword",
                            minlength: 5
                        }
                    }
                });


            });

            function spinner() {
                $('#myModal .modal-dialog').waitMe({
                    effect: 'win8',
                    text: 'Данные готовятся. Пожалуйста, подождите.',
                    bg: 'rgba(255,255,255,0.7)',
                    color: '#428bca'
                });
            }

            function createVHost(vhost, vhostDirectory) {

                $.post("/createVhost", {vhost: vhost, vhostDirectory: vhostDirectory}, function (data) {
                    return data;
                }, 'json');
            }


            function viewUser(id) {

                var n = 1;

                $.post('/getUserDetail', {
                    id: id,
                    db: store.get('databaseName'),
                    user: store.get('databaseUsername'),
                    pass: store.get('databasePassword')},
                function (data) {
                    console.info(data);
                    $('#myModalViewUserDetailForm .modal-body table').empty();
                    $.each(data, function (i, val) {
                        $('#myModalViewUserDetailForm .modal-body table').append('<tr><td><label>ФИО</label></td><td>' + data[i].first_name + ' ' + data[i].last_name +
                                '</td></tr><tr><td><label>Отдел</label></td><td>' + data[i].company +
                                '</td></tr><tr><td><label>Должность</label></td><td>' + data[i].work_position +
                                '</td></tr><tr><td><label>Телефон (внешн.)</label></td><td>' + data[i].external_phone +
                                '</td></tr><tr><td><label>Телефон (внутр.)</label></td><td>' + data[i].phone +
                                '</td></tr><tr><td><label>Email</label></td><td>' + data[i].email +
                                '</td></tr><tr><td><label>Логин</label></td><td>' + data[i].username +
                                '</td></tr><tr><td><label>Группа (роль)</label></td><td>' + data[i].description +
                                '</td></tr>');
                    });
                    $('#myModalViewUserDetailForm').modal('show');
                });

            }

            function editUser(id) {

                var n = 1;

                $.post('/getUserDetail', {
                    id: id,
                    db: store.get('databaseName'),
                    user: store.get('databaseUsername'),
                    pass: store.get('databasePassword')},
                function (data) {
                    console.info(data);
                    
                    $.each(data, function (i, val) {
                        
                        $('#myModalEditUserForm #inputLogin').val(data[i].username);
                        $('#myModalEditUserForm #inputLastName').val(data[i].last_name);
                        $('#myModalEditUserForm #inputFirstName').val(data[i].first_name);
                        $('#myModalEditUserForm #inputWorkDept').val(data[i].company);
                        $('#myModalEditUserForm #inputJobPosition').val(data[i].work_position);
                        $('#myModalEditUserForm #inputExternalPhone').val(data[i].external_phone);
                        $('#myModalEditUserForm #inputPhone').val(data[i].phone);
                        $('#myModalEditUserForm #inputEmail').val(data[i].email);
                        $('#myModalEditUserForm #inputLogin').val(data[i].description);

                    });
                    $('#myModalEditUserForm').modal('show');
                });

            }
            
            function deleteUser(id){
                bootbox.dialog({
                    message: "Вы хотите удалить пользователя?",
                    title: "Удаление пользователя",
                    buttons: {
                        main: {
                            label: "Да",
                            className: "btn btn-default",
                            callback: function () {
                                $.post("/deleteUser", {
                                    id: id,
                                    db: store.get('databaseName'),
                                    user: store.get('databaseUsername'),
                                    pass: store.get('databasePassword')
                                }, function (data) {
                                    console.info(data);
                                    location.reload();
                                });
                            }
                        },
                        danger: {
                            label: "Нет",
                            className: "btn btn-default",
                            callback: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

            function setupCRM(id) {

                $.post("/getRowByPrimaryKey", {id: id}, function (data) {
                    console.info(data);
                    $.each(data, function (i, val) {
                        var vhostDomainName = data[i].crmDomainName;
                        $.post("/createCRMDB", {user: data[i].databaseUsername, pass: data[i].databasePassword, db: data[i].databaseName}, function (data) {
                            console.info(data);
                            if (data === null) {
                                $.bootstrapGrowl("Структура базы данных создана успешно", {
                                    type: 'success',
                                    align: 'center',
                                    width: 'auto',
                                    allow_dismiss: false
                                });

                                console.info(createVHost(vhostDomainName, vhostDomainName));

                            } else {
                                $.bootstrapGrowl("Ошибка: " + data, {
                                    type: 'danger',
                                    align: 'center',
                                    width: 'auto',
                                    allow_dismiss: false
                                });
                            }
                        }, 'json');

                    });
                }, 'json');
            }

            function editCRMParameters(id) {
                // Clear all keys
                store.clear();

                $.post("/getRowByPrimaryKey", {id: id}, function (data) {


                    $.each(data, function (i, val) {

                        store.set('activeCRMModules', data[i].activeCRMModules);
                        store.set('asteriskAddress', data[i].asteriskAddress);
                        store.set('asteriskLogin', data[i].asteriskLogin);
                        store.set('asteriskPassword', data[i].asteriskPassword);
                        store.set('client_name', data[i].client_name);
                        store.set('created_at', data[i].created_at);
                        store.set('crmDescription', data[i].crmDescription);
                        store.set('crmDomainName', data[i].crmDomainName);
                        store.set('crmLogin', data[i].crmLogin);
                        store.set('crmPassword', data[i].crmPassword);
                        store.set('crmVersion', data[i].crmVersion);
                        store.set('databaseName', data[i].databaseName);
                        store.set('databasePassword', data[i].databasePassword);
                        store.set('databaseUsername', data[i].databaseUsername);
                        store.set('id', data[i].id);
                        store.set('updated_at', data[i].updated_at);

                    });
                    var delay = 100;
                    setTimeout("document.location.href='http://deploy.crm64.ru/manageCRM'", delay);

                }, 'json');


            }


            function deleteRule(id) {
                bootbox.dialog({
                    message: "Вы действительно хотите удалить CRM?",
                    title: "Удаление CRM",
                    buttons: {
                        main: {
                            label: "Да",
                            className: "btn btn-primary",
                            callback: function () {
                                $.post("/deleteRule", {id: id}, function (data) {
                                    console.info(data);
                                    location.reload();
                                });
                            }
                        },
                        danger: {
                            label: "Нет",
                            className: "btn btn-default",
                            callback: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

        </script>

        <style>
            body,html{
                font-family: 'Ubuntu', sans-serif;
                //font-size: 13px;
            }
            .navbar-brand{
                font-family: 'Ubuntu', sans-serif;
            }

            #settingsTable{
                font-family: 'Ubuntu', sans-serif;
                font-size: 13px;
            }

            #tableModuleList{
                font-family: 'Ubuntu', sans-serif;
                font-size: 13px;
            }

            #myStatisticModal .modal-dialog
            {
                width: 350px;/* your width */
            }


            div.dataTables_length label {
                float: left;
                text-align: left;
                margin-left: 30px;
            }

            div.dataTables_length select {
                width: 75px;
            }

            div.dataTables_filter label {
                float: right;
            }

            div.dataTables_info {
                padding-top: 8px;
                margin-left: 30px;
            }

            div.dataTables_paginate {
                float: right;
            }

            table.table {
                clear: both;
                margin-bottom: 6px !important;
                max-width: none !important;
            }

            table.table thead .sorting,
            table.table thead .sorting_asc,
            table.table thead .sorting_desc,
            table.table thead .sorting_asc_disabled,
            table.table thead .sorting_desc_disabled {
                cursor: pointer;
                *cursor: hand;
            }

            table.table thead .sorting { background: url('/img/sort_both.png') no-repeat center right; }
            table.table thead .sorting_asc { background: url('/img/sort_asc.png') no-repeat center right; }
            table.table thead .sorting_desc { background: url('/img/sort_desc.png') no-repeat center right; }

            table.table thead .sorting_asc_disabled { background: url('/img/sort_asc_disabled.png') no-repeat center right; }
            table.table thead .sorting_desc_disabled { background: url('/img/sort_desc_disabled.png') no-repeat center right; }

            table.dataTable th:active {
                outline: none;
            }

            /* Scrolling */
            div.dataTables_scrollHead table {
                margin-bottom: 0 !important;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            div.dataTables_scrollHead table thead tr:last-child th:first-child,
            div.dataTables_scrollHead table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.dataTables_scrollBody table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.dataTables_scrollBody tbody tr:first-child th,
            div.dataTables_scrollBody tbody tr:first-child td {
                border-top: none;
            }

            div.dataTables_scrollFoot table {
                border-top: none;
            }
            #tableData{
                font-size: 12px;
                font-family: 'Roboto', sans-serif;
            }
            #tableData_list_info, #tableData_list_paginate {
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_length_wrapper select {
                width: auto !important;
                font-size: 12px;
                margin-top:10px;
            }
            .dataTables_length label{
                font-size: 12px;
            }
            .dataTables_filter input{
                width: 120px;
                font-size: 12px;
            }
            .dataTables_filter label{
                font-size: 12px;
            }
            .dataTables_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_info{
                margin-top: 10px;
                font-size: 12px;
            }

            /*
             * TableTools styles
             */
            .table tbody tr.active td,
            .table tbody tr.active th {
                background-color: #08C;
                color: white;
            }

            .table tbody tr.active:hover td,
            .table tbody tr.active:hover th {
                background-color: #0075b0 !important;
            }

            .table-striped tbody tr.active:nth-child(odd) td,
            .table-striped tbody tr.active:nth-child(odd) th {
                background-color: #017ebc;
            }

            table.DTTT_selectable tbody tr {
                cursor: pointer;
                *cursor: hand;
            }

            div.DTTT .btn {
                color: #333 !important;
                font-size: 12px;
            }

            div.DTTT .btn:hover {
                text-decoration: none !important;
            }


            ul.DTTT_dropdown.dropdown-menu a {
                color: #333 !important; /* needed only when demo_page.css is included */
            }

            ul.DTTT_dropdown.dropdown-menu li:hover a {
                background-color: #0088cc;
                color: white !important;
            }

            /* TableTools information display */
            div.DTTT_print_info.modal {
                height: 150px;
                margin-top: -75px;
                text-align: center;
            }

            div.DTTT_print_info h6 {
                font-weight: normal;
                font-size: 28px;
                line-height: 28px;
                margin: 1em;
            }

            div.DTTT_print_info p {
                font-size: 14px;
                line-height: 20px;
            }



            /*
             * FixedColumns styles
             */
            div.DTFC_LeftHeadWrapper table,
            div.DTFC_LeftFootWrapper table,
            table.DTFC_Cloned tr.even {
                background-color: white;
            }

            div.DTFC_LeftHeadWrapper table {
                margin-bottom: 0 !important;
                border-top-right-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftHeadWrapper table thead tr:last-child th:first-child,
            div.DTFC_LeftHeadWrapper table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftBodyWrapper table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.DTFC_LeftBodyWrapper tbody tr:first-child th,
            div.DTFC_LeftBodyWrapper tbody tr:first-child td {
                border-top: none;
            }

            div.DTFC_LeftFootWrapper table {
                border-top: none;
            }
            table.display tr.even.row_selected td {
                background-color: #B0BED9;
            }

            table.display tr.odd.row_selected td {
                background-color: #9FAFD1;
            }
            .nav-pills > li > a{
                font-size: 11px;
            }

            .nav-pills > li > a span{
                font-size: 11px;
            }
            /* Set the fixed height of the footer here */

            #footer {
                height: 44px;
                padding-top: 2px;
            }

            #myModalRule .modal-dialog  {
                width: 300px;
            }

            .bootbox .modal-dialog  {
                width: 350px;
            }

            .bootstrap-switch-container{
                height: 34px;
            }
        </style>
    </head>
    <body>

