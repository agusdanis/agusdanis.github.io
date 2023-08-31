<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Sheet Rest API</title>

    <style>
        h1 {
            text-align: center;
            margin-top: 40px;
        }

        input {
            margin-bottom: 10px;  
        }

        form {
            margin: 0 auto;
            width: 120px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h1>Google Sheet Rest API</h1>

    <form action="" class="form" id="form">
        <div class="form-control">
            <label for="pekerjaan">Nama</label> <br>
            <input type="text" id="nama" name="nama" class="nama" required>
        </div>

        <div class="form-control">
            <label for="umur">Umur</label> <br>
            <input type="text" id="umur" name="umur" class="umur" required>
        </div>

        <div class="form-control">
            <label for="pekerjaan">Pekerjaan</label> <br>
            <input type="text" id="pekerjaan" name="pekerjaan" class="pekerjaan" required>
        </div>

        <input type="submit" value="Submit" id="submit">   
    </form>

    <script type="text/javascript">
        const form = document.getElementById('form');
        const inputNama = document.getElementById('nama');
        const inputUmur = document.getElementById('umur');
        const inputPekerjaan = document.getElementById('pekerjaan');

        getQSOData();
        
        function getQSOData() {
            $.ajax({
              url : "https://script.google.com/macros/s/AKfycbwY0umafNVZqRd9Py5dgJbxp20-V7DP9QMj5jd3-zffU8x9fvsZIEpbuMZCscGhQcXz/exec?action=read&callsign=JR2IGV",
              
              method : "GET",
            //   data : {edit_data: jsonData},
                // data: function() {
                //                 action = 'read';
                                
                // },
              success: function(data){
                //alert(data);
                console.log(data.records);
              },
              error : function(request, status, error) {
                console.log(error);
              }
            });
        }

        form.addEventListener('submit', (e) => {
        e.preventDefault();

        let data = {
            nama: inputNama.value,
            umur: inputUmur.value,
            pekerjaan: inputPekerjaan.value,
        };

        console.log(data);
        });


        
    </script>
</body>
</html>