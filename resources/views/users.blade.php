<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @notifyCss
    <x-notify::notify />
    <style>
    form .btn {
        background-color: #28a745;
        color: white;
        font-size: 1rem;
        border-radius: 0.25rem;
    }
</style>
</head>
<body>
    <div class="container mt-5">
        <h2>User</h2>
        <form action="{{ route('users.sendMail') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><input type="checkbox" class="usersCheckbox" name="user_ids[]" value="{{ $user->id }}"></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-success">Send Email</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-all').click(function() {
                $('input[name="user_ids[]"]').prop('checked', this.checked);
            });
        });
        $(".usersCheckbox").click(function() {
            var checkedCount = $(".usersCheckbox:checked").length;
            var totalCheckboxes = $(".usersCheckbox").length;
            $("#select-all").prop("checked", totalCheckboxes === checkedCount);
        });
    </script>
    @notifyJs
</body>
</html>