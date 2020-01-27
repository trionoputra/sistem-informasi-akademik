<?php
require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
class MYPDF extends TCPDF {
	var $israport = false;
	var $walikelas = "";
	public function isRaport($israport,$walikelas = "")
	{
		$this->israport = $israport;
		$this->walikelas = $walikelas;
	}
	
    public function Footer() {
        $this->SetY(-50);
        $this->SetFont('helvetica', 'N', 10);
		
		if($this->israport)
		{
			$this->writeHTML("Tangerang ".date("d/m/Y"), true, false, true, false, 'R');
			$this->writeHTML("Orang Tua/Wali", false, false, true, false, 'L');
			$this->SetX(20);
			$this->writeHTML("Wali Kelas", false, false, true, false, 'C');
			$this->writeHTML("Kepala Sekolah", false, false, true, false, 'R');
			
			$this->writeHTML("<br/><br/><br/><br/><br/>(..............................)", false, false, true, false, 'L');
			$this->SetX(20);
			$this->writeHTML("(    " .$this->walikelas.   " )", false, false, true, false, 'C');	
			$this->writeHTML("(Siti Rohaya, S. Pd)", false, false, true, false, 'R');	
		}
		else
		{
			$this->writeHTML("Tangerang ".date("d/m/Y"), true, false, true, false, 'R');
			$this->writeHTML("Kepala Sekolah <br/><br/><br/><br/><br/>(Siti Rohaya, S. Pd)", false, false, true, false, 'R');
		}
		
		
		
    }
}