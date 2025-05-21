<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Kaczy\Myjnia\Connect;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

$sections = [
    [
        'id' => 'aboutUs',
        'title' => 'O nas',
        'content' => 'Specjalizujemy się w profesjonalnym czyszczeniu wnętrz samochodowych. Gwarantujemy najwyższą jakość usług!'
    ],
    [
        'id' => 'services',
        'title' => 'Zakres usług',
        'content' => 'Oferujemy: czyszczenie tapicerki, odkurzanie wnętrza, mycie szyb, pielęgnację deski rozdzielczej oraz usuwanie nieprzyjemnych zapachów.'
    ],
    [
        'id' => 'contact',
        'title' => 'Kontakt',
        'content' => 'Masz pytania? Zadzwoń do nas: +48 123 456 789 lub napisz na email: kontakt@carcleaning.pl'
    ]
];

$currentWeek = isset($_GET['week']) ? (int)$_GET['week'] : 0;
$startDate = strtotime("last Sunday +$currentWeek week");
$weekDates = [];

for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("+$i day", $startDate));
    $weekDates[] = ['date' => $date];
}

$hours = [];
for ($hour = 7; $hour < 18; $hour++) {
    $hours[] = sprintf("%02d:00", $hour);
}

echo $twig->render('home.twig', [
    'sections' => $sections,
    'currentWeek' => $currentWeek,
    'daysOfWeek' => ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'],
    'weekDates' => $weekDates,
    'hours' => $hours
]);