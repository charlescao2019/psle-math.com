<?php
$Qtype_id = 2;
$online_test_id = 2;
$number_of_questions = 100;

for ($k=1; $k<=$number_of_questions; $k++)
{

    $number = rand(100000,999999);

    $nearest_method = array('ten','hundred','thousand');
    $randIndex = array_rand($nearest_method, 1);

    if ($nearest_method[$randIndex] == 'ten')
    {
        $number_den = 10;
    }
    elseif ($nearest_method[$randIndex] == 'thousand')
    {
            $number_den = 1000;
    }
    else
    {
        $number_den = 100;
    }

    $number_round = round($number/$number_den)*$number_den;

    $number_round_1 = (round($number/10)-1)*10;
    $number_round_2 = (round($number/10)+1)*10;
    $number_round_3 = (round($number/100)-1)*100;
    $number_round_4 = (round($number/100)+1)*100;
    $number_round_5 = (round($number/1000)-1)*1000;
    $number_round_6 = (round($number/1000)+1)*1000;
    $wrong_number_array = array($number_round_1,$number_round_2,$number_round_3,$number_round_4,$number_round_5,$number_round_6);
    $wrong_number_randIndex = array_rand($wrong_number_array, 3);
    $wrong_number_1 = $wrong_number_array[$wrong_number_randIndex[0]];
    $wrong_number_2 = $wrong_number_array[$wrong_number_randIndex[1]];
    $wrong_number_3 = $wrong_number_array[$wrong_number_randIndex[2]];

    $question = sprintf('There are %s people in a city. Express the population to the nearest %s', number_format($number), $nearest_method[$randIndex]);

    $correct_answer = sprintf('%s',  number_format($number_round));
    $wrong_answer_1 = sprintf('%s',  number_format($wrong_number_1));
    $wrong_answer_2 = sprintf('%s',  number_format($wrong_number_2));
    $wrong_answer_3 = sprintf('%s',  number_format($wrong_number_3));

	$title_sql = format_sql($question);
	$correctanstxt_sql = format_sql($correct_answer);
				
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