<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <title>Ajax</title>
</head>
<body>
<div class="container">
    <h1>Student come Hee</h1>
    <a href="javascript:void(0)" class="btn btn-success" id="createNewStudent" style="float:right">Add</a>
    <table class="table table-bordered data-table" id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal fade" id="ajaxModel" aria-hidden="ture">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="studentForm" name="studentForm" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="student_id" id="student_id">
                    <div class="form-group">
                        Name: <br>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                    </div>
                    <div class="form-group">
                        Email: <br>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</body>
    <script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        })
        var table =$(".data-table").DataTable({
            severSide:true,
            processing:true,
            ajax:"{{route('student.index')}}",
            columns:[
                {data:'DT_RowIndex',name:'DT_RowIndex'},
                {data:'name',name:'name'},
                {data:'email',name:'email'},
                {data:'action',name:'action'},
            ]
        });

        $('#createNewStudent').click(function(){
            $('#student_id').val('');
            $('#studentForm').trigger('reset');
            $('#modalHeading').html("Add Student");
            $('#ajaxModel').modal('show');
        });
        $('#saveBtn').click(function(e){
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data:$('#studentForm').serialize(),
                url:"{{route('student.store')}}",
                type:"POST",
                dataType:'json',
                success:function(data){
                    $('#studentForm').trigger('reset');
                    $('#ajaxModel').modal('hide');
                    location.reload();
                },
                error:function(data){
                    console.log('Error:',data);
                    $('#saveBtn').html('Save');
                }
            });
        });

        $('body').on('click','.deleteStudent',function(){
            var student_id = $(this).data("id");
            confirm("ลบข้อมูลหรือไม่");
                $.ajax({
                type:"DELETE",
                url: "student"+"/"+student_id,

                success:function(data){
                    location.reload();

                },
                error:function(data){
                    console.log('Error:',data);
                }
            });
            console.log(student_id);
        });

        $('body').on('click','.editStudent',function(){
            var student_id = $(this).data('id');
            $.get("{{route('student.index')}}"+"/"+student_id+"/edit",function(data){
                $("modelHeading").html("Edit Student");
                $('#ajaxModel').modal('show');
                $("#student_id").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);

            });
        });

    });
    </script>
</html>
