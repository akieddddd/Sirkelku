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
        'toket',
        'ngewe',
        'ewe',
        'bokep',
        'bacot',
        'sempak',
        'tempik',
        'silit',
        'pejuh',
    ];

    /**
     * Daftar singkatan umum kata kasar.
     *
     * @var array<string>
     */
    protected static array $abbreviations = [
        'ajg',
        'anj',
        'anjr',
        'kntl',
        'ktl',
        'mmk',
        'bgst',
        'bgsd',
        'gblk',
        'gblg',
        'tll',
        'bjngn',
        'jmbt',
        'asw',
        'plr',
        'pntk',
        'ngtd',
        'ngtot',
        'kmpret',
    ];

    /**
     * Peta substitusi karakter (leetspeak & variasi karakter).
     *
     * @var array<string, string>
     */
    protected static array $charMap = [
        'a' => '[a@4]',
        'b' => '[b8]',
        'c' => '[c]',
        'd' => '[d]',
        'e' => '[e3]',
        'g' => '[g9]',
        'h' => '[h]',
        'i' => '[i1!|]',
        'j' => '[j]',
        'k' => '[k]',
        'l' => '[l]',
        'm' => '[m]',
        'n' => '[n]',
        'o' => '[o0]',
        'p' => '[p]',
        'r' => '[r]',
        's' => '[s5$]',
        't' => '[t7+]',
        'u' => '[uv]',
        'w' => '[w]',
        'y' => '[y]',
    ];

    /**
     * Cache pola regex untuk efisiensi eksekusi.
     */
    protected static ?string $cachedPattern = null;

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
     * - "anjing"     -> "a****g"
     * - "pelerrrrr"  -> "p*******r"
     * - "babi"       -> "b**i"
     * - "asu"        -> "a*u"
     * - "tai"        -> "t*i"
     * - "cuk"        -> "c*k"
     * - "as"         -> "**"
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
     * Membangun regex untuk 1 kata dengan toleransi pemanjangan karakter (+),
     * leetspeak/substitusi karakter, dan pemisah (spasi, titik, dash, underscore).
     *
     * @param string $word
     * @return string
     */
    public static function buildWordRegex(string $word): string
    {
        $chars = mb_str_split(mb_strtolower($word));
        $patternParts = [];

        foreach ($chars as $ch) {
            $class = static::$charMap[$ch] ?? preg_quote($ch, '/');
            $patternParts[] = $class . '+';
        }

        // Izinkan pemisah opsional antara huruf (spasi, titik, strip, garis bawah, bintang, tilde)
        return implode('[\s\.\-_*~]*', $patternParts);
    }

    /**
     * Membangun regex pattern yang komprehensif, case-insensitive,
     * serta aman terhadap false positives dengan boundary Unicode letter (?<!\p{L}) dan (?!\p{L}).
     *
     * @return string
     */
    public static function buildRegexPattern(): string
    {
        if (static::$cachedPattern !== null) {
            return static::$cachedPattern;
        }

        $allWords = array_merge(static::$badWords, static::$abbreviations);

        // Urutkan kata dari yang paling panjang ke pendek
        usort($allWords, function ($a, $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });

        $subPatterns = [];
        foreach ($allWords as $word) {
            $subPatterns[] = static::buildWordRegex($word);
        }

        static::$cachedPattern = '/(?<!\p{L})(' . implode('|', $subPatterns) . ')(?!\p{L})/iu';

        return static::$cachedPattern;
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
        static::$cachedPattern = null;
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
        static::$cachedPattern = null;
        static::$badWords = array_values(array_unique(array_filter(array_map('mb_strtolower', $words))));
    }

    /**
     * Ambil daftar singkatan terlarang.
     *
     * @return array<string>
     */
    public static function getAbbreviations(): array
    {
        return static::$abbreviations;
    }

    /**
     * Tambahkan singkatan terlarang baru.
     *
     * @param array<string>|string $abbreviations
     * @return void
     */
    public static function addAbbreviations(array|string $abbreviations): void
    {
        static::$cachedPattern = null;
        $abbreviations = is_array($abbreviations) ? $abbreviations : [$abbreviations];
        foreach ($abbreviations as $abbr) {
            $normalized = mb_strtolower(trim($abbr));
            if ($normalized !== '' && !in_array($normalized, static::$abbreviations, true)) {
                static::$abbreviations[] = $normalized;
            }
        }
    }
}
