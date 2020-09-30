<?php
$Qtype_id = 4;
$online_test_id = 4;
$number_of_questions = 100;

for ($k=1; $k<=$number_of_questions; $k++)
{
    $number = mt_rand(100000,9999999);

    $decimal_point_array = array(1,2,3);
    $randIndex = array_rand($decimal_point_array, 1);

    $decimal_point = $decimal_point_array[$randIndex];

    $number_decimal = ($number/10000);
    $number_round = round($number_decimal, $decimal_point);
    $number_round_1 = $number_round-5*pow(10,-1*$decimal_point);
    $number_round_2 = $number_round-4*pow(10,-1*$decimal_point);
    $number_round_3 = $number_round-3*pow(10,-1*$decimal_point);
    $number_round_4 = $number_round-2*pow(10,-1*$decimal_point);
    $number_round_5 = $number_round-1*pow(10,-1*$decimal_point);
    $number_round_6 = $number_round+5*pow(10,-1*$decimal_point);
    $number_round_7 = $number_round+4*pow(10,-1*$decimal_point);
    $number_round_8 = $number_round+3*pow(10,-1*$decimal_point);
    $number_round_9 = $number_round+2*pow(10,-1*$decimal_point);
    $number_round_10 = $number_round+1*pow(10,-1*$decimal_point);
    $wrong_number_array = array($number_round_1,$number_round_2,$number_round_3,$number_round_4,$number_round_5,$number_round_6,$number_round_7,$number_round_8,$number_round_9,$number_round_10);
    $wrong_number_randIndex = array_rand($wrong_number_array, 3);
    $wrong_number_1 = $wrong_number_array[$wrong_number_randIndex[0]];
    $wrong_number_2 = $wrong_number_array[$wrong_number_randIndex[1]];
    $wrong_number_3 = $wrong_number_array[$wrong_number_randIndex[2]];

    $question = sprintf('Round off %s to the %d decimal place(s)', $number_decimal, $decimal_point);

    $correct_answer = sprintf('%s',  number_format($number_round,$decimal_point,'.',''));
    $wrong_answer_1 = sprintf('%s',  number_format($wrong_number_1,$decimal_point,'.',''));
    $wrong_answer_2 = sprintf('%s',  number_format($wrong_number_2,$decimal_point,'.',''));
    $wrong_answer_3 = sprintf('%s',  number_format($wrong_number_3,$decimal_point,'.',''));

	$correctanstxt = $correct_answer;

	$title_sql = format_sql($question);
	$correctanstxt_sql = format_sql($correctanstxt);
				
	$sql = sprintf('insert into online_test_questions (QType_id, online_test_id, title, correctanstxt) values (%s, %s, \'%s\', \'%s\')', $Qtype_id, $online_test_id, $title_sql, $correctanstxt_sql);

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

	$conn->close();
    echo "\n\r";
}
?>