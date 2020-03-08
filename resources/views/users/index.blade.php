@extends('layouts.default')

@section('content')
    <table class="table table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">User name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
        <tr class="table-primary">
            <th scope="row"><?php echo ($user->id);?></th>
            <th scope="row"><?php echo ($user->name);?></th>
            <td>
                <a class="badge badge-primary badge-pill" href="/users/<?php echo $user->id ?>">View</a>
                <a class="badge badge-primary badge-pill" href="/users/<?php echo $user->id ?>/edit">Edit</a>
                <a id="user-delete" class="badge badge-primary badge-pill" href="/users/<?php echo $user->id ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        $('#user-delete').click(function(e){
            let hrefArr = this.getAttribute('href').split('/');
            let userId = hrefArr[2];

            e.preventDefault();
            $.ajax({
                type: "DELETE",
                url: "/users/" + userId,
                data: {}
            }).done(function() {
                document.location.href = '/users';
            });
        })
    </script>
@stop
