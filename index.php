<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Captive Portal</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'captive';

    $koneksi = new mysqli($host, $user, $pass, $dbname);

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

//query untuk mengambil jumlah pertanyaan yang akan ditampilkan dari tabel config
$query_config = "SELECT config_value FROM config WHERE config_name = 'jumlah_pertanyaan'";
$result_config = $koneksi->query($query_config);

//memeriksa apakah data konfigurasi ditemukan
if ($result_config->num_rows > 0) {
    //mengambil nilai jumlah pertanyaan dari hasil kueri
    $row_config = $result_config->fetch_assoc();
    $jumlah_pertanyaan_ditampilkan = intval($row_config["config_value"]);
} else {
    //jika tidak ada data konfigurasi, tetapkan nilai default
    $jumlah_pertanyaan_ditampilkan = 3;
}

    $query = "SELECT question, choiceA, choiceB, choiceC, answer FROM question";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $questions = array();
        while ($row = $result->fetch_assoc()) {
            $questions[] = array($row["question"], $row["choiceA"], $row["choiceB"], $row["choiceC"], $row["answer"]);
        }
        
    // shuffle urutan pertanyaan dan membatasi hanya jumlah pertanyaan yang ditampilkan
    shuffle($questions);
    $questions = array_slice($questions, 0, $jumlah_pertanyaan_ditampilkan);
} else {
    echo "Tidak ada data soal yang ditemukan.";
    exit();
}

    $query_time_limit = "SELECT config_value FROM config WHERE config_name = 'time_limit'";
    $result_time_limit = $koneksi->query($query_time_limit);
    
    // Memeriksa apakah data waktu ditemukan
    if ($result_time_limit->num_rows > 0) {
        $row_time_limit = $result_time_limit->fetch_assoc();
        $time_limit_from_config = intval($row_time_limit["config_value"]);
    } else {
        // Jika data waktu tidak ditemukan, tetapkan nilai default
        $time_limit_from_config = 20;
    }

    
    $koneksi->close();
    ?>

        <div class="vertical-center">
            <div class="inner-block">
                <div class="col-lg">
                    <div id="welcomeSection" class="card card-custom">
                        <div class="row align-items-md-stretch g-1">
                        <div class="col-12 col-lg-4 px-3 mt-3">
   <img src="logo.png" alt="Logo" class="logo"><center>
   <div class="badge text-bg-warning text-wrap mt-4 fs-6" style="  border-radius: 20px;">
Guest WiFi Portal
</div></center>
</div>
                            <div class="col-12 col-lg-8">
                                <div class="card-body">
                                    <p>
                                    By accessing the wireless network, you acknowledge that you're of legal age, you have read and understood and agree to be bound by this agreement.
                                    <br/><br/>
You agree not to use the wireless network for any purpose that is unlawful and take full responsibility for any of your act. PTAR will not held any responsibility for any reason.                                </p>
                                    <button class="btn btn-warning" onclick="startQuiz()">Start Quiz</button>
                                </div>
                            </div>
                            <div class="mt-3 px-3">
                            <div class="alert alert-warning alert-custom" role="alert">
                    By connecting to the Wi-Fi network, you agree to the <a href="#" class="text-warning fw-bold" id="myLink" data-bs-toggle="modal" data-bs-target="#myModal">terms and conditions</a> of use.
                            </div>
                        </div>
                      </div>
                    </div>
                   
                </div>

                <div class="card card-custom" id="quiz" style="display: none;">
                    <div class="card-body">
                        <h2 id="test_status"></h2>
                        <div id="test"></div>
                    </div>
                </div>
                <div id="timerSection" style="display: none;">
                    <div class="card card-custom mt-3">
                        <div class="card-body">
                            <div id="timer">Waktu tersisa:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    var questions = <?php echo json_encode(array_slice($questions, 0, $jumlah_pertanyaan_ditampilkan)); ?>;
        var timeLeft = <?php echo $time_limit_from_config; ?>;
    </script>
        <script src="script.js"></script>
        <div class="modal fade " id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary-emphasis" id="exampleModalLabel">Terms and Conditions</h5>
      </div>
        <div class="modal-body font-monospace text-primary-emphasis">
      By accessing the wireless network, you acknowledge that you're of legal age, you have read and understood and agree to be bound by this agreement.<br/><br/>
The wireless network service is provided by the property owners and is completely at their discretion. Your access to the network may be blocked, suspended, or terminated at any time for any reason.<br/><br/>
You agree not to use the wireless network for any purpose that is unlawful and take full responsibility of your acts.<br/><br/>
The wireless network is provided "as is" without warranties of any kind, either expressed or implied.<br/><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">I Agree</button>
      </div>
</div>
    </div>
  </div>
</div>
<script src="bootstrap\js\bootstrap.bundle.js" crossorigin="anonymous"></script>
</body>
</html>
