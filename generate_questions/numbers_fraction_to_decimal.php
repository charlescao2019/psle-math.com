<?php
$QType_id = 9;
$online_test_id = 9;
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
	$delete_exsting_sql = sprintf('delete from online_test_questions where QType_id=%s',$QType_id);

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
		$num = rand(5,10);
		$den_array = array(2,4,5,8,10,16);
		$randIndex = array_rand($den_array,1);
		$den = $den_array[$randIndex];

		if ($num % $den == 0)
		{
			continue;
		}

		$common = rand(2,5);

		echo "num=$num\n\r";
		echo "den=$den\n\r";
		echo "common=$common\n\r";

		$num = $common * $num;
		$den = $common * $den;
		echo "num=$num\n\r";
		echo "den=$den\n\r";

		$mixed_fraction_original = fraction2mixed($num, $den, rand(0,1), 0);
		print_r($mixed_fraction_original);

		$value = $mixed_fraction_original[3];
		$wrong_1 = $value + rand(1,2)/10;
		$wrong_2 = $value + rand(3,5)/10;
		$wrong_3 = $value - rand(1,5)/10;

		if ($wrong_3>0)
			break;
	}

	$original_fraction = sprintf('$%s\\\\frac{%s}{%s}$', $mixed_fraction_original[0],$mixed_fraction_original[1], $mixed_fraction_original[2]);

    $title = sprintf('Express %s as a decimal.', $original_fraction);
	$correct_answer = sprintf('%s', $value);
	$wrong_answer_1 = sprintf('%s', $wrong_1);
	$wrong_answer_2 = sprintf('%s', $wrong_2);
	$wrong_answer_3 = sprintf('%s', $wrong_3);

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