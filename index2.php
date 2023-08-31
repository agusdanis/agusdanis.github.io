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
    <h1>ADIF Parser</h1>
    <input id="inputfile" type="file" accept=".cbr, .json, .adi">
    <div id="adif_parse"></div>

    
    <textarea name="output" id="output" cols="30" rows="5"></textarea>
    <button id="btnSaveFile">Save to file</button>
    <table id="tabelAdif" class="table table-striped" style="width:100%"></table>
    
    <footer>by YG1BSH</footer>

    <script type="text/javascript">
        // const fs = require('fs');
        // fs.readFile('8B24D.adi', (err, inputD) => {
        // if (err) throw err;
        //     console.log(inputD.toString());
        // })

        let a = 0;
        let posCursor = 0; 
        let $dataAdif = [];
        let judulKolom = {};
        let adiFile = 'LOGLIST.adi';
        const btnSave = document.getElementById('btnSaveFile');
        btnSave.style.visibility = "hidden";
        

        document.getElementById('inputfile')
                .addEventListener('change', function() {
                    
                judulKolom = [];
                let textInput = '';
                let isEoh = 0;
                let isContent = 0;
                let pos = 0;
                var fr=new FileReader();
                fr.onload=function(){
                    textInput = fr.result;
                    document.getElementById('output')
                            .textContent=textInput;
                    isEoh = textInput.toUpperCase().search("EOH");
                    isEoh > 0 ? isEoh += 1 : "";
                    isEoh < textInput.length ? isContent = 1 : "";
                    isContent == 1 ? pos = isEoh : "";
                    console.log(isEoh+' '+isContent);            
                    parseInputFile(textInput, pos);
                }
                
                fr.readAsText(this.files[0]);
                // parseInputFile(textInput);
        })

        btnSave.addEventListener('click', function(){
            // alert('button save klik');
            let jsonData = JSON.stringify(populateFromArray($dataAdif));
            writeJSONFile(jsonData, 'adif.json');
        })

        populateFromArray = (array) => {
            var output = {};
            array.forEach(function(item, index) {
                if (!item) return;
                if (Array.isArray(item)) {
                output[index] = populateFromArray(item);
                } else {
                output[index] = item;
                }
            });
            return output;
        }

        parseInputFile = (textValid, pos) => {
            // let record = [];
            // console.log('posCursur awal: ' + pos);
            // getRecord(textValid, pos);
            posCursor = pos;
            let i = 0;
            console.log('posCursur awal: ' + posCursor);
            while (posCursor+7 < textValid.length) {
                $dataAdif[i] = getRecord(textValid, posCursor); // tampung dalam variabel sebagai record
                // $dataAdif_obj.push(getRecord(textValid, posCursor));
                // console.log(getRecord(textValid, posCursor));
                // console.log('posCursor akhir: ' + posCursor + ' / ' + textValid.length);
                i++;
            }
            // let jsonData = $dataAdif;
            
            // console.log('dataAdif: ', JSON.stringify(populateFromArray($dataAdif)));

            // console.log('judul: ', judulKolom);
            btnSave.style.visibility = "visible";

            // writeJSONFile(jsonData, 'adif.json');
            showAdifData($dataAdif, judulKolom);

            // console.log(getRecord(textValid, posCursor));
            // console.log(getRecord(textValid, posCursor));
            // console.log(getRecord(textValid, posCursor));
            // console.log('posCursor akhir: ' + posCursor + ' / ' + textValid.length);
        }

        writeJSONFile = (text, fileName) => {
            let textFile = null;
            
            const makeTextFile = (text) => {
                const data = new Blob([text], {
                type: 'text/plain',
                });
                if (textFile !== null) {
                    window.URL.revokeObjectURL(textFile);
                }
                textFile = window.URL.createObjectURL(data);
                return textFile;
            };
            const link = document.createElement('a');
            link.setAttribute('download', fileName);
            link.href = makeTextFile(text);
            link.click();
        }

        // get rodData
        getRecord = (textValid, pos) => {
            if (pos+5 > textValid.length) {
                return [];
            }

            const end = textValid.toUpperCase().indexOf("EOR", pos+5);
            posCursor = end;
            // console.log('posCursor = ' + pos+'/'+posCursor);
            if (end < 0) {
                return [];
            } else {
                const record = textValid.substring(pos+5, end-1);
                let rowData = recordToArray(record); 
                // console.log('rowData: ', JSON.stringify(rowData));
                return rowData;
            }
        }

        recordToArray = (record) => {
            
            let kolom = {};
            for(let a = 0; a < record.length; a++)
		    {
                if(record[a] == '<') //find the start of the tag
			    {   
                    
                    let tag_name = "";
                    let value = "";
                    let len_str = "";
                    let len = 0;
                    a++; //go past the <

                    while(record[a] != ':') //get the tag
                    {
                        tag_name = tag_name.concat(record[a]); //append this char to the tag name
                        a++;
                    };

                    if (!judulKolom.includes(tag_name)) {
                        judulKolom.push(tag_name);
                        // console.log('tag_name: ', judulKolom);
                    }

                    a++; //iterate past the colon
                    while((record[a] != '>') && (record[a] != ':'))
                    {
                        len_str = len_str.concat(record[a]);
                        a++;
                        
                    };
                    // console.log('len_str: ' + len_str);


                    if(record[a] == ':')
                    {
                        while(record[a] != '>')
                        {
                            a++;
                        };
                    };

                    a++; //iterate over the >
                    len = parseInt(len_str);
                    // console.log('len: ' + len);
                    while(len > 0)
                    {
                        value = value.concat(record[a]);
                        len--;
                        a++;
                    };
                    kolom[tag_name] = value;
                    // console.log(kolom);
                }
                
            }

            return kolom;
        }

        addHeaderTable = (judul) => {
            let html ='<thead>' + '<tr>';
            for (let i=0; i<=judul.length; i++) {
                html += '<th></th>';
            }                        
            html += '</tr>' + '</thead>' ;
            $('#tabelAdif').html(html);
        }

        showAdifData = (dataAdif, judul) => {
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
                    data: dataAdif,

                    columns: columnData,

                        // { "title": "BAND", "data": "BAND", "defaultContent": ""},
                        // { "title": "CALL", "data": "CALL", "defaultContent": ""},
                        // { "title": "GRIDSQUARE", "data": "GRIDSQUARE", "defaultContent": ""},
                        // { "title": "FREQ", "data": "FREQ", "defaultContent": ""},
                        // { "title": "MODE", "data": "MODE", "defaultContent": ""},
                        // { "title": "MY_GRIDSQUARE", "data": "MY_GRIDSQUARE", "defaultContent": ""},
                        // { "title": "QSO_DATE", "data": "QSO_DATE", "defaultContent": ""},
                        // { "title": "QSO_DATE_OFF", "data": "QSO_DATE_OFF", "defaultContent": ""},
                        // { "title": "RST_RCVD", "data": "RST_RCVD", "defaultContent": ""},
                        // { "title": "RST_SENT", "data": "RST_SENT"},
                        // { "title": "STATION_CALLSIGN", "data": "STATION_CALLSIGN", "defaultContent": ""},
                        // { "title": "TIME_OFF", "data": "TIME_OFF", "defaultContent": ""},
                        // { "title": "TIME_ON", "data": "TIME_ON", "defaultContent": ""},
                        
                        // { "data": "nomor",
                        //     render: function(data, type, row) {
                        //         return "<strong>"+row.nomor+"</strong>";
                        //     }
                        // },
                        // { "data": "TDOH_TANGGAL"},
                        // { "data": "GUDANG_ASAL"},
                        // { "data": "GUDANG_TUJUAN"},
                        // { "data": null, className: "text-right", "title": "created_by",  
                        //         render: function(data, type, row) {
                        //             let html = '';
                        //             html += 
                        //             '<span class="badge badge-warning" style="text-align: left">' + data['TDOH_MUSR_USERNAME'] + '</span> ';
                        //             return html;
                        //         },
                        // },
                    // ],
                    // order: [[0, 'desc']],
                    // paging: true,
                    // searching: true,
                    // fixedHeader: true,
            });
        }

        
        
    </script>
</body>
</html>
