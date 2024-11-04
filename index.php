<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import CSV Data to MySQL in PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
    body {
      font-family: Arial, sans-serif;
    }
    svg {
      background-color: #f9f9f9;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body style="background:#9CB4CC">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:#06283D">
        <div class="container">
            <a class="navbar-brand" href="./">Import CSV Data to MySQL in PHP</a>
            <div>
                <a href="https://sourcecodester.com" class="text-light fw-bolder h6 text-decoration-none" target="_blank">SourceCodester</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid px-5 pb-2 pt-5">
        <div class="col-lg-6 col-md-8 col-sm-12 mx-auto">
          <h3 class="text-center text-light">Importing CSV Data to MySQL Database in PHP</h3>
          <hr>
          <?php if(isset($_SESSION['status']) && $_SESSION['status']== "success"): ?>
            <div class="alert alert-success rounded-0 mb-3">
              <?= $_SESSION['message'] ?>
            </div>
          <?php unset($_SESSION['status']);unset($_SESSION['message']) ?>
          <?php endif; ?>
          <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "error"): ?>
            <div class="alert alert-danger rounded-0 mb-3">
              <?= $_SESSION['message'] ?>
            </div>
          <?php unset($_SESSION['status']);unset($_SESSION['message']) ?>
          <?php endif; ?>
          <div class="card rounded-0 mb-3">
            <div class="card-header rounded-0">
              <div class="card-title"><b>Import Data From CSV</b></div>
            </div>
            <div class="card-body rounded-0">
              <div class="container-fluid">
                <form action="import_csv.php" id="import-form" method="POST" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="fileData" class="form-label">Browse CSV Data</label>
                    <input class="form-control" type="file" accept=".csv" name="fileData" id="fileData" required>
                  </div>
                </form>
              </div>
            </div>
            <div class="card-footer py-1">
              <div class="text-center">
                <button class="btn btn-primary rounded-pill col-lg-5 col-md-6 col-sm-12 col-xs-12" form="import-form">Import</button>
              </div>
            </div>
          </div>
          <div class="card my-2 rounded-0">
            <div class="card-header rounded-0">
              <div class="card-title"><b>Member List</b></div>
            </div>
            <div class="card-body rounded-0">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-hovered table-striped table-bordered">
                    <thead>
                      <tr class="bg-gradient bg-primary text-white">
                        <th class="text-center">#</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">ID Spesimen</th>
                        <th class="text-center">Nama Spesimen</th>
						<th class="text-center">Diameter (mm)</th>
                        <th class="text-center">Massa (gr)</th>
                        <th class="text-center">Jarak (mm)</th>
						<th class="text-center">Tegangan (Mpa)</th>
                        <th class="text-center">Waktu (detik)</th>
                        <th class="text-center">Putaran (rpm)</th>
						<th class="text-center">Siklus</th>
                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      include_once('db-connect.php');
                      $members_sql = "SELECT * FROM `members` order by id ASC";
                      $members_qry = $conn->query($members_sql);
                      if($members_qry->num_rows > 0):
                        while($row = $members_qry->fetch_assoc()):
                      ?>
                        <tr>
                          <th class="text-center"></th>
                          <td><?= $row['time'] ?></td>
                          <td><?= $row['id'] ?></td>
                          <td><?= $row['nama'] ?></td>
                          <td><?= $row['diameter_mm'] ?></td>
                          <td><?= $row['massa_gr'] ?></td>
                          <td><?= $row['jarak'] ?></td>
                          <td><?= $row['tegangan_mpa'] ?></td>
                          <td><?= $row['waktu_detik'] ?></td>
                          <td><?= $row['putaran_rpm'] ?></td>
                          <td><?= $row['siklus'] ?></td>
                        </tr>
                      <?php endwhile; ?>
                      <?php else: ?>
                        <tr>
                          <th class="text-center" colspan="4">No data on the database yet.</th>
                        </tr>
                      <?php endif; ?>
                     
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
			<div>
			Test
      <img src="generate_image.php" alt="Grafik" />
      

        </div>
        
    </div>

</body>
</html>