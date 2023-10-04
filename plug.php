<?php
$hijau = "\033[32m";
$kuning = "\033[33m";
$merah = "\033[31m";
$cyan = "\033[36m";
$reset = "\033[0m";
$banner = "{$cyan}
       _           _          
  _ __| |_  _ __ _(_)_ _ _  _ 
 | '_ \ | || / _` | | ' \ || |
 | .__/_|\_,_\__, |_|_||_\_,_|
 |_|         |___/ domain extractor
 {$hijau}Written by Zaen
 github.com/zaenhaxor         
{$reset}\n\n";
echo $banner;
echo "{$cyan}Choose an option:{$reset}\n";
echo "a. {$hijau}Extension{$reset}\n";
echo "b. {$hijau}Plugins{$reset}\n\n";
echo "Choose [a or b]: ";
$opsi = trim(fgets(STDIN));
function req_pluginu($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
if ($opsi === 'a') {
    $extension = readline("Domain ext: ");
    $start_page = intval(readline("Start page: "));
    $until_page = intval(readline("Until page: "));
    $out_file = "{$extension}.txt";
    $open = fopen($out_file, 'w');
    for ($page = $start_page; $page <= $until_page; $page++) {
        $url = "http://pluginu.com/domain-zone/{$extension}/{$page}";
        $data = req_pluginu($url);
        $regex = '/\/site\/([^"]+)"/';
        $total = 0;
        if (preg_match_all($regex, $data, $matches)) {
            $domains = $matches[1];
            $filter = array_unique(str_replace("'", "", $domains));
            foreach ($filter as $domain) {
                fwrite($open, $domain . PHP_EOL);
                $total++;
            }
        }
        echo "[{$kuning}+{$reset}] Domain: {$hijau}{$extension}{$reset} | Page: {$hijau}{$page}{$reset} | Total: {$hijau}{$total}{$reset}\n";
        sleep(1);
    }
    fclose($open);
} elseif ($opsi === 'b') {
    do {
        $plugins = readline("Plugin name: ");
        if (!preg_match('/^[a-z0-9-]+$/', $plugins)) {
            echo "[{$merah}-{$reset}] {$merah}Invalid input!{$reset} => {$merah}Plugin name must contain only lowercase letters, numbers, and hyphens.{$reset}\n";
        }
    } while (!preg_match('/^[a-z0-9-]+$/', $plugins));
    do {
        $start_page = readline("Start page: ");
        if (!is_numeric($start_page)) {
            echo "[{$merah}-{$reset}] {$merah}Invalid input!{$reset} => {$merah}Start page must be a number.{$reset}\n";
        }
    } while (!is_numeric($start_page));
    do {
        $until_page = readline("Until page: ");
        if (!is_numeric($until_page)) {
            echo "[{$merah}-{$reset}] {$merah}Invalid input!{$reset} => {$merah}Until page must be a number.{$reset}\n";
        }
    } while (!is_numeric($until_page));
    $out_file = "{$plugins}.txt";
    $open = fopen($out_file, 'w');
    for ($page = $start_page; $page <= $until_page; $page++) {
        $url = "http://pluginu.com/{$plugins}/{$page}";
        $data = req_pluginu($url);
        $regex = '/\/site\/([^"]+)"/';
        $total = 0;
        if (preg_match_all($regex, $data, $matches)) {
            $domains = $matches[1];
            $filter = array_unique(str_replace("'", "", $domains));

            foreach ($filter as $domain) {
                fwrite($open, $domain . PHP_EOL);
                $total++;
            }
        }
        echo "[{$hijau}+{$reset}] Plugin name: {$hijau}{$plugins}{$reset} | Page: {$hijau}{$page}{$reset} | Total: {$hijau}{$total}{$reset}\n";
        sleep(1);
    }
    fclose($open);
} else {
    echo "[{$merah}-{$reset}] {$merah}Invalid option!{$reset}\n";
}
?>
