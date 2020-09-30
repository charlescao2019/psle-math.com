<?php
$Qtype_id = 5;
$online_test_id = 5;
$number_of_questions = 100;

$num_array = array(10,20,40);
$den_array = array(20,40,50,200,400,500,800,2000,4000,8000);

for ($k=1; $k<$number_of_questions; $k++)
{
    if(rand(0,1)==0)
    {
        $randIndex = array_rand($num_array, 1);
        $num = $num_array[$randIndex];

        $randIndex = array_rand($den_array, 1);
        $den = $den_array[$randIndex];
    }
    else
    {
        $randIndex = array_rand($num_array, 1);
        $den = $num_array[$randIndex];

        $randIndex = array_rand($den_array, 1);
        $num = $den_array[$randIndex];
    }

    $question = sprintf('What is value of %s$\div$%s?', $num, $den);

    $result = formatDecimal($num/$den);

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