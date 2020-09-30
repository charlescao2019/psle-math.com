<?php
$Qtype_id = 18;
$online_test_id = 24;
$number_of_questions = 200;

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

$common_factor_array = range(2,9);
$distance_factor_array = range(10,20);
$time_factor_array = range(2,10);

$name_array = array('Emma','Olivia','Noah','Liam','Ava','Sophia','William','Mason','James','Isabella','Benjamin','Jacob');

for ($k=1; $k<=$number_of_questions; $k++)
{
// distance = alpha*gamma;
// time = alpha*beta;
	
	$name_index = array_rand($name_array,1);
	$name = $name_array[$name_index];

	$common_factor_array_index = array_rand($common_factor_array,1);
	$alpha = $common_factor_array[$common_factor_array_index];

	$time_factor_array_index = array_rand($time_factor_array,1);
	$beta = $time_factor_array[$time_factor_array_index];

	$distance_factor_array_index = array_rand($distance_factor_array,1);
	$gamma = $distance_factor_array[$distance_factor_array_index];

	$distance = $alpha * $gamma;
	$speed = $alpha * $beta;
	$speed_in_m_per_min = $speed/1000*60;

	$time_fraction_array = fraction2mixed($distance,$speed,1,1);
	$time_quotient = $time_fraction_array[0];
	$time_numerator = $time_fraction_array[1];
	$time_denumerator = $time_fraction_array[2];

	if ($time_denumerator==1)
		continue;

	if ($time_quotient==0)
		continue;

	echo "alpha=$alpha\n\r";
	echo "beta=$beta\n\r";
	echo "gamma=$gamma\n\r";
	echo "distance=$distance\n\r";
	echo "speed=$speed\n\r";
	echo "time_quotient=$time_quotient\n\r";
	echo "time_numerator=$time_numerator\n\r";
	echo "time_denumerator=$time_denumerator\n\r";

    $question = sprintf('%s travelled at the speed of %s km/h for $%s\\\\frac{%s}{%s}$ h. How many km did %s travel?', $name, $speed, $time_quotient, $time_numerator, $time_denumerator, $name);

	$result = $distance;

    $wrong_result_1 = formatDecimal($result);
    $wrong_result_2 = formatDecimal($result+1);
    $wrong_result_3 = formatDecimal($result+2);
    $wrong_result_4 = formatDecimal($result+3);
    $wrong_result_5 = formatDecimal($result+4);
    $wrong_result_6 = formatDecimal($result-1);
    $wrong_result_7 = formatDecimal($result-2);
    $wrong_result_8 = formatDecimal($result-3);
    $wrong_result_9 = formatDecimal($result-4);
    $wrong_result_10 = formatDecimal($result-5);
    $wrong_result_11 = formatDecimal($result+6);
    $wrong_result_12 = formatDecimal($result+8);

    $wrong_number_array = array($wrong_result_1,$wrong_result_2,$wrong_result_3,$wrong_result_4,$wrong_result_5,$wrong_result_6,$wrong_result_7,$wrong_result_8,$wrong_result_9,$wrong_result_10,$wrong_result_11,$wrong_result_12);
    $wrong_number_randIndex = array_rand($wrong_number_array, 3);
    $wrong_number_1 = $wrong_number_array[$wrong_number_randIndex[0]];
    $wrong_number_2 = $wrong_number_array[$wrong_number_randIndex[1]];
    $wrong_number_3 = $wrong_number_array[$wrong_number_randIndex[2]];

    $correct_answer = sprintf('%s', $result);
    $wrong_answer_1 = sprintf('%s', $wrong_number_1);
    $wrong_answer_2 = sprintf('%s', $wrong_number_2);
    $wrong_answer_3 = sprintf('%s', $wrong_number_3);

	$unique_array = array_unique(array($correct_answer, $wrong_answer_1, $wrong_answer_2, $wrong_answer_3));
	if (sizeof($unique_array)<4)
		continue;
	if ($correct_answer<0)
		continue;
	if ($wrong_answer_1<0)
		continue;
	if ($wrong_answer_2<0)
		continue;
	if ($wrong_answer_3<0)
		continue;

	$description = sprintf('<br>
Speed = Distance $\\\\div$ Time<br>
Time = Distance $\\\\div$ Speed<br>
Distance = Speed $\\\\times$ Time<br><br>
Here Speed=%s km/h, Time is $%s\\\\frac{%s}{%s}$ h, and therefore the distance is:
\\\\begin{array}{rcl}
%s\\\\times%s\\\\frac{%s}{%s}&=&%s\\\\times\\\\frac{%s}{%s}\\\\\\\\
&=&%s
\\\\end{array}
km.', $speed, $time_quotient, $time_numerator, $time_denumerator, $speed,$time_quotient, $time_numerator, $time_denumerator,$speed,$time_quotient*$time_denumerator+$time_numerator,$time_denumerator,$distance);

	$title_sql = format_sql($question);
	$correctanstxt_sql = format_sql($correct_answer);
	$description_sql = format_sql($description);

	$sql = sprintf('insert into online_test_questions (QType_id, online_test_id, title, correctanstxt, ans_desc) values (%s, %s, \'%s\', \'%s\', \'%s\')', $Qtype_id, $online_test_id, $title_sql, $correctanstxt_sql, $description_sql);

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

function format_sql($input)
{
	return str_replace("'","''",$input);
}
?>