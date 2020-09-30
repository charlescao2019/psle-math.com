<?php
$Qtype_id = 17;
$online_test_id = 21;
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

$mark_wrong_array = range(1,2);
$mark_correct_multiple_array = range(2,5);
$number_of_wrong_answer_multiple_array = range(0,10);
$total_score_multiple_array = range(10,20);
$name_array = array('Bill Gates', 'Larry Page','Emma','Olivia','Noah','Liam','Ava','Sophia','William','Mason','James','Isabella','Benjamin','Jacob');

$exam_array = array('Maths Competition', 'English Spelling Bee Competition', 'South-East Asia Maths Olympaid (SEAMO)', 'Singapore Maths Olympaid (SMO)', 'Chinese Multiple Choices Test', 'American Maths Olympiad (AMO)','Australian Maths Trust (AMT)');

for ($k=1; $k<=$number_of_questions; $k++)
{
// alpha = mark for correct answer;
// beta = mark for wrong answer;
// W = number of wrong answer;
// C = number of correct answer;
// T = Total answer
// T = C+W
// W = (alpha *T-S)/(alpha+beta);
// C = (beta*T+S)/(alpha+beta);
// T = W+beta*(W/alpha)+(S/alpha);
	
	$name_index = array_rand($name_array,1);
	$name = $name_array[$name_index];

	$exam_array_index = array_rand($exam_array,1);
	$exam = $exam_array[$exam_array_index];

	$mark_wrong_array_index = array_rand($mark_wrong_array,1);
	$mark_wrong = $mark_wrong_array[$mark_wrong_array_index];

	$mark_correct_multiple_index = array_rand($mark_correct_multiple_array,1);
	$mark_correct_multiple = $mark_correct_multiple_array[$mark_correct_multiple_index];
	$mark_correct = $mark_correct_multiple*$mark_wrong;

	$number_of_wrong_answer_multiple_array_index = array_rand($number_of_wrong_answer_multiple_array,1);
	$number_of_wrong_answer_multiple = $number_of_wrong_answer_multiple_array[$number_of_wrong_answer_multiple_array_index];
	$number_of_wrong_answer = $number_of_wrong_answer_multiple*$mark_correct;
		
	$total_score_multiple_array_index = array_rand($total_score_multiple_array,1);
	$total_score_multiple = $total_score_multiple_array[$total_score_multiple_array_index];
	$total_score = $total_score_multiple*$mark_correct;

	$total_answer = (1+$mark_wrong/$mark_correct)*$number_of_wrong_answer+$total_score/$mark_correct;
	$number_of_correct_answer = $total_answer - $number_of_wrong_answer;

	echo "name=$name\n\r";
	echo "mark_correct=$mark_correct\n\r";
	echo "mark_wrong=$mark_wrong\n\r";
	echo "total_answer=$total_answer\n\r";
	echo "number_of_wrong_answer=$number_of_wrong_answer\n\r";
	echo "number_of_correct_answer=$number_of_correct_answer\n\r";
	echo "total_score=$total_score\n\r";

    $question = sprintf('%s attended a %s. %s answered all %s questions. For each correct answer, %s will get %s marks. However, for each wrong answer, %s will be deducted by %s mark(s). If %s scored %s marks in total, how many questions did %s answer correctly?', $name, $exam, $name, $total_answer, $name, $mark_correct, $name, $mark_wrong, $name, $total_score, $name);

	$result = $number_of_correct_answer;

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

	$description = sprintf('<b><u>Method 1: Method of Assumption</u></b><br>
Assume %s answered all the %s questions correctly, and each correct answer has %s marks, then %s should score %s marks,
\\\\begin{array}{rcl}
%s\\\\times%s=%s.
\\\\end{array}
For every question %s answered wrongly, %s will lose,
\\\\begin{array}{rcl}
%s+%s=%s.
\\\\end{array}
marks.
<br>
Since %s only scored %s marks, therefore %s totally lost 
\\\\begin{array}{rcl}
%s-%s=%s.
\\\\end{array}
marks due to the questions answered wrongly.
<br>
Therefore the number of wrong answers is
\\\\begin{array}{rcl}
%s\\\\div%s=%s.
\\\\end{array}
The number of correct answers is
\\\\begin{array}{rcl}
%s-%s=%s.
\\\\end{array}
', $name, $total_answer, $mark_correct, $name,  $total_answer*$mark_correct, $total_answer, $mark_correct, $total_answer*$mark_correct,$name, $name,$mark_correct,$mark_wrong,$mark_correct+$mark_wrong, $name,$total_score, $name, $total_answer*$mark_correct, $total_score, $total_answer*$mark_correct-$total_score,$total_answer*$mark_correct-$total_score,$mark_correct+$mark_wrong,$number_of_wrong_answer,$total_answer, $number_of_wrong_answer, $number_of_correct_answer);

	if($mark_wrong == 1)
	{
		$description .= sprintf('<b><u>Method 2: Simple Algebra</u></b><br>
Let the number of correct answers be $C$, and the number of wrong answer be $W$.<br>
Since the total number of questions is %s, we have,
\\\\begin{array}{rcl}
C+W=%s.\\\\tag{1}
\\\\end{array}
Since for every correct answer gives %s marks, and every wrong aswer deducts %s mark(s), the total score is,
\\\\begin{array}{rcl}
%sC-%sW=%s.\\\\tag{2}
\\\\end{array}
We can eliminate $W$ by summing up the left side of (1) and (2), and also summing up the right side of (1) and (2),
\\\\begin{array}{rcl}
%sC=%s.\\\\tag{4}
\\\\end{array}
Therefore the number of correct answer is
\\\\begin{array}{rcl}
C&=&%s\\\\div%s\\\\\\\\
&=&%s.\\\\tag{5}
\\\\end{array}
From (4) and (1) we have the number of wrong answer
\\\\begin{array}{rcl}
W&=&%s-%s\\\\\\\\
&=&%s.\\\\tag{6}
\\\\end{array}
', $total_answer, $total_answer, $mark_correct, $mark_wrong, $mark_correct, $mark_wrong, $total_score, $mark_wrong+$mark_correct, $mark_wrong*$total_answer+$total_score,$mark_wrong*$total_answer+$total_score, $mark_wrong+$mark_correct, $number_of_correct_answer, $total_answer, $number_of_correct_answer, $number_of_wrong_answer);
	}
	else
	{
			$description .= sprintf('<b><u>Method 2: Simple Algebra</u></b><br>
Let the number of correct answers be $C$, and the number of wrong answer be $W$.<br>
Since the total number of questions is %s, we have,
\\\\begin{array}{rcl}
C+W=%s.\\\\tag{1}
\\\\end{array}
Since for every correct answer gives %s marks, and every wrong aswer deducts %s mark(s), the total score is,
\\\\begin{array}{rcl}
%sC-%sW=%s.\\\\tag{2}
\\\\end{array}
Multiplying both sides of (1) by %s we have,
\\\\begin{array}{rcl}
%sC+%sW=%s.\\\\tag{3}
\\\\end{array}
We can eliminate $W$ by summing up the left side of (2) and (3), and also summing up the right side of (2) and (3),
\\\\begin{array}{rcl}
%sC=%s.\\\\tag{4}
\\\\end{array}
Therefore the number of correct answer is
\\\\begin{array}{rcl}
C&=&%s\\\\div%s\\\\\\\\
&=&%s.\\\\tag{5}
\\\\end{array}
From (5) and (1) we have the number of wrong answer
\\\\begin{array}{rcl}
W&=&%s-%s\\\\\\\\
&=&%s.\\\\tag{6}
\\\\end{array}
', $total_answer, $total_answer, $mark_correct, $mark_wrong, $mark_correct, $mark_wrong, $total_score, $mark_correct, $mark_wrong, $mark_wrong, $mark_wrong*$total_answer,$mark_wrong+$mark_correct, $mark_wrong*$total_answer+$total_score,$mark_wrong*$total_answer+$total_score, $mark_wrong+$mark_correct, $number_of_correct_answer, $total_answer, $number_of_correct_answer, $number_of_wrong_answer);
	}

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