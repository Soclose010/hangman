<?php
$gallowsPictures = ["
  +---+
  |   |
      |
      |
      |
      |
=========", " 
  +---+
  |   |
  O   |
      |
      |
      |
=========", " 
  +---+
  |   |
  O   |
  |   |
      |
      |
=========", " 
  +---+
  |   |
  O   |
 /|   |
      |
      |
=========", "
  +---+
  |   |
  O   |
 /|\  |
      |
      |
=========", " 
  +---+
  |   |
  O   |
 /|\  |
 /    |
      |
=========", " 
  +---+
  |   |
  O   |
 /|\  |
 / \  |
      |
========="];
function getRandomWord(): string
{
    $words = preg_split('/[,\s]+/u', file_get_contents(__DIR__ . '/words.txt'));
    return mb_strtolower($words[array_rand($words)]);
}

function printGallows(array $gallowsPictures, int $gallowsStep): void
{
    if ($gallowsStep >= 0) {
        echo $gallowsPictures[$gallowsStep] . PHP_EOL;
    }
}

function repeatLetter(array $guessedLetters, string $letter): bool
{
    return in_array($letter, $guessedLetters);
}

function letterPositions(string $word, string $letter): array
{
    $positions = [];
    $word = mb_str_split($word);
    foreach ($word as $key => $wordLetter) {
        if ($wordLetter == $letter) {
            $positions[] = $key;
        }
    }
    return $positions;
}

function mb_substr_replace($original, $replacement, $position, $length): string
{
    $startString = mb_substr($original, 0, $position, 'UTF-8');
    $endString = mb_substr($original, $position + $length, mb_strlen($original), 'UTF-8');
    return $startString . $replacement . $endString;
}

function hangman(array $gallowsPictures): void
{
    echo "Игра началась!" . PHP_EOL;
    $word = getRandomWord();
    $guessedWord = str_repeat('*', mb_strlen($word));
    $guessedLetters = [];
    $gallowsStep = -1;
    while ($gallowsStep < (count($gallowsPictures) -1) && $word != $guessedWord) {
        printGallows($gallowsPictures, $gallowsStep);
        echo $guessedWord . PHP_EOL;
        echo "Количество ошибок: " . ($gallowsStep + 1) . "." . PHP_EOL;
        $letter = mb_strtolower(readline("Введите букву: "));
        if (preg_match('/^[а-я]$/u', $letter) && !repeatLetter($guessedLetters, $letter)) {
            if (mb_strpos($word, $letter) !== false) {
                $positions = letterPositions($word, $letter);
                if ($positions != []) {
                    foreach ($positions as $position) {
                        $guessedWord = mb_substr_replace($guessedWord, $letter, $position, 1);
                    }
                    $guessedLetters[] = $letter;
                }
            } else {
                $gallowsStep++;
                echo "Вы ошиблись!" . PHP_EOL;
            }
        } else {
            echo "Введен некорректный символ (или ранее введенный), попробуйте еще раз" . PHP_EOL;
        }
    }
    if ($gallowsStep == 6) {
        echo "Вы проиграли. Загаданное слово: {$word}." . PHP_EOL;
    } else {
        echo "Поздравляю! Вы победили." . PHP_EOL;
    }
}


do {
    echo "Выберите действие: Н - начать игру, В - выход." . PHP_EOL;
    $letter = mb_strtolower(readline(), 'UTF-8');
    if ($letter == 'н') {
        hangman($gallowsPictures);
        continue;
    }

    if ($letter != 'в') {
        echo "Введена неверная команда." . PHP_EOL;
    }

} while ($letter != 'в');