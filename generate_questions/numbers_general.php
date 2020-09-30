 <?php

	public function fraction_test($test_id=-1){
        $saved_result = session('saved_result');
        unset_session('testresult');
        unset_session('teststatus');

        if(count($saved_result)>3){
            unset_session('saved_result');
        }

        //unset_session('saved_result');
        $data['testdetails']['id']=1;
        $data['testdetails']['name']='Fraction Mental Calculations';
        $data['page']='Fraction Mental Calculations';
        $data['subjectid']=$subjectid=1;
        $subejct = $this->mastermodel->selectquerymultiplewithreturndata('subjects', array());
        if(!empty($subejct)) {
            foreach($subejct as $key=>$val) {
                $criteria = $this->mastermodel->selectquerymultiplewithreturndata('online_test_criteria', array('subjectid'=>$val['id'], 'status'=>1));
                $subejct[$key]['criteria'] = $criteria;
            }
        }
        $data['subjects'] = $subejct;

        $subjectid=1;

        for ($k=0;$k<=9;$k++)
        {
            $gcd_array = array(2,3,4,5,6,7,8,9);
            $gcd_seed = array_rand($gcd_array);
            $gcd = $gcd_array[$gcd_seed];

            do {
                $den_1 = $gcd*rand(1,9);

                do {
                    $n = rand(1,9);

                } while(in_array($n, array( $den_1 )));

                $num_1 = $n;

            } while($num_1>$den_1);

            $fraction_1 = simplify($num_1,$den_1);
            $num_1 = $fraction_1[0];
            $den_1 = $fraction_1[1];

            do {
                do {
                    $n = $gcd*rand(1,9);

                } while(in_array($n, array( $den_1 )));

                $den_2 = $n;

                do {
                    $n = rand(1,9);

                } while(in_array($n, array( $den_2 )));

                $num_2 = $n;

            } while($num_2>$den_2);

            $fraction_2 = simplify($num_2,$den_2);
            $num_2 = $fraction_2[0];
            $den_2 = $fraction_2[1];

            $data['question'][$k]['id'] = $k;
            $data['question'][$k]['online_test_id'] = 1;

            $testing_topic = rand(0,2);
            switch($testing_topic)
            {
                case 0:         //"fraction addition"

                    $data['question'][$k]['title'] = "$\\frac{".$num_1."}{".$den_1."}+\\frac{".$num_2."}{".$den_2."}=?$";
                    $result = simplify($num_1*$den_2+$den_1*$num_2,$den_2*$den_1);
                    break;

                case 1:         //"fraction substraction":

                    if($num_1/$den_1 < $num_2/$den_2)
                    {
                        $num_1_temp = $num_1;
                        $den_1_temp = $den_1;

                        $num_1 = $num_2;
                        $den_1 = $den_2;

                        $num_2 = $num_1_temp;
                        $den_2 = $den_1_temp;
                    }

                    $data['question'][$k]['title'] = "$\\frac{".$num_1."}{".$den_1."}-\\frac{".$num_2."}{".$den_2."}=?$";
                    $result = simplify($num_1*$den_2-$den_1*$num_2,$den_2*$den_1);
                    break;

                case 2:         //"fraction multiplification":

                    $data['question'][$k]['title'] = "$".$den_1."\\times\\frac{".$num_2."}{".$den_2."}=?$";
                    $result = simplify($den_1*$num_2,$den_2);
                    break;
            }

            $wrong_result_1 = simplify($result[0]+rand(1,3),$result[1]);
            $wrong_result_2 = simplify(abs($result[0]-rand(1,3)),$result[1]);

            do {
                if ($result[0] == 0)
                {
                    $perturbed_number =1;
                }
                else
                {
                    $perturbed_number = $result[0]/$result[1]*(1+rand(-19,19)/100);
                }

                $wrong_result_3 = float2rat($perturbed_number);
            } while (($wrong_result_3 === $wrong_result_2) || ($wrong_result_3 === $wrong_result_1) || ($wrong_result_3 === $result));

            if ($result[1] == 1)
            {
                $final_result_text = $result[0];
            }
            else
            {
                $final_result_text = "$\\frac{".$result[0]."}{".$result[1]."}$";
            }

            if ($wrong_result_1[1] == 1)
            {
                $final_wrong_result_text_1 = $wrong_result_1[0];
            }
            else
            {
                $final_wrong_result_text_1 = "$\\frac{".$wrong_result_1[0]."}{".$wrong_result_1[1]."}$";
            }

            if ($wrong_result_2[1] == 1)
            {
                $final_wrong_result_text_2 = $wrong_result_2[0];
            }
            else
            {
                $final_wrong_result_text_2 = "$\\frac{".$wrong_result_2[0]."}{".$wrong_result_2[1]."}$";
            }

            if ($wrong_result_3[1] == 1)
            {
                $final_wrong_result_text_3 = $wrong_result_3[0];
            }
            else
            {
                $final_wrong_result_text_3 = "$\\frac{".$wrong_result_3[0]."}{".$wrong_result_3[1]."}$";
            }

            /*$index = range(0, 3);

			
function simplify($num,$den) {
    $g = gcd($num,$den);
    return Array($num/$g,$den/$g);
}

function gcd($a,$b) {
    $a = abs($a); $b = abs($b);
    if( $a < $b) list($b,$a) = Array($a,$b);
    if( $b == 0) return $a;
    $r = $a % $b;
    while($r > 0) {
        $a = $b;
        $b = $r;
        $r = $a % $b;
    }
    return $b;
}

function float2rat($n, $tolerance = 1.e-2) {
    $h1=1; $h2=0;
    $k1=0; $k2=1;
    $b = 1/$n;
    do {
        $b = 1/$b;
        $a = floor($b);
        $aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
        $aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
        $b = $b-$a;
    } while (abs($n-$h1/$k1) > $n*$tolerance);

    return Array($h1,$k1);
}

?>