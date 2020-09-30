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

for ($k=1; $k<=$number_of_questions; $k++)
{
	$randIndex = array_rand($first_number_array, 1);
	$num = $first_number_array[$randIndex];

	$randIndex = array_rand($second_number_array, 1);
	$den = $second_number_array[$randIndex];

    $question = sprintf('What is value of %s$-$%s?', $num, $den);

    $result = formatDecimal($num-$den);

    $wrong_result_1 = formatDecimal($result*20);
    $wrong_result_2 = formatDecimal($result*10);
    $wrong_result_3 = formatDecimal($result/10);
    $wrong_result_4 = formatDecimal($result/20);
    $wrong_result_5 = formatDecimal($result*5);
    $wrong_result_6 = formatDecimal($result/5);
    $wrong_result_7 = formatDecimal($result*2);
    $wrong_result_8 = formatDecimal($result/2);
    $wrong_result_9 = formatDecimal($result*4);
    $wrong_result_10 = formatDecimal($result/4);
    $wrong_result_11 = formatDecimal($result*8);
    $wrong_result_12 = formatDecimal($result/8);

    $wrong_number_array = array($wrong_result_1,$wrong_result_2,$wrong_result_3,$wrong_result_4,$wrong_result_5,$wrong_result_6,$wrong_result_7,$wrong_result_8,$wrong_result_9,$wrong_result_10,$wrong_result_11,$wrong_result_12);
    $wrong_number_randIndex = array_rand($wrong_number_array, 3);
    $wrong_number_1 = $wrong_number_array[$wrong_number_randIndex[0]];
    $wrong_number_2 = $wrong_number_array[$wrong_number_randIndex[1]];
    $wrong_number_3 = $wrong_number_array[$wrong_number_randIndex[2]];

    $correct_answer = sprintf('%s', $result);
    $wrong_answer_1 = sprintf('%s', $wrong_number_1);
    $wrong_answer_2 = sprintf('%s', $wrong_number_2);
    $wrong_answer_3 = sprintf('%s', $wrong_number_3);

    echo $question;
    echo $correct_answer;
    echo $wrong_answer_1;
    echo $wrong_answer_2;
    echo $wrong_answer_3;

    echo sprintf("(%s) %s\n\r",$k, $question);

	$title_sql = format_sql($question);
	$correctanstxt_sql = format_sql($correct_answer);
				
	$sql = sprintf('insert into online_test_questions (QType_id, online_test_id, title, correctanstxt) values (%s, %s, \'%s\', \'%s\')', $Qtype_id, $online_test_id, $title_sql, $correctanstxt_sql);

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