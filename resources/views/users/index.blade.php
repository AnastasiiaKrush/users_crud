@extends('layouts.default')

@section('content')
    <table
        id="table"
        class="pe-account-table table-striped table-sm"
        data-toggle="table"
        data-pagination="true"
        data-side-pagination="server"
        data-server-sort="true"
        {{--        data-total-field="total"--}}
        data-data-field="rows"
        data-sort-name="id"
        {{--        data-sort-order="asc"--}}
        data-url="/get-users"
        data-page-size="10"
        data-page-list="[10, 20, 30, 50]"
        {{--        data-search="true"--}}
    >
        <thead class="thead-light"></thead>
    </table>

    <script>
        var $table = $('#table');

        function operateFormatter(value, row) {
            return [
                '<a title="View" href="/users/',
                row.id,
                '"><i class="fas fa-eye"></i></a>',
                '<a title="Edit" href="/users/',
                row.id,
                '/edit"><i class="fas fa-user-edit"></i></a>',
                '<a class="remove" title="Remove" href="/users/',
                row.id,
                '"><i class="fa fa-trash"></i></a>'
            ].join('');
        }

        window.operateEvents = {
            'click .remove': function (e, value, row) {
                e.preventDefault();
                $.ajax({
                    type: "DELETE",
                    url: "/users/" + row.id,
                    async: true,
                    data: {}
                }).done(() => {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    })
                });
            }
        };

        function initTable() {
            $table.bootstrapTable('destroy').bootstrapTable({
                height: 550,
                locale: $('#locale').val(),
                columns:
                    [
                        [{
                            title: '#',
                            field: 'id',
                            align: 'center',
                            sortable: true,
                        }, {
                            title: 'User name',
                            field: 'name',
                            align: 'center',
                            sortable: true,
                        }, {
                            title: 'Email',
                            field: 'email',
                            align: 'center',
                            sortable: true,
                        }, {
                            title: 'Actions',
                            align: 'center',
                            events: window.operateEvents,
                            formatter: operateFormatter
                        }]
                    ]
            });
        }

        $(function () {
            initTable();
            $('#locale').change(initTable);
        })
    </script>
@stop
