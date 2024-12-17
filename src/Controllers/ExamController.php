<?php 

namespace App\Controllers;

use App\Models\Question;
use App\Models\UserAnswer;
use FPDF;


class ExamController extends BaseController
{
    public function examinees()
{
    $userAnswerObj = new \App\Models\UserAnswer();

    // Fetch all exam attempts sorted by date descending
    $attempts = $userAnswerObj->getAllAttempts();

    // Render the list view
    return $this->render('examinees', ['attempts' => $attempts]);
}
    
    public function exportAttemptToPDF($attempt_id)
{
    $userAnswerObj = new \App\Models\UserAnswer();

    // Fetch attempt details and answers
    $attempt = $userAnswerObj->getAttemptDetails($attempt_id);

    if (!$attempt) {
        echo "Invalid attempt ID";
        exit;
    }

    // Generate the PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Add the exam details to the PDF
    $pdf->Cell(0, 10, 'Exam Attempt', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(5);

    $pdf->Cell(0, 10, 'Examinee: ' . $attempt['first_name'] . ' ' . $attempt['last_name'], 0, 1);
    $pdf->Cell(0, 10, 'Attempt Date: ' . $attempt['attempt_date_time'], 0, 1);
    $pdf->Cell(0, 10, 'Exam Items: ' . $attempt['exam_items'], 0, 1);
    $pdf->Cell(0, 10, 'Total Score: ' . $attempt['exam_score'], 0, 1);

    // Output the PDF
    $pdf->Output("D", "exam_attempt_{$attempt_id}.pdf"); // Download the file
}

    public function login()
{
    $data = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new \App\Models\User();
        $userId = $userModel->verifyLogin($email, $password);

        if ($userId) {
            // Initialize session and set user ID
            session_start();
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] =$username;
            $_SESSION['email'] = $email;

            // Redirect to exam page
            header("Location: /exam");
            exit;
        } else {
            // Login failed
            $data['error'] = 'Invalid email or password.';
        }
    }

    return $this->render('login', $data);
}

    public function registrationForm()
    {
        $this->initializeSession();
        return $this->render('registration-form');
    }

    public function register()
    {
        $this->initializeSession();

        $data = $_POST;

        // Save the registration to the database
        $userModel = new \App\Models\User();
        $user_id = $userModel->save([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        // Store user details in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];

        return $this->render('pre-exam', $data);
    }

    public function exam()
    {
        $this->initializeSession();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $questionObj = new Question();
        $questions = $questionObj->getAllQuestions(); // Fetch all questions
    
        // If form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answers = $_POST['answers'] ?? []; // Collect all answers
    
            $user_id = $_SESSION['user_id'];
            $userAnswerObj = new UserAnswer();
    
            // Step 1: Save the exam attempt and retrieve the attempt ID
            $attempt_id = $userAnswerObj->saveAttempt($user_id, count($questions));
    
            // Step 2: Save all answers with the attempt_id
            $userAnswerObj->save($user_id, $attempt_id, $answers);
    
            // Step 3: Compute the score and update the attempt
            $score = $questionObj->computeScore($answers);
            $userAnswerObj->updateAttemptScore($attempt_id, $score);

            // Log success
            error_log('FINISHED EXAM, SAVING ANSWERS');
            error_log('USER ID = ' . $user_id);
            error_log('ATTEMPT ID = ' . $attempt_id);

            header("Location: /result");
            exit;
        }

        // Decode choices for all questions
        foreach ($questions as &$question) {
            $question['choices'] = json_decode($question['choices'], true);
        }

        return $this->render('exam', ['questions' => $questions]);
    }

    public function result()
{
    $this->initializeSession();

    $questionObj = new Question();
    $userAnswerObj = new \App\Models\UserAnswer();

    $data['questions'] = $questionObj->getAllQuestions();

    // Fetch the user's latest attempt ID (assuming stored in session or elsewhere)
    $attempt_id = $_SESSION['attempt_id'] ?? null;

    if ($attempt_id) {
        $answers = $userAnswerObj->getAnswersByAttempt($attempt_id); // Fetch answers from DB
    } else {
        $answers = [];
    }

    // Map user answers to questions
    foreach ($data['questions'] as &$question) {
        $question['choices'] = json_decode($question['choices'], true);
        $question_item_number = $question['question_item_number'] ?? null;

        // Safely map user answers
        $question['user_answer'] = isset($answers[$question_item_number])
            ? $answers[$question_item_number]
            : 'N/A';

        // Include the correct answer for display
        $question['correct_answer'] = $question['correct_answer'];
    }
    $data['username'] = $_SESSION['username'] ?? 'Guest';
    // Fetch the total score
    $data['total_score'] = $userAnswerObj->getLatestScore($_SESSION['user_id']);
    $data['question_items'] = count($data['questions']);

    session_destroy();
    return $this->render('result', $data);
}

    
}
