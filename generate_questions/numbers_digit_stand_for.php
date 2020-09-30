<?php
$Qtype_id = 1;
$online_test_id = 1;
$number_of_questions = 100;

for ($k=1; $k<$number_of_questions; $k++)
{

	$number_array = array(1,2,3,4,5,6,7,8,9);
	shuffle($number_array);
	$randIndex = array_rand($number_array, 7);
	$number_decimal = $number_array[$randIndex[0]]*1000 + $number_array[$randIndex[1]]*100 + $number_array[$randIndex[2]]*10 + $number_array[$randIndex[3]] + $number_array[$randIndex[4]]/10 + $number_array[$randIndex[5]]/100 + $number_array[$randIndex[6]]/1000;

	$place_array = array('thousand','hundred','ten','one','tenth','hundredth','thousandth');
	$place_shift_array = array(-4,-3,-2,-1,0,1,2);
	$randIndex = array_rand($place_array, 1);
	$place = $place_array[$randIndex];
	$place_shift = $place_shift_array[$randIndex];

	$strdiv = explode('.', $number_decimal);
	if ($place_shift>=0)
	{
		$digit = substr($strdiv[1], $place_shift, 1);
	}
	else
	{
		$digit = substr($strdiv[0], $place_shift, 1);
	}

	switch($randIndex)
	{
		case '0':
			$wrong_randIndex = 6;
			break;
		case '1':
			$wrong_randIndex = 5;
			break;
		case '2':
			$wrong_randIndex = 4;
			break;
		case '3':
			$wrong_randIndex = 4;
			break;
		case '4':
			$wrong_randIndex = 2;
			break;
		case '5':
			$wrong_randIndex = 1;
			break;
		case '6':
			$wrong_randIndex = 0;
			break;
	}

	$place_wrong = $place_array[$wrong_randIndex];
	$wrong_place_1 = $place_wrong;

	$key = array_search($place, $place_array); 
	unset($place_array[$key]);
	$key = array_search($place_wrong, $place_array); 
	unset($place_array[$key]);

	$wrong_number_randIndex = array_rand($place_array, 2);
	shuffle($wrong_number_randIndex);
	$wrong_place_2 = $place_array[$wrong_number_randIndex[0]];
	$wrong_place_3 = $place_array[$wrong_number_randIndex[1]];

	if ($digit>1)
	{
		$correct_answer = sprintf('%s %ss', $digit, $place);
		$wrong_answer_1 = sprintf('%s %ss', $digit, $place_wrong);
		$wrong_answer_2 = sprintf('%s %ss', $digit, $wrong_place_2);
		$wrong_answer_3 = sprintf('%s %ss', $digit, $wrong_place_3);
	}
	else
	{
		$correct_answer = sprintf('%s %s', $digit, $place);
		$wrong_answer_1 = sprintf('%s %s', $digit, $place_wrong);
		$wrong_answer_2 = sprintf('%s %s', $digit, $wrong_place_2);
		$wrong_answer_3 = sprintf('%s %s', $digit, $wrong_place_3);
	}

	$title = sprintf('In %s, what does digit %s stand for?', $number_decimal, $digit);
    echo sprintf("(%s) %s\n\r",$k, $title);

	$correctanstxt = $correct_answer;

	$title_sql = format_sql($title);
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