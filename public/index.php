<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Kaczy\Myjnia\Connect;

$loader = new FilesystemLoader(__DIR__ . '/../templates');

$pdo = Connect::gConnection();


//DUMP ZAMIAST $twig = new Environment($loader);

use Twig\Extension\DebugExtension;

$twig = new Environment($loader, [
    'debug' => true
]);
$twig->addExtension(new DebugExtension());

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

$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
$currentYear = (int)date('Y');
$firstDay = new DateTime("$currentYear-$currentMonth-01");
$firstDayOfWeek = (int)$firstDay->format('N');
$lastDay = (clone $firstDay)->modify('last day of this month');

$weekDates = [];
$interval = new DateInterval('P1D');
$period = new DatePeriod($firstDay, $interval, $lastDay->modify('+1 day'));

foreach ($period as $date) {
    $weekDates[] = ['date' => $date->format('Y-m-d')];
}
$daysOfWeek = ['Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota','Niedziela'];
//DO CZERWONEGO
$weekDatesFull = [];

foreach ($weekDates as $day) {
    $date = $day['date'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rezerwacje WHERE data = ?");
    $stmt->execute([$date]);
    $count = $stmt->fetchColumn();

    $dateObj = new DateTime($date);
    $dayOfWeek = (int)$dateObj->format('N'); // 1 = pon, 7 = niedz

    $weekDatesFull[] = [
        'date' => $date,
        'reservedCount' => (int)$count,
        'dayOfWeek' => $dayOfWeek
    ];
}
echo $twig->render('home.twig', [
    'weekDates' => $weekDatesFull,
    'daysOfWeek' => $daysOfWeek,
    'sections' => $sections,
    'currentMonth' => $currentMonth,
    'firstDayOfWeek' => $firstDayOfWeek,
]);


