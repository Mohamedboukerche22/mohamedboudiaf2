<?php
function getStudentNotes($studentId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM notes WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $studentId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addNote($studentId, $note, $points, $conn) {
    $stmt = $conn->prepare("INSERT INTO notes (student_id, note, points) VALUES (:student_id, :note, :points)");
    $stmt->bindParam(':student_id', $studentId);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':points', $points);
    return $stmt->execute();
}
?>