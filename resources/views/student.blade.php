<html>
<head>
    <title>Student Managment System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Student Managment System</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewStudent">Add Student + </a>
    <table  class="table table-bordered data-table ">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Password</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
                        <form id="studentForm" name="studentForm" class="form-horizontal">
                   <input type="hidden" name="student_id" id="student_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">User Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter User Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter E-mail" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Contact</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Contact" value="" maxlength="10" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Adress</label>
                        <div class="col-sm-12">
                            <textarea   class="form-control" id="address" name="address" placeholder="Enter Address" value="" maxlength="100" required=""></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="update" value="create">Update
                     </button>
                     <button type="submit" class="btn btn-primary" id="create" value="create">Add
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    if(table){
        table.distroy();
    }
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {url:"{{ route('students_list.index') }}",type: "POST"},
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'contact', name: 'contact'},
            {data: 'address', name: 'address'},
            {data: 'password', name: 'password'},
            {data: 'action', name: 'action', orderable: true, searchable: false},
        ]
    });
    $('#createNewStudent').click(function () {
        alert(1)
        $('#saveBtn').val("create-student");
        $('#student_id').val('');
        $('#studentForm').trigger("reset");
        $('#modelHeading').html("Add New Student");
        $('#ajaxModel').modal('show');
        $('#update').hide();
        $('#create').show();
    });
    $('body').on('click', '.editStudent', function () {
      var student_id = $(this).data('id');
      $.get("{{ route('students.index') }}" +'/' + student_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Student");
          $('#saveBtn').val("edit-student");
          $('#ajaxModel').modal('show');
          $('#student_id').val(data.id);
          $('#username').val(data.username);
          $('#email').val(data.email);
          $('#contact').val(data.contact);
          $('#address').val(data.address);
          $('#password').val(data.password);
          $('#create').hide();
          $('#update').show()
      })
   });
    $('#update').click(function (e) {
        e.preventDefault();
        id = $('#student_id').val()
        $(this).html('updating...');
        $.ajax({
          data: $('#studentForm').serialize(),
          url: `{{ route('students.store') }}/${id}`,
          type: "PUT",
          dataType: 'json',
          success: function (data) {
            if($.isEmptyObject(data.error)){
                console.log('Success:', data);
                $('#studentForm').trigger("reset");
                $(".print-error-msg").find('ul').html('');
                alert(Object.values(data));
                $('#ajaxModel').modal('hide');
                $('#create').html('Update');
                table.rows().invalidate().draw()
            }
            else{
                    $('#create').hide();
                    $('#update').show();
                    printErrorMsg(data.error);
                    $('#create').html('Update');
                }
          },
          error: function (data) {
              console.log('Error:', data);
              //alert("Erro: "+ Object.values(data));
              printErrorMsg (data.error)
              $('#update').html('Update');
          }
      });
    });

    $('#create').click(function (e) {
        e.preventDefault();
        $(this).html('Saving...');
        $.ajax({
          data: $('#studentForm').serialize(),
          url: "{{ route('students.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
                if($.isEmptyObject(data.error)){
                    $('#update').hide();
                    $('#create').show();
                    $('#studentForm').trigger("reset");
                    $(".print-error-msg").find('ul').html('');
                    $('#ajaxModel').modal('hide');
                    table.rows().invalidate().draw()
                    console.log('Success:', data);
                    alert(Object.values(data));
                    $('#create').html('Add');
              
                    
                   
                }else{
                    $('#update').hide();
                    $('#create').show();
                    printErrorMsg(data.error);
                    $('#create').html('Add');
                }
            
          },
          error: function (data) {
              console.log('Error:', data);
              //alert("Erro: "+ Object.values(data));
              printErrorMsg (data.error)
              $('#create').html('Save Changes');
          }
      });
    });
    $('body').on('click', '.deleteStudent', function () {
        var student_id = $(this).data("id");
        $confirm = confirm("Are You sure want to delete !");
        if($confirm == true ){
            $.ajax({
                type: "DELETE",
                url: "{{ route('students.store') }}"+'/'+student_id,
                success: function (data) {
                    if($.isEmptyObject(data.error)){
                        table.draw();
                        alert(Object.values(data));
                
                    }else{
                        printErrorMsg(data.error);
                    }
                    
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
  });

  function printErrorMsg (msg) {
            console.log(msg);
            $(".print-error-msg").find('ul').html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                console.log(key, ' : ' ,value )
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
</script>
</body>
</html>