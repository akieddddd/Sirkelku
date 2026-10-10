<?php

namespace App\Services;

class ProfanityFilter
{
    /**
     * Daftar kata terlarang (bahasa Indonesia & slang umum).
     *
     * @var array<string>
     */
    protected static array $badWords = [
        'anjing',
        'asu',
        'babi',
        'bangsat',
        'kontol',
        'memek',
        'ngentot',
        'entot',
        'bajingan',
        'tolol',
        'goblok',
        'bego',
        'kampret',
        'pantek',
        'peler',
        'jembut',
        'itil',
        'tetek',
        'lonte',
        'perek',
        'pepek',
        'bodoh',
        'brengsek',
        'bangke',
        'biadab',
        'tai',
        'taek',
        'sialan',
        'jancuk',
        'jancok',
        'dancok',
        'cuk',
        'kimak',
        'pukimak',
        'coli',
        'sange',
        'ngaceng',
        'pecun',
        'jablay',
        'bencong',
        'banci',
    ];

    /**
     * Sensor kata kasar dalam teks.
     * Mengembalikan teks yang telah disensor atau nilai aslinya jika kosong/null.
     *
     * @param string|null $text
     * @return string|null
     */
    public static function censor(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return $text;
        }

        $pattern = static::buildRegexPattern();

        return preg_replace_callback($pattern, function (array $matches) {
            return static::maskWord($matches[0]);
        }, $text);
    }

    /**
     * Memeriksa apakah teks mengandung kata terlarang.
     *
     * @param string|null $text
     * @return bool
     */
    public static function contains(?string $text): bool
    {
        if ($text === null || $text === '') {
            return false;
        }

        $pattern = static::buildRegexPattern();

        return (bool) preg_match($pattern, $text);
    }

    /**
     * Sensor sebuah kata dengan mengganti karakter di tengahnya dengan bintang.
     * Contoh:
     * - "anjing"  -> "a****g"
     * - "babi"    -> "b**i"
     * - "asu"     -> "a*u"
     * - "tai"     -> "t*i"
     * - "cuk"     -> "c*k"
     * - "as"      -> "**"
     *
     * @param string $word
     * @return string
     */
    public static function maskWord(string $word): string
    {
        $len = mb_strlen($word);

        if ($len <= 2) {
            return str_repeat('*', $len);
        }

        $firstChar = mb_substr($word, 0, 1);
        $lastChar  = mb_substr($word, -1, 1);
        $stars     = str_repeat('*', $len - 2);

        return $firstChar . $stars . $lastChar;
    }

    /**
     * Membangun regex pattern yang aman dan case-insensitive dengan boundary \b.
     *
     * @return string
     */
    protected static function buildRegexPattern(): string
    {
        // Urutkan kata dari yang paling panjang ke yang pendek agar frase/kata majemuk cocok lebih dahulu
        $sortedWords = static::$badWords;
        usort($sortedWords, function ($a, $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });

        $escaped = array_map(function ($word) {
            return preg_quote($word, '/');
        }, $sortedWords);

        return '/\b(' . implode('|', $escaped) . ')\b/iu';
    }

    /**
     * Ambil seluruh daftar kata terlarang.
     *
     * @return array<string>
     */
    public static function getBadWords(): array
    {
        return static::$badWords;
    }

    /**
     * Tambahkan kata terlarang baru secara dinamis jika diperlukan.
     *
     * @param array<string>|string $words
     * @return void
     */
    public static function addBadWords(array|string $words): void
    {
        $words = is_array($words) ? $words : [$words];
        foreach ($words as $word) {
            $normalized = mb_strtolower(trim($word));
            if ($normalized !== '' && !in_array($normalized, static::$badWords, true)) {
                static::$badWords[] = $normalized;
            }
        }
    }

    /**
     * Ganti seluruh daftar kata terlarang.
     *
     * @param array<string> $words
     * @return void
     */
    public static function setBadWords(array $words): void
    {
        static::$badWords = array_values(array_unique(array_filter(array_map('mb_strtolower', $words))));
    }
}
