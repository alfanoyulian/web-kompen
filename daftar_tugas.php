<?php
    include 'koneksi.php';
    session_start();

   if (!isset($_SESSION['username'])) {
        header("location: index.php");
		exit();
   }
   $nama_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php include "title.php"; ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<?php include "favicon.php"; ?>
	
	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/azzara.min.css">
	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
	<div class="wrapper">
		<!--
				Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
		<div class="main-header" data-background-color="blue">
			<!-- Logo Header -->
			<?php include "header.php";?>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<?php include "navbar.php";?>
			<!-- End Navbar -->
		</div>
		<!-- Sidebar -->
		<?php include "sidebar.php";?>
		<!-- End Sidebar -->



		<div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Master Data</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="dashboard.php">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Daftar Tugas</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
								<div class="d-flex align-items-center">
										<h4 class="card-title">Daftar Tugas</h4>
										<button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Tambah Tugas
										</button>
									</div>
								</div>
								<div class="card-body">
								<!--tambah data -->	
								<!-- Modal -->
								<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header no-bd">
													<h5 class="modal-title">
														<span class="fw-mediumbold">
														Tambah</span> 
														<span class="fw-light">
															Tugas
														</span>
													</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<form action="" method="post">
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Name Tugas</label>
																	<input id="nama_tugas" name="nama_tugas" type="text" class="form-control" placeholder="Nama Tugas" required>
																</div>
															</div>
                                                            <input type="hidden" name="status" class="form-control" value="Belum_selesai" required>
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Prioritas</label>
																	<select name="prioritas" id="prioritas" class="form-control">
																		<option value="" disabled selected>- Pilih Prioritas -</option>
																		<option value="Tinggi">Tinggi</option>
                                                                        <option value="Sedang">Sedang</option>
                                                                        <option value="Rendah">Rendah</option>
                                                                    </select>
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Tanggal</label>
                                                                    <input type="date" name="tanggal" class="form-control" value="Belum_selesai" required>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer no-bd">
														<button type="submit" name="submit" class="btn btn-primary">Tambah</button>
														<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!-- END tambah DATA -->

                                    <!-- DAFTAR TUGAS dan  TAMBAH TUGAS-->
                                    <?php 
                                        include 'koneksi.php';

                                        if (isset($_POST['submit'])) {
                                            $nama_tugas = $_POST['nama_tugas'];
                                            $status = $_POST['status'];
                                            $prioritas = $_POST['prioritas'];
                                            $tanggal = $_POST['tanggal'];

                                            if (!empty($nama_tugas) && !empty($prioritas) && !empty($tanggal)) {
                                                $stmt = $koneksi->prepare("INSERT INTO tugas (nama_tugas,status,prioritas,tanggal) VALUES (?, ?, ?, ?)");
                                                $stmt->bind_param("ssss", $nama_tugas, $status, $prioritas, $tanggal);

                                                if ($stmt->execute()) {
                                                    echo "<script>alert('Tambah Tugas Berhasil'); location.href='daftar_tugas.php';</script>";
                                                } else {
                                                    echo "Gagal";
                                                }
                                            } else {
                                                echo "Data tidak boleh kosong";
                                            }
                                        }
                                    ?>
                                     <!-- TAB NYA-->
                                            <ul class="nav nav-pills mb-3 mt-4" id="pills-tab" role="tablist">
												 <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-belum-selesai-tab" data-bs-toggle="pill" data-bs-target="#pills-belum-selesai" type="button" role="tab" aria-controls="pills-belum-selesai" aria-selected="false">Belum Selesai</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-selesai-tab" data-bs-toggle="pill" data-bs-target="#pills-selesai" type="button" role="tab" aria-controls="pills-selesai" aria-selected="true">Selesai</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                
                                                <!-- Selesai -->
                                                <div class="tab-pane fade" id="pills-selesai" role="tabpanel" aria-labelledby="pills-selesai-tab">
                                                    <h1 class="text-center mt-4">DAFTAR TUGAS SELESAI</h1>
                                            
                                                    <table class="table table-bordered mt-3">
                                                        <thead class="table-success text-center">
                                                            <tr>
                                                                <th scope="col">NO</th>
                                                                <th scope="col">TUGAS</th>
                                                                <th scope="col">STATUS</th>
                                                                <th scope="col">PRIORITAS</th>
                                                                <th scope="col">TANGGAL</th>
                                                                <th scope="col">ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $i = 1;
                                                            $query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE status='Selesai' ORDER BY tanggal DESC");
                                                            while ($data = mysqli_fetch_assoc($query)) : ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $data['nama_tugas']; ?></td>
                                                                    <td class="text-center"><span class="badge bg-success text-light">Selesai</span></td>
                                                                    <td class="text-center">
                                                                        <?php if ($data['prioritas'] == 'Tinggi') { ?>
                                                                            <span class="badge bg-danger text-light">Tinggi</span>
                                                                        <?php } elseif ($data['prioritas'] == 'Sedang') { ?>
                                                                            <span class="badge bg-warning text-light">Sedang</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge bg-primary text-light">Rendah</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td><?php echo $data['tanggal']; ?></td>
                                                                    <td class="text-center">
                                                                        <a href="hapus_tugas.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus?');">Hapus</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Belum Selesai -->
                                                <div class="tab-pane fade show active" id="pills-belum-selesai" role="tabpanel" aria-labelledby="pills-belum-selesai-tab">
                                                    <h1 class="text-center mt-4">DAFTAR TUGAS BELUM SELESAI</h1>
                                                    <table class="table table-bordered mt-3">
                                                        <thead class="table-danger text-center">
                                                            <tr>
                                                                <th scope="col">NO</th>
                                                                <th scope="col">TUGAS</th>
                                                                <th scope="col">STATUS</th>
                                                                <th scope="col">PRIORITAS</th>
                                                                <th scope="col">TANGGAL</th>
                                                                <th scope="col">ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $i = 1;
                                                            $query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE status='Belum_selesai' ORDER BY tanggal DESC");
                                                            while ($data = mysqli_fetch_assoc($query)) : ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $data['nama_tugas']; ?></td>
                                                                    <td class="text-center"><span class="badge bg-danger text-light">Belum Selesai</span></td>
                                                                    <td class="text-center">
                                                                        <?php if ($data['prioritas'] == 'Tinggi') { ?>
                                                                            <span class="badge bg-danger text-light">Tinggi</span>
                                                                        <?php } elseif ($data['prioritas'] == 'Sedang') { ?>
                                                                            <span class="badge bg-warning text-light">Sedang</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge bg-primary text-light">Rendah</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td><?php echo $data['tanggal']; ?></td>
                                                                    <td class="text-center">
                                                                        <a href="selesai.php?id=<?php echo $data['id']; ?>" class="btn btn-success btn-sm">Selesai</a>
                                                                        <a href="edit_tugas.php?id=<?php echo $data['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                                                        <a href="hapus_tugas.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus?');">Hapus</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
 
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	<!-- Bootstrap Toggle -->
	<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>
	<!-- Azzara JS -->
	<script src="assets/js/ready.min.js"></script>
	<!-- Azzara DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo.js"></script>
	<script >
		$(document).ready(function() {
			$('#basic-datatables').DataTable({
			});

			$('#multi-filter-select').DataTable( {
				"pageLength": 5,
				initComplete: function () {
					this.api().columns().every( function () {
						var column = this;
						var select = $('<select class="form-control"><option value=""></option></select>')
						.appendTo( $(column.footer()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
								);

							column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
						} );

						column.data().unique().sort().each( function ( d, j ) {
							select.append( '<option value="'+d+'">'+d+'</option>' )
						} );
					} );
				}
			});

			// Add Row
			$('#add-row').DataTable({
				"pageLength": 5,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#add-row').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
					]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>
</body>
</html>