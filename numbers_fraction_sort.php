<?php
$QType_id = 7;
$online_test_id = 7;
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
    $x = rand(6,9);
    
    $num_1 = $x;
    $den_1 = $x-1;
    
    $num_2 = $x+1;
    $den_2 = $x;

    $num_3 = $x+2;
    $den_3 = $x+1;

    $num_4 = $x+1;
    $den_4 = $x+2;

    $num_5 = $x+4;
    $den_5 = $x+3;
    
    $num_6 = $x+3;
    $den_6 = $x+4;

    $num_7 = $x+3;
    $den_7 = $x+5;

    $num_8 = $x+4;
    $den_8 = $x+6;

    $num_8 = $x+5;
    $den_8 = $x+7;

    $num_array = array(1,2,3,4,5,6,7,8);
    $randIndex = array_rand($num_array,3);
    $selected_fraction_indexes[0] = $num_array[$randIndex[0]];
    $selected_fraction_indexes[1] = $num_array[$randIndex[1]];
    $selected_fraction_indexes[2] = $num_array[$randIndex[2]];

    $selected_fraction_num_1 = ${sprintf('num_%s',$selected_fraction_indexes[0])};
    $selected_fraction_den_1 = ${sprintf('den_%s',$selected_fraction_indexes[0])};
    $selected_fraction_num_2 = ${sprintf('num_%s',$selected_fraction_indexes[1])};
    $selected_fraction_den_2 = ${sprintf('den_%s',$selected_fraction_indexes[1])};
    $selected_fraction_num_3 = ${sprintf('num_%s',$selected_fraction_indexes[2])};
    $selected_fraction_den_3 = ${sprintf('den_%s',$selected_fraction_indexes[2])};

	$mixed_fraction_1 = fraction2mixed($selected_fraction_num_1, $selected_fraction_den_1, rand(0,1));
    $mixed_fraction_2 = fraction2mixed($selected_fraction_num_2, $selected_fraction_den_2, rand(0,1));
    $mixed_fraction_3 = fraction2mixed($selected_fraction_num_3, $selected_fraction_den_3, rand(0,1));

	$original_fraction_1 = sprintf('$%s\\\\frac{%s}{%s}$', $mixed_fraction_1[0],$mixed_fraction_1[1], $mixed_fraction_1[2]);
	$original_fraction_2 = sprintf('$%s\\\\frac{%s}{%s}$', $mixed_fraction_2[0],$mixed_fraction_2[1], $mixed_fraction_2[2]);
	$original_fraction_3 = sprintf('$%s\\\\frac{%s}{%s}$', $mixed_fraction_3[0],$mixed_fraction_3[1], $mixed_fraction_3[2]);

    echo $selected_fraction_num_1."/".$selected_fraction_den_1."\n\r";
    echo $selected_fraction_num_2."/".$selected_fraction_den_2."\n\r";
    echo $selected_fraction_num_3."/".$selected_fraction_den_3."\n\r";

    $associative_array = array(
        sprintf("%8.9f", $mixed_fraction_1[3]) => array($mixed_fraction_1[0],$mixed_fraction_1[1],$mixed_fraction_1[2]),
        sprintf("%8.9f", $mixed_fraction_2[3]) => array($mixed_fraction_2[0],$mixed_fraction_2[1],$mixed_fraction_2[2]),
        sprintf("%8.9f", $mixed_fraction_3[3]) => array($mixed_fraction_3[0],$mixed_fraction_3[1],$mixed_fraction_3[2])
    );

    ksort($associative_array);
    print_r($associative_array);

    $index = 0;
    foreach ($associative_array as $key => $fraction_array)
    {
        ${sprintf('fraction_%s',$index)} = sprintf('$%s\\\\frac{%s}{%s}$', $fraction_array[0], $fraction_array[1], $fraction_array[2]);
        echo $key."\n\r";
        echo ${sprintf('fraction_%s',$index)}."\n\r";
        $index = $index+1;
   }

    if($ascending == 1)
    {
        $correct_answer = sprintf('%s, %s, %s', $fraction_0, $fraction_1, $fraction_2);
        $wrong_answer_1 = sprintf('%s, %s, %s', $fraction_2, $fraction_1, $fraction_0);
        $wrong_answer_2 = sprintf('%s, %s, %s', $fraction_0, $fraction_2, $fraction_1);
        $wrong_answer_3 = sprintf('%s, %s, %s', $fraction_1, $fraction_2, $fraction_0);
    }
    else
    {
        $correct_answer = sprintf('%s, %s, %s', $fraction_2, $fraction_1, $fraction_0);
        $wrong_answer_1 = sprintf('%s, %s, %s', $fraction_0, $fraction_1, $fraction_2);
        $wrong_answer_2 = sprintf('%s, %s, %s', $fraction_2, $fraction_0, $fraction_1);
        $wrong_answer_3 = sprintf('%s, %s, %s', $fraction_1, $fraction_0, $fraction_2);
    }

    if ($ascending == 1)
    {
    	$title = sprintf('Arrange the following fractions from the smallest to the largest: %s, %s, %s?', $original_fraction_1, $original_fraction_2, $original_fraction_3);
    }
    else
    {
    	$title = sprintf('Arrange the following fractions from the largest to the smallest: %s, %s, %s?', $original_fraction_1, $original_fraction_2, $original_fraction_3);
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


function fraction2mixed($num,$den,$mixed) {
    $simplified = simplify($num,$den);
    $num = $simplified[0];
    $den = $simplified[1];

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

?>