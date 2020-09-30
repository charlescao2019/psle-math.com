<?php
$QType_id = 14;
$online_test_id = 16;
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
		$random_array = array(2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);
		$randIndex = array_rand($random_array, 2);

		$number = rand(2,9);
		$gcd = $random_array[$randIndex[0]];
		$gcd_2 = $random_array[$randIndex[1]];

		$num = $number*$gcd;
		$den = rand(1,9)*$gcd_2;

		$fraction_array = simplify($num,$den);
		$num = $fraction_array[0];
		$den = $fraction_array[1];

		$corrent_num = $num;
		$corrent_den = $den*$number;
		$fraction_array_correct = fraction2mixed($corrent_num,$corrent_den,1,1);
		$quotient_correct = $fraction_array_correct[0];
		$answer_num_correct = $fraction_array_correct[1];
		$answer_den_correct = $fraction_array_correct[2];

		$random_array = array(-3,-2,-1,1,2,3);
		$randIndex = array_rand($random_array, 3);
		shuffle($randIndex);
		$perterbation_1 = $random_array[$randIndex[0]];
		$perterbation_2 = $random_array[$randIndex[1]];
		$perterbation_3 = $random_array[$randIndex[2]];

		$num_wrong_1 = $corrent_num+$perterbation_1;		
		$num_wrong_2 = $corrent_num+$perterbation_2;		
		$num_wrong_3 = $corrent_num+$perterbation_3;

		if ($den<=1)
			continue;

		if ($num_wrong_1<=0)
			continue;

		if ($num_wrong_2<=0)
			continue;

		if ($num_wrong_3<=0)
			continue;

		$fraction_array_wrong_1 = fraction2mixed($num_wrong_1,$corrent_den,1,1);
		$quotient_wrong_1 = $fraction_array_wrong_1[0];
		$answer_num_wrong_1 = $fraction_array_wrong_1[1];
		$answer_den_wrong_1 = $fraction_array_wrong_1[2];

		$fraction_array_wrong_2 = fraction2mixed($num_wrong_2,$corrent_den,1,1);
		$quotient_wrong_2 = $fraction_array_wrong_2[0];
		$answer_num_wrong_2 = $fraction_array_wrong_2[1];
		$answer_den_wrong_2 = $fraction_array_wrong_2[2];

		$fraction_array_wrong_3 = fraction2mixed($num_wrong_3,$corrent_den,1,1);
		$quotient_wrong_3 = $fraction_array_wrong_3[0];
		$answer_num_wrong_3 = $fraction_array_wrong_3[1];
		$answer_den_wrong_3 = $fraction_array_wrong_3[2];

		if (($answer_num_correct<=0) || ($answer_num_wrong_1<=0) || ($answer_num_wrong_2<=0) || ($answer_num_wrong_3<=0))
			continue;

		$calculation_formula = sprintf('\\\\frac{%s}{%s}\\\\div%s',$num,$den,$number);

		$title = sprintf('Find the value of $%s$. Give your answer as a mixed number in the simplest form.', $calculation_formula);
		$correct_answer = sprintf('$%s\\\\frac{%s}{%s}$', $quotient_correct, $answer_num_correct, $answer_den_correct);
		$wrong_answer_1 = sprintf('$%s\\\\frac{%s}{%s}$', $quotient_wrong_1, $answer_num_wrong_1, $answer_den_wrong_1);
		$wrong_answer_2 = sprintf('$%s\\\\frac{%s}{%s}$', $quotient_wrong_2, $answer_num_wrong_2, $answer_den_wrong_2);
		$wrong_answer_3 = sprintf('$%s\\\\frac{%s}{%s}$', $quotient_wrong_3, $answer_num_wrong_3, $answer_den_wrong_3);

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