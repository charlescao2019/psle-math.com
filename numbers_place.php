<?php
$Qtype_id = 3;
$online_test_id = 3;
$number_of_questions = 100;

for ($k=1; $k<=$number_of_questions; $k++)
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

    $key = array_search($digit, $number_array); 
    unset($number_array[$key]);
    switch($place_shift)
    {
        case '-4':
            $wrong_number_place = 2;
            break;
        case '-3':
            $wrong_number_place = 1;
            break;
        case '-2':
            $wrong_number_place = 0;
            break;
        case '-1':
            $wrong_number_place = -1;
            break;
        case '0':
            $wrong_number_place = -2;
            break;
        case '1':
            $wrong_number_place = -3;
            break;
        case '2':
            $wrong_number_place = -4;
            break;
    }

    if ($wrong_number_place>=0)
    {
        $wrong_number_1 = substr($strdiv[1], $wrong_number_place, 1);
    }
    else
    {
        $wrong_number_1 = substr($strdiv[0], $wrong_number_place, 1);
    }

    $key = array_search($wrong_number_1, $number_array); 
    unset($number_array[$key]);

    $wrong_number_randIndex = array_rand($number_array, 2);
    shuffle($wrong_number_randIndex);
    $wrong_number_2 = $number_array[$wrong_number_randIndex[0]];
    $wrong_number_3 = $number_array[$wrong_number_randIndex[1]];

    $question = sprintf('Which digit in %s is in the %s place?', $number_decimal, $place);

    $correct_answer = sprintf('%s', $digit);
    $wrong_answer_1 = sprintf('%s', $wrong_number_1 );
    $wrong_answer_2 = sprintf('%s', $wrong_number_2 );
    $wrong_answer_3 = sprintf('%s', $wrong_number_3 );

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