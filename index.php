<?php
$jsonFile = 'profile.json';
$message = "";     // Текст сообщения
$messageType = ""; // Тип сообщения: success или error

// Загрузка данных
if (file_exists($jsonFile)) {
    $jsonString = file_get_contents($jsonFile);
    $data = json_decode($jsonString, true);
} else {
    $data = ['name' => 'Ukázkový Profil', 'skill' => [], 'interests' => []];
}

// Обработка формы
if (isset($_POST["new_interest"])) {
    $new_interest = trim($_POST["new_interest"]);
    
    if (empty($new_interest)) {
        $message = "Pole nesmí být prázdné.";
        $messageType = "error";
    } else {
        $existingInterestsLower = array_map('strtolower', $data['interests'] ?? []);
        
        if (in_array(strtolower($new_interest), $existingInterestsLower)) {
            $message = "Tento zájem už existuje.";
            $messageType = "error";
        } else {
            $data['interests'][] = $new_interest;
            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $message = "Zájem byl úspěšně přidán.";
            $messageType = "success";
        }
    }
}

$name = $data['name'] ?? 'Jméno nebylo zadáno';
$skills = $data['skill'] ?? [];
$interests = $data['interests'] ?? [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Profil: <?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($name); ?></h1>

        <section class="card">
            <h2>Přidat nový zájem</h2>

            <!-- Блок вывода сообщения из изображения -->
            <?php if (!empty($message)): ?>
                <p class="alert <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <form method="POST" class="form-add">
                <input type="text" name="new_interest" placeholder="Co tě baví?" required>
                <button type="submit">Přidat</button>
            </form>
        </section>

        <div class="grid">
            <section class="card">
                <h2>Dovednosti</h2>
                <ul class="list">
                    <?php foreach ($skills as $s): ?>
                        <li><?php echo htmlspecialchars($s); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section class="card">
                <h2>Zájmy</h2>
                <ul class="list highlight">
                    <?php foreach ($interests as $interest): ?>
                        <li><?php echo htmlspecialchars($interest); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>
    </div>
</body>
</html>
