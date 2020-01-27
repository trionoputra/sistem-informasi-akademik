<?php
class Pdf {
	private $pdf;
    public function __construct() {
        require_once 'mypdf.php';
		$this->pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }
	
	public function stream($data,$filename,$header,$extend  = null,$extend2 = null, $orientasi=null)
	{
		
		$this->pdf->SetPrintHeader(false);
		
		//$this->pdf->SetPrintFooter(false);
		if ($orientasi!=null)  
			$this->pdf->AddPage($orientasi);
		else
			$this->pdf->AddPage();
		
		$html = '<h4 align="center">SDN KAMPUNG BULAK IV<br/>';
		$html .= "Jl. KAVLING KEUANGAN IX NO. 50, KEDAUNG-TANGERANG BANTEN</h4>";
		$html .= '<hr/>';
		$html .= '<h4 align="center"></h4>';
		$html .= '<p align="center">'.$header.'</p>';
		
		if($extend != null)
		{
			$html .= '<table cellspacing="0" cellpadding="4" >';
			
			foreach ($extend as $key => $val)
			{
				$html .= "<tr >";
				$html .= '<td style="font-size: small;" width="120">'.$key.'</td>';
				$html .= '<td style="font-size: small;">'.$val.'</td>';
				$html .= '</tr>';
			}
			
			$html .= "</table>";
		}
		
		$html .= '<table cellspacing="0" cellpadding="4">';
		$html .= '<tr>';
		foreach ($data[0] as $key => $val) 
		{
			$html .= '<th bgcolor="#cccccc" style="font-size: small;">'.$key.'</th>';
        }
		$html .= "</tr>";
		foreach ($data  as $val) 
		{
			$html .= "<tr>";
			foreach ($data[0] as $key => $v) 
			{
				$html .= '<td style="font-size: small;">'.$val[$key].'</td>';
			}
			$html .= "</tr>";
        }
		$html .= "</table>";
		if($extend2 != null)
		{
			
			$html .= "<div style='margin-top:20px;padding-top:20px'>";
			$html .= implode(", ",$extend2);
			$html .= "</div>";
		}
		$this->pdf->writeHTML($html, true, false, true, false, '');
		$this->pdf->Output($filename.'.pdf', 'I');
	}
	
	public function stream_raport($data,$data2,$data3,$data4,$filename,$header,$extend,$guru)
	{
		
		$this->pdf->SetPrintHeader(false);
		$this->pdf->isRaport(true,$guru);
		//$this->pdf->SetPrintFooter(false);
		$this->pdf->AddPage();
		$html = '<h4 align="center">SDN KAMPUNG BULAK IV<br/>';
		$html .= "Jl. KAVLING KEUANGAN IX NO. 50, KEDAUNG-TANGERANG BANTEN</h4>";
		$html .= '<hr/>';
		$html .= '<h4 align="center"></h4>';
		$html .= '<p align="center">'.$header.'</p>';
		
		if($extend != null)
		{
			$html .= '<table cellspacing="0" cellpadding="4" >';
			
			foreach ($extend as $key => $val)
			{
				$html .= "<tr >";
				$html .= '<td style="font-size: small;" width="120">'.$key.'</td>';
				$html .= '<td style="font-size: small;">'.$val.'</td>';
				$html .= '</tr>';
			}
			
			$html .= "</table>";
		}
		
		$html .= '<table cellspacing="0" cellpadding="4">';
		$html .= '<tr>';
		foreach ($data[0] as $key => $val) 
		{
			$html .= '<th bgcolor="#cccccc" style="font-size: small;">'.$key.'</th>';
        }
		$html .= "</tr>";
		foreach ($data  as $val) 
		{
			$html .= "<tr>";
			foreach ($data[0] as $key => $v) 
			{
				$html .= '<td style="font-size: small;">'.$val[$key].'</td>';
			}
			$html .= "</tr>";
        }
		$html .= '<tr><td colspan ="4"> *)  KKM=Kriteria Ketuntasan Minimal</td></tr>';
		$html .= "</table>";
		$html .= "<div></div>";
		$html .= '<table cellspacing="0" cellpadding="4">';
		$html .= '<tr><td colspan="4">Nilai Kepribadian</td></tr>';
		$html .= '<tr>';
		foreach ($data2[0] as $key => $val) 
		{
			$html .= '<th bgcolor="#cccccc" style="font-size: small;">'.$key.'</th>';
        }
		$html .= "</tr>";

		foreach ($data2  as $val) 
		{
			$html .= "<tr>";
			foreach ($data2[0] as $key => $v) 
			{
				$html .= '<td style="font-size: small;">'.$val[$key].'</td>';
			}
			$html .= "</tr>";
        }
		$html .= "</table>";
		$html .= "<div></div>";
		$html .= '<table cellspacing="0" cellpadding="4">';
		$html .= '<tr><td colspan="3">Pengembangan Diri</td></tr>';
		$html .= '<tr>';
		foreach ($data4[0] as $key => $val) 
		{
			$html .= '<th bgcolor="#cccccc" style="font-size: small;">'.$key.'</th>';
        }
		$html .= "</tr>";
		foreach ($data4  as $val) 
		{
			$html .= "<tr>";
			foreach ($data4[0] as $key => $v) 
			{
				$html .= '<td style="font-size: small;">'.$val[$key].'</td>';
			}
			$html .= "</tr>";
        }
		$html .= "</table>";
		$html .= "<div></div>";
		$html .= '<table cellspacing="0" cellpadding="4">';
		$html .= '<tr><td colspan="3">Nilai Ketidakhadiran</td></tr>';
		$html .= '<tr>';
		foreach ($data3[0] as $key => $val) 
		{
			$html .= '<th bgcolor="#cccccc" style="font-size: small;">'.$key.'</th>';
        }
		$html .= "</tr>";
		foreach ($data3  as $val) 
		{
			$html .= "<tr>";
			foreach ($data3[0] as $key => $v) 
			{
				$html .= '<td style="font-size: small;">'.$val[$key].'</td>';
			}
			$html .= "</tr>";
        }
		$html .= "</table>";
		$this->pdf->writeHTML($html, true, false, true, false, '');
		$this->pdf->Output($filename.'.pdf', 'I');
	}

}