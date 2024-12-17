<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class UserAnswer extends BaseModel
{
    /**
     * Save all user answers in one go, linked to an exam attempt
     */
    public function save($user_id, $attempt_id, $answers)
    {
        $sql = "INSERT INTO users_answers (user_id, attempt_id, answers)
                VALUES (:user_id, :attempt_id, :answers)";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'user_id' => $user_id,
            'attempt_id' => $attempt_id,
            'answers' => json_encode($answers) // Store answers as JSON
        ]);

        return $statement->rowCount();
    }

    /**
     * Save a new exam attempt and return its ID
     */
    public function saveAttempt($user_id, $exam_items, $exam_score = 0)
    {
        $sql = "INSERT INTO exam_attempts (user_id, exam_items, exam_score)
                VALUES (:user_id, :exam_items, :exam_score)";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'user_id' => $user_id,
            'exam_items' => $exam_items,
            'exam_score' => $exam_score
        ]);

        return $this->db->lastInsertId(); // Return the generated attempt ID
    }
    public function getLatestScore($user_id)
    {
        $sql = "SELECT exam_score FROM exam_attempts 
                WHERE user_id = :user_id 
                ORDER BY attempt_date_time DESC 
                LIMIT 1";
    
        $statement = $this->db->prepare($sql);
        $statement->execute(['user_id' => $user_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        return $result['exam_score'] ?? 0; // Return 0 if no score is found
    }
    public function getAnswersByAttempt($attempt_id)
{
    $sql = "SELECT answers FROM users_answers WHERE attempt_id = :attempt_id";
    $statement = $this->db->prepare($sql);
    $statement->execute(['attempt_id' => $attempt_id]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result ? json_decode($result['answers'], true) : [];
}
public function getAttemptDetails($attempt_id)
{
    $sql = "
        SELECT ea.attempt_date_time, u.first_name, u.last_name, ea.exam_items, ea.exam_score
        FROM exam_attempts ea
        JOIN users u ON ea.user_id = u.id
        WHERE ea.id = :attempt_id
    ";

    $statement = $this->db->prepare($sql);
    $statement->execute(['attempt_id' => $attempt_id]);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

public function getAllAttempts()
{
    $sql = "
        SELECT ea.attempt_date_time, u.first_name, u.last_name, ea.exam_items, ea.exam_score, ea.id AS attempt_id
        FROM exam_attempts ea
        JOIN users u ON ea.user_id = u.id
        ORDER BY ea.attempt_date_time DESC
    ";

    $statement = $this->db->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Update the score for an attempt
     */
    public function updateAttemptScore($attempt_id, $exam_score)
    {
        $sql = "UPDATE exam_attempts SET exam_score = :exam_score WHERE id = :attempt_id";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'exam_score' => $exam_score,
            'attempt_id' => $attempt_id
        ]);
    }
}
