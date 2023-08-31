<?php
include 'adif_parser.php';

$p = new ADIF_Parser;
$p->load_from_file("8B24D.adi");
$p->initialize();

echo "call;gridsquare;mode;rst_sent;rst_rcvd;qso_date;qso_time;qso_date_off;time_off;band;freq;station_callsign;my_gridsquare<br>";
while($record = $p->get_record())
{
	// print_r($record);
    if(count($record) == 0)
	{
		break;
	};
	echo $record["call"].";".$record["gridsquare"].";".$record["mode"].";".$record["rst_sent"].";".$record["rst_rcvd"].";".$record["qso_date"].";".$record["time_on"].";".$record["qso_date_off"].";".$record["time_off"].";".$record["band"].";".$record["freq"].";".$record["station_callsign"].";".$record["my_gridsquare"]."<br>";
    // echo $record."<br>";
};

// print_r($record);
?>