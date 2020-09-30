<?php
$QType_id = 10;
$online_test_id = 10;
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
        echo sprintf("Deleted existing online_test_questions where QType_id=%s\n\r",$QType_id);
    }
}

for ($k=1; $k<=$number_of_questions; $k++)
{
	while (1)
	{
		$decimal_num = rand(11,100);
		$decimal_den_array = array(5,8,10);
		$randIndex = array_rand($decimal_den_array, 1);
		$decimal_den = $decimal_den_array[$randIndex];

		if ($decimal_num % $decimal_den == 0)
			continue;

		$decimal_number = $decimal_num/$decimal_den;

		$whole_number_correct = floor($decimal_num/$decimal_den);
		$decimal_num_correct = $decimal_num - $whole_number_correct*$decimal_den;
		$fraction_array_correct = simplify($decimal_num_correct, $decimal_den);
		$decimal_num_correct = $fraction_array_correct[0];
		$decimal_den_correct = $fraction_array_correct[1];

		$decimal_num_wrong_1 = $decimal_num+1;
		$whole_number_wrong_1 = floor($decimal_num_wrong_1/$decimal_den);
		echo "decimal_num_wrong_1=$decimal_num_wrong_1\n\r";
		echo "whole_number_wrong_1=$whole_number_wrong_1\n\r";
		$decimal_num_wrong_1 = $decimal_num_wrong_1 - $whole_number_wrong_1*$decimal_den;
		if ($decimal_num_wrong_1 == 0)
			continue;
		echo "decimal_num_wrong_1=$decimal_num_wrong_1\n\r";
		$fraction_array_wrong_1 = simplify($decimal_num_wrong_1, $decimal_den);
		$decimal_num_wrong_1 = $fraction_array_wrong_1[0];
		$decimal_den_wrong_1 = $fraction_array_wrong_1[1];
		echo "decimal_num_wrong_1=$decimal_num_wrong_1\n\r";
		echo "decimal_den_wrong_1=$decimal_den_wrong_1\n\r";

		$decimal_num_wrong_2 = $decimal_num-1;
		$whole_number_wrong_2 = floor($decimal_num_wrong_2/$decimal_den);
		echo "decimal_num_wrong_2=$decimal_num_wrong_2\n\r";
		echo "whole_number_wrong_2=$whole_number_wrong_2\n\r";
		$decimal_num_wrong_2 = $decimal_num_wrong_2 - $whole_number_wrong_2*$decimal_den;
		if ($decimal_num_wrong_2 == 0)
			continue;
		echo "decimal_num_wrong_2=$decimal_num_wrong_2\n\r";
		$fraction_array_wrong_2 = simplify($decimal_num_wrong_2, $decimal_den);
		$decimal_num_wrong_2 = $fraction_array_wrong_2[0];
		$decimal_den_wrong_2 = $fraction_array_wrong_2[1];
		echo "decimal_num_wrong_2=$decimal_num_wrong_2\n\r";
		echo "decimal_den_wrong_2=$decimal_den_wrong_2\n\r";

		$decimal_num_wrong_3 = $decimal_num-2;
		$whole_number_wrong_3 = floor($decimal_num_wrong_3/$decimal_den);
		echo "decimal_num_wrong_3=$decimal_num_wrong_3\n\r";
		echo "whole_number_wrong_3=$whole_number_wrong_3\n\r";
		$decimal_num_wrong_3 = $decimal_num_wrong_3 - $whole_number_wrong_3*$decimal_den;
		if ($decimal_num_wrong_3 == 0)
			continue;
		echo "decimal_num_wrong_3=$decimal_num_wrong_3\n\r";
		$fraction_array_wrong_3 = simplify($decimal_num_wrong_3, $decimal_den);
		$decimal_num_wrong_3 = $fraction_array_wrong_3[0];
		$decimal_den_wrong_3 = $fraction_array_wrong_3[1];
		echo "decimal_num_wrong_3=$decimal_num_wrong_3\n\r";
		echo "decimal_den_wrong_3=$decimal_den_wrong_3\n\r";

		$title = sprintf('Express %s as a mixed number in its simplest form.', $decimal_number);
		$correct_answer = sprintf('$%s\\\\frac{%s}{%s}$', $whole_number_correct, $decimal_num_correct, $decimal_den_correct);
		$wrong_answer_1 = sprintf('$%s\\\\frac{%s}{%s}$', $whole_number_wrong_1, $decimal_num_wrong_1, $decimal_den_wrong_1);
		$wrong_answer_2 = sprintf('$%s\\\\frac{%s}{%s}$', $whole_number_wrong_2, $decimal_num_wrong_2, $decimal_den_wrong_2);
		$wrong_answer_3 = sprintf('$%s\\\\frac{%s}{%s}$', $whole_number_wrong_3, $decimal_num_wrong_3, $decimal_den_wrong_3);

		break;
	}

	$title_sql = format_sql($title);
	$correctanstxt_sql = format_sql($correct_answer);
				
	$sql = sprintf('insert into online_test_questions (QType_id, online_test_id, title, correctanstxt) values (%s, %s, \'%s\', \'%s\')', $QType_id, $online_test_id, $title_sql, $correctanstxt_sql);

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