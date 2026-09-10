<?php

function validateSignUp($name, $email, $phone, $password, $conn) {
    $errors = [];

    if (empty($name)) {
        $errors[] = "الاسم الكامل مطلوب.";
    } elseif (mb_strlen($name) < 3) {
        $errors[] = "يجب ألا يقل الاسم عن 3 أحرف.";
    } elseif (!preg_match("/^[\p{L}\s]+$/u", $name)) {
        $errors[] = "الاسم يجب أن يحتوي على حروف ومسافات فقط.";
    }

    if (empty($email)) {
        $errors[] = "البريد الإلكتروني مطلوب.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "صيغة البريد الإلكتروني غير صحيحة.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = "هذا البريد الإلكتروني مسجل بالفعل.";
            }
        } catch (PDOException $e) {
            $errors[] = "خطأ في قاعدة البيانات.";
        }
    }

    if (empty($phone)) {
        $errors[] = "رقم الهاتف مطلوب.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $errors[] = "صيغة رقم الهاتف غير صحيحة (من 10 إلى 15 رقماً).";
    }

    if (empty($password)) {
        $errors[] = "كلمة المرور مطلوبة.";
    } elseif (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب ألا تقل عن 6 أحرف.";
    }

    return $errors;
}

function validateLogin($email, $password) {
    $errors = [];

    if (empty($email)) {
        $errors[] = "البريد الإلكتروني مطلوب.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "صيغة البريد الإلكتروني غير صحيحة.";
    }

    if (empty($password)) {
        $errors[] = "كلمة المرور مطلوبة.";
    }

    return $errors;
}
?>