<?php

namespace KodeInfo;

use Carbon\Carbon;
use DB;

class ChartsRepo {

    private $config;
    private $filesystem;

    function generate(){

        //Initial Vars
        $this->config = \Config::get('charts');

        $this->filesystem = new \Illuminate\Filesystem\Filesystem();

        //Get data and create options file
        $rows = DB::table("analytics")->get();

        $result = "";

        $start_fake_json = "{
            series: [{
                data: [";

        $result .= $start_fake_json;

        for($i = 0; $i < sizeof($rows); $i++) {

            $dd = Carbon::parse($rows[$i]->created_at);
            $rows[$i]->date = $dd->timestamp * 1000;

            if(is_null($rows[$i]->visits)){
                $result .= "[{$rows[$i]->date},null]";
            }else{
                $result .= "[{$rows[$i]->date},{$rows[$i]->visits}]";
            }

            if ($i != (sizeof($rows) - 1)) {
                $result .= ",";
            }
        }

        $end_fake_json = "],
                type: 'areaspline',
                color: '#3cf',
                threshold: null,
                fillColor:
                {
                   linearGradient: {
                        x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, '#33CCFF'],
                            [1, '#81DCFF']
                        ]
                   }
                }]
                ,xAxis:{
                    labels:{
                        enabled : false
                    }
                }
                ,yAxis:{
                    labels:{
                        style: {
	                        fontSize: '20px',
	                        fontWeight: 'bold'
                        }
                    }
                }
                ,navigator : {
				    enabled : false
			    },rangeSelector:{
                    enabled:false
                },scrollbar : {
                    enabled : false
                },credits: {
                    enabled: false
                }
            }";

        $result .= $end_fake_json;

        if (!empty ($result)) {
            \File::put($this->config['options'],$result);
        }

        //Generate Charts now
        $phantomjs = $this->config['phantomjs'];
        $highcharts_convert = $this->config['highcharts_convert'];
        $infile = $this->config['infile'];
        $constr = $this->config['constr'];
        $outfile = $this->config['outfile'];

        $command = "$phantomjs $highcharts_convert -infile $infile -constr $constr -outfile $outfile -scale 2.5 -width 800";
        system($command, $output);
    }

} 