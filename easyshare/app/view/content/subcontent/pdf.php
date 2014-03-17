
pdf file
<?php
require('lib/fpdf.php');

$pdf=new FPDF();
$pdf->AddPage(); //Create new PDF page

$pdf->SetFont('Arial','B',10); //Set the base font & size

//Cell function gets two parameters:Width & Height of the Cell
$pdf->Cell(50,3,"Cool PHP to PDF Tutorial by WebSpeaks.in");
$pdf->Ln();//Creates a new line, blank here
$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,5,"Sr.no.");
$pdf->Cell(350,5,"Message");
$pdf->Ln();
$pdf->Cell(450,3,"--------------------------------------------------------------------------");

 // Get data records from table.
/* $result=mysql_query("select * from records order by msg_id");
 while($row=mysql_fetch_array($result))
 {
  $pdf->Cell(10,5,"{$row['msg_id']}");
  $pdf->MultiCell(350,5,"{$row['message']}");//Necessary for generating a new line.
 }*/
$pdf->Output();//Finally shows the output.
// $pdf->Output('cache/upload/'.$filename,'I');
/*$pdf->Output('cache/upload/'.$filename,'I');
//i for downlod
*/
?>