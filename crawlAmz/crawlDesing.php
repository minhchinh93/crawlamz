<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname="craw";


// Create connection
$conn= mysqli_connect($servername, $username, $password, $dbname);

function qluserid($conn){
    $qr= "SELECT * FROM `link`";
    $e = $conn->query($qr);
    return $e;
}
function importdata($conn, $link){
    $qr= "INSERT INTO `product`(`link_product`, `link_pnj`) VALUES {$link}";
     $e = $conn->query($qr);
    }
$f= qluserid($conn);
while ($row = $f->fetch_array()) {
    $link = $row['link']; 
    // $link = "https://www.amazon.com/dp/B09PLQX9ZH/ref=sr_1_11?m=ATVPDKIKX0DER&qid=1641214162&refinements=p_6%3AATVPDKIKX0DER&refresh=1&s=apparel&sr=1-11";

// $url = 'https://www.amazon.com/dp/B09PNW7WZR/ref=sr_1_10?m=ATVPDKIKX0DER&qid=1641315251&refinements=p_6%3AATVPDKIKX0DER&s=apparel&sr=1-10';
    $url=  "http://api.scraperapi.com?api_key=42c47c6a09541770714ff92f7e00ba74&url=https://www.amazon.com/".$link;
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
    // var_dump($output);

    // Regex từ kết quả crawl được
      // Regex từ kết quả crawl được
      $re = '/(?<=image item itemNo0 selected maintain-height").*\n\s.*(?>\/div>)/m';
        $str = $output;
    
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        $re = '/(?<=src\=\").*?(?=")/m';
        // $re = '/(?<=7C).*?(?=%7C)/m';
        // var_dump($matches);
        $strs = $matches[0][0];
        preg_match_all($re, $strs, $matche, PREG_SET_ORDER, 0);
    
       $re = '/(?<=7C).*?(?=%7C)/m';
       $strss = $matche[0][0];
    //    var_dump($strss); 
       preg_match_all($re, $strss, $matchee, PREG_SET_ORDER, 0);
       $link_pnj = "https://m.media-amazon.com/images/I/".$matchee[1][0]."?fbclid=IwAR0AL_AbagnH07PJLgs9UR-z_rwN8bGrhem5DkRVDbUh_xbe4fZIGgWrsl0";
    //    var_dump($link_pnj);
    $links[]= "('{$strss}','{$link_pnj}')";

   
}
$link = implode(', ', $links);
importdata($conn, $link);
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