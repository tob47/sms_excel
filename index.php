<?php
include "config.php";
include "function.php";
?>
 
<html>
 <head>
   <title>Tutorial Membuat Script Broadcast SMS via Import Excel - Tobiweb.id </title>
 </head>
 <body>
   <h1>Tutorial Membuat Script Broadcast SMS via Import Excel - Tobiweb.id</h1>
   
	<form method="post" enctype="multipart/form-data" action="index.php">
	
	Pilih file source<br>
	<input type="hidden" name="MAX_FILE_SIZE" value="20000000">
	<input name="userfile" type="file" size="50"><br><br>
	Masukkan test SMS<br>
	<textarea name="template" cols="50" rows="8"></textarea><br><br>
	<input type="submit" name="submit" value="Kirim SMS">
	</form>
 <?php
   
   if (isset($_POST['submit']))
   {
		require_once 'excel_reader2.php';

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		// baca jumlah total baris dan kolom file excel
		$baris = $data->rowcount($sheet_index=0);
		$kolom = $data->colcount($sheet_index=0);

		// proses pembacaan setiap baris data, mulai pada baris ke-2
		for ($i=2; $i<=$baris; $i++)
		{
			// string template SMS
			$string = $_POST['template'];
			
			// proses pattern matching, mencari bentuk [...] dalam template sms
			preg_match_all("|\[(.*)\]|U", $string, $hasil, PREG_PATTERN_ORDER);

			for($j=1; $j<=$kolom; $j++)
			{
				$value[strtoupper($data->val(1, $j))] = $data->val($i, $j);
				// membaca nomor hp dari kolom ke-1 file excel
				$nohp = $data->val($i, 1);	
			}
     
	        // proses mengubah pattern [...] di template
			// menjadi value sesuai nama kolom di excel
			foreach($hasil[1] as $key => $nilai)
			{
				$string = str_replace('['.$nilai.']', '['.strtoupper($nilai).']', $string);
				$kapital = strtoupper($nilai);
				$string = str_replace('['.$kapital.']', $value[$kapital], $string);
			}
			
			// jika nomor hp tidak kosong, maka lakukan pengiriman sms
			if (is_string($nohp) && ($nohp != ''))
			{
				ngirimsms($nohp, $string, '');
			}		
		}	
		echo "<p>SMS dalam pengiriman</p>";	
   }
 ?>
 </body>
</html>