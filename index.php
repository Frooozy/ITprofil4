<?php
$jsonFile = 'profile.json';

// 1. Načtení dat (vždy na začátku, abychom měli aktuální seznam)
if (file_exists($jsonFile)) {
    $jsonString = file_get_contents($jsonFile);
    $data = json_decode($jsonString, true);
} else {
    // Pokud soubor neexistuje, vytvoříme prázdnou strukturu
    $data = ['name' => 'Nezadáno', 'skill' => [], 'interests' => []];
}

// 2. Zpracování POST požadavku (přidání nového zájmu)
if (isset($_POST["new_interest"])) {
    $new_interest = trim($_POST["new_interest"]);

    if (!empty($new_interest)) {
        // Příprava pro kontrolu duplicit (převod existujících na malá písmena)
        $existingInterestsLower = array_map('strtolower', $data['interests'] ?? []);

        // Kontrola duplicity bez ohledu na velikost písmen
        if (!in_array(strtolower($new_interest), $existingInterestsLower)) {
            // Přidání do pole a uložení
            $data['interests'][] = $new_interest;
            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

// Příprava proměnných pro zobrazení v HTML
$name = $data['name'] ?? 'Jméno nebylo zadáno';
$skills = $data['skill'] ?? [];
$interests = $data['interests'] ?? [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil: <?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1><?php echo htmlspecialchars($name); ?></h1>

        <!-- Formulář pro přidání zájmu -->
        <section class="form-section">
            <h2>Přidat nový zájem</h2>
            <form method="POST">
                <input type="text" name="new_interest" placeholder="Napište zájem..." required>
                <button type="submit">Přidat</button>
            </form>
        </section>

        <h2>Moje dovednosti</h2>
        <ul>
            <?php foreach ($skills as $s): ?>
                <li><?php echo htmlspecialchars($s); ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Zájmy a projekty</h2>
        <ul>
            <?php foreach ($interests as $interest): ?>
                <li><?php echo htmlspecialchars($interest); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>
