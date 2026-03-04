<?php
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);
$message = $data["message"] ?? "";
$type = $data["type"] ?? "general";

if (!$message) {
  echo json_encode(["error" => "Пустой запрос"]);
  exit;
}

$apiKey = "AIzaSyDw0Zqhs_RUu0MCQEUI64TMvzG5LvimT7A";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

if ($type === "university") {
  $systemPrompt = "Ты ИИ-консультант сайта GetItUniversity.
  Дай структурированный анализ университета:
  1. Общее описание
  2. Направления
  3. Стоимость
  4. Общежитие
  5. Плюсы и особенности.
  Используй интернет для актуальной информации.

  Университет: ";
} else {
  $systemPrompt = "Ты ИИ-консультант сайта GetItUniversity.
  Отвечай кратко и структурированно.
  Если вопрос про университет — используй интернет.

  Вопрос: ";
}

$payload = [
  "contents" => [
    [
      "parts" => [
        ["text" => $systemPrompt . $message]
      ]
    ]
  ],
  "tools" => [
    ["google_search" => new stdClass()]
  ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_TIMEOUT => 25
]);

$response = curl_exec($ch);

if ($response === false) {
  echo json_encode(["error" => "Ошибка подключения к Gemini"]);
  exit;
}

curl_close($ch);
echo $response;
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "User-Agent: Mozilla/5.0"
  ],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_TIMEOUT => 25
]);