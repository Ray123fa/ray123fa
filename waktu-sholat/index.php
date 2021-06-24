<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Waktu Sholat</title>

  <!--Bootstrap 5-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  
  <!--JQuery Ajax-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  <?php
  if (!isset($_GET['go'])) {
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
    $dt = date('d-m-Y');
    $result = http_request("https://api.aladhan.com/v1/timings/$dt?latitude=-3.320807&longitude=114.638840&method=4");

    // ubah string JSON menjadi array
    $result = json_decode($result, JSON_PRETTY_PRINT);

    $grego_date = str_replace("-", " ", $dt);
    $days_en = date('l', strtotime($dt));
    $days_id = [
      "Monday" => "Senin",
      "Tuesday" => "Selasa",
      "Wednesday" => "Rabu",
      "Thursday" => "Kamis",
      "Friday" => "Jum'at",
      "Saturday" => "Sabtu",
      "Sunday" => "Minggu"
    ];

    $months_en_grego = substr($dt, 3, 2);
    $months_id_grego = [
      "01" => "Januari",
      "02" => "Februari",
      "03" => "Maret",
      "04" => "April",
      "05" => "Mei",
      "06" => "Juni",
      "07" => "Juli",
      "08" => "Agustus",
      "09" => "September",
      "10" => "Oktober",
      "11" => "November",
      "12" => "Desember"
    ];

    $join_daysname = "$days_id[$days_en], ".$grego_date;
    $restring_dt_grego = str_replace($months_en_grego, $months_id_grego[$months_en_grego], $join_daysname);

    $hijr_date = str_replace("-", " ", $result['data']['date']['hijri']['date']);
    $months_num_hijr = substr($hijr_date, 3, 2);
    $months_name_hijr = [
      "01" => "Muharram",
      "02" => "Safar",
      "03" => "Rabiul Awal",
      "04" => "Rabiul Akhir",
      "05" => "Jumadil Awal",
      "06" => "Jumadil Akhir",
      "07" => "Rajab",
      "08" => "Sya'ban",
      "09" => "Ramadhan",
      "10" => "Syawal",
      "11" => "Dzulkaidah",
      "12" => "Dzulhijjah"
    ];
    $restring_dt_hijr = str_replace($months_num_hijr, $months_name_hijr[$months_num_hijr], $hijr_date);

    $timezone_json = $result['data']['meta']['timezone'];
    $replace_timezone = [
      "Asia/Jakarta" => "GMT+07.00 (WIB)",
      "Asia/Makassar" => "GMT+08.00 (WITA)",
      "Asia/Jayapura" => "GMT+09.00 (WIT)"
    ];
    $restring_timezone = str_replace($timezone_json, $replace_timezone[$timezone_json], $timezone_json);

    $data = [
      "date" => $restring_dt_grego." || ".$restring_dt_hijr,
      "sholat" => [
        1 => $result['data']['timings']['Imsak'],
        2 => $result['data']['timings']['Fajr'],
        3 => $result['data']['timings']['Dhuhr'],
        4 => $result['data']['timings']['Asr'],
        5 => $result['data']['timings']['Maghrib'],
        6 => $result['data']['timings']['Isha']
      ],
      "location" => [
        "latitude" => $result['data']['meta']['latitude'],
        "longitude" => $result['data']['meta']['longitude'],
        "timezone" => $restring_timezone
      ],
      "method" => [
        "calculation" => "Universitas Umm al-Qura",
        "juristic" => "Standar (Syafi`i)"
      ]
    ];
    ?>
    <!-- The Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <!--Modal Header-->
          <div class="modal-header">
            <section class="modal-title" id="exampleModalLabel">
              <p class="modal-title" style="margin: 0; padding: 0; font-size: 21px; font-weight: bold;">
                Waktu Sholat
              </p>
              <p class="modal-title" style="margin: 0; padding: 0; font-size: 15px; font-weight: 700;">
                Koordinat: <?php echo $data['location']['latitude'].", ".$data['location']['longitude'] ?>
              </p>
              <p class="modal-title" style="font-size: 14px; margin: 0; padding: 0; font-weight: 500">
                <?php echo $data['date'] ?>
              </p>
            </section>
          </div>

          <!--Modal Body-->
          <div class="modal-body" style="padding-bottom: 0;">
            <table class="table table-bordered" style="margin: 0; padding: 0;">
              <tr>
                <th><center>Waktu</center></th>
                <th><center>Jam</center></th>
              </tr>
              <tr>
                <td><center>Imsak</center></td>
                <td><center><?php echo $data['sholat'][1] ?></center></td>
              </tr>
              <tr>
                <td><center>Subuh</center></td>
                <td><center><?php echo $data['sholat'][2] ?></center></td>
              </tr>
              <tr>
                <td><center>Dzuhur</center></td>
                <td><center><?php echo $data['sholat'][3] ?></center></td>
              </tr>
              <tr>
                <td><center>Ashar</center></td>
                <td><center><?php echo $data['sholat'][4] ?></center></td>
              </tr>
              <tr>
                <td><center>Maghrib</center></td>
                <td><center><?php echo $data['sholat'][5] ?></center></td>
              </tr>
              <tr>
                <td><center>Isya</center></td>
                <td><center><?php echo $data['sholat'][6] ?></center></td>
              </tr>
            </table>
            <p class="modal-title" style="font-size: 14px; padding-top: 8px; padding-bottom: 8px;">
              <b>Metode Kalkulasi: </b><?php echo $data['method']['calculation'] ?><br>
              <b>Metode Juristik: </b><?php echo $data['method']['juristic'] ?><br>
              <b>Zona Waktu: </b><?php echo $data['location']['timezone'] ?>
            </p>
          </div>

          <!--Modal Footer-->
          <div class="modal-footer">
            <button class="btn btn-primary" data-bs-target="#exampleModal2" data-bs-toggle="modal" data-bs-dismiss="modal" onclick="alert('Turn On Your Location Please')">Change Location ?</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-body">
            <center>
              <button class="btn btn-secondary" onclick="getLocation()">Click this</button>
              <br>
              <br>
              <form action="" method="get">
                <input type="text" name="latitude" id="latitude" placeholder="Latitude" value="" readonly>
                <input type="text" name="longitude" id="longitude" placeholder="Longitude" value="" readonly>
                <br><br>
                <button class="btn btn-primary" type="submit" name="go" value="go">Go</button>
              </form>
            </center>
          </div>
        </div>
      </div>
    </div>
    <?php
  } else {
    $lat = $_GET['latitude'];
    $long = $_GET['longitude'];

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
    $dt = date('d-m-Y');
    $result = http_request("https://api.aladhan.com/v1/timings/$dt?latitude=$lat&longitude=$long&method=4");

    // ubah string JSON menjadi array
    $result = json_decode($result, JSON_PRETTY_PRINT);

    $grego_date = str_replace("-", " ", $dt);
    $days_en = date('l', strtotime($dt));
    $days_id = [
      "Monday" => "Senin",
      "Tuesday" => "Selasa",
      "Wednesday" => "Rabu",
      "Thursday" => "Kamis",
      "Friday" => "Jum'at",
      "Saturday" => "Sabtu",
      "Sunday" => "Minggu"
    ];

    $months_en_grego = substr($dt, 3, 2);
    $months_id_grego = [
      "01" => "Januari",
      "02" => "Februari",
      "03" => "Maret",
      "04" => "April",
      "05" => "Mei",
      "06" => "Juni",
      "07" => "Juli",
      "08" => "Agustus",
      "09" => "September",
      "10" => "Oktober",
      "11" => "November",
      "12" => "Desember"
    ];

    $join_daysname = "$days_id[$days_en], ".$grego_date;
    $restring_dt_grego = str_replace($months_en_grego, $months_id_grego[$months_en_grego], $join_daysname);

    $hijr_date = str_replace("-", " ", $result['data']['date']['hijri']['date']);
    $months_num_hijr = substr($hijr_date, 3, 2);
    $months_name_hijr = [
      "01" => "Muharram",
      "02" => "Safar",
      "03" => "Rabiul Awal",
      "04" => "Rabiul Akhir",
      "05" => "Jumadil Awal",
      "06" => "Jumadil Akhir",
      "07" => "Rajab",
      "08" => "Sya'ban",
      "09" => "Ramadhan",
      "10" => "Syawal",
      "11" => "Dzulkaidah",
      "12" => "Dzulhijjah"
    ];
    $restring_dt_hijr = str_replace($months_num_hijr, $months_name_hijr[$months_num_hijr], $hijr_date);

    $timezone_json = $result['data']['meta']['timezone'];
    $replace_timezone = [
      "Asia/Jakarta" => "GMT+07.00 (WIB)",
      "Asia/Makassar" => "GMT+08.00 (WITA)",
      "Asia/Jayapura" => "GMT+09.00 (WIT)"
    ];
    $restring_timezone = str_replace($timezone_json, $replace_timezone[$timezone_json], $timezone_json);

    $data = [
      "date" => $restring_dt_grego." || ".$restring_dt_hijr,
      "sholat" => [
        1 => $result['data']['timings']['Imsak'],
        2 => $result['data']['timings']['Fajr'],
        3 => $result['data']['timings']['Dhuhr'],
        4 => $result['data']['timings']['Asr'],
        5 => $result['data']['timings']['Maghrib'],
        6 => $result['data']['timings']['Isha']
      ],
      "location" => [
        "latitude" => $result['data']['meta']['latitude'],
        "longitude" => $result['data']['meta']['longitude'],
        "timezone" => $restring_timezone
      ],
      "method" => [
        "calculation" => "Universitas Umm al-Qura",
        "juristic" => "Standar (Syafi`i)"
      ]
    ];
    ?>
    <!-- The Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <!--Modal Header-->
          <div class="modal-header">
            <section class="modal-title" id="exampleModalLabel">
              <p class="modal-title" style="margin: 0; padding: 0; font-size: 21px; font-weight: bold;">
                Waktu Sholat
              </p>
              <p class="modal-title" style="margin: 0; padding: 0; font-size: 15px; font-weight: 700;">
                Koordinat: <?php echo $data['location']['latitude'].", ".$data['location']['longitude'] ?>
              </p>
              <p class="modal-title" style="font-size: 14px; margin: 0; padding: 0; font-weight: 500">
                <?php echo $data['date'] ?>
              </p>
            </section>
          </div>

          <!--Modal Body-->
          <div class="modal-body" style="padding-bottom: 0;">
            <table class="table table-bordered" style="margin: 0; padding: 0;">
              <tr>
                <th><center>Waktu</center></th>
                <th><center>Jam</center></th>
              </tr>
              <tr>
                <td><center>Imsak</center></td>
                <td><center><?php echo $data['sholat'][1] ?></center></td>
              </tr>
              <tr>
                <td><center>Subuh</center></td>
                <td><center><?php echo $data['sholat'][2] ?></center></td>
              </tr>
              <tr>
                <td><center>Dzuhur</center></td>
                <td><center><?php echo $data['sholat'][3] ?></center></td>
              </tr>
              <tr>
                <td><center>Ashar</center></td>
                <td><center><?php echo $data['sholat'][4] ?></center></td>
              </tr>
              <tr>
                <td><center>Maghrib</center></td>
                <td><center><?php echo $data['sholat'][5] ?></center></td>
              </tr>
              <tr>
                <td><center>Isya</center></td>
                <td><center><?php echo $data['sholat'][6] ?></center></td>
              </tr>
            </table>
            <p class="modal-title" style="font-size: 14px; padding-top: 8px; padding-bottom: 8px;">
              <b>Metode Kalkulasi: </b><?php echo $data['method']['calculation'] ?><br>
              <b>Metode Juristik: </b><?php echo $data['method']['juristic'] ?><br>
              <b>Zona Waktu: </b><?php echo $data['location']['timezone'] ?>
            </p>
          </div>

          <!--Modal Footer-->
          <div class="modal-footer">
            <a class="btn btn-primary" href="index.php">Back to Default</a>
          </div>
        </div>
      </div>
    </div>
    <?php
  } ?>

  <!--Bootstrap 5-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

  <!--JavaScript-->
  <script>
    //Auto show modal when the page is have been opened
    $(document).ready(function() {
      $("#exampleModal").modal("show");
    });

    var x = document.getElementById("latitude");
    var y = document.getElementById("longitude");

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    function showPosition(position) {
      x.value = position.coords.latitude;
      y.value = position.coords.longitude;
    }
  </script>
</body>
</html>
