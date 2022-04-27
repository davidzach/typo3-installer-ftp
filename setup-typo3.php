<?php
ini_set('memory_limit', '1G');

$typo3Version = '11.5.9';
$address = 'https://get.typo3.org/' . $typo3Version;
$filename = 'typo3_src.tgz';

$wget = function (string $address, string $filename) {
    file_put_contents($filename, file_get_contents($address));
};

if (file_exists($filename)) {
    echo "The file $filename exists.";
} else {
    echo "The file $filename does not exist and is loaded on the server.";
    $wget($address, $filename);
}

$buildSymlinks = function (string $typo3Version) {
    unlink('typo3_src');
    unlink('typo3');
    unlink('index.php');
    symlink('typo3_src-' . $typo3Version, 'typo3_src');
    symlink('typo3_src/typo3', 'typo3');
    symlink('typo3_src/index.php', 'index.php');
    echo "The symlinks have been created.";
};

try {
    $phar = new \PharData($filename);
    $phar->extractTo(__DIR__);
    echo "The file $filename was extracted.";
} catch (Exception $e) {
    echo $e->getMessage();
}

$buildSymlinks($typo3Version);
