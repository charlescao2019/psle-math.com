<?php
$QType_id = 15;
$online_test_id = 18;
$number_of_questions = 100;
$ascending = rand(0,1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pslemath";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if(1)
{
	$delete_exsting_sql = sprintf('delete from online_test_questions where online_test_id=%s',$online_test_id);

	if ($conn->query($delete_exsting_sql) === TRUE) 
	{
        $conn->query($delete_exsting_sql);
        echo $delete_exsting_sql."\n\r";
    }
}

for ($k=1; $k<=$number_of_questions; $k++)
{
	while (1)
	{
		$random_array = array(2,3,5,7,11,13);
		$randIndex = array_rand($random_array, 4);
		shuffle($randIndex);
		$gcd_1 = $random_array[$randIndex[0]];
		$gcd_2 = $random_array[$randIndex[1]];
		$gain_1 = rand(2,5);
		$gain_2 = rand(6,9);
		
		$number_1 = $gcd_1*$gcd_2*$gain_1;
		$number_2 = $gcd_1*$gcd_2*$gain_2;

		if (($number_1>100) || ($number_2>100))
			continue;

// factorize number_1
		$number_1_array = primeFactors($number_1);
		print_r($number_1_array);

		$N1 = count($number_1_array);
		$total = pow(2, $N1);

		$factors1 = array();
		//Loop through each possible combination  
		for ($i = 0; $i < $total; $i++) {  
			//For each combination check if each bit is set 
			$temp = 1;
			for ($j = 0; $j < $N1; $j++) { 
			   //Is bit $j set in $i? 
				if (pow(2, $j) & $i) 
				{
					$temp = $temp * $number_1_array[$j];
				}
			}
			$factors1[]=$temp;
		}

		print_r($factors1);
		$factors1 = array_unique($factors1);
		sort($factors1);
		print_r($factors1);

// factorize number_2
		$number_2_array = primeFactors($number_2);
		print_r($number_2_array);

		$N2 = count($number_2_array);
		$total = pow(2, $N2);

		$factors2 = array();
		//Loop through each possible combination  
		for ($i = 0; $i < $total; $i++) {  
			//For each combination check if each bit is set 
			$temp = 1;
			for ($j = 0; $j < $N2; $j++) { 
			   //Is bit $j set in $i? 
				if (pow(2, $j) & $i) 
				{
					$temp = $temp * $number_2_array[$j];
				}
			}
			$factors2[]=$temp;
		}

		print_r($factors2);
		$factors2 = array_unique($factors2);
		sort($factors2);
		print_r($factors2);

// common factors on number_1 and number_2
		$gcd = gcd($number_1, $number_2);
		echo "number_1=$number_1\n\r";
		echo "number_2=$number_2\n\r";
		echo "gcd=".$gcd;

		$gcd_array = primeFactors($gcd);
		print_r($gcd_array);

		$N_gcd_array = count($gcd_array);
		$total = pow(2, $N_gcd_array);

		$gcds = array();
		//Loop through each possible combination  
		for ($i = 0; $i < $total; $i++) {  
			//For each combination check if each bit is set 
			$temp = 1;
			for ($j = 0; $j < $N_gcd_array; $j++) { 
			   //Is bit $j set in $i? 
				if (pow(2, $j) & $i) 
				{
					$temp = $temp * $gcd_array[$j];
				}
			}
			$gcds[]=$temp;
		}

		print_r($gcds);
		$gcd_array_unique = array_unique($gcds);
		sort($gcd_array_unique);
		print_r($gcd_array_unique);

		$random_array = array_keys($gcd_array_unique);
		$randIndex = array_rand($random_array, 3);
		shuffle($randIndex);
		$index_1 = $random_array[$randIndex[0]];
		$index_2 = $random_array[$randIndex[1]];
		$index_3 = $random_array[$randIndex[2]];

		$wrong_array_1 = $gcd_array_unique;
		unset($wrong_array_1[$index_1]);
		sort($wrong_array_1); 

		$array3 = array_diff(array(2,3,4,5,6,7,8,9,10), $gcd_array_unique);
		$randIndex = array_rand($array3, 2);
		if (empty($randIndex))
			continue;

		$wrong_array_2 = $gcd_array_unique;
		$wrong_array_2[$index_2] = $array3[$randIndex[0]];
		sort($wrong_array_2);

		$wrong_array_3 = $gcd_array_unique;
		$wrong_array_3[$index_3] = $array3[$randIndex[1]];
		sort($wrong_array_3);

		print_r($wrong_array_1);
		print_r($wrong_array_2);
		print_r($wrong_array_3);

		$correct_answer = print_array($gcd_array_unique);
		$wrong_answer_1 = print_array($wrong_array_1);
		$wrong_answer_2 = print_array($wrong_array_2);
		$wrong_answer_3 = print_array($wrong_array_3);

		$factors_number_1 = print_factors($factors1, $gcd_array_unique);
		$factors_number_2 = print_factors($factors2, $gcd_array_unique);

		$description = sprintf('\\\\begin{array}{rcl}%s&=&%s\\\\\\\\%s&=&%s\\\\end{array}The common factors of $%s$ and $%s$ are $%s$.', $number_1, $factors_number_1, $number_2, $factors_number_2, $number_1, $number_2, $correct_answer);

		$title = sprintf('List all the common factors of %s and %s.', $number_1, $number_2);
		$correct_answer = sprintf('%s', $correct_answer);
		$wrong_answer_1 = sprintf('%s', $wrong_answer_1);
		$wrong_answer_2 = sprintf('%s', $wrong_answer_2);
		$wrong_answer_3 = sprintf('%s', $wrong_answer_3);

		break;
	}

	$title_sql = format_sql($title);
	$correctanstxt_sql = format_sql($correct_answer);
	$description_sql = format_sql($description);
	
	$sql = sprintf('insert into online_test_questions (QType_id, online_test_id, title, correctanstxt, ans_desc) values (%s, %s, \'%s\', \'%s\', \'%s\')', $QType_id, $online_test_id, $title_sql, $correctanstxt_sql, $description_sql);

	if ($conn->query($sql) === TRUE) 
	{
        echo $sql."\n\r";
		$last_id = $conn->insert_id;

		$answer_array = array(1,2,3,4);
		shuffle($answer_array);
	
		for ($l=0; $l<=3; $l++)
		{
			if ($answer_array[$l] == 1)
			{
				$sql = sprintf('insert into online_test_answers (online_test_question_id, answer, is_correct_answer) values (%s, \'%s\', %s)', $last_id, $correct_answer, 1);
				$conn->query($sql);
                echo $sql."\n\r";
			}
			elseif ($answer_array[$l] == 2)
			{
				$sql = sprintf('insert into online_test_answers (online_test_question_id, answer, is_correct_answer) values (%s, \'%s\', %s)', $last_id, $wrong_answer_1, 0);
				$conn->query($sql);
                echo $sql."\n\r";
			}
			elseif ($answer_array[$l] == 3)
			{
				$sql = sprintf('insert into online_test_answers (online_test_question_id, answer, is_correct_answer) values (%s, \'%s\', %s)', $last_id, $wrong_answer_2, 0);
				$conn->query($sql);
                echo $sql."\n\r";
			}
			elseif ($answer_array[$l] == 4)
			{
				$sql = sprintf('insert into online_test_answers (online_test_question_id, answer, is_correct_answer) values (%s, \'%s\', %s)', $last_id, $wrong_answer_3, 0);
				$conn->query($sql);
                echo $sql."\n\r";
			}
		}

	} 
	else 
	{
		echo "Error: " . $sql . "\n\r" . $conn->error;
	}
    echo "\n\r";

}

$conn->close();

function cmp($a, $b)
{
    if ($a[3] == $b[3]) {
        return 0;
    }
    return ($a[3] < $b[3]) ? 1 : -1;
}

function print_array($array_input)
{
	$n = count($array_input);
	$output = '';
	foreach ($array_input as $index => $temp)
	{
		if ($index<($n-1))
		{
			$output .= sprintf('%s, ',$temp);
		}
		else
		{
			$output .= sprintf('%s',$temp);
		}
	}
	return $output;
}

function print_factors($array_input,$common_factors_array)
{
	$n = count($array_input);
	$output = '';
	foreach ($array_input as $index => $temp)
	{
		if ($index<($n-1))
		{
			if (in_array($temp, $common_factors_array))
			{
				$output .= sprintf('\\\\underline{%s}\\\\times',$temp);
			}
			else
			{
				$output .= sprintf('{%s}\\\\times',$temp);
			}
		}
		else
		{
			if (in_array($temp, $common_factors_array))
			{
				$output .= sprintf('\\\\underline{%s}',$temp);
			}
			else
			{
				$output .= sprintf('{%s}',$temp);
			}
		}
	}
	return $output;
}


function fraction2mixed($num,$den,$mixed,$simplify) 
{
	if ($simplify == 1)
	{
	    $simplified = simplify($num,$den);
		$num = $simplified[0];
		$den = $simplified[1];
	}

    if ($mixed==1)
    {
        $quotient = floor($num/$den);
        $remainder = $num%$den;
        if ($quotient == 0)
        {
            $quotient = '';
        }
        $value = $num/$den;
        return Array($quotient, $remainder, $den, $value);
    }
    else
    {
        $value = $num/$den;
        return Array('', $num, $den, $value);
    }
}

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
		echo "a=$a\n\r";
		echo "b=$b\n\r";
		echo "r=$r\n\r";
    }
    return $b;
}

//Find Prime Factors
function primeFactors($num)
{
    //Record the base
    $base = intval($num/2);
    $pf = array();
    $pn = null;
    for($i=2;$i <= $base;$i++) {
        if(isPrime($i, $pn)) {
            $pn[] = $i;
            while($num % $i == 0)
            {
                $pf[] = $i;
                $num = $num/$i;
            }
        }
    }
    return $pf;
}

//Check if a number is prime
function isPrime($num, $pf = null)
{
    if(!is_array($pf)) 
    {
        for($i=2;$i<intval(sqrt($num));$i++) {
            if($num % $i==0) {
                return false;
            }
        }
        return true;
    } else {
        $pfCount = count($pf);
        for($i=0;$i<$pfCount;$i++) {
            if($num % $pf[$i] == 0) {
                return false;
            }
        }
        return true;
    }
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

function format_sql($input)
{
	return str_replace("'","''",$input);
}

function formatDecimal($number)
{
    $stringVal = strval($number); //convert number to string

    $decPosition = strpos($stringVal, ".");

    if ($decPosition !== false) //there is a decimal
    {
        $decPart = substr($stringVal, $decPosition); //grab only the decimal portion

        $result = number_format($stringVal) . rtrim($decPart, ".0");
    }
    else //no decimal to worry about
    {
        $result = number_format($stringVal);
    }
    $result = preg_replace('/[,]/s', '', $result);

    return $result;
}
?>