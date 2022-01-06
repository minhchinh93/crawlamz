<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname="craw";


// Create connection
$conn= mysqli_connect($servername, $username, $password, $dbname);

function importdata($conn, $link){
    $qr= "INSERT INTO `link`(`link`) VALUES {$link}";
    if($conn->query($qr)==true){
        echo "đã crawl ok";
    }else {
        echo "có lỗi";
    };
    }

$keyword = "holiday";

$page = 1;

for ($i=1;$i<=$page;$i++) {
    $linkHome= "https://www.amazon.com/s?k=".$keyword."&i=fashion-novelty&rh=p_6%3AATVPDKIKX0DER&page=".$i."&hidden-keywords=ORCA&qid=1641471223&ref=sr_pg_".$i;
    var_dump($linkHome);
    $url = "http://api.scraperapi.com?api_key=42c47c6a09541770714ff92f7e00ba74&url=".$linkHome;
    
    $curl = curl_init();
    if (! is_resource($curl)) {
        throw new \RuntimeException('curl_init() failed!');
    }
    ecurl_setopt($curl, CURLOPT_URL, $url);
    ecurl_setopt($curl, CURLOPT_VERBOSE, 1);

    ecurl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // Tránh in kết quả crawl được ra màn hình

    ecurl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Tránh yêu cầu web bị crawl cung cấp chứng chỉ SSL
ecurl_setopt($curl, CURLOPT_ENCODING, ''); // Dành cho amazon, chi tiết: https://stackoverflow.com/a/53130830/15824817

$curlstderr = etmpfile();
    $curlstdout = etmpfile();
    ecurl_setopt($curl, CURLOPT_STDERR, $curlstderr);
    ecurl_setopt($curl, CURLOPT_FILE, $curlstdout);
    if (true !== curl_exec($curl)) {
        throw new \RuntimeException("curl_exec failed! " . curl_errno($curl) . ": " . curl_error($curl));
    }
    rewind($curlstderr); // https://bugs.php.net/bug.php?id=76268
rewind($curlstdout); // https://bugs.php.net/bug.php?id=76268
$verbose = stream_get_contents($curlstderr);
    $output = stream_get_contents($curlstdout);
    curl_close($curl);
    fclose($curlstderr);
    fclose($curlstdout);
    // var_dump($verbose, $output);

    // Regex từ kết quả crawl được
    $regexPattern = '/(?<=a-color-base a-text-normal">).*(?=<\/span)/m';
    $str = $output;
    preg_match_all($regexPattern, $str, $matches, PREG_SET_ORDER, 0);

    // print_r($matches);
    foreach ($matches as $match) {
        $re = '/(?<=href=").+?(?=")/m';
        $str = $match[0];
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        $links[]= "('{$matches[0][0]}')";
        var_dump($matches[0][0]);
    }

    $link = implode(', ', $links);
    importdata($conn, $link);
}



// Function chức năng con, đừng quan tâm
function ecurl_setopt ( /*resource*/$ch, int $option , /*mixed*/ $value): bool
{
    $ret = curl_setopt($ch, $option, $value);
    if ($ret !== true) {
        // option should be obvious by stack trace
        throw new RuntimeException('curl_setopt() failed. curl_errno: ' . return_var_dump(curl_errno($ch)) . '. curl_error: ' . curl_error($ch));
    }
    return true;
}

function etmpfile()
{
    $ret = tmpfile();
    if (false === $ret) {
        throw new \RuntimeException('tmpfile() failed!');
    }
    return $ret;
}
?>