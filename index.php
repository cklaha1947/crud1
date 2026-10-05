<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<style>
    .page-link{
        background-color: green;color: white;
    }
    .page-item.active .page-link{
        background-color: darkgreen; border-color: darkgreen;
    }
</style>
</head>

<body>
    <form id="form1" enctype="multipart/form-data">
        <p>Name</p>
        <p><label for=""><input type="text" name="name"></label></p>
        <p>Gender</p>
        <p><label><input type="radio" name="gender" value="Male">Male</label></p>
        <p><label><input type="radio" name="gender" value="Female">Female</label></p>
        <p><label><input type="radio" name="gender" value="Other">Other</label></p>
        <p>Stream</p>
        <select name="stream">
            <option value="">--Select--</option>
            <option value="BCA">BCA</option>
            <option value="BBA">BBA</option>
            <option value="MCA">MCA</option>
            <option value="B.Tech">B.Tech</option>
        </select>
        <p>Subject</p>
        <p><label><input type="checkbox" name="sub[]" value="C">C</label></p>
        <p><label><input type="checkbox" name="sub[]" value="C++">C++</label></p>
        <p><label><input type="checkbox" name="sub[]" value="Java">Java</label></p>
        <p><label><input type="checkbox" name="sub[]" value="Python">Python</label></p>
        <p>Image</p>
        <p><input type="file" name="simg"></p>
        <p><input type="submit" name="save" value="save"></p>
    </form>
    <hr>
    <style>
        .modal1 {
            top: 0;
            left: 0;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            flex-wrap: wrap;
        }
    </style>
    <!-- autocomplete search -->
    <h6>Auto Complete Search</h6>
    <form id="auto-search">
        <div id="autocomplete">
            <input type="text" id="name-box" placeholder="Enter Name" autocomplete="off">
            <div id="name-list"></div>
        </div>
        <input type="submit" value="Search" id="search-btn">
    </form>
    <hr>
    <div id="load-data"></div>
    <hr>
    <div id="result"></div>
    <div id="modal" class="modal1" style="display:none;">
        <div id="modal-head">X</div>
        <div id="modal-body"></div>
    </div>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            //load data:-----------------------------------------SELECT(2)
            function loadData(page) {
                $.ajax({
                    url: "select.php",
                    type: "POST",
                    data: {
                        page_no: page
                    },
                    success: function(data) {
                        $('#result').html(data);
                    }
                });
            }
            loadData();

            //pagination:-
            $(document).on("click", ".pagination .page-link", function(e) {
                e.preventDefault();
                var page_id = $(this).attr("id");
                loadData(page_id);
            });
            //insert form data:-------------------------------------INSERT(1)
            $('#form1').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "insert.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            alert("Data inserted successfully");
                        } else {
                            alert("Failed to insert data");
                        }
                        $('#form1')[0].reset();
                        loadData();
                    }
                });
            });
            //delete data:-------------------------------------DELETE(3)
            $(document).on("click", ".delete-btn", function() {
                var studentId = $(this).data('did');
                var x = confirm("Are you sure to delete this record?");
                if (x) {
                    $.ajax({
                        url: "delete.php",
                        type: "POST",
                        data: {
                            id: studentId
                        },
                        success: function(data) {
                            loadData();
                            if (data == 0) {
                                alert("Failed to delete data");
                            }

                        }
                    });
                }

            });
            //show modal box for edit data:----------------------
            $(document).on("click", ".edit-btn", function() {
                var studentId = $(this).data('eid');
                $.ajax({
                    url: "edit-form.php",
                    type: "POST",
                    data: {
                        id: studentId
                    },
                    success: function(data) {
                        $('#modal-body').html(data);
                        $('#modal').slideDown();
                    }
                });
            });
            //save edited data:----------------------------------UPDATE(4)
            $(document).on('submit', '#updateForm', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            $('#modal').fadeOut();
                            loadData();
                        } else {
                            alert("Update failed: " + data); // show actual error
                        }
                    }
                });
            });

             //autocomplete-search:-
            $("#name-box").keyup(function() {
                var name = $(this).val();   
                if (name != "") {
                    $.ajax({
                        url: "load-name.php",
                        method: "POST",
                        data: {
                            name: name
                        },
                        success: function(data) {
                            $("#name-list").fadeIn("fast").html(data);
                        }
                    });
                } else {
                    $("#name-list").fadeOut();
                }
            });
            $(document).on("click", "#name-list li", function() {
                $("#name-box").val($(this).text());
                $("#name-list").fadeOut();
            });
            $("#search-btn").on("click", function(e) {
                e.preventDefault();
                var name = $("#name-box").val();
                if (name == "") {
                    alert("Please enter the name...");
                } else {
                    $.ajax({
                        url: "load-table-auto-search.php",
                        method: "POST",
                        data: {
                            name: name
                        },
                        success: function(data) {
                            console.log(data);
                            $("#result").html(data);
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>