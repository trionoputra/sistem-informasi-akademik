<?php
class Excel {

    private $excel;
    public function __construct() {
        require_once APPPATH . 'third_party/PHPExcel.php';
        $this->excel = new PHPExcel();
    }

    public function load($path) {
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->excel = $objReader->load($path);
    }

    public function save($path) {
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save($path);
    }

    public function stream($filename, $data = null,$header = null,$extend = null,$extend2 = null) {
        if ($data != null) {
            $col = 'A';
			$startRow = 0;
			if($header)
			{
				$startRow = 6;
			}
			
			if($extend != null)
			{
				
				$r = 0;
				foreach ($extend as $key => $val)
				{
					$objRichText = new PHPExcel_RichText();
					$objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
					
					$objRichText2 = new PHPExcel_RichText();
					$objPayable2 = $objRichText2->createTextRun(str_replace("_", " ", $val));
					$this->excel->getActiveSheet()->getCell("A" . ($startRow+$r))->setValue($objRichText);
					$this->excel->getActiveSheet()->getCell("B". ($startRow+$r))->setValue($objRichText2);
					$r++;
				}
				
				$startRow += sizeof($extend)+1;
				
			}
            foreach ($data[0] as $key => $val) {
                $objRichText = new PHPExcel_RichText();
                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                $this->excel->getActiveSheet()->getCell($col . $startRow)->setValue($objRichText);
                $col++;
            }
			
			
			$this->excel->getActiveSheet()->mergeCells('A1:'.$col.'1');
			$this->excel->getActiveSheet()->mergeCells('A2:'.$col.'2');
			$this->excel->getActiveSheet()->getCell("A" . '1')->setValue("SDN KAMPUNG BULAK IV");
			$this->excel->getActiveSheet()->getCell("A" . '2')->setValue("Jl. KAVLING KEUANGAN IX NO. 50, KEDAUNG-TANGERANG BANTEN");
			
			if($header)
				$this->excel->getActiveSheet()->getCell("A" . '4')->setValue($header);
			
			
			
            $rowNumber = 7;
			if($extend != null)
				 $rowNumber += sizeof($extend)+1;
            foreach ($data as $row) {
                $col = 'A';
				$cold = 1;
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
					$cold++;
					if($cold <= sizeof($row))
					$col++;
                }
                $rowNumber++;
            }
			
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+1))->setValue("Tangerang ".date("d/m/Y"));
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+2))->setValue("Kepala Sekolah");
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+6))->setValue("(Siti Rohaya, S. Pd)");
        }
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Cache-control: private");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
      //  $objWriter->save("export/$filename");
      //  header("location: " . base_url() . "export/$filename");
       // unlink(base_url() . "export/$filename");
	   $objWriter->save('php://output');
    }
	
	 public function stream_raport($data,$data2,$data3,$data4,$filename,$header,$extend,$walikelas = "") {
	 
        if ($data != null) {
            $col = 'A';
			$startRow = 0;
			if($header)
			{
				$startRow = 6;
			}
			
			if($extend != null)
			{
				
				$r = 0;
				foreach ($extend as $key => $val)
				{
					$objRichText = new PHPExcel_RichText();
					$objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
					
					$objRichText2 = new PHPExcel_RichText();
					$objPayable2 = $objRichText2->createTextRun(str_replace("_", " ", $val));
					$this->excel->getActiveSheet()->getCell("A" . ($startRow+$r))->setValue($objRichText);
					$this->excel->getActiveSheet()->getCell("B". ($startRow+$r))->setValue($objRichText2);
					$r++;
				}
				
				$startRow += sizeof($extend)+1;
				
			}
            foreach ($data[0] as $key => $val) {
                $objRichText = new PHPExcel_RichText();
                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                $this->excel->getActiveSheet()->getCell($col . $startRow)->setValue($objRichText);
                $col++;
            }
			
			
			$this->excel->getActiveSheet()->mergeCells('A1:'.$col.'1');
			$this->excel->getActiveSheet()->mergeCells('A2:'.$col.'2');
			$this->excel->getActiveSheet()->getCell("A" . '1')->setValue("SDN KAMPUNG BULAK IV");
			$this->excel->getActiveSheet()->getCell("A" . '2')->setValue("Jl. KAVLING KEUANGAN IX NO. 50, KEDAUNG-TANGERANG BANTEN");
			
			if($header)
				$this->excel->getActiveSheet()->getCell("A" . '4')->setValue($header);
			
            $rowNumber = 7;
			if($extend != null)
				 $rowNumber += sizeof($extend)+1;
				 
            foreach ($data as $row) {
                $col = 'A';
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
			
			$col = 'A';
			$this->excel->getActiveSheet()->setCellValue($col . $rowNumber, "*)  KKM=Kriteria Ketuntasan Minimal");
			$col++;
              
            $rowNumber++;
			
			
			$col = "A";
			$rowNumber +=2;
			$this->excel->getActiveSheet()->setCellValue($col . $rowNumber, "Nilai Kepribadian");
			
			$col = "A";
			$rowNumber ++;
			 foreach ($data2[0] as $key => $val) {
                $objRichText = new PHPExcel_RichText();
                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                $this->excel->getActiveSheet()->getCell($col .  $rowNumber)->setValue($objRichText);
                $col++;
            }
			
			$rowNumber ++;
			foreach ($data2 as $row) {
                $col = 'A';
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
			
			$col = "A";
			$rowNumber +=2;
			$this->excel->getActiveSheet()->setCellValue($col . $rowNumber, "Pengembangan Diri");
			
			$col = "A";
			$rowNumber ++;
			 foreach ($data4[0] as $key => $val) {
                $objRichText = new PHPExcel_RichText();
                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                $this->excel->getActiveSheet()->getCell($col .  $rowNumber)->setValue($objRichText);
                $col++;
            }
			
			$rowNumber ++;
			foreach ($data4 as $row) {
                $col = 'A';
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
			
			$col = "A";
			$rowNumber +=2;
			$this->excel->getActiveSheet()->setCellValue($col . $rowNumber, "Nilai Ketidak Hadiran");
			
			$col = "A";
			$rowNumber ++;
			 foreach ($data3[0] as $key => $val) {
                $objRichText = new PHPExcel_RichText();
                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                $this->excel->getActiveSheet()->getCell($col .  $rowNumber)->setValue($objRichText);
                $col++;
            }
			
			$rowNumber ++;
			foreach ($data3 as $row) {
                $col = 'A';
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
			
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+1))->setValue("Tangerang ".date("d/m/Y"));
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+2))->setValue("Kepala Sekolah");
			$this->excel->getActiveSheet()->getCell($col . ( $rowNumber+6))->setValue("(Siti Rohaya, S. Pd)");
			
			$this->excel->getActiveSheet()->getCell("A" . ( $rowNumber+2))->setValue("Orang Tua/Wali");
			$this->excel->getActiveSheet()->getCell("A" . ( $rowNumber+6))->setValue("(..................)");
			
			$this->excel->getActiveSheet()->getCell("E" . ( $rowNumber+2))->setValue("Wali Kelas");
			$this->excel->getActiveSheet()->getCell("E" . ( $rowNumber+6))->setValue("(".$walikelas.")");
			
			
        }
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Cache-control: private");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
       // $objWriter->save("export/$filename");
        //header("location: " . base_url() . "export/$filename");
        //unlink(base_url() . "export/$filename");
		$objWriter->save('php://output');
    }

    public function __call($name, $arguments) {
        if (method_exists($this->excel, $name)) {
            return call_user_func_array(array($this->excel, $name), $arguments);
        }
        return null;
    }
}