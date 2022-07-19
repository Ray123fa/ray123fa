<?php
if (isset($_POST['submit'])) {
  function http_request($url) {
    // persiapkan curl
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);
    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // tutup curl
    curl_close($ch);

    // mengembalikan hasil curl
    return $output;
  }
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $pesan = $_POST['pesan'];

  $token = "5549392155:AAGYdCxoKQTcrOSTfLNAtx-GV258TjsOKQI";
  $chat_id = "5369790259";
  $msg = urlencode("<b>Dari:</b>\nNama: $nama\nEmail: $email\n\n<b>Pesan:</b>\n$pesan");
  
  $result = http_request("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&parse_mode=html&text=$msg");
  
  if ($result) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Status Pesan</title>
      
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.js"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'success',
          html: '<h4>Pesan telah dikirim</h4>',
        }).then((result) => {
          location.replace("index.php")
        })
      </script>
    </body>
    </html>
    <?php
  }
}
?>
