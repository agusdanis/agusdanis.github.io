<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" >

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> -->

</head>
<body>
    <h1>FIND CALLSIGN</h1>
    <input id="inputfile" type="file" accept=".cbr, .json, .adi">
    <input id="inputCallSign" style="text-transform: uppercase" type="text">
    <!-- <div id="adif_parse"></div> -->

    
    <!-- <textarea name="output" id="output" cols="30" rows="5"></textarea>-->
    <button id="btnCari">Cari</button> 
    <table id="tabelAdif" class="table table-striped" style="width:100%"></table>
    
    <footer>by YG1BSH</footer>

    <script type="text/javascript">
        const inputFile = document.getElementById('inputfile');
        const searchBox = document.getElementById('inputCallSign');
        const btnCari   = document.getElementById('btnCari');
        let judulKolom = {};
        const qsoData = {"0":{"CALL":"DM5EE","GRIDSQUARE":"JO52","MODE":"FT8","RST_SENT":"-15","RST_RCVD":"-18","QSO_DATE":"20230716","TIME_ON":"123815","QSO_DATE_OFF":"20230716","TIME_OFF":"123859","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "1":{"CALL":"JA7SYA","GRIDSQUARE":"QM09","MODE":"FT8","RST_SENT":"-11","RST_RCVD":"-13","QSO_DATE":"20230716","TIME_ON":"124215","QSO_DATE_OFF":"20230716","TIME_OFF":"124259","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "2":{"CALL":"OZ5TU","GRIDSQUARE":"JO54","MODE":"FT8","RST_SENT":"-11","RST_RCVD":"-6","QSO_DATE":"20230716","TIME_ON":"124406","QSO_DATE_OFF":"20230716","TIME_OFF":"124535","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "3":{"CALL":"RK4PH","MODE":"FT8","RST_SENT":"-16","RST_RCVD":"-12","QSO_DATE":"20230716","TIME_ON":"124635","QSO_DATE_OFF":"20230716","TIME_OFF":"124804","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "4":{"CALL":"UR7UR","MODE":"FT8","RST_SENT":"-11","RST_RCVD":"-13","QSO_DATE":"20230716","TIME_ON":"124904","QSO_DATE_OFF":"20230716","TIME_OFF":"125033","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "5":{"CALL":"UA3PAB","GRIDSQUARE":"KO84","MODE":"FT8","RST_SENT":"-7","RST_RCVD":"-16","QSO_DATE":"20230716","TIME_ON":"125133","QSO_DATE_OFF":"20230716","TIME_OFF":"125302","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "6":{"CALL":"YC4AGG","GRIDSQUARE":"OI18","MODE":"FT8","RST_SENT":"-18","RST_RCVD":"-7","QSO_DATE":"20230716","TIME_ON":"125402","QSO_DATE_OFF":"20230716","TIME_OFF":"125531","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "7":{"CALL":"S58N","GRIDSQUARE":"JN76","MODE":"FT8","RST_SENT":"-12","RST_RCVD":"-16","QSO_DATE":"20230716","TIME_ON":"125631","QSO_DATE_OFF":"20230716","TIME_OFF":"125800","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "8":{"CALL":"US0KW","GRIDSQUARE":"KO30","MODE":"FT8","RST_SENT":"-11","RST_RCVD":"-18","QSO_DATE":"20230716","TIME_ON":"125900","QSO_DATE_OFF":"20230716","TIME_OFF":"130029","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "9":{"CALL":"YD9RRB","GRIDSQUARE":"PI58","MODE":"FT8","RST_SENT":"-4","RST_RCVD":"-2","QSO_DATE":"20230716","TIME_ON":"130129","QSO_DATE_OFF":"20230716","TIME_OFF":"130258","BAND":"15m","FREQ":"21.075732","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"},
        "10":{"CALL":"DL9MRF","MODE":"FT8","RST_SENT":"-15","RST_RCVD":"-12","QSO_DATE":"20230716","TIME_ON":"130265","QSO_DATE_OFF":"20230716","TIME_OFF":"130400","BAND":"15m","FREQ":"21.074688","STATION_CALLSIGN":"YH1AA","MY_GRIDSQUARE":"OI33"}};

        // console.log(JSON.stringify(qsoData));
        console.log(qsoData);
        // for (let i=0; i<qsoData; i++) {
        //     console.log(qsoData[0].BAND);
        // }

        

        inputFile.addEventListener('change', function() {
                    
                judulKolom = [];
                let textInput = '';
                let isEoh = 0;
                let isContent = 0;
                let pos = 0;
                var fr=new FileReader();
                // console.log(this.files);
                fr.onload=function(){
                    textInput = fr.result;
                    console.log(textInput);
                    // document.getElementById('output')
                    //         .textContent=textInput;
                    // isEoh = textInput.toUpperCase().search("EOH");
                    // isEoh > 0 ? isEoh += 1 : "";
                    // isEoh < textInput.length ? isContent = 1 : "";
                    // isContent == 1 ? pos = isEoh : "";
                    // console.log(isEoh+' '+isContent);            
                    // parseInputFile(textInput, pos);
                }
                
                fr.readAsText(this.files[0]);
                // parseInputFile(textInput);
        })

        searchBox.addEventListener('change', () => {
            // alert('your callsign: ', $(this)[0]);     
            console.log(searchBox.value.toUpperCase());               
        })

        btnCari.addEventListener('click', () => {
            // alert('your callsign: ', $(this)[0]);     
            // console.log(searchBox.value.toUpperCase()); 
            // let fileJSON = 'adif.json';
            // let fileReader = new FileReader();
            // fileReader.readAsText(fileJSON);
            // fileReader.onload = () => {
            //     console.log(fileReader.result);
            // }
            // fileReader.onerror = () => {
            //     console.log(fileReader.error);
            // }

        })

        showAdifData = (dataQSO, judul) => {
            $(`#tabelAdif tbody`).empty();
            addHeaderTable(judul);
            let columnData = new Array();
            for (let i=0; i<judul.length; i++) {
                columnData.push({title: judul[i], data: judul[i], defaultContent: ''});
            }
            // console.log('column: ', columnData);
            // dataAdif = JSON.stringify(dataAdif);
            // dataAdif = JSON.parse(dataAdif);
            // console.log('dataAdif: ', dataAdif);

            // let dataTable = Object.entries(dataAdif); //Object.keys(dataAdif).map((key) => [key, dataAdif[key]]);
            // console.log('dataTabel: ', dataTable);

            let table = $('#tabelAdif').DataTable();
            table.destroy();
            table = $('#tabelAdif').DataTable({
            dom: 'Bfrtip',
                    lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                    ],
                    buttons: [
                        'pageLength', //'copy', 'csv', 'excel', 'pdf', 'print',
                    ],
                    pageLength  : 1000,
                    // processing: true,
                    // serverSide: true,
                    data: dataQSO,

                    columns: columnData,
            });
        }

        
        
    </script>
</body>
</html>
