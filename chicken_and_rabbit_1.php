<?php
$Qtype_id = 16;
$online_test_id = 20;
$number_of_questions = 100;

$first_number_array = array_merge(array(10,20,30,40,50,60,70,80,90,2,3,4,5,6,7,8,9), range(0.01,9.99,0.01));
$second_number_array = array_merge(range(0.01,0.99,0.01));

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

$N_total_legs_array = range(56,280,4);
$two_legs_animal_array = array('Chicken','Duck','Goose');
$four_legs_animal_array = array('Horse', 'Rabbit','Pig');
for ($k=1; $k<=min(sizeof($N_total_legs_array)-1,$number_of_questions); $k++)
{
	$N_total_legs = $N_total_legs_array[$k];
	$N_total_array = range($N_total_legs/4+1,$N_total_legs/2-1);
	$N_total_index = array_rand($N_total_array,1);
	$N_total = $N_total_array[$N_total_index];

	$four_legs_animal_index = array_rand($four_legs_animal_array,1);
	$four_legs_animal = $four_legs_animal_array[$four_legs_animal_index];
	$two_legs_animal_index = array_rand($two_legs_animal_array,1);
	$two_legs_animal = $two_legs_animal_array[$two_legs_animal_index];

	$four_legs_animal_variable = substr($four_legs_animal,0,1);
	$two_legs_animal_variable = substr($two_legs_animal,0,1);

	echo "N_total_legs=$N_total_legs\n\r";
	echo "N_total=$N_total\n\r";
	print_r($N_total_array);

	$N_rabbit = $N_total_legs/2-$N_total;
	$N_chicken = $N_total - $N_rabbit;
	
	if (rand(0,1) == 1)
	{
		$animal = $four_legs_animal;
		$result = $N_rabbit;
	}
	else
	{
		$animal = $two_legs_animal;
		$result = $N_chicken;
	}

    $question = sprintf('There are a total of %s %ss and %ss on a farm. Given that the total number of legs on the farm is %s, find the number of %ss', $N_total, $two_legs_animal, $four_legs_animal, $N_total_legs, $animal);

    $wrong_result_1 = formatDecimal($result+5);
    $wrong_result_2 = formatDecimal($result+4);
    $wrong_result_3 = formatDecimal($result+3);
    $wrong_result_4 = formatDecimal($result+2);
    $wrong_result_5 = formatDecimal($result+1);
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

echo "N_total=$N_total<br>";
	$description1 = sprintf('<b><u>Method 1: Method of Assumption</u></b><br>
Assume all were %ss, there would be only
\\\\begin{array}{rcl}
%s\\\\times2=%s
\\\\end{array}
legs.<br><br>
However, there are actually %s legs.<br>
Therefore, there are
\\\\begin{array}{rcl}
%s-%s=%s
\\\\end{array}
extra legs.<br><br>
If we replace one %s by one %s, we will have
\\\\begin{array}{rcl}
4-2=2
\\\\end{array}
more legs.<br><br>
Therefore, the number of %ss is
\\\\begin{array}{rcl}
%s\\\\div2=%s.
\\\\end{array}
The number of %ss is
\\\\begin{array}{rcl}
%s-%s=%s.
\\\\end{array}', $two_legs_animal, $N_total, $N_total*2, $N_total_legs, $N_total_legs, $N_total*2, $N_total_legs-$N_total*2, $two_legs_animal, $four_legs_animal, $four_legs_animal, ($N_total_legs-$N_total*2), ($N_total_legs-$N_total*2)/2, $two_legs_animal, $N_total, ($N_total_legs-$N_total*2)/2, $N_total-($N_total_legs-$N_total*2)/2);

	$description2 = sprintf('<b><u>Method 2: Method of Assumption</u></b><br>
Assume all were %ss, there would be only
\\\\begin{array}{rcl}
%s\\\\times4=%s
\\\\end{array}
legs.<br><br>
However, there are actually only %s legs.<br>
Therefore, there are a shortage of
\\\\begin{array}{rcl}
%s-%s=%s
\\\\end{array}
legs.<br><br>
If we replace one %s by one %s, we will have
\\\\begin{array}{rcl}
4-2=2
\\\\end{array}
less legs.<br><br>
Therefore, the number of %ss is
\\\\begin{array}{rcl}
%s\\\\div2=%s.
\\\\end{array}
The number of %ss is
\\\\begin{array}{rcl}
%s-%s=%s.
\\\\end{array}', $four_legs_animal, $N_total, $N_total*4, $N_total_legs, $N_total*4, $N_total_legs, $N_total*4-$N_total_legs, $four_legs_animal, $two_legs_animal, $two_legs_animal, ($N_total*4-$N_total_legs), ($N_total*4-$N_total_legs)/2, $four_legs_animal, $N_total, ($N_total*4-$N_total_legs)/2, $N_total-($N_total*4-$N_total_legs)/2);

	$description3 = sprintf('<b><u>Method 3: Simple Algebra</u></b><br>
Let the number of %ss be \$%s\$, and let the number of %ss be \$%s\$.<br>
We have<br>
\\\\begin{array}{rcl}
%s+%s&=&%s\\\\tag{1}
\\\\end{array}
\\\\begin{array}{rcl}
2%s+4%s&=&%s\\\\tag{2}
\\\\end{array}

Multiplying boths sides of (1) by 4 we have,
\\\\begin{array}{rcl}
4%s+4%s&=&%s\\\\tag{3}
\\\\end{array}

Substracting the left side of (2) from the left side of (3), and substracting the right side of (1) from the right side of (3) we have, 
\\\\begin{array}{rcl}
2%s&=&%s-%s\\\\\\\\
&=&%s
\\\\end{array}

Therefore
\\\\begin{array}{rcl}
%s&=&%s\\\\div2\\\\\\\\
&=&%s
\\\\end{array}

and from above and (1) we have
\\\\begin{array}{rcl}
%s&=&%s-%s\\\\\\\\
&=&%s.
\\\\end{array}
', $four_legs_animal, $four_legs_animal_variable, $two_legs_animal, $two_legs_animal_variable, $two_legs_animal_variable, $four_legs_animal_variable, $N_total, $two_legs_animal_variable, $four_legs_animal_variable, $N_total_legs, $two_legs_animal_variable, $four_legs_animal_variable, 4*$N_total, $two_legs_animal_variable, 4*$N_total, $N_total_legs, 4*$N_total-$N_total_legs, $two_legs_animal_variable, 4*$N_total-$N_total_legs, (4*$N_total-$N_total_legs)/2, $four_legs_animal_variable, $N_total, (4*$N_total-$N_total_legs)/2, $N_total-(4*$N_total-$N_total_legs)/2);

$description = $description1.$description2.$description3;

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